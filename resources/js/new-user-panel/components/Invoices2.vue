<template>
    <div class="col-md-9 col-sm-11 col-xs-11 package basket">
        <div class="row">
            <div class="col-xs-12">
                <div class="block">
                    <div class="block-head">
                        <h3>{{ExWindow ? ExWindow.translator('panel-errors.invoices') : '' }}</h3>
                    </div>
                    <div class="list-tab">
                        <ul>
                            <li style="cursor:pointer;" :class="{'active': activeTab === 2}" @click="activeTab = 2">
                                <a v-on:click="getWaitingInvoices()">{{ExWindow ? ExWindow.translator('panel-errors.orderedOrders'): ''}} <span class="badge">{{allWaitingInvoicesCount ? allWaitingInvoicesCount : 0}}</span></a>
                            </li>
                            <li style="cursor:pointer;" :class="{'active': activeTab === 3}" @click="activeTab = 3">
                                <a v-on:click="getForeignInvoices()">{{ExWindow ? ExWindow.translator('panel-errors.inForeign'): ''}} <span class="badge">{{allForeignInvoicesCount ? allForeignInvoicesCount : 0}}</span></a>
                            </li>
                            <li style="cursor:pointer;" :class="{'active': activeTab === 4}" @click="activeTab = 4">
                                <a v-on:click="getOnTheWayInvoices()">{{ExWindow ? ExWindow.translator('panel-errors.onTheWay'): ''}} <span class="badge">{{allOnTheWayInvoicesCount ? allOnTheWayInvoicesCount : 0}}</span></a>
                            </li>
                            <li style="cursor:pointer;" :class="{'active': activeTab === 5}" @click="activeTab = 5">
                                <a v-on:click="getHomeInvoices()">{{ExWindow ? ExWindow.translator('panel-errors.bakuOfficce'): ''}} <span class="badge">{{allHomeInvoicesCount ? allHomeInvoicesCount  : 0}}</span></a>
                            </li>
                            <li style="cursor:pointer;" :class="{'active': activeTab === 6}" @click="activeTab = 6">
                                <a v-on:click="getCourierInvoices()">{{ExWindow ? ExWindow.translator('panel-errors.has_courier'): ''}} <span class="badge">{{allAtCourierInvoicesCount ? allAtCourierInvoicesCount  : 0}}</span></a>
                            </li>
                            <li style="cursor:pointer;" :class="{'active': activeTab === 7}" @click="activeTab = 7">
                                <a v-on:click="getCompletedInvoices()">{{ExWindow ? ExWindow.translator('panel-errors.completedOrder'): ''}} <span class="badge">{{allCompletedInvoicesCount ? allCompletedInvoicesCount : 0}}</span></a>
                            </li>
                            <!--<li style="cursor:pointer;" :class="{'active': activeTab === 1}" @click="activeTab = 1">
                                <a v-on:click="getAllInvoices()">{{ExWindow ? ExWindow.translator('panel-errors.all'): ''}} <span class="badge">{{allInvoicesCount ? allInvoicesCount : 0}}</span></a>
                            </li>-->
                        </ul>
                    </div>
                    <div class="select-all">
                        <label class="check-button">
                            <span class="check-text">{{ExWindow ? ExWindow.translator('panel-errors.select_all') : '' }}</span>
                            <input type="checkbox" @change="selectAll()" v-model="select_all">
                            <span class="checkmark"></span>
                        </label>
                        <div class="right-side web-ex">
                            <button type="button" class="transparent">
                                <img src="/front/new/img/excel.png" alt="excel">
                                <span>{{ExWindow ? ExWindow.translator('panel-errors.save_in_excel') : '' }}</span>
                            </button>
                        </div>
                        <div class="right-side mobile-ex">
                            <button type="button" class="transparent">
                                <img src="/front/new/img/excel.png" alt="excel">
                                <span>{{ExWindow ? ExWindow.translator('panel-errors.save') : '' }}</span>
                            </button>
                        </div>
                        <div class="filter">
                            <span>{{ExWindow ? ExWindow.translator('panel-errors.filter') : '' }}</span>
                            <div class="invoice-day input-group border-radius">
                                <v-select v-model="country"  :options="countries1" v-on:change="getByCountry"></v-select>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="block-border">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label class="check-button">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <img src="img/basket.png" class="img-responsive" alt="basket">
                            </div>
                            <div class="center-block col-md-9 col-sm-9 col-xs-12">
                                <ul>
                                    <li class="all-width">
                                        <p><b>Sifariş tarixi:</b>08/03/2019</p>
                                        <p><b>Ölkə:</b>Türkiyə</p>
                                        <p><b>Mağaza:</b>DeFacto</p>
                                        <p><button class="btn-effect" type="button" data-toggle="modal" data-target="#view">Bax</button></p>
                                    </li>
                                    <li>
                                        <p><b>İzləmə No:</b></p>
                                        <span>25091989086</span>
                                    </li>
                                    <li>
                                        <p><b>Məhsul sayı</b></p>
                                        <span>1</span>
                                    </li>
                                    <li>
                                        <p><b>Bağlama içindəki</b></p>
                                        <span>Kapşonlu sport köynək</span>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <p><b>Qiyməti</b></p>
                                        <span>286 TL</span>
                                    </li>
                                    <li>
                                        <p><b>Çəkisi</b></p>
                                        <span>7 kq</span>
                                    </li>
                                    <li>
                                        <p><b>Çatdırılma haqqı</b></p>
                                        <span>15 TL</span>
                                    </li>
                                    <li>
                                        <p><b>Status</b></p>
                                        <span class="blue">Sifariş verildi</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="block-border">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label class="check-button">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <img src="img/basket.png" class="img-responsive" alt="basket">
                            </div>
                            <div class="center-block col-md-9 col-sm-9 col-xs-12">
                                <ul>
                                    <li class="all-width">
                                        <p><b>Sifariş tarixi:</b>08/03/2019</p>
                                        <p><b>Ölkə:</b>Türkiyə</p>
                                        <p><b>Mağaza:</b>DeFacto</p>
                                        <p><button class="btn-effect" type="button" data-toggle="modal" data-target="#view">Bax</button></p>
                                    </li>
                                    <li>
                                        <p><b>İzləmə No:</b></p>
                                        <span>25091989086</span>
                                    </li>
                                    <li>
                                        <p><b>Məhsul sayı</b></p>
                                        <span>1</span>
                                    </li>
                                    <li>
                                        <p><b>Bağlama içindəki</b></p>
                                        <span>Kapşonlu sport köynək</span>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <p><b>Qiyməti</b></p>
                                        <span>286 TL</span>
                                    </li>
                                    <li>
                                        <p><b>Çəkisi</b></p>
                                        <span>7 kq</span>
                                    </li>
                                    <li>
                                        <p><b>Çatdırılma haqqı</b></p>
                                        <span>15 TL</span>
                                    </li>
                                    <li>
                                        <p><b>Status</b></p>
                                        <span class="blue">Sifariş verildi</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="block-border">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label class="check-button">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <img src="img/basket.png" class="img-responsive" alt="basket">
                            </div>
                            <div class="center-block col-md-9 col-sm-9 col-xs-12">
                                <ul>
                                    <li class="all-width">
                                        <p><b>Sifariş tarixi:</b>08/03/2019</p>
                                        <p><b>Ölkə:</b>Türkiyə</p>
                                        <p><b>Mağaza:</b>DeFacto</p>
                                        <p><button class="btn-effect" type="button" data-toggle="modal" data-target="#view">Bax</button></p>
                                    </li>
                                    <li>
                                        <p><b>İzləmə No:</b></p>
                                        <span>25091989086</span>
                                    </li>
                                    <li>
                                        <p><b>Məhsul sayı</b></p>
                                        <span>1</span>
                                    </li>
                                    <li>
                                        <p><b>Bağlama içindəki</b></p>
                                        <span>Kapşonlu sport köynək</span>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <p><b>Qiyməti</b></p>
                                        <span>286 TL</span>
                                    </li>
                                    <li>
                                        <p><b>Çəkisi</b></p>
                                        <span>7 kq</span>
                                    </li>
                                    <li>
                                        <p><b>Çatdırılma haqqı</b></p>
                                        <span>15 TL</span>
                                    </li>
                                    <li>
                                        <p><b>Status</b></p>
                                        <span class="blue">Sifariş verildi</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div v-for="invoice in allInvoices"  class="block-border">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label v-if="invoice.sum_i_shprice !== null && parseInt(invoice.i_is_paid)=== 0 && invoice.status_id!==5 && invoice.status_id!==7" class="check-button">
                                    <input type="checkbox" v-model="invoice.checked"  @change="changePrice(invoice.sum_i_shprice,invoice.id)">
                                    <span class="checkmark"></span>
                                </label>
                                <div class="box-panel">
                                    <img src="/front/new/img/box-panel.png" class="img-responsive" alt="basket">
                                    <span style="display: none" class="badge">{{invoice.i_count}}</span>
                                </div>
                            </div>
                            <div class="center-block col-md-9 col-sm-9 col-xs-12">
                                <ul>
                                    <li class="all-width">
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}:</b>{{invoice.created_at}}</p>
                                        <p><b>Ölkə:</b>{{invoice.country_name}}</p>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}}:</b>{{invoice.shop_name}}</p>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}:</b></p>
                                        <span>{{invoice.order_tracking_number}}</span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.invoices_quantity'): ''}}</b></p>
                                        <span>{{invoice.i_count}}</span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.invoice_products'): ''}}</b></p>
                                        <!--<span>{{invoice.product_type_name}}</span>-->
                                        <span>{{invoice.prodcuts !==null ? invoice.products : ExWindow ? ExWindow.translator('panel-errors.no'): ''}}</span>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.priceOrder'): ''}}</b></p>
                                        <span>{{invoice.sum_p_price}} TL</span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.weight'): ''}}</b></p>
                                        <span>{{invoice.weight !==null ? invoice.weight+' kq' : ExWindow ? ExWindow.translator('panel-errors.no'): ''}} </span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.priceShipment'): ''}}</b></p>
                                        <span>{{invoice.sum_i_shprice !== null ? invoice.sum_i_shprice+' USD ' : ExWindow ? ExWindow.translator('panel-errors.no'): ''}} </span>
                                        <hr v-if="invoice.sum_i_shprice !== null" style="margin: 0">
                                        <span>{{invoice.sum_i_shprice !== null ? (invoice.sum_i_shprice*currency).toFixed(2)+' AZN' : ''}} </span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.status'): ''}}</b></p>
                                        <span class="blue">{{invoice.status_name}}</span>
                                        <hr  v-if="parseInt(invoice.i_is_paid)=== 1" style="margin: 0">
                                        <span v-if="parseInt(invoice.i_is_paid)=== 1" style="font-weight: bold; color: green">{{ ExWindow ? ExWindow.translator('panel-errors.payed'): '' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="sum">
                        <span>{{ExWindow ? ExWindow.translator('panel-errors.curyer_price'): ''}}</span>
                        <p>{{allShippingPrices.toFixed(2)}} USD</p>
                        <p>{{(allShippingPrices*currency).toFixed(2)}} AZN</p>
                    </div>
                    <div  class="pay">
                        <button @click="payPrice" type="button" class="btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import Spinner from 'vue-spinkit';
    import swal from 'sweetalert2';
    import vSelect from 'vue-select';

    export default {
        computed: {
            countries1: function(){
                return  [{id:1,name: 'Türkiyə',label:'Türkiyə'}];
            },
        },
        mounted() {
            this.getWaitingInvoices();
            this.getCounts();
            this.getCurrency();
            this.getCountries();
            this.getAllInvoicesCount();
            this.ExWindow = window;
        },
        props: {

        },
        data: function (){
            return {
                select_all: false,
                allShippingPrices:0,
                checkedBoxes:[],

                selected:2,
                activeTab:2,
                countries:[],
                country:'',
                allInvoices: [],
                allWaitingInvoices: [],
                allForeignInvoices: [],
                allOnTheWayInvoices: [],
                allHomeInvoices: [],
                // allOrderedOrders: [],
                allCompletedInvoices: [],
                allAtCourierInvoices: [],
                allInvoicesCount : 0,
                allWaitingInvoicesCount: 0,
                allForeignInvoicesCount: 0,
                allOnTheWayInvoicesCount: 0,
                allHomeInvoicesCount: 0,
                allCompletedInvoicesCount: 0,
                // allOrderedOrdersCount: 0,
                allAtCourierInvoicesCount: 0,
                modalToggle: false,
                withCourier: false,
                invoiceId: null,
                courierCash: 0,
                deliveryPrice: 0,
                balance: 0,
                spinner: false,
                ExWindow: null,
                index: 0,
                currency:''

            }
        },
        methods: {
            payPrice(){
                let price = (this.allShippingPrices*this.currency).toFixed(2);
                if(price>0) {
                    if (parseFloat(this.balance, 2) >= price) {
                        swal({
                            title: this.ExWindow && this.ExWindow.translator('panel-errors.are_you_sure'),
                            text: this.ExWindow && this.ExWindow.translator('panel-errors.payment_process'),
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.yes'),
                            cancelButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.no')
                        }).then((result) => {
                            if(result.value===true){
                                axios.post('/user-panel/pay-invoice-price',{price:price, currency:'azn', invoices:this.checkedBoxes })
                                    .then(data=>{
                                        this.getAllInvoices();
                                        swal(this.ExWindow && this.ExWindow.translator('panel-errors.payed'),'success');
                                    });
                            }
                        });
                    } else {
                        swal({
                            title: this.ExWindow && this.ExWindow.translator('panel-errors.insufficient_balance'),
                            text: this.ExWindow && this.ExWindow.translator('panel-errors.insufficient_balance'),
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.yes'),
                            cancelButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.no')
                        }).then((result) => {
                            if (result.value) {
                                this.$router.push('/balance');
                            }
                        });
                    }
                }
            },
            getCurrency(){
                axios.get('/get-currency/usd-azn')
                    .then(data=>{
                        this.currency = data.data
                    });
            },
            getAllInvoicesCount(){
                axios.get('/user-panel/get-allInvoices-count')
                    .then(data=>{
                        this.allInvoicesCount = data.data.data.count;
                        console.log(data)
                    });
            },
            getByCountry(){
                console.log(this.country)
            },
            changePrice(shPrice,i_id){
                if(event.target.checked) {
                    this.allShippingPrices = parseFloat(this.allShippingPrices,2)+parseFloat(shPrice, 2);
                    this.checkedBoxes.push(i_id)
                }
                else {
                    this.allShippingPrices = parseFloat(this.allShippingPrices,2) - parseFloat(shPrice, 2);
                    for( var i = 0; i < this.checkedBoxes.length; i++){
                        if ( this.checkedBoxes[i] === i_id) {
                            this.checkedBoxes.splice(i, 1);
                        }
                    }
                }
                // this.allShippingPrices=this.allShippingPrices.toFixed(2);
            },
            selectAll() {
                for(let i in this.allInvoices) {
                    if(this.allInvoices[i].sum_i_shprice !== null  && parseInt(this.allInvoices[i].i_is_paid)===0 && this.allInvoices[i].status_id!==5 && this.allInvoices[i].status_id!==7){
                        this.allInvoices[i].checked = this.select_all
                        if (this.allInvoices[i].checked === true) {
                            if(!this.checkedBoxes.includes(this.allInvoices[i].id)) {
                                this.allShippingPrices =parseFloat(this.allShippingPrices,2) + parseFloat(this.allInvoices[i].sum_i_shprice, 2);
                                this.checkedBoxes.push(this.allInvoices[i].id);
                            }
                        }
                        else {
                            if(this.checkedBoxes.includes(this.allInvoices[i].id)) {
                                this.allShippingPrices = parseFloat(this.allShippingPrices,2) - parseFloat(this.allInvoices[i].sum_i_shprice, 2);
                                for (var p = 0; p < this.checkedBoxes.length; p++) {
                                    if (this.checkedBoxes[p] === this.allInvoices[i].id) {
                                        this.checkedBoxes.splice(p, 1);
                                    }
                                }
                            }
                        }
                    }
                }
                console.log(this.checkedBoxes)
                // this.allShippingPrices=this.allShippingPrices.toFixed(2);
            },
            async getCountries(){
                let country;
                axios.get('/user-panel/get-countries')
                    .then(data=>{
                        console.log(data.data.data)
                        country=data.data.data;
                    });
                this.countries = country;
            },
            getCountryData(){

            },
            accept() {
                var path = '/user-panel/pay';
                axios.post(path, {withCourier: this.withCourier, invoiceId: this.invoiceId}).then((response) => {
                    if(response.data.code === 1602) {
                        swal(this.ExWindow && this.ExWindow.translator('panel-errors.payed'),)
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
                // console.log(this.courierCash, this.deliveryPrice);
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
                // this.allInvoicesCount = 0;
                // this.allInvoicesCount = this.allInvoices.length;
                this.balance = test.data.balance;
                this.index = test.data.index;
                this.spinner = false;
            },
            async getWaitingInvoices() {

                const test  = await this.getInvoices('waiting');
                // this.allWaitingInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allWaitingInvoicesCount = 0;
                // this.allWaitingInvoicesCount = this.allWaitingInvoices.length;

            },
            async getForeignInvoices() {

                const test  = await this.getInvoices('foreign');
                // this.allForeignInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allForeignInvoicesCount = 0;
                // this.allForeignInvoicesCount = this.allForeignInvoices.length;
            },
            async getOnTheWayInvoices() {

                const test  = await this.getInvoices('on_the_way');
                // this.allOnTheWayInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allOnTheWayInvoicesCount = 0;
                // this.allOnTheWayInvoicesCount = this.allOnTheWayInvoices.length;
            },
            async getHomeInvoices() {

                const test  = await this.getInvoices('home');
                // this.allHomeInvoices = test.data.data;
                this.allInvoices = test.data.data;
                this.allHomeInvoices.map((loop) => {
                    if(loop.courier) {
                        return loop.courierPrice = loop.courier.delivery_type == 1? 4: 10;
                    } else {
                        return loop.courierPrice = 0;
                    }
                });
                // this.allHomeInvoicesCount = 0;
                // this.allHomeInvoicesCount += this.allHomeInvoices.length;
                this.balance = test.data.balance;
            },
            async getCompletedInvoices() {

                const test  = await this.getInvoices('completed');
                // this.allCompletedInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allCompletedInvoicesCount = 0;
                // this.allCompletedInvoicesCount += this.allCompletedInvoices.length;
            },
            // async getOrderedOrders() {
            //     const test  = await this.getOrders('ordered');
            //     // this.allOrderedOrders = test.data;
            //     this.allInvoices = test.data.data;
            //     this.allOrderedOrdersCount = 0;
            //     this.allOrderedOrdersCount = this.allOrderedOrders.length;
            //
            // },
            // async getOrderedOrdersCount(type) {
            //     axios.get('/user-panel/get-orders-count/'+type)
            //         .then(data => {
            //
            //                 });
            //     // this.allOrderedOrders = test.data;
            //     this.allInvoices = test.data.data;
            //     this.allOrderedOrdersCount = 0;
            //     this.allOrderedOrdersCount = this.allOrderedOrders.length;
            //
            // },
            async getCourierInvoices() {

                const test  = await this.getInvoices('has_courier');
                // this.allAtCourierInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allAtCourierInvoicesCount= 0;
                // this.allAtCourierInvoicesCount += this.allAtCourierInvoices.length;
            },
            getOrders(type) {
                var path = '/user-panel/get-orders/' +type;
                return  axios.get(path);

            },
            getInvoices(type) {
                this.select_all=false;
                this.allShippingPrices=0;
                this.checkedBoxes=[];

                var path = '/user-panel/get-invoices/' +type;
                return  axios.get(path);

            },
            storageUrl(url) {
                if(url) {
                    return url;
                }
                return '';
            },
            getInvoicesCount(){
                var path = '/user-panel/get-invoices-count/';
                return  axios.get(path);
            },
             async getCounts(){
                const data  = await this.getInvoicesCount();
                this.allWaitingInvoicesCount = data.data.data.waiting;
                this.allForeignInvoicesCount = data.data.data.foreign;
                this.allOnTheWayInvoicesCount = data.data.data.on_the_way;
                this.allHomeInvoicesCount = data.data.data.home;
                this.allCompletedInvoicesCount = data.data.data.completed;
                this.allAtCourierInvoicesCount = data.data.data.has_courier;


            }
        },
        components: {
            modal: Modal,
            Spinner: Spinner,
            vSelect
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
