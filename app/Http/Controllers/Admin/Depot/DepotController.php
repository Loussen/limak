<?php

namespace App\Http\Controllers\Admin\Depot;

use App\Depot;
use App\DepotInvoice;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelCountry\Regions;
use App\ModelCountry\RegionTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\InvoiceDates;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DepotController extends Controller
{
    private $path = 'admin.depot.';
    public function index()
    {
        return view($this->path.'index');
    }

    public function cashier() {
        $term = Input::get('term');
        $label = 'home';
        $data = null;
        if(!empty($term)) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($term) {
                if (!empty($term)) {
                    $query->where('pin', '=', $term)
                        ->orWhere('name', 'like', '%' . $term .'%')
                        ->orWhere('surname', 'like', '%' . $term .'%')
                        ->orWhere('uniqid', '=', $term)
                        ->orWhere('serial_number', '=', $term);
                }
            })->paginate(100);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users.userContacts')->paginate(100);
        }

        return response()->json($data, 200);
    }
    public function payed() {
        $term = Input::get('term');
        $label = 'home';
        $data = null;
        if(!empty($term)) {
            $data = Invoice::where('is_paid', 1)->with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($term) {
                if (!empty($term)) {
                    $query->where('pin', '=', $term)
                        ->orWhere('name', 'like', '%' . $term .'%')
                        ->orWhere('surname', 'like', '%' . $term .'%')
                        ->orWhere('uniqid', '=', $term)
                        ->orWhere('serial_number', '=', $term);
                }
            })->paginate(15);
        } else{
            $data = Invoice::where('is_paid', 1)->with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users.userContacts')->paginate(15);
        }

        return response()->json($data, 200);
    }

    public function pay(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        $invoice->is_paid = true;
        $invoice->update();

        return response()->json('OK', 200);
    }

    public function finish(Request $request) //new
    {
        $invoice = Invoice::findOrFail($request->id);
        $invoice->status_id = Invoice::STATUS_FINISHED;
        if($invoice->update()){
            DepotInvoice::where('invoice_id','=',$invoice->id)->delete();
            InvoiceDates::create([
                'status_id' =>  $invoice->status_id,
                'invoice_id' => $invoice->id,
                'action_date' => Carbon::now()
            ]);
        };

        return response()->json('OK', 200);
    }

    public function editorList()
    {
        $list = Depot::get();

        return response()->json($list, 200);
    }

    public function list(Request $request)
    {
        $region_id = $request->get("region_id",1);
        $depot = Depot::where("region_id",$region_id)->orderBy('number', 'asc')->orderBy('index', 'asc')->withCount('depotInvoice')->get()->groupBy('index');
        $list = [];
        foreach($depot as $key => $value) {
            $boxes = [];
            foreach($value->groupBy('box') as $k => $v) {
                $boxes[] = (object)['box' => $k, 'storage' => $v];
            }
            $list[] = (object)['index' => $key, 'boxes' => $boxes];
        }
        return response()->json($list, 200);
    }
    public function editorInsert(Request $request)
    {
        $depot = new Depot();
        $depot->index = $request->index;
        $depot->number = $request->number;
        $depot->capacity = $request->capacity;
        $depot->size = $request->size;
        $depot->box = $request->box;
        $depot->barcode_id = $request->barcode_id;

        $depot->save();

        return response()->json('OK', 200);
    }
    public function editorUpdate(Request $request)
    {
        $depot = Depot::findOrFail($request->id);
        $depot->index = $request->index;
        $depot->number = $request->number;
        $depot->capacity = $request->capacity;
        $depot->size = $request->size;
        $depot->box = $request->box;
        $depot->barcode_id = $request->barcode_id;
        $depot->update();
        return response()->json('OK', 200);
    }
    public function editorDelete(Request $request)
    {
        $depot = Depot::findOrFail($request->id);

        $depot->delete();

        return response()->json('OK', 200);
    }

    public function storeInDepot(Request $request)
    {
        $region_id = $request->get("region_id",1);

        $begin = substr($request->invoice_id,0,4);
        if($begin==1910 && strlen($request->invoice_id)==14){
            $request->invoice_id = substr($request->invoice_id,4,7);
        }

        $hasProduct = $this->checkInvoice2($request->invoice_id);
        $hasStorage = $this->checkStorage($request->depot_id,$region_id);
        if (!empty($hasStorage) && !empty($hasProduct) && $region_id==$hasProduct->region_id) {
            $has = DepotInvoice::where('depot_id', $hasStorage->id)->where('invoice_id', $hasProduct->id)->first();
            $hasCapacity = DepotInvoice::where('depot_id', $hasStorage->id)->count();
            if (empty($has) && $hasStorage->size > $hasCapacity) {
                $depotInvoice = new DepotInvoice();
                $depotInvoice->depot_id = $hasStorage->id;
                $depotInvoice->invoice_id = $hasProduct->id;
                $depotInvoice->region_id = $region_id;

                $invoiceStatus = getStatusByLabel('home', 'invoice');
                $invoice = Invoice::findOrFail($hasProduct->id);
                //$invoice->status_id = $invoiceStatus;
                $invoice->s_id = $invoiceStatus;
                $invoice->depo =$request->depot_id;
                $invoice->update();
                $depotInvoice->save();
                /*InvoiceDates::create([
                    'status_id' => $invoiceStatus,
                    'invoice_id' => $invoice->id,
                    'action_date' => Carbon::now()
                ]);*/
                return response()->json((object)["data" => 'Ok', "error" => null]);
            }else {
                $msg = empty($has) ? 'Bu rəfdə yer qalmayıb digər rəfdən istifadə edin!' : 'Bu mal artıq bu rəfə əlavə olunmuşdur';
                return response()->json((object)["data" => null, "error" => $msg]);
            }
        } else {

            if($hasProduct && $hasProduct->region_id!=$region_id){
                $regionRow = DB::table("regions")->where("id",$hasProduct->region_id)->first();
                $msg = 'Bu mal sizin regiona aid deyil';
                if($regionRow!=null){
                    $msg .= " - ".$hasProduct->region_id." - ".$regionRow->name;
                }
                return response()->json((object)["data" => null, "error" => $msg]);
            }else{
                $msg = empty($hasStorage) ? 'Rəf tapılmadı!'.$request->depot_id : "Yolda olan mallar arasında axtarılan mal tapılmadı!";
                return response()->json((object)["data" => null, "error" => $msg]);
            }

        }
    }

    private function checkInvoice($purchaseNo)
    {
        $label = 'on_the_way';
        $data = Invoice::where('purchase_no', $purchaseNo)->with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
            $query->where('label', '=', $label);
        })->first();
        return $data;
    }

    private function checkInvoice2($purchaseNo){

        $data = DB::table("invoices")->select("id","region_id")->where("purchase_no",$purchaseNo)->where("status_id",11)->where("s_id","!=",4)->first();
        /*$label = 'on_the_way';
        $data = Invoice::where('purchase_no', $purchaseNo)->with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
            $query->where('label', '=', $label);
        })->first();*/
        return $data;
    }

    private function checkStorage($barcodeIndex,$region_id=1) {
        $storage = Depot::where("region_id",$region_id)->where('barcode_id', $barcodeIndex)->first();
        return $storage;
    }

    public function role()
    {
        $roles = [];
        foreach (Auth::guard('admin')->user()->relAdminRoles as $role) {
            $roles[] = $role->relRole->label;
        }
        return response()->json($roles, 200);
    }

}
