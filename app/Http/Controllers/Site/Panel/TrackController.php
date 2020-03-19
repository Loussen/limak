<?php

namespace App\Http\Controllers\Site\Panel;

use App\Contact;
use App\Invoice;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;
use function PHPSTORM_META\type;
use DB;
use App\InvoicePayment;
use App\ModelUser\User;
use App\Currency;
use Illuminate\Support\Facades\Input;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index2($type)
    {
//        $belongsToCourier = Input::get('courier');
        $ifInvoiceHasCourierId = Input::get('invoice_courier');
        $response = null;
        $userId = 892;
        $statusId = null;

        if(isset($type) && $type != 'undefined') {
            $response = $this->getTypedInvoices($userId, $type,$ifInvoiceHasCourierId);
        } else {
            $response = $this->getAllInvoices($userId);
        }
        $balance = Auth::user()->balance;
        $index = Currency::first();
        return response()->json(['data' => $response, 'balance' => $balance, 'index' => $index,'type' =>$type]);
    }

    public function index_new($type)
    {
//        $belongsToCourier = Input::get('courier');
        $ifInvoiceHasCourierId = Input::get('invoice_courier');
        $country_id = Input::get('country_id',1);
        $response = null;
        $userId = Auth()->user()->id;
        $statusId = null;

        if(isset($type) && $type != 'undefined') {
            $response = $this->getTypedInvoices($userId, $type,$ifInvoiceHasCourierId,$country_id);
        } else {
            $response = $this->getAllInvoices($userId,$country_id);
        }
        $balance = Auth::user()->balance;
        $index = Currency::first();
        return response()->json(['data' => $response, 'balance' => $balance, 'index' => $index,'type' =>$type]);
    }
    private function getAllInvoices ($userId,$country_id=1) {
        $data = DB::table('invoices as i')
            ->select(
                'i.id','i.shipping_price','i.weight','i.created_at','i.order_tracking_number','i.is_paid','i.status_id','i.width','i.height','i.courier_id',
                'p.shop_name', 'p.quantity','p.price','p.product_type_name',
                'c.name as country_name',
                'st.name as status_name',
//                DB::raw('GROUP_CONCAT(i.id) as i_ids'),
//                DB::raw('GROUP_CONCAT(id.action_date) as dates'),
//                DB::raw('GROUP_CONCAT(p.product_type_name) as products'),
//                DB::raw('SUM(p.price) as sum_p_price'),
//                DB::raw('SUM(i.shipping_price) as sum_i_shprice'),
//                DB::raw('COUNT(i.id) as i_count'),
//                DB::raw('SUM(i.is_paid) as i_is_paid')
                DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as i_count"),
                DB::raw("(select GROUP_CONCAT(p1.product_type_name) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as product_names"),
                DB::raw("(select GROUP_CONCAT(p1.shop_name) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as shop_names"),
                DB::raw("(select SUM(p1.price) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as sum_p_price"),
//                DB::raw("(select SUM(i1.is_paid) from invoices as i1  where i1.package_id=i.package_id) as i_is_paid"),
                DB::raw("(select GROUP_CONCAT(id.action_date) from invoice_dates as id where id.invoice_id=i.id order by id.id ASC) as dates"),
                DB::raw("(select GROUP_CONCAT(id.status_id) from invoice_dates as id where id.invoice_id=i.id order by id.id ASC) as statuses")

            )
//            ->with('dates')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('invoice_dates as id','id.invoice_id','=','i.id')
            ->leftJoin('countries as c', 'i.country_id', '=', 'c.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as st', 'st.id', '=', 'i.status_id')

            ->where('i.user_id', '=', $userId)
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->whereIn('i.status_id',[1,2,3,4,6])
            ->orderBy('i.created_at','desc')
            ->groupBy('i.id')
            ->get();
        return $data;
    }

    private function getTypedInvoices ($userId, $type, $ifInvoiceHasCourierId=null,$country_id=1) {
        $statusId = Status::where('label', '=', $type)->where('type', '=', 'invoice')->get();

        $data = DB::table('invoices as i')
            ->select( 'i.id','i.shipping_price','i.weight','i.created_at','i.order_tracking_number','i.is_paid','i.status_id',
                'p.shop_name', 'p.quantity','p.price','p.product_type_name',
                'c.name as country_name',
                'st.name as status_name',
                DB::raw('GROUP_CONCAT(p.product_type_name) as products'),
                DB::raw('SUM(p.price) as sum_p_price'),
                DB::raw('SUM(i.shipping_price) as sum_i_shprice'),
                DB::raw('COUNT(i.id) as i_count'),
                DB::raw('SUM(i.is_paid) as i_is_paid')


            )
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('countries as c', 'i.country_id', '=', 'c.id')
            ->leftJoin('statuses as st', 'st.id', '=', 'i.status_id')
            ->where('i.user_id', '=', $userId)
            ->where('i.country_id', '=', $country_id)
            ->where('i.status_id','=',$statusId[0]->id)
            ->orderBy('i.created_at','desc')
            ->groupBy('i.package_id');

        $data=$data->whereNull('i.courier_id');

        $data=$data->get();
        return $data;
    }

    public function index()
    {
        echo "1"; exit;
       $userId = Auth::user()->id;
       $statuses = Status::where('type', '=', 'invoice')->where('label', '<>', 'completed')->get();
       $collectedIds = $statuses->map(function ($data) {
            return $data->id;
        });
        $data = Invoice::with(['invoiceStatus','dates','packages','products.country', 'products.extras', 'products.productsType', 'products.relUserProducts', 'courier'])
            ->whereHas('invoiceStatus', function($query) use($collectedIds) {
            $query->whereIn('status_id', $collectedIds);
        })->where('user_id',$userId)->groupBy('package_id')
            ->orderBy('id', 'desc')->get();
        $balance = Auth::user()->balance;
       return response()->json(['data' => $data, 'balance' => $balance]);
    }


}
