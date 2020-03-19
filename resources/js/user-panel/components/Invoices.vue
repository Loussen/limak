<template>
    <div class="beyenname">
        <div class=" right-side col-md-9 col-sm-12 col-xs-12">
            <div class="block tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a v-on:click="getAllInvoices()" href="#hamisi" aria-controls="hamisi" role="tab"
                                                data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.all'): ''}}<span class="badge">{{allInvoicesCount ? allInvoicesCount : 0}}</span></a>
                    </li>

                    <li role="presentation"><a v-on:click="getWaitingInvoices()" href="#gozleyir" aria-controls="gozleyir" role="tab"
                                               data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.waiting'): ''}}<span class="badge">{{allWaitingInvoicesCount ? allWaitingInvoicesCount : 0}}</span></a>
                    </li>

                    <li class="setWidth" role="presentation"><a v-on:click="getForeignInvoices()" href="#sifarish" aria-controls="sifarish" role="tab"
                                               data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.inForeign'): ''}}<span class="badge">{{allForeignInvoicesCount ? allForeignInvoicesCount : 0}}</span></a>
                    </li>

                    <li class="setWidthMin" role="presentation"><a v-on:click="getOnTheWayInvoices()" href="#productReject" aria-controls="imtina" role="tab"
                                                data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.onTheWay'): ''}}<span class="badge">{{allOnTheWayInvoicesCount ? allOnTheWayInvoicesCount : 0}}</span></a>
                    </li>

                    <li class="setWidth" role="presentation"><a v-on:click="getHomeInvoices()" href="#transactionReject" aria-controls="imtina" role="tab"
                                                data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.bakuOfficce'): ''}}<span class="badge">{{allHomeInvoicesCount ? allHomeInvoicesCount  : 0}}</span></a>
                    </li>
                    <li class="setWidth" role="presentation"><a v-on:click="getCompletedInvoices()" href="#completed" aria-controls="imtina" role="tab"
                                                                     data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.completedOrder'): ''}}<span class="badge">{{allCompletedInvoicesCount ? allCompletedInvoicesCount : 0}}</span></a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="hamisi">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <Spinner v-if="spinner" name="circle" color="#ef865f"/>
                        <div v-if="!spinner" class="table-block">
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.type'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.amount'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.priceOrder'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.priceShipment'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.weight'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                    <th style="text-align: center" >{{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}}</th>
<!--
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
-->
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="invoice in allInvoices" >
                                        <td>{{invoice.id}}</td>
                                        <td>{{invoice.product_type_name ? invoice.product_type_name : ''}}</td>
                                        <td>{{invoice.shop_name}}</td>
                                        <td>{{invoice.quantity}}</td>
                                        <td>{{invoice.price}} TL</td>
                                        <td v-if="invoice.shipping_price == null">0</td>
                                        <td v-else >${{parseFloat(invoice.shipping_price)}} <br><span>{{(parseFloat(invoice.shipping_price) * parseFloat(index.usd)).toFixed(2)}} AZN</span></td>
                                        <td v-if="invoice.weight == null">0</td>
                                        <td v-else>{{invoice.weight}} Kg</td>

                                        <td>{{invoice.created_at}}</td>
                                        <td v-if="!invoice.is_paid" >
                                            <router-link v-if="balance <  parseFloat(invoice.courierPrice) + parseFloat(invoice.delivery_price)" :to="{ name: 'Balance', query: { order: invoice.id } }">
                                                <a style="text-transform: uppercase;" href="#" class="btn btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}}</a>
                                            </router-link>
                                            <a v-if="balance >=  parseFloat(invoice.courierPrice) + parseFloat(invoice.delivery_price)" style="text-transform: uppercase;" v-on:click="openModal(invoice.id ,invoice.courierPrice, invoice.delivery_price)" href="#" class="btn btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}}</a>
                                        </td>
                                        <td style="text-align: center" v-if="invoice.is_paid"><span style="text-transform: uppercase;">{{ExWindow ? ExWindow.translator('panel-errors.paid'): ''}}</span></td>
