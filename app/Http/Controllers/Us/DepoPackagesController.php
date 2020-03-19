<?php

namespace App\Http\Controllers\Us;

use App\Currencies;
use App\Currency;
use App\DepoPackages;
use App\ForeignDepo;
use App\ModelCountry\Country;
use App\Invoice;
use App\InvoiceDates;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\Client;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\Packages;
use App\Persons;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use \Milon\Barcode\DNS1D;

class DepoPackagesController extends BaseController
{
    public function __construct()
    {
        $data = Country::where('prefix','usa')->first();
        $this->country=$data->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function packages(Request $request)
    {
        $admin_id = Auth::guard('usa')->user()->id;


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');

        $data = DB::table('depo_packages')
            ->where("status_id",0)
            ->where("country_id",2)
            ->orderBy("id","DESC")
            ->get();
        $status = 'packages';

        return view('usa.packages.depo_packages',  compact('data','status' ));
    }

    public function packagesConfirmed(Request $request)
    {
        $admin_id = Auth::guard('usa')->user()->id;


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');

        $data = DB::table('depo_packages as d')
            ->select("d.id","d.invoice_id","d.user_code","d.country_id","d.tracking","d.status_id","d.description","d.invoice_id",
                "d.currency","d.weight","d.height","d.length","d.width","d.quantity","d.store","d.order_date",
                "d.created_at","d.updated_at","i.price","i.user_id")
            ->leftJoin("invoices as i","i.purchase_no","d.invoice_id")
            ->where("d.status_id",1)
            ->where("d.country_id",2)
            ->orderBy("d.id","DESC")
            ->get();
        $status = 'packages_confirmed';


        return view('usa.packages.depo_packages',  compact('data','status' ));
    }




    public function confirmPackageOld(Request $request)
    {
        $id = $request->id;
        $invoice_id = $request->invoice_id;

        $invoice  = Invoice::where("status_id",1)->where("purchase_no",$invoice_id)->first();

        $depoPackage = DepoPackages::where("id",$id)->where("status_id",0)->first();
        if($invoice != null and $depoPackage!=null){

            $invoice->weight    = $depoPackage->weight;//lbToKg( $depoPackage->weight);
            $invoice->length    = $depoPackage->length;
            $invoice->width     = $depoPackage->width;
            $invoice->height    = $depoPackage->height;
            $region_id = $invoice->region_id;
            $tariffs = Country::where('prefix', '=', 'usa')->with('tariffs')->first();

            $tariffArr = [];

            foreach ($tariffs->tariffs as $val) {
                if($val->region_id==$region_id){
                    $tariffArr[$val->label] = (float)$val->price;
                }
            }
            // IF CORPORATE BEGIN TARIFF
            if($invoice->corporate==1){
                $user_id = $invoice->user_id;
                $tariff_array = [];
                $user = DB::table("users")->select("tariffs")->where("id",$user_id)->first();
                if($user!=null){
                    $tariffAll = json_decode($user->tariffs,true);
                    if($invoice->region_id==1 and isset($tariffAll["tariffs"][$invoice->country_id][1])) {
                        $tariff_array = $tariffAll["tariffs"][$invoice->country_id][1]; // Baki
                    }
                    elseif($invoice->region_id==3 and isset($tariffAll["tariffs"][$invoice->country_id][2])){
                        $tariff_array = $tariffAll["tariffs"][$invoice->country_id][2]; // Sumqayit
                    }else{
                        if(isset($tariffAll["tariffs"][$invoice->country_id][3])){
                            $tariff_array = $tariffAll["tariffs"][$invoice->country_id][3]; //Regionlar
                        }
                    }

                    if(count($tariff_array)>=1){
                        foreach ($tariff_array as $t){
                            $tariffArr[$t["label"]] = floatval($t["price"]);
                        }
                    }

                }
            }
            // IF CORPORATE END TARIFF

            $cm = 99;

            $weightResult = (float) ($invoice->length > $cm || $invoice->width > $cm || $invoice->height   > $cm) ? dimensionalWeight($invoice->width, $invoice->length, $invoice->height, $invoice->weight ) : $invoice->weight ;


            if($weightResult>0) {
                if ($weightResult >= 0 && $weightResult <= 0.25) {
                    $result = $tariffArr['minWeightPrice'];
                } elseif ($weightResult > 0.25 && $weightResult <= 0.5) {
                    $result = $tariffArr['halfWeightPrice'];
                } elseif ($weightResult > 0.5 && $weightResult <= 0.7) {
                    $result = $tariffArr['bigHalfWeightPrice'];
                } elseif ($weightResult > 0.7 && $weightResult <= 1) {
                    $result = $tariffArr['weightPrice'];
                } else {
                    $result = round($tariffArr['weightPrice'] * $weightResult, 2);
                }
            }

            $invoice->delivery_price = $result;
            $invoice->shipping_price = $result;
            if($invoice->save()){
                $depoPackage->invoice_id = $invoice_id;
                $depoPackage->status_id = 1;
                $depoPackage->save();
            }

            return 1;
        }

        return 0;

    }


    public function removePackage(Request $request)
    {
        $id = $request->package_id;
        $depoPackage = DepoPackages::where("id",$id)->where("status_id","!=",2)->first();

        if($depoPackage!=null){
            if($depoPackage->delete()){
                return response()->json(['status' => 200, 'message' => 'Package deleted']);
            }
        }


        return response()->json(['status' => 500, 'message' => 'Wrong Package']);
    }

    public function addPackage(Request $request)
    {
        $admin_id = Auth::guard('usa')->user()->id;

        $id = $request->package_id;
        $depoPackage = DepoPackages::find($id);

        $package_invoice_id = 0;

        if($depoPackage!=null){
            $package_invoice_id = $depoPackage->invoice_id;
        }

        $invoice = Invoice::where("purchase_no",$package_invoice_id)->where("status_id",1)->first();
        if($invoice!=null){

            $old_status_id = $invoice->status_id;

            $invoice->weight    = $depoPackage->weight;//lbToKg( $depoPackage->weight);
            $invoice->length    = $depoPackage->length;
            $invoice->width     = $depoPackage->width;
            $invoice->height    = $depoPackage->height;
            $invoice->status_id = 2;
            $region_id = $invoice->region_id;

            $result = calculateInvoiceShipping($invoice);

            $invoice->delivery_price = $result;
            $invoice->shipping_price = $result;

            if($invoice->save()){
                $depoPackage->status_id = 2;
                $depoPackage->save();
                if($old_status_id!=2){
                    $invoice_date=new InvoiceDates();
                    $invoice_date->status_id=2;
                    $invoice_date->invoice_id=$invoice->id;
                    $invoice_date->action_date=date('Y-m-d H:i:s');
                    $invoice_date->save();
                }


                // Saklanan koli begin
                $currency = Currencies::where("name","=","try-usd")->first();
                $invoices = Invoice::where("user_id",$invoice->user_id)->where("status_id",2)->where("id","!=",$request->id)->get();

                $depo_price = 0;
                foreach ($invoices as $item){
                    $depo_price += $item->shipping_price;
                    if($item->country_id==2){
                        $depo_price += $item->price;
                    }elseif($item->country_id==1){
                        if($currency!=null){
                            $depo_price += $item->price*$currency->val;
                        }
                    }
                }

                if($invoice->status_id == 2){
                    $contact = UserContact::where('user_id', '=', $invoice->user_id)->first();
                    $data = (object) ['text' => 'Baglamaniz Amerika anbarimiza daxil olub. Baglamanizi https://limak.az vasitesi ile izleye bilersiniz.', "user_id" =>$invoice->user_id];
                    smsSend($data, $contact->name);
                }

                if($invoice->shipping_price>0){

                    $user_id = $invoice->user_id;
                    $client_id = intval($invoice->client_id);
                    $person_id = intval($invoice->person_id);
                    $user_price = userLast30($user_id,$client_id,$person_id);

                    $all_price = $invoice->price + $invoice->shipping_price + $user_price;

                    $all_price += $depo_price;

                    if($all_price>1000){
                        $status = Status::where('label', '=', 'stored_auto')->where('type', '=', 'invoice')->first();
                        $invoice->status_id = $status->sid;
                        $invoice->save();

                        $invoice_date=new InvoiceDates();
                        $invoice_date->status_id=$status->sid;
                        $invoice_date->invoice_id=$invoice->id;
                        $invoice_date->action_date=date('Y-m-d H:i:s');
                        $invoice_date->note = 'Admin '.$admin_id;
                        $invoice_date->save();
                        return response()->json(['status' => 201, 'message' => 'Invoice is stored box']);

                    }
                }
                // Saklanan koli end


                return response()->json(['status' => 200, 'message' => 'Invoice is uploaded']);
            }


        }

        return response()->json(['status' => 500, 'message' => 'Invoice is worng']);
    }


    public function confirmPackage(Request $request)
    {
        $client_id = 0;
        $user_id = 0;

        $admin_id = Auth::guard('usa')->user()->id;
        $package_id = $request->id;
        $invoice_id = intval($request->invoice_id);
        $invoice  = Invoice::where("status_id",1)->where("purchase_no",$invoice_id)->first();

        $depo = DepoPackages::where("id",$package_id)->where("status_id",0)->first();

        $uniqid = $request->user_id;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
            $client_id = intval(substr($uniqid,1,6));
            $client = Client::find($client_id);
            if($client != null){
                $client_id = $client->id;
                $uniqid = $client->uniqid;
            }
        }
        $User = User::where('uniqid', '=', $uniqid)->first();

        if($depo!=null and $invoice_id==0 and $User!=null){
            $user_id = $User->id;
            $newRelUserProduct = new RelUserProduct();
            $newRelUserProduct->status_id = 1;
            $newRelUserProduct->client_id = $client_id;
            $newRelUserProduct->user_id = $user_id;
            $newRelUserProduct->is_paid = 1;
            $newRelUserProduct->is_ordered = 1;
            $newRelUserProduct->save();

            $newProduct = new Product();
            $newProduct->user_id = $user_id;
            $newProduct->client_id = $client_id;
            $newProduct->region_id = $User->region_id;
            $newProduct->product_type_id = 0;
            $newProduct->admin_id = $admin_id; //Gunay Memmedova
            $newProduct->product_type_name = $depo->description;
            $newProduct->rel_user_product_id = $newRelUserProduct->id;
            $newProduct->price = $depo->price;
            $newProduct->quantity = $depo->quantity;
            $newProduct->shop_name = $depo->store;
            $newProduct->description = '';
            $newProduct->status_id = 2;
            $newProduct->save();

            $newPackage = new Packages();
            $newPackage->status=1;
            $newPackage->save();

            $newInvoice = new Invoice();
            $newInvoice->user_id = $User->id;
            $newInvoice->client_id = $client_id;
            $newInvoice->corporate = $User->corporate;
            $newInvoice->region_id = $User->region_id;
            $newInvoice->country_id = 2;
            $newInvoice->product_id = $newProduct->id;
            $newInvoice->price = $depo->price;
            $newInvoice->rel_user_product_id = $newRelUserProduct->id;
            $newInvoice->status_id = 1;
            $newInvoice->package_id = $newPackage->id;
            //$newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
            $newInvoice->order_tracking_number = $depo->tracking;
            $newInvoice->order_date = $depo->order_date;
            $newInvoice->file = null;
            $newInvoice->added_by = 'depo';


            if ($newInvoice->save()) {
                $newInvoice->purchase_no = str_pad($newInvoice->id, 9, 0, STR_PAD_LEFT);
                $newInvoice->save();

                $depo->user_id = $user_id;
                $depo->client_id = $client_id;
                $depo->status_id = 1;
                $depo->invoice_id =  $newInvoice->purchase_no;
                $depo->save();

                $contact = UserContact::where('user_id', '=', $newInvoice->user_id)->first();
                $data = (object) ['text' => 'Hormetli musteri, '.$depo->tracking.' tracking nomreli baglamanizi tesdiqlemeyiniz xahis olunur', "user_id" =>$newInvoice->user_id];
                smsSend($data, $contact->name);

                return response()->json(['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi']);
            }
        }elseif($invoice!=null){

            if($invoice->save()){
                $depo->invoice_id = $invoice_id;
                $depo->status_id = 1;
                $depo->save();
            }

            return response()->json(['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi']);
        }

    }

