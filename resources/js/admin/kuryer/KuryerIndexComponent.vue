<template>
    <div v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Kuryer Sifarişlərinin qəbulu və təyini</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li class="active">Kuryer</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active" ><a>Sifarişin qəbulu və təyini</a></li>
                    <li><router-link to="/kuryer-panel">Kuryer Panel</router-link></li>
                    <li><router-link to="/kuryer-completed">Təhvil verilmiş sifarişlər</router-link></li>
                    <li><router-link>Kuryerlər</router-link></li>
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
        <div class="tab-content courier">
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
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dataOrder in data">
                                <th>{{count_i = count_i+1}}</th>
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
                                <th>{{dataOrder.sum_price}}$ <br />{{convertCur(dataOrder.sum_price)}}AZN</th>
                                <th>
                                    <a href="javascript:void(0)" @click="showInvoice(dataOrder)" class="btn-effect">Bax</a>
                                </th>
                                <th>
                                    <a class="btn-effect" target="_blank" :href="'/cp/courier/act/'+dataOrder.id">AKT</a>
                                </th>
                                <th>{{ dataOrder.created_at | formatDate}}</th>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <show-invoice-data-modal v-bind:typePage="typePage" v-bind:productData="showProductData"  v-bind:courierData="showCourierData"  v-bind:usdToAzn="usdToAzn" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal" ></show-invoice-data-modal>
    </div>
</template>
<script>
    import ShowInvoiceDataModal from "./modals/showInvoiceDataModal";
    import auth from '../auth.js'

    import swal from 'sweetalert2';

    export default {
        components: {ShowInvoiceDataModal},
        name: "KuryerIndexComponent",
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
                typePage: 'index'

            }
        },
        methods: {
            convertCur(dollar){
                var azn = dollar*this.usdToAzn;
                return parseFloat(azn).toFixed(2);
            },

            getCourierOrders() {
                axios.get('/cp/courier/orders-waiting?status=4&has_courier=0')
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
                this.refreshDatas();
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