<template>
    <div v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Kuryer - Təhvil verilmiş sifarişlər</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li class="active">Kuryer Panel</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li><router-link to="kuryer">Sifarişin qəbulu və təyini</router-link></li>
                    <li><router-link to="kuryer-panel">Kuryer Panel</router-link></li>
                    <li class="active" ><a>Təhvil verilmiş sifarişlər</a></li>
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
        <div class="tab-content courier">
            <div class="row">
                <div class="com-md-10">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Kuryer </label>
                            <select v-on:change="checkForm" v-model="filter.courier_user_id" class="form-control">
                                <option value="0">Bütün kuryerler</option>
                                <option v-for="(courier,index) in arrayCouriers" v-bind:value="index" >{{courier}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Status </label>
                            <select v-on:change="checkForm" v-model="filter.status_id" class="form-control">
                                <option value="0">Hamısı</option>
                                <option v-for="(status,index) in arrayStatuses" v-bind:value="index" >{{status}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label> </label>
                    <button class="btn-effect" @click="checkForm()">Axtar</button>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade in active" id="home">
                <div class="block table-block">
                    <div v-if="data == null" class="loading"></div>
                    <!--<div  class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>-->
                    <table  class="table table-striped">
                        <thead>
                        <tr v-bind="count_i=(data.current_page-1)*20">
                            <th></th>
                            <th></th>
                            <th>Müştəri Adı, Soyadı, Kodu</th>
                            <th>Telefon, Email</th>
                            <th>Çatdırmaq istədiyi ünvan</th>
                            <th>Qeyd</th>
                            <th>Kuryer Qiyməti</th>
                            <th>Bağlama sayı</th>
                            <th>Daşınma qiyməti</th>
                            <th></th>
                            <th>Tarix</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dataOrder in data.data" v-if="filter.status_id==0 || (filter.status_id==1 && dataOrder.is_paid==1 &&  dataOrder.paid_count == dataOrder.count) || (filter.status_id==2 && (dataOrder.paid_count != dataOrder.count || dataOrder.is_paid==0) )">
                                <td>{{count_i = count_i+1}})</td>
                                <th>{{dataOrder.id}}</th>
                                <th>{{dataOrder.name}} {{dataOrder.surname}} {{dataOrder.uniqid}}</th>
                                <th>{{dataOrder.phone}} {{dataOrder.email}}</th>
                                <th>{{dataOrder.city}},{{dataOrder.district}},{{dataOrder.village}},{{dataOrder.street}},{{dataOrder.home}}</th>
                                <th>{{dataOrder.description}}</th>
                                <th>{{dataOrder.price}} AZN
                                    <a href="javascript:void(0)" v-if="dataOrder.is_paid == 1"  style="background:green" class="green btn-effect">Ödənilib</a>
                                    <a href="javascript:void(0)" v-if="dataOrder.is_paid == 0" style="width: 100px;background:red" class="btn-effect red1">Ödənilməyib</a>

                                </th>

                                <th>{{dataOrder.count}}</th>
                                <th>{{dataOrder.sum_price}}$ <br />{{convertCur(dataOrder.sum_price)}}AZN
                                    <a href="javascript:void(0)" v-if="dataOrder.paid_count == dataOrder.count"  style="background:green" class="green btn-effect">Ödənilib</a>
                                    <a href="javascript:void(0)" v-if="dataOrder.paid_count != dataOrder.count" style="width: 100px;background:red" class="btn-effect red1">Ödənilməyib</a>
                                </th>
                                <th>
                                    <a href="javascript:void(0)" @click="showInvoice(dataOrder)" class="btn-effect">Bax</a>
                                </th>
                                <th>{{ dataOrder.created_at | formatDate}}</th>
                            </tr>

                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li ><a @click.prevent="getCourierOrders(data.current_page - 1)" href="#" aria-label="Previous"><span
                                    aria-hidden="true">ƏVVƏL</span></a></li>
                            <template  v-for="page in data.last_page">
                                <li v-if="page ==1 && data.current_page==1" class="active">
                                    <a @click.prevent="getAllInvoices( page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="page ==1">
                                    <a @click.prevent="getCourierOrders( page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="Math.abs(data.current_page - page)> 3 && Math.abs(data.current_page - page)<= 6 ">
                                    <a href="#">.</a>
                                </li>
                                <li v-else-if="Math.abs(data.current_page - page)<= 3 && data.current_page == page" class="active">
                                    <a @click.prevent="getCourierOrders( page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="Math.abs(data.current_page - page)<= 3 ">
                                    <a @click.prevent="getCourierOrders(  page)" href="#">{{ page }}</a>
                                </li>
                                <li v-else-if="page == data.last_page">
                                    <a @click.prevent="getCourierOrders( page)" href="#">{{ page }}</a>
                                </li>
                            </template>
                            <li>
                                <a v-show="data.current_page < data.last_page" @click.prevent="getCourierOrders( data.current_page + 1)" href="#" aria-label="Next">
                                    <span aria-hidden="true">NÖVBƏTİ</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
        <!--<div :class="showModal === true ? 'show in' : ''" class="modal fade" id="myModal" role="dialog">
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
        </div>-->
        <show-invoice-data-modal  v-bind:typePage="completed"  v-bind:productData="showProductData"  v-bind:courierData="showCourierData"  v-bind:usdToAzn="usdToAzn" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal" ></show-invoice-data-modal>
    </div>
</template>
<script>
    import ShowInvoiceDataModal from "./modals/showInvoiceDataModal";
    import auth from '../auth.js'

    import swal from 'sweetalert2';

    export default {
        components: {ShowInvoiceDataModal},
        name: "KuryerCompletedComponent",
        mounted() {
            this.getCourierOrders();
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
                showCourierData: null,
                sumPrice:0,
                courier_users:[],
                usdToAzn:'',
                couriers: [],
                acceptedOrders: null,
                arrayCouriers: {64: "Kənan İsmayılov",40: "Həşim Abaszadə"},
                arrayStatuses: {1: "Ödənilmiş",2: "Ödənilməmiş"},
                filter:{
                    courier_user_id: 0,
                    status_id: 0
                },

            }
        },
        methods: {
            convertCur(dollar){
                var azn = dollar*this.usdToAzn;
                return parseFloat(azn).toFixed(2);
            },
            checkForm () {

                this.errors = {};
                if (this.isEmpty(this.errors)) {

                    this.getCourierOrders();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },

            getCourierOrders(page_id=1) {
                axios.get('/cp/courier/orders-waiting?has_courier=1&status=7&page='+page_id,{params:this.filter})
                    .then(data => {
                        this.data = data.data.data;
                        this.usdToAzn = data.data.usdToAzn;
                        // this.dataLength= this.data.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
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
                let allDataCourier = null;
                axios.get('/cp/courier/getCourierData/?courier_id='+productData.id)
                    .then(data => {
                        allDataCourier = data.data.data;
                        this.showShowInvoiceModal = true;
                        var dataCourier = JSON.parse(JSON.stringify(allDataCourier));
                        this.showProductData = dataCourier;
                        this.showCourierData = productData;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            refreshDatas(){
                this.getCourierOrders();
                //this.getDeliveredOrders();
            },
            closeShowInvoiceModal () {
                this.showShowInvoiceModal = false;
                //this.refreshDatas();
            },
            //////////////////////////////////////////////////////////////////////////////////////////////////////////




            /*submitDepot(){
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
            },*/
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
            /*getCourierAccepted() {
                axios.get('/admin/courier/orders-waiting?has_courier=1&status=6')
                    .then(data => {
                        this.acceptedOrders = data.data.data;
                        this.usdToAzn = data.data.usdToAzn;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },*/


            /*getTotalPrice(order){
                let total_price = 0;
                if(!order.is_paid)
                    total_price += parseFloat(order.c_price);//parseFloat((order.delivery_type == 1)? 3 : 5);

                //if(!order.invoice_paid)
                if(order.quantity>order.is_paids)
                    total_price += parseFloat((parseFloat(order.odenilmemis) * this.usdToAzn).toFixed(2));

                if(parseFloat(order.balance) < 0 )
                    total_price -= parseFloat(order.balance);

                return total_price.toFixed(2);
            },*/

            getCourierUsers(){
                axios.get('/admin/courier/courier-users')
                    .then(data => {
                        this.couriers = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            /*getDeliveredOrders() {
                axios.get('/admin/courier/orders-waiting?status=7&has_courier=1')
                    .then(data => {
                        this.delivered = data.data.data;

                        // this.dataLength= this.data.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },*/

            /*completeDeliveredOrder(id){
                axios.get('/admin/courier/orders-complete-delivered/' + id)
                    .then(data => {
                        this.refreshDatas();
                        // this.dataLength= this.data.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },*/
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


        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','dispatcher','casher'))
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