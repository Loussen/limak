<?php

namespace App\Http\Controllers\Company;

use App\DepoPackages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MillikartCoreController;
use App\Invoice;
use App\InvoicePayment;
use App\ModelLogs\LogBalance;
use App\ModelLogs\LogPaymentDeliveryInvoices;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\Models\Courier;
use App\Models\Transactions;
use App\Packages;
use App\Persons;
use App\RelUserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\Libraries\Upload\Uploader;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\Phone;
use App\Utility\UserUtility;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ApiController extends Controller
{

    public $companies = ["vipex" => ["password" => "vipex123456@","user_id" => 30592,"uniqid" => 1001]];

    public function invoice(Request $request)
    {
        $companies = $this->companies;
        $company = $request->company;
        $password = $request->password;
        $region_id = 5;
        $country_id = 2;

        if(isset($companies[$company]) and $companies[$company]["password"] == $password){

            $rules = [
                "auto_id" => "required",
                "name" => "required",
                "surname" => "required",
                "birthday" => "required",
                "email" => "required",
                "phone" => "required",
                "pin" => "required",
                "serial" => "required",
                "city" => "required",
                "address" => "required",

                "shop_name" => 'required',
                "price" => 'required',
                "product_name" => 'required',
                "quantity" => 'required',
                "tracking" => 'required',
                "order_date" => 'required',
            ];

            $validator =  Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return ['success'=> false,'message'=>$validator->errors()];
            }


            $person = Persons::where("auto_id",$request->auto_id)->first();
            if($person!=null){
                $person->created_at = date("Y-m-d H:i:s");
            }else{
                $person = new Persons();
                $person->auto_id = $request->auto_id;
                $person->updated_at = date("Y-m-d H:i:s");
            }

            $user_id = $companies[$company]["user_id"];

            $person->company = $company;
            $person->user_id = $user_id;
            $person->uniqid = $companies[$company]["uniqid"];
            $person->name = $request->name;
            $person->surname = $request->surname;
            $person->serial_number = $request->serial;
            $person->pin = $request->pin;
            $person->email = $request->email;
            $person->phone = $request->phone;
            $person->address = $request->address;
            $person->city = $request->city;
            $person->gender = $request->gender;
            $person->note = 'api';
            $person->status_id = 1;
            $person->save();

            $person_id = $person->id;


            $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();

            $lastPurchase_no = $lastPurchase_no->id;
            $package= new Packages();
            $package->status = 1;
            $package->save();

            $invoiceAddStatus = getStatusByLabel('invoice_added', 'transaction');

            $status = getStatusByLabel('with_invoice', 'product');
            $statusInvoice = getStatusByLabel('waiting', 'invoice');


            $newRelUserProduct = new RelUserProduct();
            $newRelUserProduct->status_id = $invoiceAddStatus;
            $newRelUserProduct->user_id = $user_id;
            $newRelUserProduct->is_paid = 1;
            $newRelUserProduct->is_ordered = 1;
            $newRelUserProduct->save();
            //
            $newProduct = new Product();
            $newProduct->product_type_id = 1;
            $newProduct->product_type_name = $request->product_name;
            $newProduct->rel_user_product_id = $newRelUserProduct->id;
            $newProduct->price = $request->price;
            $newProduct->region_id = $region_id;
            $newProduct->country_id = $country_id;
            $newProduct->user_id = $user_id;
            $newProduct->corporate = 0;
            $newProduct->client_id = 0;
            $newProduct->person_id = $person_id;
            $newProduct->quantity = $request->quantity;
            $newProduct->shop_name = $request->shop_name;
            $newProduct->description = $request->description;
            $newProduct->is_ordered = 1;
            $newProduct->is_paid = 1;
            $newProduct->status_id = $status;
            $newProduct->save();


            $newInvoice = new Invoice();
            $newInvoice->user_id = $user_id;
            $newInvoice->corporate = 0;
            $newInvoice->client_id = $request->get('client_id', 0);
            $newInvoice->person_id = $person_id;
            $newInvoice->price = $request->price;
            $newInvoice->product_id = $newProduct->id;
            $newInvoice->region_id = $region_id;
            $newInvoice->country_id = $country_id;
            $newInvoice->rel_user_product_id = $newRelUserProduct->id;
            $newInvoice->status_id = $statusInvoice;
            $newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
            $newInvoice->order_tracking_number = $request->tracking;
            $newInvoice->order_date = $request->order_date;
            $newInvoice->package_id = $package->id;

            if ($newInvoice->save()) {
                $newInvoice->purchase_no = str_pad($newInvoice->id, 9, 0, STR_PAD_LEFT);
                $depo_package_id = $request->package_id;
                if(intval($depo_package_id)>0){
                    $depo = DepoPackages::where("id",$depo_package_id)->where("status_id",0)->first();
                    if($depo!=null){
                        $newInvoice->weight = $depo->weight;
                        $newInvoice->length = $depo->length;
                        $newInvoice->width = $depo->width;
                        $newInvoice->height = $depo->height;

                        $depo->status_id = 1;
                        $depo->invoice_id = $newInvoice->purchase_no;
                        $depo->user_id = $user_id;
                        $depo->client_id = 0;
                        $depo->price = $request->price;
                        $depo->updated_at = date("Y-m-d H:i:s");
                        $depo->save();
                    }

                }
                $newInvoice->save();
                return response()->json(['success'=> 200,'message'=>'Bəyənnamə müvəffəqiyyətlə yükləndi',"invoice_id" => $newInvoice->id,"limak_user_id" => $person_id]);
            }

            return response()->json(['success'=> 500,'message'=>'Bəyənnamə yüklənmadi']);

        }else{
            return response()->json(['success'=> 505,'message'=>'Şirkət məlumatları düzgün deyil']);
        }

    }

    public function getInvoice(Request $request)
    {
        $companies = $this->companies;
        $company = $request->company;
        $password = $request->password;

        if(isset($companies[$company]) and $companies[$company]["password"] == $password)
        {
            $person_id = $request->limak_user_id;
            $user_id = $companies[$company]["user_id"];
            $invoice_id = $request->invoice_id;
            $invoice = Invoice::with('dates')->with('invoiceStatus')->where("id",$invoice_id)->where("user_id",$user_id)->where("person_id",$person_id)->first();

            if($invoice!=null){
                $data = [
                    "id"  => $invoice->id,
                    "order_date"  => $invoice->order_date,
                    "width"  => $invoice->width,
                    "height"  => $invoice->height,
                    "length"  => $invoice->length,
                    "weight"  => $invoice->weight,
                    "tracking"  => $invoice->order_tracking_number,
                    "price"  => $invoice->price,
                    "shipping_price"  => $invoice->shipping_price,
                    "status_id"  => $invoice->status_id,
                    "status" => $invoice["invoiceStatus"]["name"]
                ];


                return response()->json(['success' => 200, 'data' => $data]);
            }else{
                return response()->json(['success' => 501, 'data' => 'Bağlama yoxdur']);
            }

        }else{
            return response()->json(['success'=> 505,'message'=>'Şirkət məlumatları düzgün deyil']);
        }
    }




}