<?php

namespace App\Http\Controllers\cp;


use App\DepotLogs;
use App\InvoiceDates;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Depot;
use App\DepotInvoice;
use App\Invoice;
use Carbon\Carbon;
use DB;

class DepotController extends Controller
{
    public function addCustomProduct(Request $request)
    {
        $invoice_id = $request->post("invoice_id",0);
        if($invoice_id>0){
            $invoice = Invoice::where("id",$invoice_id)->where("status_id",getStatusByLabel('on_the_way', 'invoice'))->first();
            if($invoice!=null){
                $invoice->status_id = getStatusByLabel('custom', 'invoice');
                $invoice->save();
                $result = "ok";
                $code = 200;
                $inv_date  = new InvoiceDates();
                $inv_date->status_id = getStatusByLabel('custom', 'invoice');
                $inv_date->invoice_id = $invoice->id;
                $inv_date->save();
            }else{
                $result = 'Invoys tapilmadi';
                $code  = 401;
            }
        }else{
            $result = 'Invoys id duzgun gonderilmeyib';
            $code = 500;
        }

        $data = ["result" => $result,"code" => $code];
        return response()->json($data,200);
    }

    public function deleteCustomProduct(Request $request)
    {
        $region_id = $request->post("region_id",1);
        $invoice_id = $request->post("invoice_id",0);
        if($invoice_id>0){
            $invoice = Invoice::where("id",$invoice_id)->where("region_id",$region_id)->where("status_id",getStatusByLabel('custom', 'invoice'))->first();
            if($invoice!=null){
                $invoice->status_id = getStatusByLabel('on_the_way', 'invoice');
                $invoice->save();
                $result = "ok";
                $code = 200;
                $inv_date  = new InvoiceDates();
                $inv_date->status_id = getStatusByLabel('on_the_way', 'invoice');
                $inv_date->invoice_id = $invoice->id;
                $inv_date->save();
            }else{
                $result = 'Invoys tapilmadi';
                $code  = 401;
            }
        }else{
            $result = $invoice_id.' Invoys id duzgun gonderilmeyib';
            $code = 500;
        }

        $data = ["result" => $result,"code" => $code];
        return response()->json($data,200);
    }

    public function getDetails(Request $request){

        $region_id = $request->get("region_id",1);

        $depots_count = DepotInvoice::where("region_id",$region_id)->count('id');
        $data['depots_count'] = $depots_count;

        $all_places = Depot::where("region_id",$region_id)->sum('size');
        $empty_places = $all_places - $depots_count;
        $data['empty_places'] = $empty_places;

        $payed_products = Invoice::where("region_id",$region_id)->where('status_id','=',4)->where('is_paid', '=',1)->count();
        $data['payed_products'] = $payed_products;

        $delivered_products = Invoice::where("region_id",$region_id)->where('status_id','=',5)->count();
        $data['deliverd_products'] = $delivered_products;

        $custom_products = Invoice::where("region_id",$region_id)->where('status_id','=',8)->count();
        $data['custom_products'] = $custom_products;

        $region_products = Invoice::where("region_id",$region_id)->where('status_id','=',11)->count();
        $data['region_products'] = $region_products;

        $inDepot15Days =  DepotInvoice::where("region_id",$region_id)->where('created_at', '<', Carbon::now()->subDays(15)->toDateTimeString())->where('created_at', '>', Carbon::now()->subDays(45)->toDateTimeString())->count();
        $data['inDepot15Days']= $inDepot15Days;

        $inDepot45Days =  DepotInvoice::where("region_id",$region_id)->where('created_at', '<', Carbon::now()->subDays(45)->toDateTimeString())->count();
        $data['inDepot45Days']= $inDepot45Days;

        return response()->json($data,200);

    }

    public function show($id){
        return DB::table('depots')
            ->where('depots.id','=',$id)
            ->join('depot_invoices','depot_invoices.depot_id','=','depots.id')
            ->join('invoices','depot_invoices.invoice_id','=','invoices.id')
            ->join('users','users.id','=','invoices.user_id')
            ->leftJoin('products','products.id','=','invoices.product_id')
            ->orWhere(function ($query) {
                $query->where('invoices.status_id', '=', 4)
                    ->where('invoices.s_id', '=', 4);
            })
            /*->where('invoices.status_id','=',4)
            ->orWhere('invoices.s_id','=',4)*/
            ->get(array('invoices.id as invoice','depots.index','depots.number','users.name','users.surname','users.uniqid','depots.barcode_id','products.product_type_name'));
    }

