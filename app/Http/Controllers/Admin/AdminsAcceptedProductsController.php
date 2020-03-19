<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderAccepted;
use App\ModelPermissions\RelAdminRole;
use App\ModelProduct\Product;
use App\ModelUser\User;
use App\RelAdminsAcceptedProduct;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminsAcceptedProductsController extends Controller
{
    public function index() {
        $adminId = Auth::guard('admin')->user()->id;
        return View('admin.accept.index', compact('data', 'adminId'));
    }

    public function acceptOrder(Request $request) {
        $adminId = $request['adminId'];
        $relUserProductId = $request['relUserProductId'];

        $count = RelUserProduct::where('id', '=', $relUserProductId)->where('admin_id', '<>', null)->count();

        if ($count == 0) {
            $orderWaitingStatus = getStatusByLabel('waiting', 'transaction');
            $orderCount = RelUserProduct::where('status_id', '=', $orderWaitingStatus)->where('admin_id', '=', $adminId)->count();
            if ($orderCount < 700) {
                $model = RelUserProduct::find($relUserProductId);
                $model->admin_id = $adminId;

                if ($model->save()) {
                    event(new OrderAccepted('test', true));
                    //notify((object)['template' => 'order-accepted'], (object)['phone' => $model->users->userContacts[0]->name, 'email' => $model->users->email]);
                    return response()->json(['code' => 200, 'message' => 'Sizde alindi ;)']);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Sizde alinmadi ;(']);
                }
            } else {
                return response()->json(['code' => 203, 'message' => 'Sizin artıq 20 sifarişiniz var. Əvvəlcə onları yekunlaşdırmalısınız']);
            }
        } else {
            return response()->json(['code' => 500, 'message' => 'Artiq secilib :|']);
        }
    }

    public function order(Request $request, $id) {
        $model = RelUserProduct::with('users', 'products.extras.countries.translates', 'products.productsType')->where('id', '=', $id)->first();

        return response()->json(['status' => 200, 'result' => $model]);
    }

    public function getOrders() {
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $relAdminRoles = RelAdminRole::with('relRole')->where('admin_id', '=', Auth::guard('admin')->user()->id)->get();

        $deliveryType = [];

        foreach ($relAdminRoles as $relAdminRole) {
            if ($relAdminRole->relRole->label === 'express-buyer') {
                $deliveryType[] = 'express';
            } else if ($relAdminRole->relRole->label === 'buyer') {
                $deliveryType[] = 'standart';
            }

            if ($relAdminRole->relRole->label === 'super_admin') {
                $deliveryType = null;
                $deliveryType = ['standart', 'express'];
            }
        }

        return response()->json([
            'data' => RelUserProduct::with('users', 'users.userContacts', 'products.extras.countries.translates', 'products.productsType')
                ->where('admin_id', '=', null)
                ->whereIn('delivery_type', $deliveryType)
                ->where('transaction_id', '<>', 'null')
                ->where('is_paid', '=', RelUserProduct::PAID)
                ->where('status_id', '=', $transactionWaiting)->get()
        ]);

        /*
         *  ->whereDoesntHave('relAdminsAcceptedProduct', function($query) {
                    $query->whereIn('rel_user_product_id', [1, 2, 3]);
                })
         * */
    }

    public function getFinishedOrdersLog () {
        $invoiceAddStatus = getStatusByLabel('invoice_added', 'transaction');
        $adminId = Auth::guard('admin')->user()->id;

        return response()->json([
            'data' => RelUserProduct::with('users', 'users.userContacts', 'products.extras.countries.translates', 'products.productsType')
                ->where('admin_id', '=', $adminId)
                ->where('status_id', '=', $invoiceAddStatus)->orderBy('created_at', 'DESC')->get()
        ]);
    }

    public function getOrderDetail($id) {
        return response()->json([
            'data' => RelUserProduct::with('users', 'products.extras.countries.translates', 'products.productsType')
                ->where('id', '=', $id)->first()
        ]);
    }

    public function getOrderDetailByAdmin($id) {
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $result = RelUserProduct::with('users.userContacts', 'products.extras.countries.translates', 'products.productsType', 'products.invoices', 'products.statuses', 'statuses')
            ->where('status_id', '=', $transactionWaiting)
            ->where('id', '=', $id)->first();

        if($result) {
            $acceptButton = $this->checkAcceptButton($result);
            return response()->json([
                'status' => 200,
                'data' => $result,
                'acceptButton' => $acceptButton,
                'rejRefCompleteButton' => $this->checkIsAllRejections($result)
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }


    // new
    public function getOrderDetailByUser($id) {
        // status qalib
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $callback = function ($query) use ($transactionWaiting) {
            $query->where('status_id', '=', $transactionWaiting);
        };
        $result = User::with('userContacts','products','products.extras.countries.translates','products.productsType','products.invoices','products.statuses')
            ->whereHas("products",$callback)
            ->where('id', '=', $id)->first();
        if($result) {
            $acceptButton = $this->checkAcceptedButton($result);
            return response()->json([
                'status' => 200,
                'data' => $result,
                'acceptButton' => $acceptButton,
                'rejRefCompleteButton' => $this->checkAllRejections($result)
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function getWillUploadInvoiceDetailByAdmin ($id) {
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $result = RelUserProduct::with('users.userContacts', 'products.extras.countries.translates', 'products.productsType', 'products.invoices', 'products.statuses', 'statuses')
            //->where('is_ordered', '=', RelUserProduct::ORDERED)
            //->where('status_id', '=', $transactionWaiting)
            ->where('id', '=', $id)->first();


        if($result) {
            $acceptButton = $this->hasNullFile($result);
            return response()->json([
                'status' => 200,
                'data' => $result,
                'acceptButton' => $acceptButton,
                'rejRefCompleteButton' => $this->checkIsAllRejections($result)
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function getOrderLogDetailByAdmin($id) {
        $adminId = Auth::guard('admin')->user()->id;
        $invoiceAdd = getStatusByLabel('invoice_added', 'transaction');
        $result = RelUserProduct::with('users.userContacts', 'products.extras.countries.translates', 'products.productsType', 'products.invoices', 'products.statuses', 'statuses')
            ->where('admin_id', '=', $adminId)
            ->where('status_id', '=', $invoiceAdd)
            ->where('id', '=', $id)->first();


        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'İmtina edilmişdir'
            ]);
        }
    }

    public function finishOrder(Request $request) {
        $transactionId = $request['transactionId'];
        $invoiceAddStatus = getStatusByLabel('invoice_added', 'transaction');
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');

        $result = RelUserProduct::with('users.userContacts', 'products.extras.countries.translates', 'products.productsType', 'products.invoices', 'products.statuses', 'products')
            ->where('is_ordered', '=', RelUserProduct::ORDERED)
            ->where('status_id', '=', $transactionWaiting)
            ->where('id', '=', $transactionId)->first();

        foreach ($result->products as $product) {
            $product->status_id = getStatusByLabel('completed', 'product');
        }

        $result->status_id = $invoiceAddStatus;

        if ($result->save()) {
            return response()->json(['status' => 200, 'message' => 'Tamamlandı']);
        }
    }

    private function checkAcceptButton($data) {
        $statusRejection = getStatusByLabel('rejection', 'product');
        $statusRejectionCom = getStatusByLabel('rejection_accepted', 'product');
        $statusWaiting = getStatusByLabel('waiting', 'product');
        $productStatuses = [];

        foreach ($data['products'] as $product) {
            $productStatuses[] = $product->status_id;
        }

        foreach ($data['products'] as $product) {
            if (count($product->invoices) == 0 && $product->status_id !== $statusRejection && $product->status_id !== $statusRejectionCom) {
                    return false;
            }

            if (!in_array($statusWaiting, $productStatuses)) {
                return false;
            }
        }
        return true;
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

    private function checkIsAllRejections($data) {
        $statusWaiting = getStatusByLabel('waiting', 'product');
        foreach ($data['products'] as $product) {
            if ($product->status_id === $statusWaiting) {
                return false;
            }
        }
        return true;
    }

    private function checkAllRejections($data) {
        $statusWaiting = getStatusByLabel('waiting', 'product');
        foreach ($data["products"] as $product) {
            if ($product->status_id === $statusWaiting) {
                return false;
            }
        }
        return true;
    }

    private function hasNullFile($data) {
        $statusRejection = getStatusByLabel('rejection', 'product');
        $statusRejectionCom = getStatusByLabel('rejection_accepted', 'product');
        foreach ($data['products'] as $product) {
            if (count($product->invoices) != 0 && $product->invoices[0]->file == null && $product->status_id !== $statusRejection && $product->status_id !== $statusRejectionCom) {
                return false;
            }
        }
        return true;
    }
}
