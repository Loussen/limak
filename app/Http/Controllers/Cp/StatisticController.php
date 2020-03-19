<?php

namespace App\Http\Controllers\Cp;

use App\Invoice;
use App\ModelShop\Shop;
use App\ModelCountry\Country;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon ;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $limit = 10;

    public function getShippingPrices(Request $request)
    {
        $country_id = $request->get("country_id",1);
        $date1 =  date('Y-m-d', strtotime('first day of last month'));
        $date2 = date('Y-m-d', strtotime('last day of last month'));
        $tr_depo = DB::table("invoices")->selectRaw("count(id) as count,sum(weight) as weight,sum(shipping_price) as price")->where("country_id",$country_id)->where("status_id",2)->where("active",1)->first();
        $on_the_way = DB::table("invoices")->selectRaw("count(id) as count,sum(weight) as weight,sum(shipping_price) as price")->where("country_id",$country_id)->where("status_id",3)->where("active",1)->first();
        $custom = DB::table("invoices")->selectRaw("count(id) as count,sum(weight) as weight,sum(shipping_price) as price")->where("country_id",$country_id)->where("status_id",11)->where("active",1)->first();
        $az_depo = DB::table("invoices")->selectRaw("count(id) as count,sum(weight) as weight,sum(shipping_price) as price")->where("country_id",$country_id)->where("status_id",4)->where("active",1)->first();
        $month = DB::table("invoices as i")
            ->leftJoin("invoice_dates as ind","i.id","ind.invoice_id")
            ->selectRaw("count(i.id) as count,sum(i.weight) as weight,sum(i.shipping_price) as price")
            ->where("i.country_id",$country_id)->where("i.active",1)
            ->where("ind.action_date",">=",date("Y-m-")."01")->where("ind.status_id",4)
            ->first();

        $million_azn = DB::table("log_balances")->selectRaw("sum(money_log) as money")->whereRaw("message LIKE '%Million%'")->where("created_at",">=",$date1)->where("created_at","<=",$date2)->where("type","try")->first();
        $million_try = DB::table("log_balances")->selectRaw("sum(money_log) as money")->whereRaw("message LIKE '%Million%'")->where("created_at",">=",$date1)->where("created_at","<=",$date2)->where("type","azn")->first();

        $data["million"]["azn"] = $million_azn->money;
        $data["million"]["try"] = $million_try->money;
        $data["million"]["date1"] = $date1;
        $data["million"]["date2"] = $date2;

        $data["tr"] = $tr_depo;
        $data["way"] = $on_the_way;
        $data["az"] = $az_depo;
        $data["custom"] = $custom;
        $data["month"] = $month;
        return response()->json([
            "status" => 200,
            "data" => $data,
        ]);
    }

    //Bütün sifarişlər
    public function allInvoices (Request $request)
    {
        $status = $request->get('status', 4);
        $firstDay=date('Y-m-d',strtotime("first day of this month"));



        $begin_date = $request->get('begin_date', $firstDay);
        $end_date = $request->get('end_date', date("Y-m-d"));


        $rows = DB::table('invoices')
            ->select(DB::raw("sum(invoices.shipping_price) as sum_shipping,count(invoices.id) as count,r.name"))
            //->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"))
            ->whereIn('invoices.id', function($query) use($status,$begin_date,$end_date)
            {
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>=', $begin_date)
                    ->where('action_date', '<=', $end_date)
                    ->distinct('invoice_id')
                    ->where("status_id","=",$status);
            })
            ->where('invoices.status_id','!=',777)
            ->leftJoin("regions as r","r.id","invoices.region_id")
            ->groupBy("invoices.region_id")
            ->get();

        if($rows) {
            return response()->json([
                'status' => 200,
                'data' => $rows
            ]);
        }
    }

}
