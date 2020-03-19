<template>
    <div v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Kuryer Panel</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li class="active">Kuryer Panel</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li><router-link to="/kuryer">Sifarişin qəbulu və təyini</router-link></li>
                    <li class="active" ><a>Kuryer Panel</a></li>
                    <li role="presentation" ><router-link to="/kuryer-completed">Təhvil verilmiş sifarişlər</router-link></li>
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
        <div class="tab-content courier">

            <!--<download-excel :fetch = "xlsGenerate()"
                            class = "btn"
                            :fields = "json_fields"
                            :before-generate = "startDownload"
                            :before-finish = "finishDownload">
                Download Data
            </download-excel>-->

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

                </div>
                <div class="col-md-2">
                    <label> </label>
                    <button class="btn-effect" @click="checkForm()">Axtar</button>
                </div>
                <div class="col-md-2 pull-right">
                    <label> </label>
                    <button class="btn-effect" @click="getPrint()">Excel-ə çıxart</button>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade in active" id="home">
                <div class="block table-block">
                    <div v-if="data == null" class="loading"></div>
                    <!--<div  class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>-->
                    <table  class="table table-striped">
                        <thead>
                        <tr v-bind="count_i=0">
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
                            <th></th>
                            <th>Tarix</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dataOrder in data">
                                <td>{{count_i = count_i+1}}</td>
                                <th>
                                    <input type="checkbox" class="checkOrders"  :value="dataOrder.id" :id="dataOrder.id"  v-model="checkedCategories" @click="check($event)">{{dataOrder.id}}</th>
                                <th>{{dataOrder.name}} {{dataOrder.surname}} {{dataOrder.uniqid}}</th>
                                <th>{{dataOrder.phone}} {{dataOrder.email}}</th>
                                <th>{{dataOrder.city}},{{dataOrder.district}},{{dataOrder.village}},{{dataOrder.street}},{{dataOrder.home}}</th>
                                <th>{{dataOrder.description}}</th>
                                <th>{{dataOrder.price}} AZN
                                    <a href="javascript:void(0)" v-if="dataOrder.is_paid == 1"  style="background:green" class="green btn-effect">Ödənilib</a>
                                    <a href="javascript:void(0)" v-if="dataOrder.is_paid == 0" style="width: 100px;background:red" class="btn-effect red1">Ödənilməyib</a>

                                </th>

                                <th>{{dataOrder.count}}</th>
                                <th>{{dataOrder.sum_price}}$ <br />{{convertCur(dataOrder.sum_price)}}AZN</th>
                                <th>
                                    <a href="javascript:void(0)" @click="showInvoice(dataOrder)" class="btn-effect">Bax</a>
                                </th>
                                <th>
                                    <a class="btn-effect" target="_blank" :href="'/cp/courier/act/'+dataOrder.id">AKT</a>
                                </th>
                                <th>
                                    <button @click="completeOrder(dataOrder)" type="button" class="btn-effect blue">Təhvil verildi</button>
                                </th>
                                <th>
                                    <button @click="anbaraVerildi(dataOrder)" type="button" class="btn-effect blue">Anbara verildi</button>
                                </th>
                                <th>{{ dataOrder.created_at | formatDate}}</th>

                            </tr>

                        </tbody>
                    </table>
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
                        <h4 v-if="selected_invoice[0]!=null">{{selected_invoice[0].i_id}} nömrəli bağlamanın
                            anbarda qoyulacaq yerini qeyd edin</h4>
                        <input class="form-control" type="text" v-model="depot">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-effect" style="width: 50px" @click="submitDepot()">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <show-invoice-data-modal  v-bind:typePage="typePage"  v-bind:productData="showProductData"  v-bind:courierData="showCourierData"  v-bind:usdToAzn="usdToAzn" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal" ></show-invoice-data-modal>
    </div>
</template>
<script>
    import ShowInvoiceDataModal from "./modals/showInvoiceDataModal";
    import auth from '../auth.js'
    import JsonExcel from 'vue-json-excel'

    import swal from 'sweetalert2';

    export default {
        components: {ShowInvoiceDataModal,downloadExcel:JsonExcel},
        name: "KuryerPanelComponent",
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
                typePage: 'panel',
                checkedCategories: [],
                arrayCouriers: {64: "Kənan İsmayılov",40: "Həşim Abaszadə"},
                filter:{
                    courier_user_id: 0
                },
                json_fields: {
                    'Complete name': 'name',
                    'City': 'city',
                    'Telephone': 'phone.mobile',
                    'Telephone 2' : {
                        field: 'phone.landline',
                        callback: (value) => {
                            return `Landline Phone - ${value}`;
                        }
                    },
                },
                json_data: [
                    {
                        'name': 'Tony Peña',
                        'city': 'New York',
                        'country': 'United States',
                        'birthdate': '1978-03-15',
                        'phone': {
                            'mobile': '1-541-754-3010',
                            'landline': '(541) 754-3010'
                        }
                    },
                    {
                        'name': 'Thessaloniki',
                        'city': 'Athens',
                        'country': 'Greece',
                        'birthdate': '1987-11-23',
                        'phone': {
                            'mobile': '+1 855 275 5071',
                            'landline': '(2741) 2621-244'
                        }
                    }
                ],
                json_meta: [
                    [
                        {
                            'key': 'charset',
                            'value': 'utf-8'
                        }
                    ]
                ],
            }
        },
        methods: {
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
            xlsGenerate()
            {
               let array = this.checkedCategories;
               let print = [];
                axios.get('/cp/courier/print-data?array='+array)
                    .then(data => {
                        console.log(data.data.data)
                        print=data.data.data;
                    })
                    .catch(err => {
                        console.log(err);

                    });


               return print;
            },
            startDownload(){
                alert('show loading');
            },
            finishDownload(){
                alert('hide loading');
            },
            check: function(e) {
                if (e.target.checked) {
                    console.log(e.target.value)
                }
            },
            convertCur(dollar){
                var azn = dollar*this.usdToAzn;
                return parseFloat(azn).toFixed(2);
            },

            getCourierOrders() {
                axios.get('/cp/courier/orders-waiting?has_courier=1&status=6',{params:this.filter})
                    .then(data => {
                        this.data = data.data.data;
                        this.usdToAzn = data.data.usdToAzn;
                        // this.dataLength= this.data.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            getPrint(){
                window.location.href = `/cp/courier/orders-print?has_courier=1&status=6&courier_user_id=${this.filter.courier_user_id}`;
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
                showCourierInvoices(id) {
                let allDataCourier = null;
                axios.get('/cp/courier/getCourierData/?courier_id='+id)
                    .then(data => {
                        allDataCourier = data.data.data;
                        this.selected_invoice = allDataCourier;
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
                this.refreshDatas();
            },
            //////////////////////////////////////////////////////////////////////////////////////////////////////////




            submitDepot(){
                console.log(this.selected_invoice[0].i_id);
                axios.get(`/cp/courier/check-depot?barcode=${this.depot}&invoice_id=${this.selected_invoice[0].i_id}`)
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
                axios.post('/cp/courier/complete-order', {courierId:order.id,email:order.email,phone:order.phone })
                    .then(data => {
                        this.refreshDatas();
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            anbaraVerildi(order){
                this.selected_invoice = order;
                this.showModal = true;
                this.showCourierInvoices(order.id);
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
                this.ifNoAccessRedirect(Array('super_admin','dispatcher'))
            }
        },
        mixins: [auth],
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