<!--
                                        <td><a :href="storageUrl(invoice.file)" download><img src="/front/img/download.png" alt="download"></a></td>
-->
                                        <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="gozleyir">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.type'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.amount'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.priceOrder'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
<!--
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
-->
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="invoice in allWaitingInvoices" >
                                <td>{{invoice.id}}</td>
                                <td>{{invoice.product_type_name ? invoice.product_type_name : ''}}</td>
                                <td>{{invoice.shop_name}}</td>
                                <td>{{invoice.quantity}}</td>
                                <td>{{invoice.price}} TL</td>
                                <td>
                                    ${{parseFloat(invoice.shipping_price)}} <br><span>{{(parseFloat(invoice.shipping_price) * parseFloat(index.usd)).toFixed(2)}} AZN</span>
                                </td>
                                <td>{{invoice.created_at}}</td>
<!--
                                <td><a :href="storageUrl(invoice.file)" download><img src="/front/img/download.png" alt="download"></a></td>
-->
                                <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="sifarish">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.type'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.amount'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.priceOrder'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
<!--
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
-->
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="invoice in allForeignInvoices" >
                                <td>{{invoice.id}}</td>
                                <td>{{invoice.product_type_name ? invoice.product_type_name : ''}}</td>
                                <td>{{invoice.shop_name}}</td>
                                <td>{{invoice.quantity}}</td>
                                <td>{{invoice.price}} TL</td>
                                <td>
                                    ${{parseFloat(invoice.shipping_price)}} <br><span>{{(parseFloat(invoice.shipping_price) * parseFloat(index.usd)).toFixed(2)}} AZN</span>
                                </td>
                                <td>{{invoice.created_at}}</td>
<!--
                                <td><a :href="storageUrl(invoice.file)" download><img src="/front/img/download.png" alt="download"></a></td>
-->
                                <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="productReject">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.type'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.amount'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.priceOrder'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
<!--
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
-->
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="invoice in allOnTheWayInvoices" >
                                <td>{{invoice.id}}</td>
                                <td>{{invoice.product_type_name ? invoice.product_type_name : ''}}</td>
                                <td>{{invoice.shop_name}}</td>
                                <td>{{invoice.quantity}}</td>
                                <td>{{invoice.price}} TL</td>
                                <td>
                                    ${{parseFloat(invoice.shipping_price)}} <br><span>{{(parseFloat(invoice.shipping_price) * parseFloat(index.usd)).toFixed(2)}} AZN</span>
                                </td>
                                <td>{{invoice.created_at}}</td>
<!--
                                <td><a :href="storageUrl(invoice.file)" download><img src="/front/img/download.png" alt="download"></a></td>
-->
                                <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="transactionReject">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.type'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.amount'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.priceOrder'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                <th style="text-align: center" >{{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}}</th>
<!--
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
-->
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="invoice in allHomeInvoices" >
                                <td>{{invoice.id}}</td>
                                <td>{{invoice.product_type_name ? invoice.product_type_name : ''}}</td>
                                <td>{{invoice.shop_name}}</td>
                                <td>{{invoice.quantity}}</td>
                                <td>{{invoice.price}} TL</td>
                                <td>
                                    ${{parseFloat(invoice.shipping_price)}} <br><span>{{(parseFloat(invoice.shipping_price) * parseFloat(index.usd)).toFixed(2)}} AZN</span>
                                </td>
                                <td>{{invoice.created_at}}</td>
                                <td v-if="!invoice.is_paid" ><a style="text-transform: uppercase;" href="#" class="btn btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}}</a></td>
                                <td style="text-align: center" v-if="invoice.is_paid"><span style="text-transform: uppercase;">{{ExWindow ? ExWindow.translator('panel-errors.paid'): ''}}</span></td>
<!--
                                <td><a :href="storageUrl(invoice.file)" download><img src="/front/img/download.png" alt="download"></a></td>
-->
                                <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="completed">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.type'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.amount'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.priceOrder'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
