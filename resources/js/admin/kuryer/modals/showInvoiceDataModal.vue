<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }" v-if="productData">
        <div class="modal-dialog" style="width: 70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button  v-on:click="closeModal()" type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Kuryer Sifarişi </h4>
                </div>
                <div class="modal-body">
                    <div v-if="!productData" class="loading"></div>
                    <div v-if="productData">
                        <div class="row">
                            <div class="col-md-6">
                                <p>
                                    <b>Müştəri:</b> {{courierData.uniqid}} {{courierData.name}} {{courierData.surname}}
                                </p>
                                <p>
                                    <b>Email, Nömrə</b> {{courierData.email}} {{courierData.phone}}
                                </p>
                                <p>
                                    <b>Ünvan</b> {{courierData.city}},{{courierData.district}},{{courierData.village}},{{courierData.street}},{{courierData.home}}
                                </p>
                                <p>
                                    <b>Qeyd</b> {{courierData.description}}
                                </p>
                                <p>
                                    <b>Sifariş tarixi</b> {{courierData.created_at}}
                                </p>
                                <p>
                                    <b>Bağlama sayı</b> {{courierData.count}}
                                </p>
                            </div>
                            <div class="col-md-6">

                                <p>
                                    <b>Çatdırılma qiyməti</b> {{sumPrice()}} AZN
                                </p>
                                <p>
                                    <b>Müştəri balansı: </b> {{courierData.balance}} AZN
                                </p>
                                <p>
                                    <b>Kuryer qiyməti</b> {{courierData.price}} AZN
                                    <a href="javascript:void(0)" v-if="courierData.is_paid == 1" class="btn btn-sm btn-success">Ödənilib</a>
                                    <a href="javascript:void(0)" v-if="courierData.is_paid == 0" class="btn btn-sm btn-danger">Ödənilməyib</a>
                                </p>

                                <hr />

                                    <h4>Toplam ödəniş {{sumPaymentPrice(courierData)}} AZN</h4>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Bağlama nömrəsi</th>
                                    <th>İzləmə nömrəsi</th>
                                    <th>Sifariş tarixi</th>
                                    <th>Mağaza</th>
                                    <th>Çatdırılma qiyməti</th>
                                    <th>Məhsulun tipinin adı</th>
                                    <th>Anbarda yeri</th>
                                </tr>

                                <tr v-for="invoice in productData">
                                    <td>
                                        <div style="font-weight: bold;">{{invoice.i_id}}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: bold;">{{invoice.order_tracking_number}}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: bold;">{{invoice.order_date}}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: bold;">{{invoice.shop_name}}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: bold;">
                                            {{invoice.shipping_price}}
                                            <button v-if="invoice.invoice_paid==1" class="btn btn-sm btn-success">Ödənib</button>
                                            <button v-else class="btn btn-danger btn-sm">Ödənməyib</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: bold; overflow-x: scroll">{{invoice.product_type_name}}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: bold;">{{invoice.depo}}</div>
                                    </td>
                                </tr>
                            </tbody>


                    </table>
                    </div>
                    <div v-if="typePage=='index'">
                        <h4>Kuryer seç</h4>

                       <select class="form-control" v-model="courier_user" style="display: inline;width: 120px;">
                           <option  v-for="data of courier_users" v-bind:value="{ id: data.id}">{{data.name}} {{data.surname}}</option>
                       </select>

                        <button v-on:click="addCourierInvoice()"  class="btn-effect" style="width: 120px;margin-left:2%" >Təsdiqlə</button>
                        <button v-on:click="rejectOrder()"  class="btn-effect" style="width: 150px;float: right;background: red" >Sifarişi imtina et</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>
    import swal from 'sweetalert2';

    export default {
        mounted() {
            this.getCourierUsers();
            //this.getCourierData(this.productData.id);
        },
        data: function () {
            return {
                courier_user: "",
                courier_users: [],
                courier_usersLength: 0,
                user_invoices: [],
                sum_payment: 0,
                all_payment: 0
                }
            },
        // courier_user: '',
        name: "show-invoice-data-modal",
        props: ['productData','courierData','usdToAzn' ,'show', 'typePage'],

        methods: {

            sumPrice(){
                let sum = 0;
                this.productData.forEach(function(item) {
                    if(item.invoice_paid==0){
                        sum += parseFloat(item.shipping_price)
                    }
                });
                sum = parseFloat(sum*this.usdToAzn).toFixed(2);
                this.sum_payment = sum;
                return sum;
            },

            sumPaymentPrice(courierData)
            {
                let sum = 0;
                if(courierData.balance<=0){
                    sum -= parseFloat(this.courierData.balance);
                }

                sum += parseFloat(this.sum_payment);

                if(courierData.is_paid==0){
                    sum += parseFloat(courierData.price);
                }

                this.all_payment = sum;
                return this.all_payment;

            },

            convertCur(dollar){


                var azn = dollar*this.usdToAzn;
                return parseFloat(azn).toFixed(2);
            },
            rejectOrder(){
                axios.get(`/cp/courier/reject-order?id=${this.productData[0].i_id}`)
                    .then(data => {
                        this.$emit('close-modal');
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            closeModal(){
                this.$emit('close-modal');
            },
            getUserInvoices(id)
            {
                axios.get('/cp/invoicesByUserWithId/'+id)
                    .then(data => {
                        this.user_invoices = data.data.data.stock;
                        console.log(this.user_invoices);
                        // this.courier_usersLength= this.courier_users.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });

            },
            getCourierUsers(){
                axios.get('/admin/courier/courier-users')
                    .then(data => {
                        this.courier_users = data.data.data;
                        // this.courier_usersLength= this.courier_users.length;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            addCourierInvoice() {
                if(this.courier_user.id) {
                    axios.post('/cp/courier/addCourier', {
                        courier_id: this.courierData.id,
                        invoice_id: this.productData[0].i_id,
                        user_id: this.courierData.user_id,
                        courier_user_id: this.courier_user.id,
                        phone: this.courierData.phone,
                        email: this.courierData.email
                    }).then(data => {
                        this.$emit('close-modal');

                    }).catch(err => {
                            console.log(err);
                        });
                }else{
                    swal({
                        title: 'Kuryer secin',
                    })
                }
            }
        }
    }
</script>

<style scoped>

    .limac-modal {
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.4);
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0;
        -webkit-transition: 400ms;
        -moz-transition: 400ms;
        -ms-transition: 400ms;
        -o-transition: 400ms;
        transition: 400ms;
    }

    .limac-modal.show-modal {
        z-index: 100;
        opacity: 1;
    }

    .limac-modal-dialog {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        width: 1200px;
        max-height: 90%;
        overflow-y: auto;
        border-radius: 3px;
        padding: 15px;
        -webkit-transition: 200ms;
        -moz-transition: 200ms;
        -ms-transition: 200ms;
        -o-transition: 200ms;
        transition: 200ms;
    }

    .limac-modal.show-modal .limac-modal-dialog {
        transform: translate(-50%, 0);
    }

    .limac-modal-close {
        position: absolute;
        top: 13px;
        right: 13px;
        cursor: pointer;
    }

    .limac-modal-close span {
        font-size: 17px;
    }

    .limac-model-header h3 {
        padding-bottom: 12px;
        font-weight: bolder;
        text-align: center;
    }

    .table td {
        padding: 0;
        color: #000;
        font-weight: 400;
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
        top: 45px;
        left: 49%;
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