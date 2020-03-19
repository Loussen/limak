<?php
namespace App\Http\Controllers\Cp;
use App\Admins;
use App\Courier;
use App\Currencies;
use App\DepotInvoice;
use App\Http\Middleware\Admin;
use App\Invoice;
use App\InvoiceDates;
use App\ModelUser\User;
use App\CashBack;
use http\Env\Response;
use Illuminate\Http\Request;
use App\ModelAccount\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class CourierController extends BaseController
{
    public function getCourierOrders () {
        $has_courier = Input::get('has_courier',0);
        $courier_user_id = Input::get('courier_user_id',0);
        $status = Input::get('status',4);
        $status_id = Input::get('status_id',0);
        $data = DB::table("couriers as c")
            ->select('c.id','c.user_id','c.is_paid','c.price','c.has_courier','c.courier_user_id','c.phone','c.city','c.district','c.village','c.street','c.home','c.description','c.created_at',
                'u.uniqid','u.name','u.surname','u.balance','u.email',DB::raw("count('i.id') as count"),DB::raw("SUM(i.shipping_price) as sum_price"),DB::raw("SUM(i.is_paid) as paid_count")
            )
            ->leftJoin('invoices as i', 'i.courier_id', '=', 'c.id')
            ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
            ->where('i.status_id', '=', $status)
            ->where('c.has_courier', '=', $has_courier)
            ->where('i.active','=',1)
            ->groupBy('i.courier_id')
            ->orderBy('c.created_at','DESC');

        if($courier_user_id>0){
            $data = $data->where("c.courier_user_id",$courier_user_id);
        }

        /*if($status_id==1){
            $data  = $data->where("c.is_paid","=",1);
        }elseif($status_id==2){
            $data  = $data->where("c.is_paid","=",0);
        }*/

        if($status==7){
            $data = $data->paginate(20);
        }else{
            $data = $data->get();
        }



        $currency=Currencies::where('name','=','usd-azn')->first();

        return response()->json([
            "status" => 200,
            "data" => $data,
            "usdToAzn"=>$currency->val
        ]);

    }

    public function getCourierOrdersPrint () {
        $has_courier = Input::get('has_courier',0);
        $courier_user_id = Input::get('courier_user_id',0);
        $status = Input::get('status',4);
        $data = DB::table("couriers as c")
            ->select('c.id','c.user_id','c.is_paid','c.price','c.has_courier','c.courier_user_id','c.phone','c.city','c.district','c.village','c.street','c.home','c.description','c.created_at',
                'u.uniqid','u.name','u.surname','u.balance','u.email',DB::raw("count('i.id') as count"),DB::raw("SUM(i.shipping_price) as sum_price"),DB::raw("SUM(i.is_paid) as paid_count")
            )
            ->leftJoin('invoices as i', 'i.courier_id', '=', 'c.id')
            ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
            ->where('i.status_id', '=', $status)
            ->where('c.has_courier', '=', $has_courier)
            ->where('i.active','=',1)
            ->groupBy('i.courier_id')
            ->orderBy('c.created_at','DESC');

        if($courier_user_id>0){
            $data = $data->where("c.courier_user_id",$courier_user_id);
        }

        $admin = false;
        if($courier_user_id>0){
            $admin = Admins::find($courier_user_id);
        }

        if($status==7){
            $data = $data->paginate(20);
        }else{
            $data = $data->get();
        }

        $c_ids = [];
        if(count($data)>0){
            foreach ($data as $c){
                $c_ids[] = $c->id;
            }
        }


        $prices = [];
        $invoices = DB::table("invoices")->select("id","shipping_price","is_paid","courier_id")->whereIn("courier_id",$c_ids)->get();
        foreach ($invoices as $invoice){
            if(!isset($prices[$invoice->courier_id])){
                $prices[$invoice->courier_id] = 0;
            }

            if($invoice->is_paid==0){
                $prices[$invoice->courier_id] = $prices[$invoice->courier_id] + $invoice->shipping_price;
            }

        }
        $currency=Currencies::where('name','=','usd-azn')->first();

        return view('cp.account.orderCourierPrint',[
            "status" => 200,
            "orders" => $data,
            "admin" => $admin,
            "prices" => $prices,
            "usdToAzn"=>$currency->val
        ]);

    }


    public function getCourierInvoicesData(Request $request)
    {
        $courier_id = $request->get('courier_id',0);
        $data = DB::table('invoices as i')
                ->select('i.order_tracking_number','i.courier_id as c_id','u.balance', 'i.id as i_id','i.is_paid as invoice_paid','i.price','i.shipping_price','i.order_date','i.depo','p.product_type_name','p.shop_name')
                ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
                ->leftJoin('users as u', 'u.id', '=', 'i.user_id')
                ->where("i.courier_id",$courier_id)
                ->get();

        return response()->json([
            "status" => 200,
            "data" => $data,
        ]);
    }
    public function rejectOrder()
    {
        $id = Input::get('id',0);

        $invoice = Invoice::find($id);
        if($invoice->courier_id>0){
            $c_id = $invoice->courier_id;
            $data = DB::table('invoices as i')->where('courier_id', $c_id)
                ->update(['courier_id' => null]);
        }


        return response()->json([
            "status" => 200,
            "data" => $id,
        ]);
    }

    public function addCourier (Request $request) {

        $status_6 = getStatusByLabel('has_courier', 'invoice');
        $courierId = Input::get('courier_id',0);
        $courier_user_id = Input::get('courier_user_id',0);

        if($courierId!=0) {
            $courier = Courier::find($courierId);

            $invoices = Invoice::where('courier_id', '=', $courierId)->get();
            foreach ($invoices as $invoice){
                $date=new InvoiceDates();
                $date->invoice_id=$invoice->id;
                $date->status_id=$status_6;
                $date->action_date=Carbon::now();
                $date->save();

                $invoice->status_id=$status_6;
                $invoice->save();

                    DepotInvoice::where('invoice_id','=',$invoice->id)->delete();
            }
            $courier->has_courier=1;
            $courier->courier_user_id=$courier_user_id;
            $courier->save();

            $data = (object) ['text' => template("courier-assigned"),"user_id" =>$request->u_id];

            if($request->phone!=null) smsSend($data, $request->phone);
            if($request->email!=null) emailSend($data, $request->email);

            return response()->json([
                "status" => 200,
                "data" => 'Kuryer əlavə edildi',
            ]);
        }
        return response()->json([
            "status" => 404,
            "data" => 'Tapılmadı',
        ]);
    }

    public function completeOrder(Request $request){
        $courierId = Input::get('courierId',0);
        $invoice= Invoice::where('courier_id','=',$courierId)->get();
        foreach ($invoice as $item) {
            $date=new InvoiceDates();
            $date->invoice_id=$item->id;
            $date->status_id=7;
            $date->action_date=Carbon::now();
            $date->save();

            $item->status_id=7;
            $item->save();

            DepotInvoice::where('invoice_id','=',$item->id)->delete();
        }
        $data = (object) ['text' => template("courier-delivered"),"user_id" =>$request->u_id];

        if($request->phone!=null) smsSend($data, $request->phone);
        if($request->email!=null) emailSend($data, $request->email);

        return response()->json([
            "status" => 200,
            "data" => 'ok',
        ]);
    }

    public function printData()
    {
        $data = [];
        $array_str = Input::get('array');

        $array = explode(",",$array_str);

        $couriers = DB::table("couriers as c")
            ->select("c.id","c.price",'c.phone','c.city','c.district','c.village','c.street','c.home','c.description',
                'u.name','u.surname','u.uniqid','u.email','u.balance',
                'uc.name as user_phone'
                )
            ->leftJoin("users as u","u.id","c.user_id")
            ->leftJoin("user_contacts as uc","u.id","uc.user_id")
            ->whereIn("c.id",$array)->get();
        $dataHeader[0] = ["user",'phone','address','balance','price','description','shipping_price','all_price','depo','count'];

        $i=1;
        foreach ($couriers as $courier){
            $data[$i]["user"] = $courier->name." ".$courier->surname." ".$courier->uniqid;
            $data[$i]["phone"] = $courier->user_phone;
            $data[$i]["address"] = $courier->city." ".$courier->district." ".$courier->village." ".$courier->street." ".$courier->home." ".$courier->phone;
            if($courier->balance<=0){
                $data[$i]["balance"] = $courier->balance;
            }else{
                $data[$i]["balance"] = 0;
            }
            $data[$i]["price"] = $courier->price;
            $data[$i]["description"] = $courier->description;

            $invoices = DB::table("invoices")->select("id","shipping_price","depo")->where("courier_id",$courier->id)->get();

            $count = 1;
            $shipping_price = 0;
            $depo = '';
            foreach ($invoices as $invoice)
            {
                $shipping_price += $invoice->shipping_price;
                if($count>1){
                    $depo .= ',';
                }
                $depo .= $invoice->depo;

                $count++;
            }

            $data[$i]["shipping_price"] = $shipping_price;
            $data[$i]["all_price"] = $data[$i]["shipping_price"] + $data[$i]["price"] - $data[$i]["balance"] ;
            $data[$i]["depo"] = $depo;
            $data[$i]["count"] = $count;

            $i++;
        }
            $filename = "salam.xls";
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
            header("Content-Disposition: attachment; filename=\"$filename\"");

             $heading = false;
             $records = $data;
            if(!empty($records))
                foreach($records as $row) {
                    if(!$heading) {
                        // display field/column names as a first row
                        echo implode("\t", array_keys($row)) . "\n";
                        $heading = true;
                    }

                    echo implode("\t", array_values($row)) . "\n";
                }
        mb_convert_encoding($filename, 'UTF-16LE', 'UTF-8');



        exit;





        $filename = 'userData.csv';
        header("Content-type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$filename");
        $output = fopen("php://output", "w");

        $header = array_values($dataHeader[0]);
        mb_convert_encoding($output, 'UTF-16LE', 'UTF-8');

        fputcsv($output, $header);
        foreach($data as $row)
        {
            fputcsv($output, $row);
        }

        fclose($output);
        /*$json = json_encode($data);
        $data = json_decode($json);

        return response()->json([
            "status" => 200,
            "data" =>  $data,
        ]);*/
    }

    public function checkDepot()
    {
        $status_depo = getStatusByLabel('home', 'invoice');

        $barcode = Input::get('barcode',0);
        $i_id = Input::get('invoice_id',0);
        $depot = DB::table('depots')->where('barcode_id',$barcode)->first();
        if($depot!=null){
            $depot_i = DB::table('depot_invoices')->where('depot_id',$depot->id)->count();
            if($depot_i<50){
                $data = DB::table('depot_invoices')->insert(['depot_id' => $depot->id, 'invoice_id' => $i_id, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
                $invoice = Invoice::find($i_id);
                $invoice->courier_id = null;
                $invoice->status_id =$status_depo;
                $invoice->save();

                $inv_date = new InvoiceDates();
                $inv_date->status_id = $status_depo;
                $inv_date->invoice_id = $i_id;
                $inv_date->action_date = date("Y-m-d H:i:s");
                $inv_date->save();

                //$invoice = DB::table('invoices')->update(['courier_id' => null, 'status_id' => 4])->where('id',$i_id);
                return response()->json([
                    "status" => 200,
                    "data" => $data,
                ]);
            }else{
                return response()->json([
                    "status" => 500,
                    "data" => 'Yer doludur.',
                ]);
            }
        }else{
            return response()->json([
                "status" => 500,
                "data" => 'Anbarda belə yer mövcud deyil.',
            ]);
        }
    }

}
