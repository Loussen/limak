<?php

namespace App\Http\Controllers\Site\Panel;

use App\Contact;
use App\Http\Controllers\Front\PayTrController;
use App\InvoiceDates;
use App\InvoicePayment;
use App\ModelLogs\LogBalance;
use App\ModelUser\User;
use App\Currency;
use App\Invoice;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\UserPromoCodes;
use Carbon\Carbon;

use function PHPSTORM_META\type;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($type)
    {
//        $belongsToCourier = Input::get('courier');
        $ifInvoiceHasCourierId = Input::get('invoice_courier');
        $response = null;
        $userId = Auth()->user()->id;
        $statusId = null;
        $country_id = Input::get('country_id',1);
        $client_id = Input::get('client_id',0);



        if(isset($type) && $type != 'undefined') {
            $response = $this->getTypedInvoices($userId, $type,$ifInvoiceHasCourierId,$country_id,$client_id);
        } else {
            $response = $this->getAllInvoices($userId,$country_id,$client_id);
        }
        $balance = Auth::user()->balance;
        $index = Currency::first();
        return response()->json(['data' => $response, 'balance' => $balance, 'index' => $index,'type' =>$type]);
    }

    public function view($id)
    {
        $userId = Auth()->user()->id;
        $invoice = Invoice::with('dates')->with('packages')->with('packages.products')->with('packages.products.extras')->with('invoiceStatus')->where("id",$id)->where("user_id",$userId)->first();

        return response()->json(['data' => $invoice]);
    }

    public function allInvoicesCount(Request $request)
    {
        $userId = Auth()->user()->id;
        $country_id = $request->get('country_id',1);

        $data = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select(DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->whereBetween('i.status_id',[1,8])
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        return response()->json([
            'data' => $data
        ]);
    }

    public function invoiceCount(Request $request){
        $userId = Auth()->user()->id;
        $country_id = $request->get('country_id',1);

        $data1 = DB::table('invoices as i')
            ->select('i.status_id', DB::raw('count(i.id) as  count'))
            ->where('i.user_id', '=', $userId)
            ->whereBetween('i.status_id',[1,16])
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->groupBy("i.status_id")
            ->get();
        $array = [
            "waiting" => 0,
            "foreign" => 0,
            "on_the_way" => 0,
            "at_custom" => 0,
            "home" => 0,
            "completed" => 0,
            "has_courier" => 0,
            "returns" => 0,

        ];

        $all = 0;

        foreach($data1 as $data){
            if($data->status_id==1){
                $array["waiting"] = $data->count;
            }elseif($data->status_id == 2){
                $array["foreign"] = $data->count;
            }elseif($data->status_id == 3){
                $array["on_the_way"] = $data->count;
            }elseif($data->status_id == 11){
                $array["at_custom"] = $data->count;
            }elseif($data->status_id == 4){
                $array["home"] = $data->count;
            }elseif($data->status_id == 5){
                $array["completed"] = $data->count;
            }elseif($data->status_id == 7){
                $array["has_courier"] = $data->count;
            }elseif($data->status_id == 16){
                $array["returns"] = $data->count;
            }

            $all = $all + $data->count;
        }

        $array["all"] = $all;

        return response()->json([
            'data' => $array
        ]);
    }

    private function getAllInvoices ($userId,$country_id=1,$client_id=0) {

        $data = DB::table('invoices as i')
            ->select( 'i.id','i.created_at','i.order_tracking_number','i.is_paid','i.status_id','i.added_by','i.price','i.shipping_price',
                'p.shop_name',
                'st.name as status_name')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('statuses as st', 'st.sid', '=', 'i.status_id')
            ->where('i.user_id', '=', $userId)
            ->where('i.active','=',1)
            ->where('i.country_id','=',$country_id)
            ->where('st.type','=','invoice');

        if(intval($client_id)>0){
            $data = $data->where('i.client_id','=',$client_id);
        }

        $data =  $data->whereBetween('i.status_id',[1,11])
            ->orderBy('i.id','desc')
            ->get();

        return $data;
    }

    private function getTypedInvoices ($userId, $type, $ifInvoiceHasCourierId=null,$country_id=1,$client_id=0) {
        $statusId = Status::where('label', '=', $type)->where('type', '=', 'invoice')->get();


        $data = DB::table('invoices as i')
            ->select( 'i.id','i.created_at','i.order_tracking_number','i.is_paid','i.status_id','i.price','i.added_by','i.shipping_price',
                'p.shop_name',
                'st.name as status_name')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('statuses as st', 'st.sid', '=', 'i.status_id')
            ->where('i.user_id', '=', $userId)
            ->where('i.active','=',1)
            ->where('i.country_id','=',$country_id)
            ->where('st.type','=','invoice')
            ->orderBy('i.id','desc');

            $data = $data->where('i.status_id','=',$statusId[0]->sid);
            if(intval($client_id)>0){
                $data = $data->where('i.client_id','=',$client_id);
            }
            $data=$data->whereNull('i.courier_id');
            $data=$data->whereNull('i.transfer_id');


            $data=$data->get();
        return $data;
    }

    public function deleteInvoice(Request $request)
    {
        $invoice_id = $request->get("invoice_id",false);
        $user_id = Auth::user()->id;

        $invoice = DB::table("invoices")->where("user_id",$user_id)->where("id",$invoice_id)->where("status_id",1)->where("added_by","user")->first();
        if($invoice!=null){
            $update = DB::table("invoices")->where("id",$invoice_id)->update(['status_id' => 777]);
            $date=new InvoiceDates();
            $date->invoice_id=$invoice_id;
            $date->status_id=777;
            $date->note = 'User deleted';
            $date->action_date=Carbon::now();
            $date->save();
            return response()->json(["message" => 'OK', "status" => 200]);
        }else{
            return response()->json(["message" => 'Bu bağlamanı silmək olmaz',"status" => 500]);
        }

    }

    public function payBasket(Request $request)
    {
        $uniqueId = time().Auth::user()->id;

        if (!empty($request->products) && count($request->products) > 0) {
            $validation = [
                'price' => 'required|numeric',
                'products' => 'required',
            ];
            $request->validate($validation);
            $promo_code = $request->get("promo_code",false);

            $newRel=new RelUserProduct();
            $newRel->price = $request->price;
            $newRel->transaction_id = $uniqueId;
            $newRel->is_paid = 0;
            $newRel->is_ordered = 0;
            $newRel->user_id = Auth::user()->id;
            $newRel->status_id = 1;
            $newRel->admin_id = null;
            $newRel->created_at = date("Y-m-d H:i:s");
            $newRel->save();

            if($request->products!=null){
                for($i=0;$i<count($request->products);$i++){
                    DB::table('products')
                        ->where('id', $request->products[$i])
                        ->update(['rel_user_product_id' => $newRel->id]);
                }
            }

            $pay = new PayTrController();
            $token = $pay->payByTr($request->price, $request->products, $uniqueId);
            if($promo_code){
                UserPromoCodes::create([
                    "user_id" => auth()->id(),
                    "campaign_id" => 1,
                    "promo_code" => $promo_code,
                    "invoice_id" => 0,
                    "status" => 0
                ]);
            }
            return view('paymentTr', compact('token'));
        } else {
            return response()->json('Məlumatlar tam deyildir', 500);
        }
    }

    public function payInvoice(Request $request)
    {
        $invoices = $request->invoices;
        $price = Input::get('price', 0);
        $currency = Input::get('currency', 'azn');
        $user=User::where('id',Auth()->user()->id)->first();
        if($user->balance>=$price){
            for ($i = 0; $i < count($invoices); $i++) {
                $payment = new InvoicePayment();
                $payment->invoice_id = $invoices[$i];
                $payment->price = $price;
                $payment->currency = $currency;
                $payment->save();

                if($invoices[$i]>0){
                    $invoice = Invoice::where('id',$invoices[$i])->first();
                    $invoice->is_paid=1;
                    $invoice->save();
                }
            }
            $old_balance = $user->balance;
            $user->balance = $old_balance - $price;
            $user->save();



            $message = 'Balansdan çatdırılma haqqı ödənişi';

            LogBalance::create([
                'user_id' => $user->id,
                'old_balance' => $old_balance,
                'new_balance' => $user->balance,
                'money' => $price,
                'message' => $message,
                'type' => 'azn'
            ]);

            return response()->json([
                'data'=>'ok'
            ]);
        }else{
            return response()->json(['data' => 'Balansınız çatmır', 'code' => 1601]);
        }


    }

    public function orderStatus()
    {
        $orderStatus = orderStatus();
        return response()->json(['status' => $orderStatus["status"], 'message' => $orderStatus["text"]]);
    }

}