    public function byUser(Request $request){

        $uniqid = $request->uniqid;
        $region_id = $request->get("region_id",1);
        return DB::table('depots')
            ->join('depot_invoices','depot_invoices.depot_id','=','depots.id')
            ->join('invoices','depot_invoices.invoice_id','=','invoices.id')
            ->join('users','users.id','=','invoices.user_id')
            ->join('products','products.id','=','invoices.product_id')
            ->where('invoices.status_id','=',4)
            ->where('depots.region_id','=',$region_id)
            ->where('users.uniqid','=',$uniqid)
            ->get(array('invoices.id as invoice','depots.index','depots.number','users.name','users.surname','users.uniqid','depots.barcode_id','products.product_type_name'));
    }

    public function packages(Request $request){
        $region_id = $request->region_id;
        return DB::table('depots')
            ->join('depot_invoices','depot_invoices.depot_id','=','depots.id')
            ->join('invoices','depot_invoices.invoice_id','=','invoices.id')
            ->join('users','users.id','=','invoices.user_id')
            ->leftJoin('products','products.id','=','invoices.product_id')
            ->orderBy('users.id','asc')
            ->where('invoices.status_id','=',4)
            ->where('depots.region_id','=',$region_id)
            ->get(array('invoices.id as invoice','depots.index','depots.number','users.name','users.surname','users.uniqid','depots.barcode_id','products.product_type_name'));
    }

    public function changeInvoicePlaceAll(Request $request){
        $old_barcode = $request->old_barcode;
        $new_barcode = $request->new_barcode;

        $old_depot_row = Depot::where('barcode_id','=',$old_barcode)->first();
        $new_depot_row = Depot::where('barcode_id','=',$new_barcode)->first();

        if($old_depot_row!=null && $new_depot_row!=null){
            $invoices = DepotInvoice::where('depot_id','=',$old_depot_row->id)->get();
            foreach ($invoices as $invoice){
                $invoice->depot_id = $new_depot_row->id;
                $invoice->save();

                $invoice_row = Invoice::find($invoice->invoice_id);
                $invoice_row->depo = $new_barcode;
                $invoice_row->save();

                $logs = new DepotLogs();
                $logs->invoice_id = $invoice->invoice_id;
                $logs->barcode_old = $old_depot_row->barcode_id;
                $logs->barcode_new = $new_depot_row->barcode_id;
                $logs->created_at = date("Y-m-d H:i:s");
                $logs->updated_at = date("Y-m-d H:i:s");
                $logs->save();
            }
        }

        return response()->json($new_depot_row->id,200);
    }

    public function changeInvoicePlace(Request $request){
        $invoice_id = $request->invoice_id;
        $barcode = $request->barcode;
        $depot_id = Depot::where('barcode_id','=',$barcode)->first()->id;
        $depot_invoice = DepotInvoice::where('invoice_id','=',$invoice_id)->first();
        $depot_invoice->depot_id = $depot_id;
        $depot_invoice->save();
        $new_place = DB::table('depot_invoices')
            ->join('depots','depots.id','=','depot_invoices.depot_id')
            ->where('depot_invoices.invoice_id','=',$invoice_id)
            ->first();

        $invoice = Invoice::find($invoice_id);
        $old_depot = $invoice->depo;

        $invoice->depo = $barcode;
        $invoice->save();

        $logs = new DepotLogs();
        $logs->invoice_id = $invoice_id;
        $logs->barcode_old = $old_depot;
        $logs->barcode_new = $barcode;
        $logs->created_at = date("Y-m-d H:i:s");
        $logs->updated_at = date("Y-m-d H:i:s");
        $logs->save();

        return response()->json($new_place,200);
    }

