<?php

namespace App\Http\Controllers\Tr;

use App\Currency;
use App\DailyFiles;
use App\DepoPackages;
use App\ForeignDepo;
use App\ModelCountry\Country;
use App\Invoice;
use App\InvoiceDates;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\User;
use App\Packages;
use App\RelUserProduct;
use App\Returns;
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
use Illuminate\Support\Facades\Storage;


class ReturnsController extends BaseController
{
    public function __construct()
    {
        $data = Country::where('prefix','tr')->first();
        $this->country=$data->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index(Request $request)
    {
        $admin_id = Auth::guard('tr')->user()->id;


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');


        $status_id = $request->get("to",false);
        $s = '=';
        if($status_id===false){
            $status_id = 0;
            $s = '>';
        }

        $data = DB::table('returns')
            ->where("status_id",$s,$status_id)
            ->where("country_id",1)
            ->orderBy("id","DESC")
            ->get();
        $status = 'returns';

        return view('tr.returns.index',  compact('data','status' ));
    }

    public function changeStatus(Request $request)
    {
        if(count($request->ids_array)>0){
            $status_id = $request->status_id;
            foreach ($request->ids_array as $id){

                $return = Returns::find($id);
                $return->status_id = $status_id;
                if($status_id==1){
                    $return->send_date = date("Y-m-d H:i:s");
                }elseif($status_id==2){
                    $return->depo_date = date("Y-m-d H:i:s");
                }elseif($status_id==3){
                    $return->ok_date = date("Y-m-d H:i:s");
                }

                $return->updated_at = date("Y-m-d H:i:s");
                $return->save();
            }
        }


        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
    }

    public function sendReturn(Request $request)
    {
        $return_id = $request->id;
        $status_id = $request->status_id;

        $return  = Returns::where("id",$return_id)->first();
        if($return!=null and !empty($return->tracking_number) and in_array($status_id,[4,5])){
            $return->status_id = $status_id;
            $return->save();
            return 1;
        }

        return 0;
    }


    public function getReturn($id)
    {
        $data = DB::table("returns as r")->where("r.id",$id)->first();
        if($data!=null){

            return view('tr.returns.get_return', compact('data'));
        }else{
            echo "İade bulunamadi";
        }
    }


    public function updateTrackingNumber(Request $request)
    {
        $return_id = $request->id;
        $tracking_number = $request->tracking_number;
        $cargo_name = $request->cargo_name;
        $return_code = $request->return_code;

        $return  = Returns::where("id",$return_id)->first();
        if($return!=null){
            $return->tracking_number = $tracking_number;
            $return->cargo_name = $cargo_name;
            $return->return_code = $return_code;
            $return->save();
            return 1;
        }

        return 0;

    }

    public function removeReturn(Request $request)
    {
        $id = $request->return_id;
        $return = Returns::where("id",$id)->first();

        if($return!=null){
            if($return->delete()){
                return response()->json(['status' => 200, 'message' => 'Iade silindi']);
            }
        }


        return response()->json(['status' => 500, 'message' => 'Yanlış Iade']);
    }


}
