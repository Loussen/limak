<?php

namespace App\Http\Controllers\Cp;
use App\Currencies;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $limit = 10;
    public function index()
    {
        echo "salam222"; exit;
    }


    public function act(Request $request)
    {
        $id = $request->id;
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
            ->where('i.status_id',$status)
            ->where('c.has_courier', '=', $has_courier)
            ->where('i.active','=',1)
            ->where('c.id','=',$id)
            //->where('c.id','=',$id)
            ->groupBy('i.courier_id')->orderBy('c.created_at','DESC')->get();

       /* return response()->json([
            "status" => 200,
            "data" => $data
        ]);*/

        return view('cp.home.act', [
            'data' => $data
        ]);
    }
}
