<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }" v-if="productData">
        <div class="modal-dialog" style="width: 70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button  v-on:click="closeModal()" type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <div v-if="!productData" class="loading"></div>
                    <div v-if="productData">
                        <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    Mağaza
                                    <div style="font-weight: bold;">{{productData.shop_name}}</div>
                                </td>
                                <td >
                                    Məhsulun tipinin adı
                                    <div style="font-weight: bold; overflow-x: scroll">{{productData.product_name}}</div>
                                </td>
                                <td>
                                    Anbardakı Bağlama sayı
                                    <div style="font-weight: bold;" v-if="productData.count_depo!=null">{{productData.count_depo}}</div>
                                    <div v-else></div>
                                </td>
                                <td>
                                    Bağlama sayı
                                    <div style="font-weight: bold;">{{productData.quantity}}</div>
                                </td>
                                <td>
                                    Məhsulun qiyməti
                                    <div style="font-weight: bold;">{{productData.price}} TL</div>
                                </td>
                                <td>
                                    İzləmə nömrəsi
                                    <div style="font-weight: bold;">{{productData.order_tracking_number}}</div>
                                </td>
                                <td>
                                    Sifarişin tarixi
                                    <div style="font-weight: bold;">{{productData.created_at}}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div>
                        <h4 v-if="productData.count_depo!=null && productData.count_depo>productData.quantity" v-on:click="getUserInvoices(productData.u_id)">Kuryer seç</h4>
                       <p v-for="check_item of user_invoices">
                           <input type="checkbox"> {{check_item.id}}
                       </p>

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
        },
        data: function () {
            return {
                courier_user: "",
                courier_users: [],
                courier_usersLength: 0,
                user_invoices: [],
                }
            },
        // courier_user: '',
        name: "show-invoice-data-modal",
        props: ['productData', 'show'],
        methods: {
            rejectOrder(){
                axios.get(`/admin/courier/reject-order?id=${this.productData.i_id}`)
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
                    axios.post('/admin/courier/addCourier', {
                        courier_id: this.productData.c_id,
                        invoice_id: this.productData.i_id,
                        user_id: this.productData.u_id,
                        courier_user_id: this.courier_user.id,
                        phone: this.productData.phone,
                        email: this.productData.email
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