<template>
    <div>
        <div v-if="orderSelected" id="waiting-orders-change" class="modal fade in show" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="closeModal" type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Sifarişdə dəyişiklik et</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label >Link</label>
                                        <input type="input" class="form-control" v-model="selectedOrder.extras.link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label >Məbləğ (TL)</label>
                                        <input  type="number"  v-model="selectedOrder.price"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div  class="input-group">
                                        <label >Türkiyə içi kargo</label>
                                        <select @change="changeCargoPrice"  v-model="productCargo" title="Əlavə et" class="selectpicker-data form-control">
                                            <option  value="">Çatdırılma ödənişi</option>
                                            <option  value="yes">Ödənişlidir</option>
                                            <option  value="">Ödənişsizdir</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="productCargo" class="col-md-2">
                                    <div  class="input-group">
                                        <label>Karqo (TL)</label>
                                        <input v-model="selectedOrder.extras.cargo_price" type="number" class="form-control">
                                    </div>
                                </div>

                                <div  :class="productCargo ? 'col-md-2 web-span change-order-sum' : 'col-md-4 web-span change-order-sum'">
                                    <span class="btn-effect " style="width: 100%; float: none;">+5%</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label >Cəmi (TL)</label>
                                        <input disabled="disabled"  type="text" :value="calculateSumPrice()"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label >Mağaza</label>
                                        <input  type="text" v-model="selectedOrder.shop_name"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label >Brend</label>
                                        <input  type="text"  v-model="selectedOrder.extras.brand"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label >Rəngi</label>
                                        <input  type="text" v-model="selectedOrder.extras.color"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label >Tipi</label>
                                        <input  type="text" v-model="selectedOrder.product_type_name"  class="form-control">
                                    </div>
                                </div>                                
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label >Miqdarı</label>
                                        <input  type="number" v-model="selectedOrder.quantity"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label >Ölçü</label>
                                        <input  type="text" v-model="selectedOrder.extras.size"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label>Qeyd</label>
                                        <textarea v-model="selectedOrder.description" cols="15" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input @click="changeOrder(selectedOrder.id)" type="submit" value="Sifarişi dəyiş" class="btn btn-effect">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
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
                        <li class="setWidth" role="presentation"><a v-on:click="getProductRejectOrders()" href="#productReject" aria-controls="imtina" role="tab"
                                                                    data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.deficiency'): ''}}<span class="badge">{{allProductRejectOrdersCount}}</span></a>
                        </li>
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

                                    <template v-for="commonData in allOrders" >
                                        <tr v-if="commonData.status_id === 7" v-for="product in commonData.products" >
                                            <td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                            <td>{{product.description}}</td>
                                            <td>{{product.created_at}}</td>
                                            <td>İmtina</td>
                                            <td>{{product.price}} TL</td>
                                            <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                                        </tr>
                                        <tr v-for="product in commonData.products" >
                                            <td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                            <td>{{product.description}}</td>
                                            <td>{{product.created_at}}</td>
                                            <td>{{commonData.is_ordered == 1 && product.statuses.id != 6? 'Sifariş verildi' : product.statuses.name}}</td>
                                            <td>{{product.price}} TL</td>
                                            <td>
                                                <a v-if="product.is_ordered ===0" @click.prevent="handleOrder(product)" href="#"><img src="/front/img/Edit.png" alt="edit"></a>
                                                <a v-else @click.prevent="handleOrder(product)" href="#"><img src="/front/img/Edit.png" alt="edit"></a>
                                            </td>
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
                                <template v-for="commonData in allWaitingOrders" >
                                    <tr v-for="product in commonData.products" >
                                        <td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                        <td>{{product.description}}</td>
                                        <td>{{product.created_at}}</td>
                                        <td>{{product.price}} TL</td>
                                        <td>
                                            <a  @click.prevent="handleOrder(product)" href="#"><img src="/front/img/Edit.png" alt="edit"></a>
                                        </td>
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
                                <template v-for="commonData in allOrderedOrders" >
                                    <tr v-for="product in commonData.products" >
                                        <td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
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
                                <template v-for="commonData in allProductRejectOrders" >
                                    <tr v-for="product in commonData.products" >
                                        <td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
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
                                <template v-for="commonData in allTransactionRejectOrders" >
                                    <tr v-for="product in commonData.products" >
                                        <td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
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
                spinner: false,
                productCargo: '',
                orderSelected: false,
                selectedOrder: {
                    extras: {}
                }
            }
        },
        methods: {
            changeCargoPrice(){
                if(!this.productCargo){
                    this.selectedOrder.extras.cargo_price = null;
                }
            },
            calculateSumPrice(){
                return ((parseInt(this.nullToZero(this.selectedOrder.price)) + parseInt(this.nullToZero(this.selectedOrder.extras.cargo_price)))*1.05).toFixed(2);
            },
            nullToZero(val){
                if(val === null || val === undefined || val === ''){
                    return 0;
                }
                return val;
            },
            handleOrder(product){
                console.log(product)
                this.selectedOrder = product;
                this.orderSelected = true;
                if(this.selectedOrder.extras.cargo_price){
                    this.productCargo= 'yes'
                }

            },
            closeModal(){
                this.orderSelected = false;
                this.selectedOrder = {
                }
            },
            changeOrder(product_id){
                console.log(product_id);
                if(!this.productCargo){
                    this.selectedOrder.extras.cargo_price=null;
                }
                axios.post('/order/link/update/'+ product_id,
                    {
                        ...this.selectedOrder,
                        _method: 'put'
                    }
                )
                    .then((response) => {
                        this.getWaitingOrders();
                        this.orderSelected = false;
                        // location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            async getAllOrders() {

                this.spinner = true;
                const test  = await this.getOrders();
                this.allOrders = test.data;
                this.allOrdersCount = 0;
                this.allOrders.forEach((dat) => {
                    this.allOrdersCount += dat.products.length;
                });
                this.spinner = false
            },
            async getWaitingOrders() {
                const test  = await this.getOrders('waiting');
                this.allWaitingOrders = [];
                this.allWaitingOrdersCount = 0;
                test.data.forEach((dat) => {
                    this.allWaitingOrdersCount += dat.products.length;
                    this.allWaitingOrders.push(dat);
                });
            },
            async getOrderedOrders() {
                const test  = await this.getOrders('ordered');
                this.allOrderedOrders = [];
                this.allOrderedOrdersCount = 0;
                test.data.forEach((dat) => {
                    this.allOrderedOrdersCount += dat.products.length;
                    this.allOrderedOrders.push(dat);
                });

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
                this.allProductRejectOrders.forEach((dat) => {
                    this.allProductRejectOrdersCount += dat.products.length;
                });
            },
            async getTransactionRejectOrders() {
                const test  = await this.getOrders('transaction');
                this.allTransactionRejectOrders = test.data;
                this.allTransactionRejectOrdersCount = 0;
                this.allTransactionRejectOrders.forEach((dat) => {
                    this.allTransactionRejectOrdersCount += dat.products.length;
                });
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
