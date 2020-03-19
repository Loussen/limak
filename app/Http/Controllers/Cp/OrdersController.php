<?php

namespace App\Http\Controllers\Cp;
use App\Bonus;
use App\DepotInvoice;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceDates;
use App\ModelCountry\Country;
use App\ModelCountry\Regions;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\Currency;
use App\Returns;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ModelUser\User;
use App\RelUserProduct;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;


class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders () {
        $array["incoming_order"] = $this->incomingOrdersCount();
        $array["all_order"] = ' - ';//$this->allOrdersCount();
        $array["all_invoices"] = ' - ';//$this->allInvoicesCount();
        $array["executing"] = $this->executingOrdersCount();
        $array["invoice"] = ' - '; //$this->invoiceOrdersCount();
        $array["waiting"] = $this->waitingOrdersCount();
        $array["late_invoices"] = ' - '; //$this->lateInvoicesCount();
        $array["eft"] = 50;//$this->allOrdersCount();
        $array["back_to_cards"] = 50;//$this->allOrdersCount();

        return response()->json(['status' => 200, 'data' => $array ]);

    }

    public function delete ($id){
        $Product = Product::find((int) $id);
        $Product->status_id = 777;
        $Product->save();

        return response()->json(['status' => 200, 'message' => 'Deleted' ]);
    }

    /**
     * Gelen sifarisler
     *
     */
    // Gelen Sifarislerin sayisi
    protected function incomingOrdersCount(){
        $result = Product::where('admin_id', '=', null)
            ->where('is_ordered', '<>', Product::ORDERED)
            ->where('is_paid', '=', 1)
            ->where('status_id', '=', 1)
            ->groupBy('user_id')
            ->get()
            ->count();
        if($result==null){
            $result = 0;
        }
        return $result;

    }

    public function getProductsByPackage($id){
        $result = Invoice::with('invoiceStatus', 'dates','dates.status','products', 'users' ,'users.userContacts', 'products.extras.countries.translates', 'products.productsType','products.statuses')
            ->where('package_id', '=', $id)->get();

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    // Gelen Sifarislerin sayisi
    protected function allInvoicesCount(){
        $result = Invoice::where('active', '=', 1)->where('status_id','!=', 777)->get()
            ->count();
        if($result==null){
            $result = 0;
        }
        return $result;

    }


    // Gelen Sifarislerin sayisi
    protected function lateInvoicesCount(){
        $result = Invoice::where("status_id",1)->where('active', '=', 1)
            ->where('invoices.order_date', '<=', date("Y-m-d 00:00:00",strtotime(date('Y-m-d', strtotime('-7 days'))." 00:00:00")))
            ->get()
            ->count();
        if($result==null){
            $result = 0;
        }
        return $result;

    }


    // Gelen Sifarisler siyahisi
    public function incoming (Request $request) {
        $result = Product::select(DB::raw('products.is_problem as problem'),DB::raw('GROUP_CONCAT(products.problem_text) as problem_text'),DB::raw("products.id,products.user_id,sum(products.price) as price,products.shop_name,products.is_premium,products.created_at,products.is_urgent,u.name as name,u.surname as surname,u.uniqid as uniqid,c.name as client_name,c.surname as client_surname,products.client_id"))
            ->leftJoin('product_types as pt', function($join) {
                $join->on('pt.id', '=', 'products.product_type_id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('products.user_id', '=', 'u.id');
            })
            ->leftJoin('clients as c', function($join) {
                $join->on('products.client_id', '=', 'c.id');
            })
            ->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->where('products.admin_id', '=', null)
            ->where('products.is_ordered', '=', 0)
            ->where('products.is_paid', '=', 1)
            ->where('products.status_id', '=', 1);

        $uniqid = $request->get('uniqid', false);
        $name = $request->get('name', false);
        $surname = $request->get('surname', false);
        $email = $request->get('email', false);
        $phone = $request->get('phone', false);
        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);

        if($uniqid) $result = $result->where("u.uniqid",$uniqid);
        if($name) $result = $result->where("u.name",'like','%'.$name.'%');
        if($surname) $result = $result->where("u.surname",'like','%'.$surname.'%');
        if($email) $result = $result->where("u.email",'like','%'.$email.'%');
        if($phone) $result = $result->where("uc.name",'like','%'.$phone.'%');
        if($begin_date) $result = $result->where("products.created_at",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $result = $result->where("products.created_at",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        $result = $result->groupBy('products.user_id')
            ->groupBy("products.client_id")
            ->orderBy('products.id', 'asc')
            ->orderBy('products.is_urgent', 'desc')
            ->get();

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result
            ]);
        }
    }


    // Gelen Sifarisi qebul etmek ve icra olunana oturmek
    public function acceptOrder(Request $request)
    {
        $adminId = Auth::guard('admin')->user()->id;
        $user_id = intval($request->id);
        $client_id = $request->client_id;

        $update = DB::table('products')
            ->where('user_id', $user_id)
            ->where('client_id', $client_id)
            ->where('admin_id', null)
            ->update(['admin_id' => $adminId]);


        if ($update) {

            return response()->json([
                'status'  => 200,
                'message' => 'Qəbul edildi'
            ]);
        } else {
            //$errors = $update->errors();
            return response()->json([
                'status'  => 500,
                'message' => 'Server xətası!',
            ]);
        }
    }

    /**
     * Icra olunan sifarisler
     *
     */
    // icra olunanlarin siyahisi
    public function executing (Request $request) {

        $adminId = Auth::guard('admin')->user()->id;
        $superAdminPermission = $this->checkRole('super_admin');

        $result = Product::select(DB::raw("products.id,products.is_premium,products.region_id, r.name as region_name, products.user_id,products.price,products.shop_name,products.created_at,products.is_urgent,u.name as name,u.surname as surname,u.uniqid as uniqid,uc.name as phone,a.name as admin_name,a.surname as admin_surname,c.name as client_name,c.surname as client_surname,products.client_id,products.problem_text"),DB::raw('SUM(products.is_problem) as problem'),DB::raw('GROUP_CONCAT(products.problem_text) as problem_text'))
            ->leftJoin('product_types as pt', function($join) {
                $join->on('pt.id', '=', 'products.product_type_id');
            })
            ->leftJoin('admins as a', function($join) {
                $join->on('products.admin_id', '=', 'a.id');
            })
            ->leftJoin('clients as c', function($join) {
                $join->on('products.client_id', '=', 'c.id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('products.user_id', '=', 'u.id');
            })
            ->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->leftJoin('regions as r', function($join) {
                $join->on('products.region_id', '=', 'r.id');
            })
            ->where('products.is_ordered', '<>', Product::ORDERED)
            ->where('products.is_paid', '=', 1)
            ->where('products.status_id', '=', 1);


        $now = time();
        $livetime = $now-3600;
        $beforeOneHour = date("Y-m-d H:i:s", $livetime);


        $uniqid = $request->get('uniqid', false);
        $name = $request->get('name', false);
        $surname = $request->get('surname', false);
        $email = $request->get('email', false);
        $phone = $request->get('phone', false);
        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);
        $admin_name = $request->get('admin_name', false);
        $updated_date = $request->get('type');

        if($uniqid) $result = $result->where("u.uniqid",$uniqid);
        if($name) $result = $result->where("u.name",'like','%'.$name.'%');
        if($surname) $result = $result->where("u.surname",'like','%'.$surname.'%');
        if($email) $result = $result->where("u.email",'like','%'.$email.'%');
        if($phone) $result = $result->where("uc.name",'like','%'.$phone.'%');
        if($begin_date) $result = $result->where("products.created_at",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $result = $result->where("products.created_at",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        if($admin_name) $result = $result->where("a.name",'like','%'.$admin_name.'%');

        if($updated_date=='waiting') {
            $result = $result->where("products.updated_at",'<',$beforeOneHour);
            $result = $result->where('admin_id', '<>', null);
        }
        else {
            if($superAdminPermission){
                $result = $superAdminPermission ? $result->where('products.admin_id', '<>', null) : $result->where('products.admin_id', '=', $adminId);
                $result = $result->where("products.updated_at",'>',$beforeOneHour);
            }else{
                $superAdminPermission = $this->checkRole('admin');
                $result = $superAdminPermission ? $result->where('products.admin_id', '<>', null) : $result->where('products.admin_id', '=', $adminId);
                $result = $result->where("products.updated_at",'>',$beforeOneHour);
            }

        }

        $result = $result->groupBy('products.user_id')
            ->groupBy('products.client_id')
            ->groupBy('products.region_id')
            ->orderBy('products.is_urgent', 'desc')
            ->orderBy('products.id', 'asc')
            ->get();

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result
            ]);
        }
    }



    // icra olunanlarin sayi
    protected function executingOrdersCount(){
        $adminId = Auth::guard('admin')->user()->id;
        $superAdminPermission = $this->checkRole('super_admin');

        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $now = time();
        $livetime = $now-3600;
        $beforeOneHour = date("Y-m-d H:i:s", $livetime);

        $result = Product::where('is_ordered', '<>', Product::ORDERED)
            ->where('is_paid', '=', 1)
            ->where('updated_at', '>', $beforeOneHour)
            ->where('status_id', '=', 1);

        $result = $superAdminPermission ? $result->where('admin_id', '<>', null) : $result->where('admin_id', '=', $adminId);


        $result =
            $result->groupBy('user_id')
                ->get()->count();

        if($result==null){
            $result = 0;
        }
        return $result;

    }

    protected function waitingOrdersCount(){
        $adminId = Auth::guard('admin')->user()->id;
        $superAdminPermission = $this->checkRole('super_admin');

        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $now = time();
        $livetime = $now-3600;
        $beforeOneHour = date("Y-m-d H:i:s", $livetime);

        $result = Product::where('is_ordered', '<>', Product::ORDERED)
            ->where('is_paid', '=', 1)
            ->where('status_id', '=', 1)

            ->where('updated_at', '<', $beforeOneHour);

//        $result = $superAdminPermission ? $result->where('admin_id', '<>', null) : $result->where('admin_id', '=', $adminId);


        $result = $result->where('admin_id', '<>', null);


        $result =
            $result->groupBy('user_id')
                ->get()->count();

        if($result==null){
            $result = 0;
        }
        return $result;

    }

    /** icra olunan sifarisin icine baxmaq  user id-ye gore +*/
    public function getOrderDetailByUser(Request $request) {
        $id = $request->id;
        // status qalib
        //$transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $callback = function ($query) {
            $query->where('status_id', '=', 1)->where('is_ordered','=',0);
        };
        User::$region_id = $request->get('region_id', 1);
        User::$client_id = $request->get('client_id',0);
        $result = User::with('userContacts')->with('notOrderedProducts.relUserProducts','notOrderedProducts','notOrderedProducts.extras.countries.translates','notOrderedProducts.productsType','notOrderedProducts.invoices','notOrderedProducts.statuses','notOrderedProducts.clients')

            ->where('id', '=', $id)->first();
        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
                "client_id" => User::$client_id
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function getOrderDetailByUser5(Request $request) {
        $id = $request->id;
        // status qalib
        //$transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $callback = function ($query) {
            $query->where('status_id', '=', 1)->where('is_ordered','=',0);
        };
        User::$region_id = $request->get('region_id', 1);
        User::$client_id = $request->get('client_id',0);
        $result = User::with('userContacts')->with('notOrderedProducts.relUserProducts','notOrderedProducts','notOrderedProducts.extras.countries.translates','notOrderedProducts.productsType','notOrderedProducts.invoices','notOrderedProducts.statuses','notOrderedProducts.clients')
            ->where('id', '=', $id)->first();
        if($result) {
            // Bon.az begin

            $urls = [];
            $data_urls = [];
            $url = 'email=kamil419@gmail.com';
            foreach ($result->notOrderedProducts as $product){
                if($product->extras->link2==null){
                    $urls[] = ["id" => $product->id,"url" => $product->extras->link,"extra_id" => $product->extras->id];
                    $data_urls[] = $product->extras->link;
                }
            }

            if(count($urls)>0){

                $curl = curl_init();

                $datas = [
                    'email' => 'kamil419@gmail.com',
                    'url' => $data_urls
                ];

                $fields_string = http_build_query($datas);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://bon.az/api/short-it",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $fields_string,
                ));

                $response_bon = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    //echo "cURL Error #:" . $err;
                }


                $bon_array = json_decode($response_bon,true);

                $bonus = new Bonus();
                $bonus->request_url = $url;
                $bonus->response_url = $response_bon;
                $bonus->company = 'bon.az';
                $bonus->created_at = date("Y-m-d H:i:s");
                $bonus->updated_at = date("Y-m-d H:i:s");
                $bonus->save();

                $i=0;

                if(count($bon_array)>0){
                    foreach ($bon_array as $bon){

                        $extras = Extras::find($urls[$i]["extra_id"]);
                        if($extras != null){
                            $extras->link2 = $bon["short_url"];
                            $extras->bonus_id = $bonus->id;
                            $extras->save();

                            $extras_array[$extras->id] = $bon["short_url"];
                        }

                        $i++;
                    }

                    $result = User::with('userContacts')->with('notOrderedProducts.relUserProducts','notOrderedProducts','notOrderedProducts.extras.countries.translates','notOrderedProducts.productsType','notOrderedProducts.invoices','notOrderedProducts.statuses','notOrderedProducts.clients')
                        ->where('id', '=', $id)->first();
                }

            }

            // Bon.az end


            return response()->json([
                'status' => 200,
                'data' => $result,
                "client_id" => User::$client_id
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function getInvoiceProducts ($id) {
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $result = Invoice::with('invoiceStatus','returns','dates','dates.status' ,'products', 'users' ,'users.userContacts', 'products.extras.countries.translates', 'products.productsType','products.statuses')
            ->where('id', '=', $id)->first();

        if($result) {
            if($result->weight<=0.500){
                $label = 'minWeightPrice';
            }else{
                $label = 'weightPrice';
            }

            $tariff = DB::table("return_tariffs")
                ->where("country_id",$result->country_id)
                ->where("region_id",$result->region_id)
                ->where("label",$label)
                ->first();
            $r_price = 0;
            if($tariff!=null){
                if($result->weight>=1){
                    $r_price = $result->weight*$tariff->price;
                }else{
                    $r_price = $tariff->price;
                }
            }


            $result->r_price = $r_price;

            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }


    // new
    private function checkAcceptedButton($data) {
        $statusRejection = getStatusByLabel('rejection', 'product');
        $statusRejectionCom = getStatusByLabel('rejection_accepted', 'product');
        $statusWaiting = getStatusByLabel('waiting', 'product');
        $productStatuses = [];
        foreach ($data["products"] as $product) {
            $productStatuses[] = $product->status_id;
        }
        foreach ($data["products"] as $product) {
            if (count($product->invoices) == 0 && $product->status_id !== $statusRejection && $product->status_id !== $statusRejectionCom) {
                return false;
            }

            if (!in_array($statusWaiting, $productStatuses)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Invoyslar
     *
     */


    public function noInvoices (Request $request) {
        $pageLimit = 10;
        $result = Invoice::select(DB::raw("invoices.id as id,invoices.purchase_no,a.name as admin_name,a.surname as admin_surname,u.id as user_id,u.name as name,u.surname as surname,u.email as email,u.uniqid as uniqid,uc.name as phone,p.price as price"))
            ->leftJoin('products as p', function($join) {
                $join->on('p.id', '=', 'invoices.product_id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('p.user_id', '=', 'u.id');
            })->leftJoin('admins as a', function($join) {
                $join->on('p.admin_id', '=', 'a.id');
            })->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->where('invoices.file', '=', NULL)
            ->where('invoices.status_id', '<', 3)
            ->where('invoices.added_by', '<>', 'foreign')
            ->where('invoices.active', '=', '1');

        $uniqid = $request->get('uniqid', false);
        $name = $request->get('name', false);
        $surname = $request->get('surname', false);
        $email = $request->get('email', false);
        $phone = $request->get('phone', false);
        $purchase_no = $request->get('purchase_no', false);
        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);
        $admin_name = $request->get('admin_name', false);

        if($uniqid) $result = $result->where("u.uniqid",$uniqid);
        if($name) $result = $result->where("u.name",'like','%'.$name.'%');
        if($surname) $result = $result->where("u.surname",'like','%'.$surname.'%');
        if($email) $result = $result->where("u.email",'like','%'.$email.'%');
        if($phone) $result = $result->where("uc.name",'like','%'.$phone.'%');
        if($admin_name) $result = $result->where("a.name",'like','%'.$admin_name.'%');
        if($purchase_no) $result = $result->where("invoices.purchase_no",'=',$purchase_no);
        if($begin_date) $result = $result->where("invoices.order_date",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $result = $result->where("invoices.order_date",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        $result = $result->orderBy('invoices.id', 'desc')
        ->paginate($pageLimit);

        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    public function noInvoicesForeign (Request $request) {
        $pageLimit = 10;
        $result = Invoice::select(DB::raw("invoices.id as id,invoices.purchase_no,a.name as admin_name,a.surname as admin_surname,u.id as user_id,u.name as name,u.surname as surname,u.email as email,u.uniqid as uniqid,uc.name as phone,p.price as price"))
            ->leftJoin('products as p', function($join) {
                $join->on('p.id', '=', 'invoices.product_id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('p.user_id', '=', 'u.id');
            })->leftJoin('admins as a', function($join) {
                $join->on('p.admin_id', '=', 'a.id');
            })->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->where('invoices.file', '=', NULL)
            ->where('invoices.status_id', '<', 3)
            ->where('added_by', '=', 'foreign')
            ->where('invoices.active', '=', '1');

        $uniqid = $request->get('uniqid', false);
        $name = $request->get('name', false);
        $surname = $request->get('surname', false);
        $email = $request->get('email', false);
        $phone = $request->get('phone', false);
        $purchase_no = $request->get('purchase_no', false);
        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);
        $admin_name = $request->get('admin_name', false);

        if($uniqid) $result = $result->where("u.uniqid",$uniqid);
        if($name) $result = $result->where("u.name",'like','%'.$name.'%');
        if($surname) $result = $result->where("u.surname",'like','%'.$surname.'%');
        if($email) $result = $result->where("u.email",'like','%'.$email.'%');
        if($phone) $result = $result->where("uc.name",'like','%'.$phone.'%');
        if($admin_name) $result = $result->where("a.name",'like','%'.$admin_name.'%');
        if($purchase_no) $result = $result->where("invoices.purchase_no",'=',$purchase_no);
        if($begin_date) $result = $result->where("invoices.order_date",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $result = $result->where("invoices.order_date",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        $result = $result->orderBy('invoices.id', 'desc')
            ->paginate($pageLimit);

        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    public function deleteInvoice ($id) {

        $invoice=Invoice::where('id','=',$id)->where('status_id','=',1)->first();
        $invoice->active=0;
        $invoice->status_id=777;
        $invoice->save();
        return response()->json([
            'status' => 200,
            'data' => $id
        ]);
    }

    //Bütün sifarişlər
    public function allInvoices (Request $request) {
        $pageLimit = 10;
        $result = Invoice::select(DB::raw(
            "invoices.status_id as status_id,invoices.is_premium,invoices.shipping_price,invoices.country_id,invoices.region_id,invoices.id as id,invoices.purchase_no,invoices.order_date,invoices.created_at,invoices.price as price,
            r.name as region_name,
            c.name as country_name,s.name as status,
            a.name as admin_name,a.surname as admin_surname,
            u.id as user_id,u.name as name,u.surname as surname,u.email as email,u.uniqid as uniqid,uc.name as phone,
            p.shop_name,p.product_type_name,
            cl.name as client_name,cl.surname as client_surname,invoices.client_id,invoices.corporate,
            pe.name as person_name,pe.surname as person_surname,pe.auto_id as person_id
            "))
            ->join('statuses as s','invoices.status_id','=','s.sid')
            ->leftJoin('clients as cl','invoices.client_id','=','cl.id')
            ->leftJoin('persons as pe','invoices.person_id','=','pe.id')
            ->leftJoin('regions as r','invoices.region_id','=','r.id')
            ->leftJoin('countries as c','invoices.country_id','=','c.id')
            //->leftJoin('account_logs as al','invoices.id','=','al.invoice_id')
            ->leftJoin('products as p', function($join) {
                $join->on('p.id', '=', 'invoices.product_id');
            })->leftJoin('extras as e', function($join) {
                $join->on('e.id', '=', 'p.extras_id');
            })->leftJoin('users as u', function($join) {
                $join->on('invoices.user_id', '=', 'u.id');
            })->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })->leftJoin('fatura as f', function($join) {
                $join->on('f.id', '=', 'invoices.fatura_id');
            })->leftJoin('admins as a', function($join) {
                $join->on('p.admin_id', '=', 'a.id');
            })->where('s.type','=','invoice')
            ->where('invoices.active','=',1);
        $uniqid = $request->get('uniqid', false);
        $name = $request->get('name', false);
        $surname = $request->get('surname', false);
        $email = $request->get('email', false);
        $phone = $request->get('phone', false);
        $purchase_no = $request->get('purchase_no', false);
        $order_tracking_number = $request->get('order_tracking_number', false);
        $delivery_number = $request->get('delivery_number', false);
        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);
        $way_date = $request->get('way_date', false);
        $admin_name = $request->get('admin_name', false);
        $status = $request->get('status', false);
        $region_id = $request->get('region_id', false);
        $is_premium = $request->get('is_premium', false);
        $liquid_type = $request->get('liquid_type', false);
        $price = $request->get('price', false);
        $weight = $request->get('weight', false);
        $country_id = $request->get('country_id', false);
        $shop_name = $request->get('shop_name', false);
        $link = $request->get('link', false);
        $product_type_name = $request->get('product_type_name', false);


        if($uniqid){
            if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
                $client_id = intval(substr($uniqid,1,6));
                $result = $result->where("invoices.client_id",$client_id);
            }else{
                $result = $result->where("u.uniqid",$uniqid);
            }

        }
        if($is_premium!==false){
            if($is_premium==1){
                $result = $result->where('invoices.is_premium','=',$is_premium);
            }elseif($is_premium==0){
                $result = $result->where('invoices.is_premium','=',$is_premium);
            }
        }

        if($liquid_type!==false){
            if($liquid_type==1){
                $result = $result->where('invoices.liquid_type','=',$liquid_type);
            }elseif($liquid_type==0){
                $result = $result->where('invoices.liquid_type','=',$liquid_type);
            }
        }

        if($name) $result = $result->where("u.name",'like','%'.$name.'%');
        if($surname) $result = $result->where("u.surname",'like','%'.$surname.'%');
        if($email) $result = $result->where("u.email",'like','%'.$email.'%');
        if($phone) $result = $result->where("uc.name",'like','%'.$phone.'%');
        if($admin_name) $result = $result->where("a.name",'like','%'.$admin_name.'%');
        if($purchase_no) $result = $result->where("invoices.purchase_no",'=',$purchase_no);
        if($price) $result = $result->where("invoices.price",'=',$price);
        if($weight) $result = $result->where("invoices.weight",'=',$weight);
        if($order_tracking_number) $result = $result->where("invoices.order_tracking_number",'=',$order_tracking_number);
        if($delivery_number) $result = $result->where("invoices.delivery_number",'=',$delivery_number);
        if($begin_date) $result = $result->where("invoices.created_at",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $result = $result->where("invoices.created_at",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));
        if($status) $result = $result->where('invoices.status_id','=',$status);
        if($region_id) $result = $result->where('invoices.region_id','=',$region_id);
        if($country_id) $result = $result->where('invoices.country_id','=',$country_id);
        if($shop_name) $result = $result->where("p.shop_name",'like','%'.$shop_name.'%');
        if($product_type_name) $result = $result->where("p.product_type_name",'like','%'.$product_type_name.'%');
        if($link) $result = $result->where("e.link",'like','%'.$link.'%');
        if($way_date) $result = $result->where("f.updated_at",'>=',date("Y-m-d 00:00:00",strtotime($way_date." 00:00:00")))
                                       ->where("f.updated_at",'<',date("Y-m-d 23:59:59",strtotime($way_date." 23:59:59")));

        $result = $result->orderBy('invoices.id', 'desc')
            ->paginate($pageLimit);


        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    public function noInvoicesForeignOld (Request $request) {
        $pageLimit = 10;
        $result = Invoice::with('products','products.users','products.users.userContacts')
            ->where('file', '=', NULL)
            ->where('status_id', '<', 3)
            ->where('added_by', '=', 'foreign')
            ->where('active', '=', '1')
            ->orderBy('id', 'desc')
            ->paginate($pageLimit);
        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }


    protected function invoiceOrdersCount(){
        $count = Invoice::where('file', '<>', NULL)
            ->where('status_id', '<', 3)
            ->where('active', '=', '1')
            ->get()->count();
        if($count==null){
            $count = 0;
        }
        return $count;

    }

    public function changeStatus(Request $request){
        $invoice = Invoice::find($request->get('id'));
        if($invoice){
            $old_status = $invoice->status_id;
            $invoice->status_id = $request->get('status_id');
            $invoice->save();
            if($old_status==4 and $old_status!=$invoice->status_id){
                $depot = DepotInvoice::where("invoice_id",$invoice->id)->first();
                $depot->delete();
            }elseif($old_status!=$invoice->status_id){
                $adminId = Auth::guard('admin')->user()->id;
                $note = "Admin id ".$adminId;
                $date=new InvoiceDates();
                $date->invoice_id=$invoice->id;
                $date->status_id=$invoice->status_id;
                $date->note = $note;
                $date->action_date=date("Y-m-d H:i:s");
                $date->save();
            }
        }


        return response()->json([
            'status' => 200,
            'data' => $invoice
        ]);
    }

    public function changeRegionCountry(Request $request){
        $cm = 99;

        $invoice = Invoice::find($request->get('id'));
        if($request->get("region_id",0)>0){
            $invoice->region_id = $request->get('region_id');
        }

        if($request->get("country_id",0)>0){
            $invoice->country_id = $request->get('country_id');
        }

        $invoice->save();


        if($invoice->corporate==0){
            $model = $invoice;

            $region_id = $model->region_id;

            $region_row = Regions::find($region_id);
            if($region_row==null){
                $region_id = 1;
            }

            $tariffs = Country::where('id', '=', $model->country_id)->with('tariffs')->first();

            $tariffArr = [];

            foreach ($tariffs->tariffs as $val) {
                if($val->region_id==$region_id){
                    $tariffArr[$val->label] = (float)$val->price;
                }
            }

            $weightResult = (float) ($model->length > $cm || $model->width > $cm || $model->height > $cm) ? $this->dimensionalWeight($model->width, $model->length, $model->height, $model->weight) : $model->weight;

            $resultCampaign = 0;
            $result = 0;

            if($weightResult>0) {
                if ($weightResult >= 0 && $weightResult <= 0.25) {
                    $result = $tariffArr['minWeightPrice'];
                } elseif ($weightResult > 0.25 && $weightResult <= 0.5) {
                    $result = $tariffArr['halfWeightPrice'];
                } elseif ($weightResult > 0.5 && $weightResult <= 0.7) {
                    $result = $tariffArr['bigHalfWeightPrice'];
                } elseif ($weightResult > 0.7 && $weightResult <= 1) {
                    $result = $tariffArr['weightPrice'];
                } else {
                    $result = round($tariffArr['weightPrice'] * $weightResult, 2);
                }
            }

            $result = $result + $resultCampaign;

            $model->delivery_price = $result;
            $model->shipping_price = $result;

            $model->save();
        }

        return response()->json([
            'status' => 200,
            'data' => $invoice
        ]);

    }

    private function dimensionalWeight($width, $length, $height, $weight) {
        $dimensionalWeight = round(($width * $length * $height) / 6000, 2);

        if ($dimensionalWeight > $weight) {
            return $dimensionalWeight;
        } else {
            return $weight;
        }
    }

    public function changeCountry(Request $request){
        $invoice = Invoice::find($request->get('id'));
        $invoice->country_id = $request->get('country_id');
        $invoice->save();

        return response()->json([
            'status' => 200,
            'data' => $invoice
        ]);
    }

    public function hasInvoices (Request $request) {
        $pageLimit = 10;
        $result = Invoice::with('products','products.users','products.users.userContacts')
            ->where('file', '<>', NULL)
            ->where('status_id', '>=', 2)
            ->where('active', '=', '1')
            ->orderBy('id', 'desc')
            ->paginate($pageLimit);
        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    public function hasInvoicesNew (Request $request) {
        $pageLimit = 10;
        $result = Invoice::select(DB::raw("invoices.id as id,invoices.purchase_no,a.name as admin_name,a.surname as admin_surname,u.id as user_id,u.name as name,u.surname as surname,u.email as email,u.uniqid as uniqid,uc.name as phone,p.price as price"))
            ->leftJoin('products as p', function($join) {
                $join->on('p.id', '=', 'invoices.product_id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('invoices.user_id', '=', 'u.id');
            })->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })->leftJoin('admins as a', function($join) {
                $join->on('p.admin_id', '=', 'a.id');
            })
            ->where('invoices.file', '<>', NULL)
            ->where('invoices.status_id', '>=', 2)
            ->where('invoices.active', '=', '1');

        $uniqid = $request->get('uniqid', false);
        $name = $request->get('name', false);
        $surname = $request->get('surname', false);
        $email = $request->get('email', false);
        $phone = $request->get('phone', false);
        $purchase_no = $request->get('purchase_no', false);
        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);
        $admin_name = $request->get('admin_name', false);

        if($uniqid) $result = $result->where("u.uniqid",$uniqid);
        if($name) $result = $result->where("u.name",'like','%'.$name.'%');
        if($surname) $result = $result->where("u.surname",'like','%'.$surname.'%');
        if($email) $result = $result->where("u.email",'like','%'.$email.'%');
        if($phone) $result = $result->where("uc.name",'like','%'.$phone.'%');
        if($admin_name) $result = $result->where("a.name",'like','%'.$admin_name.'%');
        if($purchase_no) $result = $result->where("invoices.purchase_no",'=',$purchase_no);
        if($begin_date) $result = $result->where("invoices.order_date",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $result = $result->where("invoices.order_date",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        $result = $result->orderBy('p.id', 'desc')
            ->paginate($pageLimit);

        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    public function hasInvoicesLate (Request $request) {
        $pageLimit = 10;
        $result = Invoice::select(DB::raw("invoices.id as id,invoices.order_date,invoices.purchase_no,a.name as admin_name,a.surname as admin_surname,u.id as user_id,u.name as name,u.surname as surname,u.email as email,u.uniqid as uniqid,uc.name as phone,p.price as price"))
            ->leftJoin('products as p', function($join) {
                $join->on('p.id', '=', 'invoices.product_id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('invoices.user_id', '=', 'u.id');
            })->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })->leftJoin('admins as a', function($join) {
                $join->on('p.admin_id', '=', 'a.id');
            })
            //->where('invoices.file', '<>', NULL)
            ->where('invoices.status_id', '=', 1)
            ->where('invoices.order_date', '<=', date("Y-m-d 00:00:00",strtotime(date('Y-m-d', strtotime('-7 days'))." 00:00:00"))) //
            ->where('invoices.active', '=', '1');

        $uniqid = $request->get('uniqid', false);
        $name = $request->get('name', false);
        $surname = $request->get('surname', false);
        $email = $request->get('email', false);
        $phone = $request->get('phone', false);
        $purchase_no = $request->get('purchase_no', false);
        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);
        $admin_name = $request->get('admin_name', false);

        if($uniqid) $result = $result->where("u.uniqid",$uniqid);
        if($name) $result = $result->where("u.name",'like','%'.$name.'%');
        if($surname) $result = $result->where("u.surname",'like','%'.$surname.'%');
        if($email) $result = $result->where("u.email",'like','%'.$email.'%');
        if($phone) $result = $result->where("uc.name",'like','%'.$phone.'%');
        if($admin_name) $result = $result->where("a.name",'like','%'.$admin_name.'%');
        if($purchase_no) $result = $result->where("invoices.purchase_no",'=',$purchase_no);
        if($begin_date) $result = $result->where("invoices.order_date",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $result = $result->where("invoices.order_date",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        $result = $result->orderBy('p.id', 'desc')
            ->paginate($pageLimit);

        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }


/*    public function invoicesByUser (Request $request) {
        $pageLimit = 10;
        $result = Invoice::with('products','products.users','products.users.userContacts')
            ->where('file', '<>', NULL)
            ->where('status_id', '=', 4)
            ->where('active', '=', '1')
            ->where('user_id', '=', $request->id)
            ->orderBy('id', 'desc')
            ->paginate($pageLimit);
        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }*/

    public function invoicesByUser(Request $request) {
        $uniqid = $request->get('uniqid', false);
        $fullname = $request->get('fullname', false);
        $User = User::with('userContacts','stock', 'stock.depotInvoice.depot','stock.courier', 'stock.products', 'stock.products.extras','stock.products.productsType')->where('uniqid','=', $uniqid);
        $fullname=explode(' ',$fullname);
        if(!empty($fullname[0])) $User = $User->orWhere('name' ,'like','%' . $fullname[0] . '%')->orWhere('surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $User = $User->orWhere('name' ,'like','%' . $fullname[1] . '%')->orWhere('surname' ,'like','%' . $fullname[1] . '%');

        $result = $User->first();
        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function invoicesByUserWithId($id) {
        $User = User::with('userContacts','stock', 'stock.depotInvoice.depot','stock.courier', 'stock.products', 'stock.products.extras','stock.products.productsType')->where('id','=', $id);

        $result = $User->first();
        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function invoicesByUser2(Request $request) {
        $uniqid = $request->get('uniqid', false);
        $fullname = $request->get('fullname', false);
        $region_id = $request->get('region_id', false);

        $User = User::with('userContacts')->where('uniqid','=', $uniqid);
       /* $fullname=explode(' ',$fullname);
        if(!empty($fullname[0])) $User = $User->orWhere('users.name' ,'like','%' . $fullname[0] . '%')->orWhere('users.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $User = $User->orWhere('users.name' ,'like','%' . $fullname[1] . '%')->orWhere('users.surname' ,'like','%' . $fullname[1] . '%');*/

        $result = $User->first();

        if($result) {
            $invoices = Invoice::select(DB::raw("invoices.id,invoices.client_id,invoices.country_id,invoices.status_id,invoices.shipping_price,invoices.purchase_no,invoices.weight,invoices.is_paid,c.is_paid as c_paid,di.depot_id,d.index,d.number,d.barcode_id,e.link,e.cargo_price,p.shop_name,a.name as admin_name,a.surname as admin_surname,p.price as price,p.product_type_name,p.quantity,cl.id as client_id,cl.name as client_name,cl.surname as client_surname"),
                DB::raw('(select action_date from invoice_dates where invoice_dates.invoice_id=invoices.id and invoice_dates.status_id = 4 order by id desc limit 1) as action_date'))

                ->leftJoin('depot_invoices as di', function($join) {
                    $join->on('invoices.id', '=', 'di.invoice_id');
                })
                ->leftJoin('depots as d', function($join) {
                    $join->on('di.depot_id', '=', 'd.id');
                })
                ->leftJoin('products as p', function($join) {
                    $join->on('p.id', '=', 'invoices.product_id');
                })
                ->leftJoin('extras as e', function($join) {
                    $join->on('p.extras_id', '=', 'e.id');
                })
                ->leftJoin('admins as a', function($join) {
                    $join->on('p.admin_id', '=', 'a.id');
                })
                ->leftJoin('couriers as c', function($join) {
                    $join->on('invoices.courier_id', '=', 'c.id');
                })->leftJoin('clients as cl', function($join) {
                    $join->on('invoices.client_id', '=', 'cl.id');
                })

                ->where("invoices.user_id","=",$result["id"])
                ->whereIn("invoices.status_id",[4,6,7,8])
                ->where(function ($query) {
                    $query->whereIn("invoices.status_id",[4,6])->orWhere('c.is_paid', '=', 0)
                        ->orWhere('invoices.is_paid', '=', 0);
                })
                ->where('invoices.active', '=', '1');

            if($region_id){
                $invoices = $invoices->where("invoices.region_id",$region_id);
            }

            $invoices = $invoices->get();
            $all_weight = 0;
            foreach ($invoices as $invoice){
                $all_weight += $invoice->weight;
            }
            $result["stock"] = $invoices;
            $result["all_weight"] = $all_weight;

            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function invoicesByUser3(Request $request) {
        $uniqid = $request->get('uniqid', false);
        $fullname = $request->get('fullname', false);
        $region_id = $request->get('region_id', false);

        $User = User::with('userContacts')->where('uniqid','=', $uniqid);
        /* $fullname=explode(' ',$fullname);
         if(!empty($fullname[0])) $User = $User->orWhere('users.name' ,'like','%' . $fullname[0] . '%')->orWhere('users.surname' ,'like','%' . $fullname[0] . '%');
         if(isset($fullname[1]) || !empty($fullname[1])) $User = $User->orWhere('users.name' ,'like','%' . $fullname[1] . '%')->orWhere('users.surname' ,'like','%' . $fullname[1] . '%');*/

        $result = $User->first();

        if($result) {
            $invoices = Invoice::select(DB::raw("invoices.id,invoices.country_id,invoices.status_id,invoices.shipping_price,invoices.purchase_no,invoices.weight,invoices.is_paid,c.is_paid as c_paid,di.depot_id,d.index,d.number,d.barcode_id,e.link,e.cargo_price,p.shop_name,a.name as admin_name,a.surname as admin_surname,p.price as price,p.product_type_name,p.quantity"))

                ->leftJoin('depot_invoices as di', function($join) {
                    $join->on('invoices.id', '=', 'di.invoice_id');
                })
                ->leftJoin('depots as d', function($join) {
                    $join->on('di.depot_id', '=', 'd.id');
                })
                ->leftJoin('products as p', function($join) {
                    $join->on('p.id', '=', 'invoices.product_id');
                })
                ->leftJoin('extras as e', function($join) {
                    $join->on('p.extras_id', '=', 'e.id');
                })
                ->leftJoin('admins as a', function($join) {
                    $join->on('p.admin_id', '=', 'a.id');
                })
                ->leftJoin('couriers as c', function($join) {
                    $join->on('invoices.courier_id', '=', 'c.id');
                })
                ->where("invoices.user_id","=",$result["id"])
                ->whereIn("invoices.status_id",[4,6,7,8])
                ->where(function ($query) {
                    $query->whereIn("invoices.status_id",[4,6])->orWhere('c.is_paid', '=', 0)
                        ->orWhere('invoices.is_paid', '=', 0);
                })
                ->where('invoices.active', '=', '1');

            if($region_id){
                $invoices = $invoices->where("invoices.region_id",$region_id);
            }

            $invoices = $invoices->get();

            $result["stock"] = $invoices;

            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    // icra olunanlarin siyahisi

    private function checkAllRejections($data) {
        $statusWaiting = getStatusByLabel('waiting', 'product');
        foreach ($data["products"] as $product) {
            if ($product->status_id === $statusWaiting) {
                return false;
            }
        }
        return true;
    }

    protected function allOrdersCount(){
        $result = Invoice::where('file', '<>', NULL)
            ->where('status_id', '>', 2)
            ->where('active', '=', '1')->get()->count();
        if($result==null){
            $result = 0;
        }
        return $result;
    }

    public function isEftOrders() {
        $adminId = Auth::guard('admin')->user()->id;
        $superAdminPermission = $this->checkRole('super_admin');

        $result = Product::with('adminsAcceptedProduct','users.userContacts', 'extras.countries.translates', 'productsType', 'invoices', 'statuses')
            ->where('is_ordered', '<>', Product::ORDERED)
            ->where('is_paid', '=', 1)
            ->where('is_eft', '=', 1)
            ->where('status_id', '=', 1);

        $result = $superAdminPermission ? $result->where('admin_id', '<>', null) : $result->where('admin_id', '=', $adminId);

        $result =
            $result->groupBy('user_id')
                ->get();

        $currnecy = Currency::find(1);

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
                'tl' => $currnecy->tl
            ]);
        }
    }

    protected function eftOrdersCount(){
        $result = Product::where('is_ordered', '<>', Product::ORDERED)
            ->where('status_id', '=', 1)
            ->where('is_eft', '=', 1)
            ->get()->count();
        if($result==null){
            $result = 0;
        }
        return $result;

    }

    private function checkRole ($label) {
        $status = false;
        foreach (Auth::guard('admin')->user()->relAdminRoles as $value) {
            if($value->relRole->label === $label) {
                $status = true;
            }
        }
        return $status;
    }

    public function statuses()
    {
        $statuses = DB::table('statuses')->where("type","invoice")->get();
        return response()->json(['status' => 200, 'statuses' => $statuses]);
    }

    public function regions()
    {
        $statuses = DB::table('regions')->get();
        return response()->json(['status' => 200, 'regions' => $statuses]);
    }

    public function countries()
    {
        $countries = DB::table('countries')->where("status",1)->get();
        return response()->json(['status' => 200, 'countries' => $countries]);
    }

    public function update(Request $request,Product $product){
        $product_extra = DB::table('extras')->where('id','=',$product->extras_id);
        $result = $product_extra->update([
            'link' => $request->link,
            'link2' => $request->link,
        ]);

        return $result;
    }

    public function problem(Request $request)
    {
        $product_id = $request->product;
        $product = Product::find($product_id);
        $problem_text  = $request->problem_text;

        $is_problem  = intval($request->get("is_problem",0));

        $product->update([
            "is_problem" => $is_problem,
            "problem_text" => $problem_text
        ]);

        return 1;
    }

    public function sendSMS(Request $request)
    {
        $phone = str_replace(' ','',$request->phone);
        $text = $request->text;
        $user_id = $request->get('user_id', 0);


        $data = (object) ['text' => $text,"user_id" =>$user_id];
        smsSend($data, $phone);

        //$cvb = sms((object) ['text' => $text], $phone);
        return response()->json(['status' => 200]);
    }

    public function  sendEmail(Request $request)
    {
        $email =str_replace(' ','',$request->email);
        $text = $request->text;

        email((object) ['text' => $text], $email);
        return response()->json(['status' => 200]);
    }

    public function backInvoice (Request $request) {
        $invoiceId = $request['invoiceId'];
        $invoiceModel = Invoice::find($invoiceId);
        $adminId = Auth::guard('admin')->user()->id;
        if($invoiceModel!=null){
            //$returnRow = DB::table("returns")->where("invoice_id",$invoiceModel->id)->first();
            $returnRow = Returns::where("invoice_id",$invoiceModel->id)->first();

            if(intval($invoiceModel->return_id)==0 and $returnRow==null){
                if(in_array($invoiceModel->status_id,[1,2]))
                {
                    $return_status = 3;
                    $send_date = date("Y-m-d H:i:s");
                    $depo_date = date("Y-m-d H:i:s");
                }else{
                    $return_status = 1;
                    $send_date = '';
                    $depo_date = '';
                }

                $returnModel = new Returns();
                $returnModel->country_id            = $invoiceModel->country_id;
                $returnModel->region_id             = $invoiceModel->region_id;
                $returnModel->user_id               = $invoiceModel->user_id;
                $returnModel->invoice_id            = $invoiceModel->id;
                $returnModel->shop_name             = $request['shop'];
                $returnModel->product_type_name     = $request['productType'];
                $returnModel->product_price         = $request['productPrice'];
                $returnModel->product_price         = $request['productPrice'];
                $returnModel->product_quantity      = $request['packageCount'];
                $returnModel->price                 = $request['returnPrice'];
                $returnModel->is_paid               = 1;
                $returnModel->weight                = $invoiceModel->weight;
                $returnModel->tracking_number       = "";
                $returnModel->send_date             = $send_date;
                $returnModel->depo_date             = $depo_date;
                $returnModel->ok_date               = "";
                $returnModel->created_at            = date("Y-m-d H:i:s");
                $returnModel->updated_at            = date("Y-m-d H:i:s");
                $returnModel->status_id             = $return_status;
                $returnModel->admin_id              = $adminId;
                $returnModel->comment               = $request['comment'];;

                if ($returnModel->save()) {

                    $statusRow = Status::where("type","invoice")->where("label","returns")->first();

                    $invoiceModel->return_id = $returnModel->id;
                    $invoiceModel->status_id = $statusRow->sid;
                    $invoiceModel->save();

                    $invoiceDates = new InvoiceDates();
                    $invoiceDates->invoice_id = $invoiceModel->id;
                    $invoiceDates->status_id =$statusRow->sid;
                    $invoiceDates->action_date = date("Y-m-d H:i:s");
                    $invoiceDates->note = "Admin id ".$adminId;
                    $invoiceDates->save();

                    return response()->json([
                        'status'  => 200,
                        'message' => 'Bəyənnamə qaytarılacaqlar siyahısına əlavə edildi!'
                    ]);
                }


            }else{
                if($returnRow!=null){

                    $returnRow->comment               = $request["comment"];
                    $returnRow->shop_name             = $request['shop'];
                    $returnRow->product_type_name     = $request['productType'];
                    $returnRow->product_price         = $request['productPrice'];
                    $returnRow->product_price         = $request['productPrice'];
                    $returnRow->product_quantity      = $request['packageCount'];
                    $returnRow->price                 = $request['returnPrice'];
                    $returnRow->save();

                    return response()->json([
                        'status'  => 200,
                        'message' => 'Məlumat yadda saxlanıldı!'
                    ]);
                }else{
                    return response()->json([
                        'status'  => 500,
                        'message' => 'Qaytarılacaq mallar içərisində var'
                    ]);
                }


            }
        }else{
            return response()->json([
                'status'  => 500,
                'message' => 'Xəta.Bəyənnamə yoxdur!'
            ]);
        }


    }

}
