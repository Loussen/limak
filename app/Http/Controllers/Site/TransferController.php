<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use App\ModelPostIndexes\PostIndexes;
use App\ModelPostIndexes\PostRegions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;


class TransferController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function getPostRegions()
    {

        $regions = PostRegions::where("status_id",1)->get();

        foreach ($regions as $region){
            $data[] = ["id" => $region->id,"name" => $region->name,"label" => $region->name];
        }

        return response()->json(['data' => (object) $data]);

    }

    public function getPostIndexes(Request $request)
    {
        $region_id = intval($request->id);
        if($region_id>0){
            $indexes = PostIndexes::where("region_id",$region_id)->where("status_id",1)->get();

            foreach ($indexes as $index){
                $data[] = ["id" => $index->id,"name" => $index->name,"label" => $index->name];
            }
            return response()->json(['code' => 200,'data' => (object) $data]);

        }else{
            return response()->json(['code' => 500,'Error']);
        }


    }



}