    public function store(Request $request)
    {
        $admin_id = Auth::guard('usa')->user()->id;
        $server_output = '';
        $rules = [
            'product_type_name' => 'required',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'shop_name' => 'required|string',
            //'invoice' => 'required',
            'order_number' => 'required',
            'order_date' => 'required',
        ];

        $messages = [
            'price.integer' => 'Price field is required',
            'quantity.integer' => 'Quantity is required',
            'product_type_id' => 'Select product type',
            'required' => 'Required',
        ];

        Validator::make($request->all(), $rules, $messages);
        $user_id = 0;
        $client_id = 0;
        $uniqid = $request->user_code;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
            $client_id = intval(substr($uniqid,1,6));
            $client = Client::find($client_id);
            if($client != null){
                $client_id = $client->id;
                $uniqid = $client->uniqid;
            }
        }
        $User = User::where('uniqid', '=', $uniqid)->first();
        if($User!=null){
            $user_id = $User->id;
        }

        $depo = new DepoPackages();
        $depo->user_id = $user_id;
        $depo->client_id = $client_id;
        $depo->user_code = $request->user_name;
        $depo->country_id = 2;
        $depo->order_date = $request->order_date;
        $depo->tracking = $request->order_number;
        $depo->description = $request->product_type_name;
        $depo->price = $request->price;
        $depo->currency = 'USD';
        $depo->quantity = $request->quantity;
        $depo->store = $request->shop_name;
        $depo->weight = $request->weight;
        $depo->length = $request->length;
        $depo->width = $request->width;
        $depo->height = $request->height;
        $depo->created_at = date("Y-m-d H:i:s");
        $depo->updated_at = date("Y-m-d H:i:s");
        $depo->status_id = 0;
        $depo->save();

