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
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Sifarişin qəbulu və təyini</a></li>
                    <li role="presentation" ><a href="#delivered" aria-controls="delivered" role="tab" data-toggle="tab">Sifarişin təhvili</a></li>
                    <li role="presentation" ><a href="#couriers" aria-controls="couriers" role="tab" data-toggle="tab">Kuryerlər</a></li>
                    <li role="presentation" ><a href="#courier_panel" aria-controls="courier_panel" role="tab" data-toggle="tab">Kuryer Panel</a></li>
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
        <div class="tab-content courier">
            <div role="tabpanel" class="tab-pane fade in active" id="home">
                <div class="block table-block">
                    <div v-if="data.data == null" class="loading"></div>
                    <!--<div  class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>-->
                    <table  class="table table-striped">
                        <thead>
                        <tr v-bind="count_i=0">
                            <th></th>
                            <th>Müştəri Adı, Soyadı, Kodu</th>
                            <th>Telefon, Email</th>
                            <th>Çatdırmaq istədiyi ünvan</th>
                            <th>Qeyd</th>
                            <th>Çatdırılma qiyməti(xarici)</th>
                            <th>İstifadəçi balansı</th>
                            <th>Anbar</th>
                            <th>Anbardakı Bağlama sayı</th>
                            <th>Bağlama sayı</th>
                            <th>Bağlama içindəkilər</th>
                            <th>Qiymət</th>
                            <th>Ödənilib</th>
                            <th>Cəmi qiymət </th>
                            <th></th>
                            <th>Tarix</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="order of data.data">
                            <td>{{count_i = count_i+1}}</td>
                            <td>{{order.name}} {{order.surname}} <b>{{order.uniqid}}</b></td>
                            <td>{{order.phone}} <b>{{order.email}}</b></td>
                            <td>{{order.city}} {{order.district}} {{order.street}} {{order.village}} {{order.home}} {{order.phone2}}</td>
                            <td>{{order.description}}</td>
                            <td>
                                <a href="javascript:void(0)" v-if="order.is_paids == order.quantity" class="green btn-effect">Ödənilib</a>
                                <p v-if="order.is_paids <  order.quantity">{{order.odenilmemis}} USD ({{(order.odenilmemis * usdToAzn).toFixed(2)}} AZN) </p>
                                <p v-if="order.is_paids <  order.quantity && order.is_paids!=0">{{order.is_paids}} ədəd ödənilib</p>
                            </td>
                            <td>{{order.balance}} AZN</td>
                            <td style="word-break: break-all;">{{order.depot}}</td>
                            <td v-if="order.count_depo">{{order.count_depo }} </td>
                            <td v-else></td>
                            <td>{{order.quantity }} </td>
                            <td style="word-break: break-all;">{{order.product_name}}</td>
                            <td>{{order.delivery_type==1 ? '3' : '5'}} AZN</td>
                            <td>
                                <a href="javascript:void(0)" v-if="order.is_paid == 1"  style="background:green" class="green btn-effect">Ödənilib</a>
                                <a href="javascript:void(0)" v-if="order.is_paid == 0" style="width: 100px;background:red" class="btn-effect red1">Ödənilməyib</a>
                            </td>
                            <td>{{ getTotalPrice(order) }}</td>
                            <td >
                                <a href="javascript:void(0)" @click="showInvoice(order)" class="btn-effect">Bax</a>
                            </td>
                            <td>{{ order.created_at | formatDate}}</td>

                        </tr>
                        </tbody>
                    </table>
                    <!--<nav>-->
                        <!--<ul class="pagination">-->
                            <!--<li class="disabled"><a href="#" aria-label="Previous"><span-->
                                    <!--aria-hidden="true">ƏVVƏL</span></a></li>-->
                            <!--<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>-->
                            <!--<li><a href="#">2</a></li>-->
                            <!--<li><a href="#">3</a></li>-->
                            <!--<li><a href="#">4</a></li>-->
                            <!--<li><a href="#">5</a></li>-->
                            <!--<li>-->
                                <!--<a href="#" aria-label="Next">-->
                                    <!--<span aria-hidden="true">NÖVBƏTİ</span>-->
                                <!--</a>-->
                            <!--</li>-->
                        <!--</ul>-->
                    <!--</nav>-->
                </div>
            </div>
            <div role="tabpane2" class="tab-pane" id="delivered">
                <div class="block table-block">
                    <div v-if="delivered.data == null" class="loading"></div>
                    <!--<div  class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>-->
                    <table  class="table table-striped">
                        <thead>
                        <tr v-bind="count_i=0">
                            <th></th>
                            <th>Müştəri Adı, Soyadı, Kodu</th>
                            <th>Telefon, Email</th>
                            <th>Çatdırmaq istədiyi ünvan</th>
                            <th>Qeyd</th>
                            <th>Çatdırılma qiyməti(xarici)</th>
                            <th>İstifadəçi balansı</th>
                            <th>Bağlama sayı</th>
                            <th>Bağlama içindəkilər</th>
                            <th>Qiymət</th>
                            <th>Ödənilib</th>
                            <th>Təhvil verilmə tarixi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="order of delivered.data">
                            <td>{{count_i = count_i+1}}</td>
                            <td>{{order.name}} {{order.surname}} <b>{{order.uniqid}}</b></td>
                            <td>{{order.phone}} <b>{{order.email}}</b></td>
                            <td>{{order.city}} {{order.district}} {{order.street}} {{order.village}} {{order.home}} {{order.phone2}}</td>
                            <td>{{order.description}}</td>
                            <td>
                                <a href="javascript:void(0)" v-if="order.is_paids == order.quantity" class="green btn-effect">Ödənilib</a>
                                <p v-if="order.is_paids <  order.quantity">{{order.odenilmemis}} USD ({{(order.odenilmemis * usdToAzn).toFixed(2)}} AZN) </p>
                                <p v-if="order.is_paids <  order.quantity && order.is_paids!=0">{{order.is_paids}} ədəd ödənilib</p>
                            <td>{{order.balance}} AZN</td>
                            <td>{{order.quantity }} </td>
                            <td style="overflow-x: scroll">{{order.product_name}}</td>
                            <td>{{order.delivery_type==1 ? '3' : '5'}} AZN</td>
                            <td>
                                <a v-if="order.is_paid == 1" style="width: 100px" class="btn-effect green">Ödənilib</a>
                                <a v-if="order.is_paid == 0" style="width: 100px" class="btn-effect red1">Ödənilməyib</a>
                            </td>
                            <td>
                                {{ order.created_at}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li ><a @click.prevent="getDeliveredOrders(delivered.current_page - 1)" href="#" aria-label="Previous"><span
                                    aria-hidden="true">ƏVVƏL</span></a></li>
                            <template  v-for="page in delivered.last_page">
                                <li v-if="page ==1 && delivered.current_page==1" class="active">
                                    <a @click.prevent="getDeliveredOrders( page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="page ==1">
                                    <a @click.prevent="getDeliveredOrders( page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="Math.abs(delivered.current_page - page)> 3 && Math.abs(delivered.current_page - page)<= 6 ">
                                    <a href="#">.</a>
                                </li>
                                <li v-else-if="Math.abs(delivered.current_page- page)<= 3 && delivered.current_page == page" class="active">
                                    <a @click.prevent="getDeliveredOrders( page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="Math.abs(delivered.current_page - page)<= 3 ">
                                    <a @click.prevent="getDeliveredOrders(  page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="page == delivered.last_page">
                                    <a @click.prevent="getDeliveredOrders( page)" href="#">{{ page }}</a>
                                </li>
                            </template>
                            <li>
                                <a v-show="delivered.current_page < delivered.last_page" @click.prevent="getDeliveredOrders( delivered.current_page + 1)" href="#" aria-label="Next">
                                    <span aria-hidden="true">NÖVBƏTİ</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div role="tabpane3" class="tab-pane" id="couriers">
                <div class="block table-block">
                    <!--<div  class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>-->
                    <table  class="table table-striped">
                        <thead>
                        <tr v-bind="count_i=0">
                            <th></th>
                            <th>Kuryer Adı, Soyadı</th>
                            <th>İnvoys sayı</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item of couriers">
                            <td>{{count_i = count_i+1}}</td>
                            <td>{{ item.name }} {{ item.surname }}</td>
                            <td>{{ item.count }} AZN</td>
                            <td>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div role="tabpane4" class="tab-pane" id="courier_panel">
                <div class="tab-content courier">
                    <div v-if="acceptedOrders.data == null" class="loading"></div>

                    <div role="tabpanel" class="tab-pane fade in active" id="profile">
                        <div class="block table-block">
                            <!--<div class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>-->
                            <table  class="table table-striped">
                                <thead>
                                <tr v-bind="count_i=0">
                                    <th></th>
                                    <th>Müştəri Adı, Soyadı, Kodu</th>
                                    <th>Telefon, Email</th>
                                    <th>Çatdırmaq istədiyi ünvan</th>
                                    <th>Qeyd</th>
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
                                <tr v-for="order of acceptedOrders.data">
                                    <td>{{count_i = count_i+1}}</td>
                                    <td>{{order.name}} {{order.surname}} <b>{{order.uniqid}}</b></td>
                                    <td>{{order.phone}} <b>{{order.email}}</b></td>
                                    <td>{{order.city}} {{order.district}} {{order.street}} {{order.village}} {{order.home}} {{order.phone2}}</td>
                                    <td>{{order.description}}</td>
                                    <td>
                                        <a href="javascript:void(0)" v-if="order.is_paids == order.quantity" class="green btn-effect">Ödənilib</a>
                                        <p v-if="order.is_paids <  order.quantity">{{order.odenilmemis}} USD ({{(order.odenilmemis * usdToAzn).toFixed(2)}} AZN) </p>
                                        <p v-if="order.is_paids <  order.quantity && order.is_paids!=0">{{order.is_paids}} ədəd ödənilib</p>
                                    </td>
                                    <td>{{order.balance}} AZN</td>
                                    <td>{{order.quantity }} </td>
                                    <td style="word-break: break-all;">{{order.product_name}}</td>
                                    <td>{{order.delivery_type==1 ? '3' : '5'}} AZN</td>
                                    <td>
                                        <a href="javascript:void(0)" v-if="order.is_paid == 1"   class="green btn-effect">Ödənilib</a>
                                        <a href="javascript:void(0)" v-if="order.is_paid == 0"  class="btn-effect red1">Ödənilməyib</a>
                                    </td>
                                    <td>{{getTotalPrice(order)}}</td>
                                    <td>
                                        <button @click="completeOrder(order)" type="button" class="btn-effect executed blue">Sifariş təhvil verildi</button>
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
        </div>
    </div>
        <div :class="showModal === true ? 'show in' : ''" class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="showModal = false">&times;</button>
                        <h4 class="modal-title" >Anbarda yeri.</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" type="text" v-model="depot">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-effect" style="width: 50px" @click="submitDepot()">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <show-invoice-data-modal v-bind:productData="showProductData" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal" ></show-invoice-data-modal>
    </div>
</template>
<script>
    import ShowInvoiceDataModal from "./modals/showInvoiceDataModal";
    import auth from '../auth.js'

    import swal from 'sweetalert2';

    export default {
        components: {ShowInvoiceDataModal},
        name: "CourierIndexComponent",
        mounted() {
            this.getCourierOrders();
            this.getDeliveredOrders();
            this.getCourierUsers();
            this.getCourierAccepted();
            setInterval(function() {
                this.getCourierAccepted();
            }.bind(this), 3000);
        },
        data: function () {
            return {
                depot:'',
                selected_invoice:'',
                showModal: false,
                data: null,
                delivered: null,
                show: false,
                modalData: null,
                showShowInvoiceModal: false,
                showProductData: null,
                sumPrice:0,
                courier_users:[],
                usdToAzn:'',
                couriers: [],
                acceptedOrders: null,


            }
        },
        methods: {
            submitDepot(){
                axios.get(`/admin/courier/check-depot?barcode=${this.depot}&invoice_id=${this.selected_invoice}`)
                    .then(data => {
                        console.log(data)
                        if(data.data.status === 200) {
                            swal({
                                title: 'Əlavə edildi.',
                                type: 'success',
                            })
                        }else if(data.data.status === 500){
                            swal({
                                title: 'Yer doludur',
                                type: 'error',
                            })
                        }
                        this.showModal = false;
                        this.refreshDatas();
                    })
                    .catch(err => {
                        console.log(err);
                    });
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
                this.showModal = true;
                this.selected_invoice = order.i_id;
                // axios.post('/admin/courier/give-to-depot', {courierId:order.c_id,email:order.email,phone:order.phone })
                //     .then(data => {
                //         this.refreshDatas();
                //     })
                //     .catch(err => {
                //         console.log(err);
                //     });
            },
            getCourierAccepted(page_id=1) {
                axios.get('/admin/courier/orders-waiting?has_courier=1&status=6&page='+page_id)
                    .then(data => {
                        this.acceptedOrders = data.data.data;
                        this.usdToAzn = data.data.usdToAzn;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getCourierOrders(page_id=1) {
                axios.get('/admin/courier/orders-waiting?status=4&has_courier=0&page='+page_id)
                    .then(data => {
                        this.data = data.data.data;
                        this.usdToAzn = data.data.usdToAzn;
                        // this.dataLength= this.data.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            getTotalPrice(order){
                let total_price = 0;
                if(!order.is_paid)
                    total_price += parseFloat((order.delivery_type == 1)? 3 : 5);

                //if(!order.invoice_paid)
                if(order.quantity>order.is_paids)
                    total_price += parseFloat((parseFloat(order.odenilmemis) * this.usdToAzn).toFixed(2));

                if(parseFloat(order.balance) < 0 )
                    total_price -= parseFloat(order.balance);

                return total_price.toFixed(2);
            },

            getCourierUsers(){
                axios.get('/admin/courier/courier-users')
                    .then(data => {
                        this.couriers = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getDeliveredOrders(page_id=1) {
                axios.get('/admin/courier/orders-waiting?status=7&has_courier=1&page='+page_id)
                    .then(data => {
                        this.delivered = data.data.data;

                        // this.dataLength= this.data.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            completeDeliveredOrder(id){
                axios.get('/admin/courier/orders-complete-delivered/' + id)
                    .then(data => {
                        this.refreshDatas();
                        // this.dataLength= this.data.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            // addCourier(courierId) {
            //     swal({
            //         title: 'Kuryer teyin edilsin?',
            //         text: "",
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#28a745',
            //         cancelButtonColor: '#3085d6',
            //         confirmButtonText: 'Bəli',
            //         cancelButtonText: 'Xeyr'
            //     }).then((result) => {
            //         if (result.value) {
            //             axios.post('/admin/courier/selected', {courierId: courierId})
            //                 .then(data => {
            //                     if (data.data.status === 200) {
            //                         this.getCourierOrders();
            //                         this.swalFunc(data.data.message);
            //                     } else if (data.data.status === 201) {
            //                         this.getCourierOrders();
            //                         this.swalFunc(data.data.message, 10000, 'warning');
            //                     }
            //                 })
            //                 .catch(err => {
            //                     console.log(err);
            //                 });
            //         }
            //         if(result.dismiss) {
            //             swal.closeModal();
            //         }
            //     });
            // },
            productsList(data) {
                this.show = true;
                this.modalData = data
            },

            closeModal() {
                this.show = false;
            },

            swalFunc(message, time = 1500, type = 'success') {
                swal({
                    type: type,
                    title: message,
                    showConfirmButton: true,
                    timer: time
                });
            },
            showInvoice(productData) {

                this.showShowInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));
                // console.log(data);
                /*data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];
*/
                this.showProductData = data;

            },
            refreshDatas(){
                this.getCourierOrders();
                this.getDeliveredOrders();
            },
            closeShowInvoiceModal () {
                this.showShowInvoiceModal = false;
                this.refreshDatas();
            },
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','dispatcher'))
            }
        },
        mixins: [auth]
    }
</script>


<style scoped>
    .blue{
        height: auto !important;
    }
    .red1{
        background: red !important;
    }
    #admin-index .table .btn-effect:after{
        content:'';
    }
    #admin-index .table .btn-effect{
        padding: 0 !important;

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
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>