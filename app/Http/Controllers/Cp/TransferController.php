<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 20.12.2018
 * Time: 23:18
 */

namespace App\Http\Controllers\Cp;


use App\Courier;
use App\Currency;
use App\Currencies;
use App\DepotInvoice;
use App\Invoice;
use App\InvoiceDates;
use App\Http\Controllers\Controller;
use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class TransferController extends Controller
{
    public function getTransferOrders () {

        $currency=Currencies::where('name','=','usd-azn')->first();

        $status = Input::get('status',0);

        if($status == 0){
            $invoice_status = 4;
        }elseif ($status ==1){
            $invoice_status = 9;

        }


        $data = DB::table('transfers as t')
            ->select(
                'u.uniqid', 'u.name', 'u.surname', 'u.email','u.pin','u.balance',
//                 'p.quantity',
                'p.shop_name',
                'i.order_tracking_number', 'i.id as i_id','i.is_paid as invoice_paid',
                't.id as t_id','t.phone','t.district','t.city','t.village','t.street','t.home','t.is_paid', 't.created_at','t.sum_price',
                'd.index as d_index','d.number as d_number',
                'u.id as u_id',
                'uc.name as phone',
                'pi.name as post_index',
                'pr.name as post_region',
                DB::raw('COUNT(i.id) as quantity'),
//                DB::raw('SUM(p.price) as price'),
                DB::raw('GROUP_CONCAT(p.product_type_name) as product_name'),
                DB::raw('GROUP_CONCAT(CONCAT(d.index," ",d.number)) as depot'),
//                DB::raw('COUNT(p.quantity) as count_product'),
                DB::raw('SUM(i.shipping_price) as shipping_price'),
                DB::raw('SUM(i.is_paid) as is_paids'),
                DB::raw('(select SUM(i1.shipping_price) from invoices as i1 where i1.status_id='.$invoice_status.' and i1.is_paid=0 and i1.user_id=i.user_id GROUP BY i1.user_id) as odenilmemis')
            )
            ->leftJoin('users as u', 't.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as uc', 'uc.user_id', '=', 'u.id')
            ->leftJoin('invoices as i', 'i.transfer_id', '=', 't.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('depot_invoices as di','di.invoice_id','=','i.id')
            ->leftJoin('depots as d','di.depot_id','=','d.id')
            ->leftJoin('post_indexes as pi','pi.id','=','t.post_index')
            ->leftJoin('post_regions as pr','pi.region_id','=','pr.id')
            ->where('i.status_id', '=', $invoice_status)
            ->where('i.active','=',1)
            ->where('t.status_id',$status)
            ->groupBy('i.transfer_id')->orderBy('t.created_at','DESC')->get();;

        return response()->json([
            "status" => 200,
            "data" => $data,
            "usdToAzn"=>$currency->val
        ]);
    }


    public function addTransfer (Request $request) {

        $status_9 = getStatusByLabel('post_transfer', 'invoice');
        $transfer_id = Input::get('transfer_id',0);
        if($transfer_id!=0){
            $transfer = Transfer::find($transfer_id);
            $invoices = Invoice::where('transfer_id', '=', $transfer_id)->get();
            foreach ($invoices as $invoice){
                $depot_ids["depots"] = [];
                $depots = DepotInvoice::where('invoice_id','=',$invoice->id)->get();
                foreach ($depots as $depot){
                    $depot_ids["depots"][""] =  $depot->depot_id;
                }
                $note = json_encode($depot_ids);
                $date=new InvoiceDates();
                $date->invoice_id=$invoice->id;
                $date->status_id=$status_9;
                $date->note = $note;
                $date->action_date=Carbon::now();
                $date->save();

                $invoice->status_id=$status_9;
                $invoice->save();

                DepotInvoice::where('invoice_id','=',$invoice->id)->delete();
            }

            $transfer->status_id=1;
            $transfer->save();

            $data = (object) ['text' => template("post-transfer"),"user_id" =>$request->u_id];

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
}
