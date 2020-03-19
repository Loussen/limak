<template>
    <div v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Kuryer</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li class="active">Kuryer</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Kuryer paneli</a></li>
                    <!--<li role="presentation" ><a href="#handed" aria-controls="handed" role="tab" data-toggle="tab">Təhvil verilmiş</a></li>-->
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="tab-content courier">
                <div role="tabpanel" class="tab-pane fade in active" id="profile">
                    <div class="block table-block">
                        <!--<div class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>-->
                        <table  class="table table-striped">
                            <thead>
                            <tr>
                                <th>Müştəri Adı, Soyadı, Kodu</th>
                                <th>Telefon, Email</th>
                                <th>Çatdırmaq istədiyi ünvan</th>
                                <th>Çatdırma vaxtı</th>
                                <th>Çatdırılma qiyməti(xarici)</th>
                                <th>İstifadəçi balansı</th>
                                <th>Bağlama sayı</th>
                                <th>Bağlama içindəkilər</th>
                                <th>Qiymət</th>
                                <th>Ödənilib</th>
                                <th>Cəmi qiymət</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order of acceptedOrders">
                                <td>{{order.name}} {{order.surname}} <b>{{order.uniqid}}</b></td>
                                <td>{{order.phone}} <b>{{order.email}}</b></td>
                                <td>{{order.city}} {{order.district}} {{order.street}} {{order.village}} {{order.home}}</td>
                                <td>{{order.delivery_type==1 ? '12' : '2'}} Saat</td>
                                <td>
                                    <a href="javascript:void(0)" v-if="order.is_paids == order.quantity" class="green1 btn-effect">Ödənilib</a>
                                    <p v-if="order.is_paids <  order.quantity">{{order.odenilmemis}} USD ({{(order.odenilmemis * usdToAzn).toFixed(2)}} AZN) </p>
                                    <p v-if="order.is_paids <  order.quantity && order.is_paids!=0">{{order.is_paids}} ədəd ödənilib</p>
                                </td>
                                <td>{{order.balance}} AZN</td>
                                <td>{{order.quantity }} </td>
                                <td style="word-break: break-all;">{{order.product_name}}</td>
                                <td>{{order.delivery_type==1 ? '3' : '5'}} AZN</td>
                                <td>
                                    <a href="javascript:void(0)" v-if="order.is_paid == 1"   class="green1 btn-effect">Ödənilib</a>
                                    <a href="javascript:void(0)" v-if="order.is_paid == 0"  class="btn-effect red1">Ödənilməyib</a>
                                </td>
                                <td>{{getTotalPrice(order)}}</td>
                                <td>
                                    <button @click="completeOrder(order)" type="button" class="btn-effect executed blue">Sifariş təhvil verildi</button>
                                    <!--<a @click="showInvoice(order)" class="btn-effect">Bax</a>-->
                                </td>
                                <td>
                                    <button @click="anbaraVerildi(order)" type="button" class="btn-effect executed blue">Sifariş anbara verildi</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModal === true" class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p>Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>
<script>
    import auth from '../auth.js'
    export default {
        name: "CourierPanelComponent",
        mounted() {
            this.getCourierAccepted();
            /*setInterval(function() {
                this.getCourierAccepted();
            }.bind(this), 3000);*/
        },
        data: function () {
            return {
                showModal: false,
                acceptedOrders: null,
                handedOrders: null,
                usdToAzn: null,
            }
        },
        methods: {

            getCourierAccepted() {
                axios.get('/admin/courier/orders-waiting?has_courier=1&status=6')
                    .then(data => {
                        this.acceptedOrders = data.data.data;
                        this.usdToAzn = data.data.usdToAzn;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getTotalPrice(order){
                let total_price = 0;
                if(!order.is_paid)
                    total_price += parseFloat((order.delivery_type == 1)? 3 : 5);

                if(!order.invoice_paid)
                    total_price += parseFloat((parseFloat(order.shipping_price) * this.usdToAzn).toFixed(2));

                if(parseFloat(order.balance) < 0 )
                    total_price -= parseFloat(order.balance);

                return total_price.toFixed(2);
            },
            completeOrder(order){
                axios.post('/admin/courier/complete-order', {courierId:order.c_id,email:order.email,phone:order.phone })
                    .then(data => {
                        this.refreshDatas();
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            anbaraVerildi(order){
                // this.showModal = true;
                axios.post('/admin/courier/give-to-depot', {courierId:order.c_id,email:order.email,phone:order.phone })
                    .then(data => {
                        this.refreshDatas();
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            showInvoice(productData) {

                this.showShowInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

            },
            refreshDatas(){
                this.getCourierAccepted();
                // this.getCourierAccepted();
                // this.getCourierHanded();
            },
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','courier'))
            }
        },
        mixins: [auth]
    }
</script>


<style scoped>
    .btn-effect.blue{
        padding: 5px;
        height: auto;
        padding: 5px
    }
    .btn-effect.red1{
        height: auto;
        width: 90px;
        padding: 5px;
        background: red;
    }
    .btn-effect.green1{
        height: auto;
        width: 100%;
        padding: 5px;
        background: green;
    }
    #admin-index .table .btn-effect:after{
        content:'';
    }

    .loading {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 26px;
        height: 26px;
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%);
    }

    .table td, .table th {
        font-size: 15px;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>