<!--
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
-->
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="invoice in allCompletedInvoices" >
                                <td>{{invoice.id}}</td>
                                <td>{{invoice.product_type_name ? invoice.product_type_name : ''}}</td>
                                <td>{{invoice.shop_name}}</td>
                                <td>{{invoice.quantity}}</td>
                                <td>{{invoice.price}} TL</td>
                                <td>
                                    ${{parseFloat(invoice.shipping_price)}} <br><span>{{(parseFloat(invoice.shipping_price) * parseFloat(index.usd)).toFixed(2)}} AZN</span>
                                </td>
                                <td>{{invoice.created_at}}</td>
<!--
                                <td><a :href="storageUrl(invoice.file)" download><img src="/front/img/download.png" alt="download"></a></td>
-->
                                <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <modal :size="'modal-md'" :toggle="modalToggle" :modal="'Hello'" @modal="modalToggle = $event">
                <template slot="header">
                    <h3 style="margin: 0; display: inline-block;">{{ExWindow ? ExWindow.translator('panel-errors.payment_panel'): ''}}</h3>
                </template>
                <template slot="body">
                    <div class="row">
                        <div v-if="courierCash > 0" class="col-md-12">
                            <h3>{{ExWindow ? ExWindow.translator('panel-errors.really_pay'): ''}}</h3>
                            <h6 style="color: red">{{ExWindow ? ExWindow.translator('panel-errors.payment_notice'): ''}}</h6>
                            <div class="form-check">
                                <input @change="check($event)" v-model="withCourier" class="form-check-input" type="checkbox" id="defaultCheck1">
                                <label style="font-size: 18px;margin-left: 5px;color: #36ad3a;" class="form-check-label" for="defaultCheck1">
                                    Bəli + <span style="color:rgb(54, 173, 102)" >{{courierCash}}</span> AZN
                                </label>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <h3 style="margin-top: 0px;">{{ExWindow ? ExWindow.translator('panel-errors.delivery_payment'): ''}} - <span style="color: #f95732;" >{{deliveryPrice}}</span> AZN</h3>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <h3 style="margin-top: 0px;" v-if="withCourier" >{{ExWindow ? ExWindow.translator('panel-errors.total'): ''}} - <span style="color: #f95732;font-weight: 700;">{{ parseFloat(courierCash) + parseFloat(deliveryPrice)}}</span> AZN</h3>
                            <h3 style="margin-top: 0px;" v-if="!withCourier" >{{ExWindow ? ExWindow.translator('panel-errors.total'): ''}} - <span style="color: #f95732;font-weight: 700;" >{{parseFloat(deliveryPrice)}}</span> AZN</h3>
                        </div>
                    </div>
                </template>
                <template style="background:#f2f6ff" slot="footer">
                    <button class="btn btn-effect" @click="deni()">{{ExWindow ? ExWindow.translator('panel-errors.reject'): ''}}</button>
                    <button class="btn btn-effect" @click="accept()">{{ExWindow ? ExWindow.translator('panel-errors.accept'): ''}}</button>
                </template>
            </modal>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import Spinner from 'vue-spinkit'
    import swal from 'sweetalert2';
    export default {
        mounted() {
            this.getAllInvoices();
            this.getWaitingInvoices();
            this.getForeignInvoices();
            this.getOnTheWayInvoices();
            this.getHomeInvoices();
            this.getCompletedInvoices();
            this.ExWindow = window;
        },
        props: {

        },
        data: function (){
            return {
                allInvoices: [],
                allWaitingInvoices: [],
                allForeignInvoices: [],
                allOnTheWayInvoices: [],
                allHomeInvoices: [],
                allCompletedInvoices: [],
                allInvoicesCount : 0,
                allWaitingInvoicesCount: 0,
                allForeignInvoicesCount: 0,
                allOnTheWayInvoicesCount: 0,
                allHomeInvoicesCount: 0,
                allCompletedInvoicesCount: 0,
                modalToggle: false,
                withCourier: false,
                invoiceId: null,
                courierCash: 0,
                deliveryPrice: 0,
                balance: 0,
                spinner: false,
                ExWindow: null,
                index: 0

            }
        },
        methods: {

            accept() {
                var path = '/user-panel/pay';
                axios.post(path, {withCourier: this.withCourier, invoiceId: this.invoiceId}).then((response) => {
                    if(response.data.code === 1602) {
                        swal('Artıq ödənilib!')
                    }
                    this.closeModal = false;
                    this.$emit('changeBalance', {});
                    this.getAllInvoices();
                    this.getHomeInvoices();
                    this.modalToggle = false;
                }).catch((e) => {
                    console.log(e);
                });
                // this.modalToggle = false;
            },
            deni() {
                this.modalToggle = false;
            },
            openModal(invoiceId, courierCash, deliveryPrice) {
                this.withCourier = false;
                this.invoiceId = invoiceId;
                this.courierCash = courierCash;
                this.deliveryPrice = deliveryPrice;
                this.modalToggle = true;
                console.log(this.courierCash, this.deliveryPrice);
            },
            async getAllInvoices() {
                this.spinner = true;
               const test  = await this.getInvoices();
                this.allInvoices = test.data.data;
                this.allInvoices.map((loop) => {
                    if(loop.courier) {
                        return loop.courierPrice = loop.courier.delivery_type == 1? 4: 10;
                    } else {
                        return loop.courierPrice = 0;
                    }
                });
                this.allInvoicesCount = 0;
                this.allInvoicesCount = this.allInvoices.length;
                this.balance = test.data.balance;
                this.index = test.data.index;
                this.spinner = false;
            },
            async getWaitingInvoices() {
                const test  = await this.getInvoices('waiting');
                this.allWaitingInvoices = test.data.data;
                this.allWaitingInvoicesCount = 0;
                this.allWaitingInvoicesCount = this.allWaitingInvoices.length;

            },
            async getForeignInvoices() {
                const test  = await this.getInvoices('foreign');
                this.allForeignInvoices = test.data.data;
                this.allForeignInvoicesCount = 0;
                this.allForeignInvoicesCount = this.allForeignInvoices.length;
            },
            async getOnTheWayInvoices() {
                const test  = await this.getInvoices('on_the_way');
                this.allOnTheWayInvoices = test.data.data;
                this.allOnTheWayInvoicesCount = 0;
                this.allOnTheWayInvoicesCount = this.allOnTheWayInvoices.length;
            },
            async getHomeInvoices() {
                const test  = await this.getInvoices('home');
                this.allHomeInvoices = test.data.data;
                this.allHomeInvoices.map((loop) => {
                    if(loop.courier) {
                        return loop.courierPrice = loop.courier.delivery_type == 1? 4: 10;
                    } else {
                        return loop.courierPrice = 0;
                    }
                });
                this.allHomeInvoicesCount = 0;
                this.allHomeInvoicesCount += this.allHomeInvoices.length;
                this.balance = test.data.balance;
            },
            async getCompletedInvoices() {
                const test  = await this.getInvoices('completed');
                this.allCompletedInvoices = test.data.data;
                this.allCompletedInvoicesCount = 0;
                this.allCompletedInvoicesCount += this.allCompletedInvoices.length;
            },
            getInvoices(type) {
                var path = '/user-panel/get-invoices/' +type;
                return  axios.get(path);

            },
            storageUrl(url) {
                if(url) {
                    return url;
                }
                return '';
            }
        },
        components: {
            modal: Modal,
            Spinner: Spinner
        }
    }

</script>
<style scoped>
    @media only screen and (min-width: 680px) {
        .kabinet .nav-tabs li {
            width: 83px;
        }

        .setWidth {
            width: 154px !important;
        }
        .setWidthMin {
            width: 90px !important;
        }
    }

    @media only screen and (max-width: 680px) {
         .block .nav > li > a {
            position: initial;
            display: block;
            padding: 7px 15px;
            font-size: 14px;
        }
    }
    .sk-fade-in.sk-spinner.sk-circle {
        display: inline-block !important;
        left: 50%;
        margin-left: -50px;
    }
</style>
