<?php

namespace App\Http\Controllers\Admin\Accountant;

use App\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class OnTheWayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tab = Input::get('tab')?Input::get('tab') : 'all';
        $pin = Input::get('pin');
        $tel = Input::get('tel');
        $params = Input::get();
        $dataInRoad = [];

        $dataInRoad = $this->inRoad($pin, $tel);

        if($request->ajax()) {
            return view('admin.accountant.on-the-way.ajax', compact('dataInRoad'));
        } else {
            return view('admin.accountant.on-the-way.index', compact('dataInRoad'));
        }
    }

    private function inRoad($pin, $tel) {
        $label = 'on_the_way';
        if($tel || $pin) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                if ($pin) {
                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                } else {
                    $query->where('name', 'like', '%' . $tel .'%');
                }
            })->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users.userContacts')->paginate(50);
        }
        return $data;
    }
}
