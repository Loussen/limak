<template>
    <div>
        <div class="sifarishlerim right-side col-md-9 col-sm-12 col-xs-12">
            <div class="block tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a v-on:click="getAllOrders()" href="#hamisi" aria-controls="hamisi"
                                                              role="tab" data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.all'): ''}}<span
                        class="badge">{{allOrdersCount}}</span></a></li>
                    <li role="presentation"><a v-on:click="getWaitingOrders()" href="#gozleyir" aria-controls="gozleyir" role="tab"
                                               data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.waiting'): ''}}<span class="badge">{{allWaitingOrdersCount}}</span></a></li>
                    <!--<li role="presentation"><a v-on:click="getOrderedOrders()" href="#orderedOrder" aria-controls="gozleyir" role="tab"-->
                                               <!--data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.orderedOrders'): ''}}<span class="badge">{{allOrderedOrdersCount}}</span></a></li>-->
                    <li class="setWidth" role="presentation"><a v-on:click="getOrderedOrders()" href="#orderedOrder" aria-controls="orderedOrder" role="tab"
                                               data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.orderedOrders'): ''}}<span class="badge">{{allOrderedOrdersCount}}</span></a>
                    </li>
                    <!--<li class="setWidth" role="presentation"><a v-on:click="getProductRejectOrders()" href="#productReject" aria-controls="imtina" role="tab"
                                                                     data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.deficiency'): ''}}<span class="badge">{{allProductRejectOrdersCount}}</span></a>
                    </li>-->
                    <li class="setWidth" role="presentation"><a v-on:click="getTransactionRejectOrders()" href="#transactionReject" aria-controls="imtina" role="tab"
                                                                     data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.rejected'): ''}}<span class="badge">{{allTransactionRejectOrdersCount}}</span></a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="hamisi">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <Spinner v-if="spinner" name="circle" color="#ef865f"/>
                        <div v-if="!spinner"  class="table-block">

                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.note'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.status'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                <template v-for="product in allOrders" >
                                    <tr>
                                        <td v-if="product.extras!=null"><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                        <td v-else>Yoxdur</td>
                                        <td>{{product.description}}</td>
                                        <td>{{product.created_at}}</td>
                                        <td v-if="product.statuses!=null">{{product.statuses.name}}</td>
                                        <td>{{product.price}} TL</td>
                                        <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <!--<div class="pay">-->
                            <!--<a href="#" class="btn-effect">Toplam ödə</a>-->
                            <!--<span>Ödəniş olunmayan linklər 30 dəqiqə sonra silinir.</span>-->
                        <!--</div>-->
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="gozleyir">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.note'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="product in allWaitingOrders" >
                                <tr>
                                    <td v-if="product.extras!=null"><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                    <td v-else>Yoxdur</td>
                                    <td>{{product.description}}</td>
                                    <td>{{product.created_at}}</td>
                                    <td>{{product.price}} TL</td>
                                    <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="orderedOrder">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.note'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.edit'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="product in allOrderedOrders" >
                                <tr>
                                    <td v-if="product.extras!=null"><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                    <td v-else>Yoxdur</td>
                                    <td>{{product.description}}</td>
                                    <td>{{product.created_at}}</td>
                                    <td>{{product.price}} TL</td>
                                    <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--<div role="tabpanel" class="tab-pane fade" id="sifarish">-->
                    <!--<div class="block col-md-12 col-sm-12 col-xs-12">-->
                        <!--<table class="table table-striped table-responsive">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</th>-->
                                <!--<th>{{ExWindow ? ExWindow.translator('panel-errors.note'): ''}}</th>-->
                                <!--<th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>-->
                                <!--<th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>-->
                                <!--<th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<template v-for="commonData in allCompletedOrders" >-->
                                <!--<tr v-for="product in commonData.products" >-->
                                    <!--<td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>-->
                                    <!--<td>{{product.description}}</td>-->
                                    <!--<td>{{product.created_at}}</td>-->
                                    <!--<td>{{product.price}} TL</td>-->
                                    <!--<td><a href="#"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> </a></td>-->
                                <!--</tr>-->
                            <!--</template>-->
                            <!--</tbody>-->
                        <!--</table>-->
                    <!--</div>-->
                <!--</div>-->
                <div role="tabpanel" class="tab-pane fade" id="productReject">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.note'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="product in allProductRejectOrders" >
                                <tr>
                                    <td v-if="product.extras!=null"><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                    <td v-else>Yoxdur</td>
                                    <td>{{product.description}}</td>
                                    <td>{{product.created_at}}</td>
                                    <td>{{product.price}} TL</td>
                                    <td><a href="#"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> </a></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="transactionReject">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.note'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.invoice'): ''}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="product in allTransactionRejectOrders" >
                                <tr>
                                    <td v-if="product.extras!=null"><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                    <td v-else>Yoxdur</td>
                                    <td>{{product.description}}</td>
                                    <td>{{product.created_at}}</td>
                                    <td>{{product.price}} TL</td>
                                    <td><a href="#"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> </a></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import swal from 'sweetalert2';
    import Spinner from 'vue-spinkit'
    export default {
        mounted() {
            this.getAllOrders();
            this.getWaitingOrders();
            // this.getCompletedOrders();
            this.getProductRejectOrders();
            this.getTransactionRejectOrders();
            this.getOrderedOrders();
            this.ExWindow = window;
        },
        props: {

        },
        data: function (){
            return {
                allOrders: [],
                allWaitingOrders: [],
                allOrderedOrders: [],
                allCompletedOrders: [],
                allProductRejectOrders: [],
                allTransactionRejectOrders: [],
                allOrdersCount : 0,
                allWaitingOrdersCount: 0,
                allOrderedOrdersCount: 0,
                allCompletedOrdersCount: 0,
                allProductRejectOrdersCount: 0,
                allTransactionRejectOrdersCount: 0,
                ExWindow: null,
                spinner: false
            }
        },
        methods: {
            async getAllOrders() {

                this.spinner = true;
                const test  = await this.getOrders();
                this.allOrders = test.data;
                this.allOrdersCount = 0;
                this.allOrdersCount = this.allOrders.length
                this.spinner = false
            },
            async getWaitingOrders() {
                const test  = await this.getOrders('waiting');
                this.allWaitingOrders = test.data;
                this.allWaitingOrdersCount = 0;
                this.allWaitingOrdersCount = this.allWaitingOrders.length;

            },
            async getOrderedOrders() {
                const test  = await this.getOrders('ordered');
                this.allOrderedOrders = test.data;
                this.allOrderedOrdersCount = 0;
                this.allOrderedOrdersCount = this.allOrderedOrders.length;

            },
            // async getCompletedOrders() {
            //     const test  = await this.getOrders('waiting');
            //     this.allCompletedOrders = test.data;
            //     this.allCompletedOrdersCount = 0;
            //     this.allCompletedOrders.forEach((dat) => {
            //         this.allCompletedOrdersCount += dat.products.length;
            //     });
            // },
            async getProductRejectOrders() {
                const test  = await this.getOrders('rejection');
                this.allProductRejectOrders = test.data;
                this.allProductRejectOrdersCount = 0;
                this.allProductRejectOrdersCount = this.allProductRejectOrders.length;
            },
            async getTransactionRejectOrders() {
                const test  = await this.getOrders('transaction');
                this.allTransactionRejectOrders = test.data;
                this.allTransactionRejectOrdersCount = 0;
                this.allTransactionRejectOrdersCount = this.allTransactionRejectOrders.length;

            },
            getOrders(type) {
                var path = '/user-panel/get-orders/' +type;
                return  axios.get(path);

            }
        },
        components: {
            Spinner: Spinner
        }
    }

</script>
<style scoped>
    @media (max-width: 680px) {
        ul.nav-tabs li {
            width:50% !important;
        }
    }
    .sk-fade-in.sk-spinner.sk-circle {
        display: inline-block !important;
        left: 50%;
        margin-left: -50px;
    }
    .nav-tabs li {
        width: initial !important;
    }
</style>