        if ($depo->save()) {

            if($User!=null){
                $newRelUserProduct = new RelUserProduct();
                $newRelUserProduct->status_id = 1;
                $newRelUserProduct->client_id = $client_id;
                $newRelUserProduct->user_id = $user_id;
                $newRelUserProduct->is_paid = 1;
                $newRelUserProduct->is_ordered = 1;
                $newRelUserProduct->save();

                $newProduct = new Product();
                $newProduct->user_id = $user_id;
                $newProduct->client_id = $client_id;
                $newProduct->region_id = $User->region_id;
                $newProduct->product_type_id = 0;
                $newProduct->admin_id = $admin_id; //Gunay Memmedova
                $newProduct->product_type_name = $request->product_type_name;
                $newProduct->rel_user_product_id = $newRelUserProduct->id;
                $newProduct->price = $request->price;
                $newProduct->quantity = $request->quantity;
                $newProduct->shop_name = $request->shop_name;
                $newProduct->description = '';
                $newProduct->status_id = 2;
                $newProduct->save();

                $newPackage = new Packages();
                $newPackage->status=1;
                $newPackage->save();

                $newInvoice = new Invoice();
                $newInvoice->user_id = $User->id;
                $newInvoice->client_id = $client_id;
                $newInvoice->corporate = $User->corporate;
                $newInvoice->region_id = $User->region_id;
                $newInvoice->country_id = 2;
                $newInvoice->product_id = $newProduct->id;
                $newInvoice->price = $request->price;
                $newInvoice->rel_user_product_id = $newRelUserProduct->id;
                $newInvoice->status_id = 1;
                $newInvoice->package_id = $newPackage->id;
                //$newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
                $newInvoice->order_tracking_number = $request->order_number;
                $newInvoice->order_date = $request->order_date;
                $newInvoice->weight = $request->weight;
                $newInvoice->length = $request->length;
                $newInvoice->width = $request->width;
                $newInvoice->height = $request->height;
                $newInvoice->file = null;
                $newInvoice->added_by = 'depo';

                if ($newInvoice->save()) {
                    $newInvoice->purchase_no = str_pad($newInvoice->id, 9, 0, STR_PAD_LEFT);
                    $shipping_price = calculateInvoiceShipping($newInvoice);
                    $newInvoice->shipping_price  = $shipping_price;
                    $newInvoice->delivery_price  = $shipping_price;
                    $newInvoice->save();

                    $depo->status_id = 1;
                    $depo->invoice_id =  $newInvoice->purchase_no;
                    $depo->save();

                    $contact = UserContact::where('user_id', '=', $newInvoice->user_id)->first();
                    $data = (object) ['text' => 'Hormetli musteri, '.$depo->tracking.' tracking nomreli baglamanizin qiymetini yazaraq tesdiqlemeyinizi xahis olunur', "user_id" =>$newInvoice->user_id];
                    smsSend($data, $contact->name);

                    return response()->json(['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi']);
                }
            }else{
                $person_id = 0;
                if(1){//substr($uniqid,0,1)==1 and strlen(trim($uniqid))==6
                    $company_user_id = intval(trim($uniqid));
                    $person = Persons::where("auto_id",$company_user_id)->first();
                    if($person!=null){
                        $person_id = $person->id;
                    }

                    $ch = curl_init();

                    $postFields = 'company=limak&password=Limak123456@&limak_user_id='.$person_id.'&user_id='.$uniqid.'&limak_package_id='.$depo->id.
                        '&order_date='.$depo->order_date.'&tracking='.$depo->tracking.'&product_name='.$depo->description.
                        '&currency='.$depo->currency.'&quantity='.$depo->quantity.'&shop_name='.$depo->store.
                        '&weight='.$depo->weight.'&length='.$depo->length.'&width='.$depo->width.'&height='.$depo->height;

                    curl_setopt($ch, CURLOPT_URL,"https://vipex.az/api/batches");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$postFields);


                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //0 or 1
                    $server_output = curl_exec($ch);
                    if($server_output){
                        $depo->user_id = $uniqid;
                        $depo->save();
                    }
                    curl_close ($ch);

                }
            }