    public function getPayedProducts(Request $request){

        $region_id = $request->get("region_id",1);
        $payed_products = DB::table('invoices')
            ->where('invoices.status_id','=',4)
            ->where('invoices.is_paid','=',1)
            ->where('invoices.region_id','=',$region_id);

        if($request->has('uniqid'))
            $payed_products = $payed_products->where('users.uniqid', $request->get('uniqid'));

        $payed_products = $payed_products->join('users', 'users.id', '=', 'invoices.user_id')
            ->leftJoin('products', 'products.id', '=', 'invoices.product_id')
            ->join('depot_invoices','depot_invoices.invoice_id','=','invoices.id')
            ->join('depots','depots.id','=','depot_invoices.depot_id')
            ->orderBy('created_at','desc')
            ->get(array('invoices.*','depots.barcode_id','users.name','users.surname','users.uniqid','users.pin','users.balance','users.serial_number','products.product_type_name'));
        return response()->json($payed_products,200);
    }

    public function getDeliveredProducts(Request $request){
        $region_id = $request->get("region_id",1);
        $user_id = $request->get("user_id",false);
        $delivered_products = DB::table('invoices as i')
            ->select('i.updated_at', 'u.name', 'u.surname', 'u.uniqid', 'u.balance', 'i.weight', 'p.product_type_name', 'p.quantity', 'i.shipping_price','i.depo')
            ->where('i.status_id','=',5)
            ->where('i.region_id','=',$region_id);

        if($request->has('date1') && !empty($request->get('date1'))){
            $delivered_products = $delivered_products
                ->where('i.updated_at', '>', date('Y-m-d 00:00:01', strtotime($request->get('date1'))));
        }else{
            $myDate = date("Y-m-d 00:00:01", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-10 days" ) );
            $delivered_products = $delivered_products
                ->where('i.updated_at', '>', $myDate);
        }


        if($request->has('date2') && !empty($request->get('date2')))
            $delivered_products = $delivered_products
                ->where('i.updated_at', '<', date('Y-m-d 23:59:59', strtotime($request->get('date2'))));

        if($user_id){
            $delivered_products = $delivered_products->where("i.user_id",$user_id);
        }

        $delivered_products = $delivered_products
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->orderBy('i.updated_at','desc')
            ->paginate(30);
        return response()->json($delivered_products,200);
    }

    public function getDeliveredProductsAll(Request $request){
        $region_id = $request->get("region_id",1);
        $delivered_products = DB::table('invoices as i')
            ->select('i.updated_at', 'u.name', 'u.surname', 'u.uniqid', 'u.balance', 'i.weight', 'p.product_type_name', 'p.quantity', 'i.shipping_price','i.depo')
            ->where('i.status_id','=',5)
            ->where('i.region_id','=',$region_id);

        if($request->has('date1') && !empty($request->get('date1')))
            $delivered_products = $delivered_products
                ->where('i.updated_at', '>', date('Y-m-d 00:00:01', strtotime($request->get('date1'))));

        if($request->has('date2') && !empty($request->get('date2')))
            $delivered_products = $delivered_products
                ->where('i.updated_at', '<', date('Y-m-d 23:59:59', strtotime($request->get('date2'))));


        $delivered_products = $delivered_products
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->orderBy('i.updated_at','desc')
            ->get();
        return response()->json($delivered_products,200);
    }

    public function getCustomProducts(Request $request){

        $delivered_products = DB::table('invoices as i')
            ->select('i.id as invoice_id','i.updated_at', 'u.name', 'u.surname', 'u.uniqid', 'u.balance', 'i.weight', 'p.product_type_name', 'p.quantity', 'i.shipping_price')
            ->where('i.status_id','=',8);

        if($request->has('date1') && !empty($request->get('date1')))
            $delivered_products = $delivered_products
                ->where('i.updated_at', '>', date('Y-m-d 00:00:01', strtotime($request->get('date1'))));

        if($request->has('date2') && !empty($request->get('date2')))
            $delivered_products = $delivered_products
                ->where('i.updated_at', '<', date('Y-m-d 23:59:59', strtotime($request->get('date2'))));


        $delivered_products = $delivered_products
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->orderBy('i.updated_at','desc')
            ->get();
        return response()->json($delivered_products,200);
    }

    public function getRegionInvoices(Request $request){

        $delivered_products = DB::table('invoices as i')
            ->select('i.id as invoice_id','i.updated_at', 'i.purchase_no','d.barcode_id as depo','di.depot_id','i.region_id','i.status_id','i.s_id','u.name', 'u.surname', 'u.uniqid', 'u.balance', 'i.weight', 'p.product_type_name', 'p.quantity', 'i.shipping_price')
            ->where('i.status_id','=',11)->where("i.s_id","!=",4);

        if($request->has('region_id') && !empty($request->get('region_id')))
            $delivered_products = $delivered_products
                ->where('i.region_id', '=', $request->get('region_id'));

        $delivered_products = $delivered_products
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('depot_invoices as di', 'i.id', '=', 'di.invoice_id')
            ->leftJoin('depots as d', 'di.depot_id', '=', 'd.id')
            ->orderBy('u.name', 'ASC')
            ->orderBy('u.surname', 'ASC')
            ->get();
        return response()->json($delivered_products,200);
    }

    public function get15DaysInDepot(Request $request){

        $region_id = $request->get("region_id",1);
        $inDepot15Days =  DB::table('depot_invoices as di')
            ->where('di.region_id',$region_id)
            ->where('di.created_at', '<', Carbon::now()->subDays(15)->toDateTimeString())
            ->where('di.created_at', '>', Carbon::now()->subDays(45)->toDateTimeString())
            ->selectRaw('datediff(CURDATE(), id.action_date) as daysAgo,i.shipping_price,i.purchase_no,i.depo,di.*,u.name,u.surname,u.uniqid,u.serial_number,u.pin,u.balance')
            ->join('invoices as i','i.id','=','di.invoice_id')
            ->join('users as u','u.id','=','i.user_id')
            ->leftJoin('invoice_dates as id','i.id','=','id.invoice_id')
            ->where('id.status_id',4)
            ->get();
        return response()->json($inDepot15Days,200);
    }

    public function get45DaysInDepot(Request $request){

        $region_id = $request->get("region_id",1);
        $inDepot15Days =  DB::table('depot_invoices as di')
            ->where('di.region_id',$region_id)
            ->where('di.created_at', '<', Carbon::now()->subDays(45)->toDateTimeString())
            ->selectRaw('datediff(CURDATE(), id.action_date) as daysAgo,i.shipping_price,i.depo,i.purchase_no,di.*,u.name,u.surname,u.uniqid,u.serial_number,u.pin,u.balance')
            ->join('invoices as i','i.id','=','di.invoice_id')
            ->join('users as u','u.id','=','i.user_id')
            ->leftJoin('invoice_dates as id','i.id','=','id.invoice_id')
            ->where('id.status_id',4)
            ->get();
        return response()->json($inDepot15Days,200);
    }

    public function get45DaysInDepot_old(Request $request){
        $region_id = $request->get("region_id",1);
        $inDepot45Days =  DepotInvoice::where("region_id",$region_id)->where('created_at', '<', Carbon::now()->subDays(45)->toDateTimeString())->get();
        return response()->json($inDepot45Days,200);
    }


    public function addDepotStatusForAll(Request $request){

        $region_id = $request->get("region_id",1);
        $invoices = DB::table('invoices as i')
            ->select('i.id')
            ->where('i.status_id', '=', 11)
            ->where('i.s_id', '=', 4)
            ->where('i.region_id', '=', $region_id)
            //->where('i.updated_at', '<', date("Y-m-d 23:59:59"))
            //->where('i.updated_at', '>', date("Y-m-d  00:00:01"))
            ->get();

        $invoiceStatus = getStatusByLabel('home', 'invoice');
        foreach($invoices as $item){
            $invoiceRow  = Invoice::find($item->id);
            $invoiceRow->status_id = 4;
            $invoiceRow->s_id = 0;
            $invoiceRow->save();
            InvoiceDates::create([
                'status_id' => $invoiceStatus,
                'invoice_id' => $invoiceRow->id,
                'action_date' => Carbon::now()
            ]);
        }
        return response()->json(['success' => true]);
    }

}
