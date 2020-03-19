<?php
namespace App\Http\Controllers\Admin\Accountant;
use App\ModelLogs\LogProductRejection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class LogRejectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $limit = 10;
    public function index()
    {
        $params = [];

        $data = DB::table('log_product_rejections')
            ->select(
        'log_product_rejections.product_id',
                'log_product_rejections.id as id',
                'log_product_rejections.updated_at',
                'log_product_rejections.rel_user_product_id',
                'log_product_rejections.note',
                'admins.name as fromName',
                'admins.surname as fromSurname',
                'ad1.name as toName',
                'ad1.surname as toSurname',
                'product_types.name as productTypeName',
                'rel_user_products.price',
                'rel_user_products.is_paid',
                'users.name as userName',
                'users.surname as userSurname',
                'users.email',
                'user_contacts.name as phone',
                'products.status_id as productStatus',
                'products.shop_name as shopName',
                'products.price as productPrice',
                'products.quantity as productQuantity',
                'products.product_type_name',

                'rejectPt.name as rel_productTypeName',
                'rejectRup.price as rel_price',
                'rejectRup.is_paid as rel_is_paid',
                'relUser.name as rel_userName',
                'relUser.surname as rel_userSurname',
                'relUser.email as rel_email',
                'relUserContacts.name as rel_phone',
                'relProducts.status_id as rel_productStatus',
                'relProducts.price as rel_productPrice',
                'relProducts.shop_name as rel_shopName',
                'relProducts.quantity as rel_productQuantity',
                'relProducts.product_type_name as relProductTypeName'
            )
            ->leftJoin('admins','log_product_rejections.from_admin_id', '=', 'admins.id')
            ->leftJoin('admins as ad1','log_product_rejections.to_admin_id', '=', 'ad1.id')
            ->leftJoin('products',function($join) {
                $join->on('log_product_rejections.product_id', '=', 'products.id')
                ->leftJoin('product_types','products.product_type_id', '=', 'product_types.id')
                ->leftJoin('rel_user_products', function($join) {
                    $join->on('products.rel_user_product_id', '=', 'rel_user_products.id')
                    ->leftJoin('users', function($join) {
                        $join->on('rel_user_products.user_id', '=', 'users.id')
                            ->leftJoin('user_contacts', 'users.id', '=', 'user_contacts.user_id');
                    });
                });
            })
            ->leftJoin('rel_user_products as rejectRup', function($join) {
               $join->on('log_product_rejections.rel_user_product_id', '=', 'rejectRup.id')
               ->leftJoin('products as relProducts', function($join) {
                   $join->on('rejectRup.id', '=', 'relProducts.rel_user_product_id')
                       ->leftJoin('product_types as rejectPt','relProducts.product_type_id', '=', 'rejectPt.id');
                })
               ->leftJoin('users as relUser', function($join) {
                   $join->on('rejectRup.user_id', '=', 'relUser.id')
                       ->leftJoin('user_contacts as relUserContacts', 'relUser.id', '=', 'relUserContacts.user_id' );
               });
            })
            ->orderBy('log_product_rejections.created_at', 'desc')
            ->paginate(2);

        return view('admin.accountant.log_rejections.index', compact('data', 'params'));
    }



    public function edit($id)
    {
        $data = Faq::find($id);

        return view('admin.faq.form', compact('data', 'id'));
    }

    public function search ($q) {
        $cp = [['locale', '=', 'az']];
        $p = [['id','<>', 0]];
        isset($q['id'])?$p[] = ['id', '=', $q['id']]:'';
        isset($q['answer'])?$cp[] = ['answer', 'like', '%'.$q['answer'].'%']:'';
        isset($q['question'])?$cp[] = ['question', 'like', '%'.$q['question'].'%']:'';
        $data = LogProductRejection::where($p)->with('translates')->whereHas('translates', function($query) use ($cp) {
            $query->where($cp);
        })->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
