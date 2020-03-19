<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 20.12.2018
 * Time: 23:18
 */

namespace App\Http\Controllers\Admin;


use App\Courier;
use App\Currency;
use App\Currencies;
use App\DepotInvoice;
use App\Invoice;
use App\InvoiceDates;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class CourierController extends Controller
{
    public function index () {
        return view('admin/courier/index');
    }

    public function checkDepot()
    {
        $status_depo = getStatusByLabel('home', 'invoice');

        $barcode = Input::get('barcode',0);
        $i_id = Input::get('invoice_id',0);
        $depot = DB::table('depots')->where('barcode_id',$barcode)->first();
        if($depot!=null){
            $depot_i = DB::table('depot_invoices')->where('depot_id',$depot->id)->count();
            if($depot_i<5){
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
    
    public function getCourierOrders () {

        $currency=Currencies::where('name','=','usd-azn')->first();

        $has_courier = Input::get('has_courier',0);
        $status = Input::get('status',4);
        $data = DB::table('couriers as c')
            ->select(
                'c.price as c_price','u.uniqid', 'u.name', 'u.surname', 'u.email','u.pin','u.balance',
//                 'p.quantity',
                'p.shop_name',
                'i.order_tracking_number', 'i.id as i_id','i.is_paid as invoice_paid','c.has_courier as has_courier',
                'c.id as c_id','c.phone as phone2','c.district','c.city','c.village','c.street','c.home','c.delivery_type','c.is_paid', 'c.created_at','c.description',
                'd.index as d_index','d.number as d_number',
                'u.id as u_id',
                'uc.name as phone',
                DB::raw('COUNT(i.id) as quantity'),
//                DB::raw('SUM(p.price) as price'),
                DB::raw('GROUP_CONCAT(p.product_type_name) as product_name'),
                DB::raw('GROUP_CONCAT(CONCAT(d.index," ",d.number)) as depot'),
//                DB::raw('COUNT(p.quantity) as count_product'),
                DB::raw('SUM(i.shipping_price) as shipping_price'),
                DB::raw('SUM(i.is_paid) as is_paids'),
                DB::raw('(select SUM(i1.shipping_price) from invoices as i1 where i1.status_id='.$status.' and i1.is_paid=0 and i1.user_id=i.user_id GROUP BY i1.user_id) as odenilmemis'),
                DB::raw('(select count(i1.id) from invoices as i1 where i1.status_id='.$status.' and i1.user_id=i.user_id GROUP BY i1.user_id) as count_depo')
            )
            ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as uc', 'uc.user_id', '=', 'u.id')
            ->leftJoin('invoices as i', 'i.courier_id', '=', 'c.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('depot_invoices as di','di.invoice_id','=','i.id')
            ->leftJoin('depots as d','di.depot_id','=','d.id')
            ->where('i.status_id', '=', $status)
            ->where('c.has_courier', '=', $has_courier)
            ->where('i.active','=',1)
            ->groupBy('i.courier_id')->orderBy('c.created_at','DESC')->get();
//        dd($data);
        return response()->json([
            "status" => 200,
            "data" => $data,
            "usdToAzn"=>$currency->val
        ]);
    }

    public function ordersCount(){
        $has_courier = Input::get('has_courier',0);
        $status = Input::get('status',4);
        $data = DB::table('couriers as c')
            ->select(
                'c.id'
            )
            ->leftJoin('invoices as i', 'i.courier_id', '=', 'c.id')
            ->where('i.status_id', '=', $status)
            ->where('c.has_courier', '=', $has_courier)
            ->where('i.active','=',1)
            ->groupBy('i.user_id')->orderBy('c.created_at','DESC')->get();
//        dd($data);
        return response()->json([
            "status" => 200,
            "data" => count($data),
        ]);
    }
    public function getDeliveredOrders () {
        $status = Input::get('status',7);
        $data = DB::table('couriers as c')
            ->select(
                'c.price as c_price','u.uniqid', 'u.name', 'u.surname', 'u.email','u.pin',
                'p.quantity','p.shop_name',
                'i.order_tracking_number','i.created_at',
                'c.phone','c.district','c.city','c.village','c.street','c.home','c.delivery_type','c.id','c.is_paid',
                DB::raw('SUM(p.quantity) as quantity'),
                DB::raw('SUM(p.price) as price'),
                DB::raw('GROUP_CONCAT(p.product_type_name) as product_name'),
                DB::raw('COUNT(p.quantity) as count_product')
            )
            ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
            ->leftJoin('invoices as i', 'i.courier_id', '=', 'c.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', $status)
            ->where('i.active','=',1)
            ->groupBy('p.user_id')
            ->orderBy('c.id', 'desc')->get();
        return response()->json([
            "status" => 200,
            "data" => $data,
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
    public function giveToDepot(){
        $courierId = Input::get('courierId',0);
        $invoice= Invoice::where('courier_id','=',$courierId)->get();
        foreach ($invoice as $item) {
            $item->status_id=4;
            $item->courier_id=null;
            $item->save();
        }
        return response()->json([
            "status" => 200,
            "data" => 'ok',
        ]);
    }
    public function completeDelivereOrder($id){
//        $id = Input::get('id',0);
//        var_dump($id);
        $invoice= Invoice::where('courier_id','=',$id)->get();
        foreach ($invoice as $item) {
            $item->status_id=5;
            $item->save();
        }
        return response()->json([
            "status" => 200,
            "data" => 'ok',
        ]);
    }
    public function getCourierUsers(){
        $data = DB::table('admins as a')
            ->select('a.*')
            ->leftJoin('rel_admin_roles as rr','rr.admin_id','=','a.id')
            ->where('rr.role_id','=',13)
            ->groupBy('a.id')
            ->get();
        return response()->json([
            "status" => 200,
            "data" => $data,
        ]);
    }

    public function getCourierInvoices(){
        $data = DB::table('admins as a')
            ->select('a.*', DB::raw('i.id as count'))
            ->leftJoin('rel_admin_roles as rr','rr.admin_id','=','a.id')
            ->leftJoin('invoices as i','i.courier_id','=','a.id')
            ->where('rr.role_id','=',13)
            ->where('i.status_id','=',6)
            ->groupBy('a.id')
            ->get();
        return response()->json([
            "status" => 200,
            "data" => $data,
        ]);
    }

    public function getCourierAnswerOrders () {
        $couriers = Courier::with( 'invoices.products', 'users')->where([
            ['has_courier', '=', Courier::OK_TYPE],
            ['is_paid', '=', Courier::NON_TYPE],
        ])->orderBy('id', 'desc')->get();

        return response()->json([
            "status" => 200,
            "data" => $couriers
        ]);
    }

    public function courierSelected(Request $request) {
        $courier = Courier::find($request['courierId'])->first();

        if ($courier->has_courier) {
            return response()->json([
                "status" => 201,
                "message" => "Artıq digər əməkdaş tərəfindən kuryer təyin edilib."
            ]);
        }

        $courier->has_courier = 1;

        if ($courier->save()) {
            notify((object)['template' => 'courier-assigned'], (object)['phone' => $courier->users->userContacts[0]->name, 'email' => $courier->users->email]);
            return response()->json([
                "status" => 200,
                "message" => "Kuryer təyin edildi"
            ]);
        }
    }

    public function productDelivered (Request $request) {
        $courier = Courier::with('invoices')->where('id', '=', $request['courierId'])->first();

        if ($this->checkInvoicesPaid($courier->invoices) && $courier->is_paid) {
            return response()->json([
                "status" => 201,
                "message" => "Artıq digər əməkdaş tərəfindən sifariş yekunlaşdırılıb."
            ]);
        }

        foreach ($courier->invoices as $invoice) {
            $invoice->is_paid = Courier::OK_TYPE;
            $invoice->status_id = getStatusByLabel('completed', 'invoice');
            $invoice->save();
        }

        $courier->is_paid = Courier::OK_TYPE;

        if ($courier->save()) {
            notify((object)['template' => 'courier-delivered'], (object)['phone' => $courier->users->userContacts[0]->name, 'email' => $courier->users->email]);
            return response()->json([
                "status" => 200,
                "message" => "Sifariş Yekunlaşmışdır"
            ]);
        }

    }

    public function courierDeliveryLogs () {
        $params = [];
        $couriers = Courier::with('invoices.products', 'users')->where('is_paid', '=', Courier::OK_TYPE)->orderBy('id', 'desc')->paginate(10);
        return view('admin/courier/log', compact('couriers', 'params'));
    }

    public function getCouriersByUserId () {
        $userId = Auth::user()->id;

        $courier = Courier::with( 'invoices.products')->where([
            ['user_id', '=', $userId],
            ['is_paid', '=', Courier::NON_TYPE],
        ]);

        return response()->json([
            "status" => 200,
            "data" => $courier
        ]);
    }

    private function checkInvoicesPaid ($invoices) {

        foreach ($invoices as $invoice) {
            if (!$invoice->is_paid) {
                return false;
            }
        }

        return true;
    }
}