            return response()->json(['status' => 200, 'message' => 'Invoice is uploaded'.$server_output]);
        }

    }

    public function getPackage($id)
    {
        $data = DB::table("foreign_depo as f")->where("f.parcel_id",$id)->first();
        $user = (object)["address" => "","phone" => "","name" => "","surname" => "","email" => ""];
        if($data!=null){
            $user_id = $data->user_id;
            if(intval($user_id)==0){
                $user_id = str_replace("LMA-","",$data->suite_number);
            }

            if($user_id>0){
                $user = DB::table("users as u")
                    ->select("u.name",'u.surname','u.address',"u.email","uc.name as phone")
                    ->leftJoin("user_contacts as uc","u.id","uc.user_id")
                    ->where("u.id",$user_id)->first();

                iF($user!=null){
                    $data->user_id = $user_id;
                }else{
                    $user = (object)["address" => "","phone" => "","name" => "","surname" => "","email" => ""];

                }

            }
            return view('usa.packages.get_package', compact('data','user'));
        }else{
            echo "Package not found";
        }
    }

    public function getDepoPackage($id)
    {
        $data = DB::table("depo_packages")->where("id",$id)->where("status_id",0)->first();
        if($data!=null){

            return view('usa.packages.get_depo_package', compact('data'));
        }else{
            echo "Package not found";
        }
    }


    public function packagePostParcelContents(Request $request)
    {
        $curl = curl_init();

        $parcel_id = $request->get("parcel_id");
        $description = $request->get("desc");//'1 PCSS SHOES';
        $desc = urlencode($description);
        $value = $request->get("price");
        $url = "https://api.ypn.io/v1/fulfillments/parcels/$parcel_id/contents?description=$desc&value=$value";

        $package_invoice_id = $request->get("package_invoice_id",0);
        $package_user_id = $request->get("package_user_id",0);
        $package_address = $request->get("package_address");
        $package_email = $request->get("package_email");
        $package_phone = $request->get("package_phone");
        $package_company = $request->get("package_company");

        $data = ForeignDepo::where("parcel_id",$parcel_id)->where("status_id","<",2)->first();

        if($data!=null){
            if($package_invoice_id>0){
                $data->invoice_id = $package_invoice_id;
            }

            if($package_user_id>0){
                $data->user_id = $package_user_id;
                $data->suite_number = "LMA-".$package_user_id;
                $data->client_phone = $package_phone;
                $data->client_company = $package_company;
                $data->client_email = $package_email;
                $data->client_address = $package_address;
                $data->price = $value;
                $data->description = $description;
            }

            $data->save();


            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "Accept: */*",
                    "Accept-Encoding: gzip, deflate",
                    "Authorization: Basic TWpBPS5YY2lma0pNc1lkQzBwc240OTNwczAxU00wUkY3RG8zY3p4cE8xcCtpckZJPTo=",
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "Content-Length: 0",
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;

                $invoice = Invoice::find($package_invoice_id);
                if($invoice!=null){
                    $old_status_id = $invoice->status_id;

                    $invoice->weight    = lbToKg( $data->weight);
                    $invoice->length    = inchToSm($data->length);
                    $invoice->width     = inchToSm($data->width);
                    $invoice->height    = inchToSm($data->height);
                    $invoice->status_id = 2;
                    $invoice->purchase_no = $data->parcel_id;
                    $region_id = $invoice->region_id;
                    $tariffs = Country::where('prefix', '=', 'usa')->with('tariffs')->first();

                    $tariffArr = [];

                    foreach ($tariffs->tariffs as $val) {
                        if($val->region_id==$region_id){
                            $tariffArr[$val->label] = (float)$val->price;
                        }
                    }
                    // IF CORPORATE BEGIN TARIFF
                    if($invoice->corporate==1){
                        $user_id = $invoice->user_id;
                        $tariff_array = [];
                        $user = DB::table("users")->select("tariffs")->where("id",$user_id)->first();
                        if($user!=null){
                            $tariffAll = json_decode($user->tariffs,true);
                            if($invoice->region_id==1 and isset($tariffAll["tariffs"][$model->country_id][1])) {
                                $tariff_array = $tariffAll["tariffs"][$invoice->country_id][1]; // Baki
                            }
                            elseif($invoice->region_id==3 and isset($tariffAll["tariffs"][$model->country_id][2])){
                                $tariff_array = $tariffAll["tariffs"][$invoice->country_id][2]; // Sumqayit
                            }else{
                                if(isset($tariffAll["tariffs"][$model->country_id][3])){
                                    $tariff_array = $tariffAll["tariffs"][$invoice->country_id][3]; //Regionlar
                                }
                            }

                            if(count($tariff_array)>=1){
                                foreach ($tariff_array as $t){
                                    $tariffArr[$t["label"]] = floatval($t["price"]);
                                }
                            }

                        }
                    }
                    // IF CORPORATE END TARIFF

                    $cm = 99;

                    $weightResult = (float) ($invoice->length > $cm || $invoice->width > $cm || $invoice->height   > $cm) ? dimensionalWeight($invoice->width, $invoice->length, $invoice->height, $invoice->weight ) : $invoice->weight ;


                    if($weightResult>0) {
                        if ($weightResult >= 0 && $weightResult <= 0.25) {
                            $result = $tariffArr['minWeightPrice'];
                        } elseif ($weightResult > 0.25 && $weightResult <= 0.5) {
                            $result = $tariffArr['halfWeightPrice'];
                        } elseif ($weightResult > 0.5 && $weightResult <= 0.7) {
                            $result = $tariffArr['bigHalfWeightPrice'];
                        } elseif ($weightResult > 0.7 && $weightResult <= 1) {
                            $result = $tariffArr['weightPrice'];
                        } else {
                            $result = round($tariffArr['weightPrice'] * $weightResult, 2);
                        }
                    }

                    $invoice->delivery_price = $result;
                    $invoice->shipping_price = $result;

                    $invoice->save();

                    if($old_status_id!=2){
                        $invoice_date=new InvoiceDates();
                        $invoice_date->status_id=2;
                        $invoice_date->invoice_id=$invoice->id;
                        $invoice_date->action_date=date('Y-m-d H:i:s');
                        $invoice_date->save();

                    }

                }

                // Invoice edit

                echo "<hr />";
                $address1 = urlencode($request->get("package_address",""));
                $address2 = urlencode('');
                $address3 = urlencode('');
                $email = urlencode($request->get("package_email",""));
                $phone = urlencode($request->get("package_phone",""));
                $company = urlencode($request->get("package_company",""));
                $suiteNumber = $data->suite_number;
                $country = 'AZ';

                $url = "https://api.ypn.io/v1/fulfillments/parcels/$parcel_id/consignee?company=$company&suiteNumber=$suiteNumber&name=$company&phone=$phone&address1=$address1&address2=$address2&address3=$address3&country=$country&email=$email";
                $curl = curl_init();


                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                        "Accept: */*",
                        "Accept-Encoding: gzip, deflate",
                        "Authorization: Basic TWpBPS5YY2lma0pNc1lkQzBwc240OTNwczAxU00wUkY3RG8zY3p4cE8xcCtpckZJPTo=",
                        "Cache-Control: no-cache",
                        "Connection: keep-alive",
                        "Content-Length: 0",
                        "Content-Type: application/json",
                        "cache-control: no-cache"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                    echo "Data has been saved! ";
                }

            }
        }






    }




}
