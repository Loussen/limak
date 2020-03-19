<template>
    <div class="col-md-9 col-sm-11 col-xs-11 package basket">
        <div class="row">
            <div class="col-xs-12 order-body">
                <div class="block">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#turkey" aria-controls="home" role="tab" data-toggle="tab" class="order-img1"
                               @click="changeFilter(1)">
                                {{ExWindow ? ExWindow.translator('common.turkey') : '' }}
                            </a>
                        </li>
                        <li role="presentation" class="">
                            <a href="#usa" aria-controls="home" role="tab" data-toggle="tab" class="order-img2"
                               @click="changeFilter(2)">
                                {{ExWindow ? ExWindow.translator('common.usa') : '' }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12" id="tabContent">
                <div class="block">
                    <div class="block-head">
                        <h3>{{ExWindow ? ExWindow.translator('panel-errors.invoices') : '' }}</h3>
                        <ul class="nav navbar-nav navbar-right">

                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown" aria-haspopup="true" class="drop-link"
                                   aria-expanded="true">{{selected_type_name}} <span class="badge">{{selected_type_count}}</span><i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                    <li>
                                        <a v-on:click="getTypeInvoices('waiting')">{{ExWindow ? ExWindow.translator('panel-errors.orderedOrders'): ''}} <span class="badge">{{allWaitingInvoicesCount ? allWaitingInvoicesCount : 0}}</span></a>
                                    </li>
                                    <li>
                                        <a v-on:click="getTypeInvoices('foreign')">{{ExWindow ? ExWindow.translator('panel-errors.inForeign'): ''}} <span class="badge">{{allForeignInvoicesCount ? allForeignInvoicesCount : 0}}</span></a>
                                    </li>
                                    <li>
                                        <a v-on:click="getTypeInvoices('on_the_way')">{{ExWindow ? ExWindow.translator('panel-errors.onTheWay'): ''}} <span class="badge">{{allOnTheWayInvoicesCount ? allOnTheWayInvoicesCount : 0}}</span></a>
                                    </li>
                                    <li>
                                        <a v-on:click="getTypeInvoices('home')">{{ExWindow ? ExWindow.translator('panel-errors.bakuOfficce'): ''}} <span class="badge">{{allHomeInvoicesCount ? allHomeInvoicesCount  : 0}}</span></a>
                                    </li>
                                    <li>
                                        <a v-on:click="getTypeInvoices('has_courier')">{{ExWindow ? ExWindow.translator('panel-errors.has_courier'): ''}} <span class="badge">{{allAtCourierInvoicesCount ? allAtCourierInvoicesCount  : 0}}</span></a>
                                    </li>
                                    <li>
                                        <a v-on:click="getTypeInvoices('completedOrder')">{{ExWindow ? ExWindow.translator('panel-errors.completedOrder'): ''}} <span class="badge">{{allCompletedInvoicesCount ? allCompletedInvoicesCount : 0}}</span></a>
                                    </li>
                                    <li>
                                        <a v-on:click="getTypeInvoices('all')">{{ExWindow ? ExWindow.translator('panel-errors.all'): ''}} <span class="badge">{{allInvoicesCount ? allInvoicesCount : 0}}</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="block-inner">
                        <div class="select-all">
                            <label class="check-button">
                                <span class="check-text">{{ExWindow ? ExWindow.translator('panel-errors.select_all') : '' }}</span>
                                <input type="checkbox" @change="selectAll()" v-model="select_all">
                                <span class="checkmark"></span>
                            </label>
                            <!--
                                                        <button class="archive" type="button">Arxivə əlavət et</button>
                            -->

                        </div>
                        <div class="block-border">
                            <div class="table-list" :class="selected_invoice_id==invoice.id?'table-open':''"  v-for="invoice in allInvoices" >
                                <table class="table table-bordered table-responsive">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <label  v-if="invoice.shipping_price !== null && parseInt(invoice.is_paid)=== 0 && invoice.status_id!==5 && invoice.status_id!==7" class="check-button">
                                                <input type="checkbox" v-model="invoice.checked"
                                                       @change="changePrice(invoice.shipping_price,invoice.id)">
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{{ExWindow ? ExWindow.translator('panel-errors.trackingNo'): ''}}<br><span>0000{{invoice.id}}</span></p>
                                        </td>
                                        <td>
                                            <p>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}} <br><span>{{invoice.created_at}}</span></p>
                                        </td>
                                        <td>
                                            <p>{{ExWindow ? ExWindow.translator('panel-errors.shop'): ''}} <br><span>{{invoice.shop_name}}</span></p>
                                        </td>
                                        <td>
                                            <p>{{ExWindow ? ExWindow.translator('panel-errors.status'): ''}}<br><span>{{invoice.status_name}}</span></p>
                                        </td>
                                        <td>
                                            <button type="button" class="btn-effect" @click="fullData(invoice.id)">Bax
                                            </button>
                                            <button v-if="invoice.status_id==1 && invoice.added_by=='user'" type="button" class="btn-effect green" data-toggle="modal"
                                                    data-target=".edit" @click="fullData(invoice.id)">Düzəliş
                                            </button>
                                            <button v-if="invoice.status_id==1 && invoice.added_by=='user'" type="button" class="btn-effect red" @click="deleteInvoice(invoice.id)">Sil</button>

                                        </td>
                                    </tr>
                                    <tr v-if="selected_invoice_id==invoice.id && selected_invoice">
                                        <td>
                                            <p>İzləmə №<br><span>{{selected_invoice.order_tracking_number}}</span></p>
                                        </td>
                                        <td class="product">
                                            <p>Məhsul sayı<br><span>{{selected_invoice_count}}</span></p>
                                            <p>Çəkisi<br><span>{{selected_invoice.weight}}kq</span></p>
                                        </td>
                                        <td>
                                            <p>Çatdırılma qiyməti<br><span>{{selected_invoice.shipping_price}} AZN</span></p>
                                        </td>
                                        <td>
                                            <p>Qiyməti<br><span>{{selected_invoice.price}} TL</span></p>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn-effect follow_order"
                                                    data-toggle="modal" data-target=".follow">Sifaişi izlə
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table  v-if="selected_invoice_id==invoice.id && selected_invoice" class="table table-bordered table-responsive">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div v-if="selected_invoice.packages.length>=1" v-for="package in selected_invoice.packages">
                                                <p v-if="package.products!=null">
                                                    {{package.products.product_type_name}}:
                                                    <a href="" v-if="package.products.extras!=null">
                                                        {{package.products.extras.link}}
                                                    </a>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal fade follow" id="myModal" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <table v-if="selected_invoice" class="table table-responsive">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <p>Sifariş № <br><span>0000{{selected_invoice.id}}</span></p>
                                                    </td>
                                                    <td>
                                                        <p>Tarixi <br><span>{{selected_invoice.order_date}}</span></p>
                                                    </td>
                                                    <td>
                                                        <p>Mağaza <br><span>{{selected_invoice.packages[0].products.shop_name}}</span></p>
                                                    </td>
                                                    <td>
                                                        <p>Statusu<br><span>{{selected_invoice.invoice_status.name}}</span></p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><img src="/front/new/img/error.png" alt="error">
                                            </button>
                                        </div>
                                        <div class="modal-body follow-order">
                                            <div class="follow-line">
                                                <ul v-if="selected_invoice && selected_invoice.dates!=null">
                                                    <li  class="active">
                                                        <span class="shopping-bag"></span>
                                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.ordered') : '' }}</b>{{ returnStatusDate( selected_invoice.dates,1 ) }}</p>
                                                    </li>
                                                    <li :class="returnStatus( selected_invoice.dates,2 ) ? 'active' : ''">
                                                        <span class="order-follow"></span>
                                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.in_foreign') : '' }}</b>{{returnStatusDate( selected_invoice.dates,2 ) }}</p>
                                                    </li>
                                                    <li :class="returnStatus( selected_invoice.dates,3 ) ? 'active' : ''">
                                                        <span class="departures"></span>
                                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.on_way') : '' }}</b>{{ returnStatusDate( selected_invoice.dates,3 ) }}</p>
                                                    </li>
                                                    <li :class="returnStatus( selected_invoice.dates,4 ) ? 'active' : ''">
                                                        <span class="warehouse"></span>
                                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.at_home') : '' }}</b>{{ returnStatusDate( selected_invoice.dates,4 ) }}</p>
                                                    </li>
                                                    <li :class="returnStatus( selected_invoice.dates,6 ) ? 'active' : '' ">
                                                        <span class="shipped"></span>
                                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.has_courier') : '' }}</b>{{ returnStatusDate( selected_invoice.dates,6 ) }}</p>
                                                    </li>
                                                    <li :class="returnStatus( selected_invoice.dates,5 ) ? 'active' : ''">
                                                        <span class="package"></span>
                                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.completed') : '' }}</b>
                                                            <!--{{ returnStatusDate( InvoiceData.dates,InvoiceData.statuses,5 ) }}-->
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>





















                            <div class="modal fade edit" id="myModal1" role="dialog"
                                 aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4>Düzəlİş</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><img src="/front/new/img/error.png"
                                                                            alt="error">
                                            </button>
                                        </div>
                                        <div class="modal-body edit-order">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-6 ">
                                                    <div :class="this.errors.shop ? 'error-message' : ''" class="input-group border-radius">
                                                        <label>
                                                            <input type="hidden" name="invoice_id" v-model="selected_invoice_id">
                                                            <input v-model="shop" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                            <span>{{ExWindow ? ExWindow.translator('panel.shop_name') : '' }}  * </span>
                                                        </label>
                                                        <span v-if="this.errors.shop" class="error-text">{{ this.errors.shop }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <div :class="this.errors.product_type ? 'error-message' : ''" class="input-group border-radius">
                                                        <label>
                                                            <input v-model="product_type" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                            <span>{{ExWindow ? ExWindow.translator('panel.product_type') : '' }}  * </span>
                                                        </label>
                                                        <span v-if="this.errors.product_type" class="error-text">{{ this.errors.product_type }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div :class="this.errors.price ? 'error-message' : ''" class="input-group border-radius">
                                                        <label>
                                                            <input v-model="price" type="text" @input="check()" @change="check()" name="length" class="form-control inputText" placeholder=" ">
                                                            <span>{{ExWindow ? ExWindow.translator('panel.price') : '' }}  * </span>
                                                        </label>
                                                        <span v-if="this.errors.price" class="error-text">{{ this.errors.price }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <div :class="this.errors.order_track ? 'error-message' : ''" class="input-group border-radius">
                                                        <label>
                                                            <input v-model="order_track" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                            <span>{{ExWindow ? ExWindow.translator('panel.track_code') : '' }}  * </span>
                                                        </label>
                                                        <span v-if="this.errors.order_track" class="error-text">{{ this.errors.order_track }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-12 form-group">
                                                    {{region}}
                                                    <label>
                                                        <span>{{ExWindow ? ExWindow.translator('panel.delivery_office') : '' }}*</span>

                                                    </label>
                                                    <select v-model="region" class="form-control">
                                                        <option :value="regionOption.id" v-for="regionOption in regions"  :selected="region==regionOption.id">{{regionOption.name}}</option>
                                                    </select>

                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <label>
                                                        <span>{{ExWindow ? ExWindow.translator('panel.product_count') : '' }}*</span>

                                                    </label>
                                                    <div :class="this.errors.count ? 'error-message' : ''" class="ch-input-1 input-group number-spinner">
                                                        <span class="input-group-prepend">
                                                            <button class="btn btn-default" type="button" data-dir="dwn" @click="dec()"><strong >-</strong></button>
                                                        </span>
                                                                <input class="form-control text-center" v-model="count" @input="check()" @change="check()" :placeholder="ExWindow ? ExWindow.translator('panel.product_count') : '' +'*'" type="number" value="" min="1"/>
                                                                <span class="input-group-append">
                                                            <button class="btn btn-default" type="button" data-dir="up" @click="inc()"><strong>+</strong></button>
                                                        </span>
                                                        <span v-if="this.errors.count" class="error-text">{{ this.errors.count }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="textarea-form form-group">
                                                        <textarea v-model="description" class="form-control" rows="4" id="comment" :placeholder="ExWindow ? ExWindow.translator('panel-errors.package_desc') : ''"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="btn-part text-left">
                                                        <label for="file" class="invoice-upload">
                                                            <span>{{ExWindow ? ExWindow.translator('panel.invoice_upload') : '' }}</span>
                                                            <input @change="handleFileUpload" refs="file" type="file" class="form-control-file" id="file" name="invoice">
                                                        </label>
                                                        <button @click="send" class="btn-effect">
                                                            <span>{{ExWindow ? ExWindow.translator('common.send') : '' }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



















                        </div>
                        <div class="sum">
                            <span>{{ExWindow ? ExWindow.translator('panel-errors.curyer_price'): ''}}</span>
                            <p>{{allShippingPrices.toFixed(2)}} USD</p>
                            <p>{{(allShippingPrices*currency).toFixed(2)}} AZN</p>
                        </div>
                        <div class="pay">
                            <button @click="payPrice" type="button" class="btn-effect">{{ExWindow ?
                                ExWindow.translator('panel-errors.pay'): ''}}
                            </button>
                        </div>
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
            countries1: function () {
                return [{id: 1, name: 'Türkiyə', label: 'Türkiyə'}];
            },
            years: function(){
                let date = new Date()
                return (this.range(date.getFullYear()-2,date.getFullYear())).reverse();
            },
            full_date: function(){
                let date = new Date(this.year,this.month.id,this.day);
                return date.getFullYear() +':'+ date.getMonth()+ ':'+ date.getDate();
            }
        },
        mounted() {
            this.ExWindow = window;
            this.getCounts();
            this.getCurrency();
            this.getTypeInvoices("waiting");
            //this.getAllInvoicesCount();

            this.ExWindow = window;
            this.day = (new Date()).getDate();

            let month_names = this.ExWindow && this.ExWindow.translator('register.months');
            let months_array = month_names.split(',');
            let months = months_array.map((el,index) =>{
                return {
                    id: index+1,
                    name: el
                }
            });
            this.months = months;

            let cur_month = (new Date()).getMonth()+ 1;
            let month = this.months.find((el) =>{
                return el.id == cur_month
            });
            this.month = month;
            this.regions = [
                {id: 0, name: ''},
                {id: 1, name: this.ExWindow ? this.ExWindow.translator('panel.Baku') : ''},
                {id: 2, name: this.ExWindow ? this.ExWindow.translator('panel.Ganja') : ''},
                {id: 3, name: this.ExWindow ? this.ExWindow.translator('panel-errors.sumgait') : ''},
                {id: 4, name: this.ExWindow ? this.ExWindow.translator('panel-errors.zagatala') : ''}

            ];

        },
        props: {},
        data: function () {
            return {
                select_all: false,
                allShippingPrices: 0,
                checkedBoxes: [],

                selected: 2,
                activeTab: 2,
                countries: [],
                country: {id: 1, name: 'Türkiyə', label: 'Türkiyə'},
                allInvoices: [],
                allWaitingInvoices: [],
                allForeignInvoices: [],
                allOnTheWayInvoices: [],
                allHomeInvoices: [],
                allCompletedInvoices: [],
                allAtCourierInvoices: [],
                allInvoicesCount: 0,
                allWaitingInvoicesCount: 0,
                allForeignInvoicesCount: 0,
                allOnTheWayInvoicesCount: 0,
                allHomeInvoicesCount: 0,
                allCompletedInvoicesCount: 0,
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
                currency: '',
                selected_type: '',
                selected_type_name: '',
                selected_type_count: '',
                selected_invoice_id: '',
                selected_invoice: null,
                selected_invoice_count: '',

                errors : {
                    shop: null,
                    count: null,
                    price: null,
                    order_track: null,
                    product_type: null

                },
                product_type:'',
                months: [],
                shop: '',
                count: '',
                price: '',
                order_track: '',
                description: '',
                file: '',
                regions: [],
                region: {},
                country_id: 1,
                day: 1,
                month: {id:1,name: 'Yanvar'},
                year: '2019',
                editable: false,
                days: []
            }
        },
        methods: {
            changeFilter(country_id) {
                if (this.country.id != country_id) {
                    $("#tabContent").fadeOut("5000");
                    $("#tabContent").fadeIn("5000");
                }
                this.country.id = country_id;
                this.activeTab = 2;
                this.getCounts();
                this.getTypeInvoices('waiting');
                //this.getWaitingInvoices();

                //console.log(this.country);
            },

            deleteInvoice(invoice_id)
            {
                swal({
                    title: this.ExWindow && this.ExWindow.translator('panel-errors.are_you_sure'),
                    text: this.ExWindow && this.ExWindow.translator('panel-errors.invoice_delete_process'),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.yes'),
                    cancelButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.no')
                }).then((result) => {
                    if (result.value === true) {
                        axios.post('/panel/delete-invoice', {
                            invoice_id: invoice_id
                        })
                            .then(data => {
                                if(data.status==200){
                                    this.getTypeInvoices(this.selected_type);
                                    swal(this.ExWindow && this.ExWindow.translator('panel-errors.success_deleted'), 'success');
                                }else{
                                    swal(this.ExWindow && this.ExWindow.translator('panel-errors.cannot_delete'), 'error');
                                }
                            });
                    }
                });
            },

            payPrice() {
                let price = (this.allShippingPrices * this.currency).toFixed(2);
                if (price > 0) {
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
                            if (result.value === true) {
                                axios.post('/user-panel/pay-invoice-price', {
                                    price: price,
                                    currency: 'azn',
                                    invoices: this.checkedBoxes
                                })
                                    .then(data => {
                                        this.getAllInvoices();
                                        swal(this.ExWindow && this.ExWindow.translator('panel-errors.payed'), 'success');
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
            getCurrency() {
                axios.get('/get-currency/usd-azn')
                    .then(data => {
                        this.currency = data.data
                    });
            },
            getAllInvoicesCount() {
                axios.get('/user-panel/get-allInvoices-count?country_id=' + this.country.id)
                    .then(data => {
                        this.allInvoicesCount = data.data.data.count;
                        console.log(data)
                    });
            },

            returnStatus(statuses,status){
                if(statuses !== null){
                    for(let i = 0; i < statuses.length; i++){
                        if(statuses[i].status_id==status) return true;
                    }
                }
            },
            send(){
                this.resetErrors();
                this.checkErrors();
                if(Object.values(this.errors).every((el) => el == null)){
                    swal.showLoading();
                    const data= new FormData();
                    data.append('invoice_id', this.selected_invoice_id);
                    data.append('shop_name', this.shop);
                    data.append('country_id', this.country_id);
                    data.append('product_type', this.product_type);
                    data.append('quantity', this.count);
                    data.append('region', this.region.id);
                    data.append('country_id', this.country_id);
                    data.append('price', this.price);
                    data.append('order_number', this.order_track);
                    data.append('order_date', this.full_date);
                    data.append('description', this.description);

                    data.append('file', this.file);
                    axios({url: '/site/invoice/edit', method: 'POST', data: data}).then(response => {
                        swal({
                            type: "info",
                            text: this.ExWindow.translator('panel.invoice_uploaded')
                        }).then(() =>{
                            this.product_type='';
                            this.shop= '';
                            this.count= '';
                            this.price= '';
                            this.order_track= '';
                            this.day= 1,
                                this.month= {id:1,name: 'Yanvar'};
                            this.year= '2019';
                            this.description= '';
                            this.file= '';
                        })
                    });
                }
            },
            showSelect() {
                this.editable = true;
            },
            range(a, b, step){
                var A= [];
                if(typeof a== 'number'){
                    A[0]= a;
                    step= step || 1;
                    while(a+step<= b){
                        A[A.length]= a+= step;
                    }
                }
                else{
                    var s= 'abcdefghijklmnopqrstuvwxyz';
                    if(a=== a.toUpperCase()){
                        b=b.toUpperCase();
                        s= s.toUpperCase();
                    }
                    s= s.substring(s.indexOf(a), s.indexOf(b)+ 1);
                    A= s.split('');
                }
                return A;
            },
            handleFileUpload(e){
                console.log(e.target.files)
                this.file = e.target.files[0];
            },
            returnStatusDate(dates,status){
                if(dates !== null){
                    for(let i = 0; i < dates.length; i++){
                        if(dates[i].status_id==status) {
                            let date_str = new Date(dates[i].action_date);
                            let year = date_str.getFullYear();
                            let month = date_str.getMonth() +1;
                            let day = date_str.getDate();
                            return day + '.'+ month + '.'+ year;
                        }
                    }
                }

                /*if(dates !== null){
                    let dates1=dates.split(",");
                    let statuses1=statuses.split(",");
                    for(let i = 0; i < statuses1.length; i++){
                        if(statuses1[i]==status) {
                            let date_str = new Date(dates1[i]);
                            let year = date_str.getFullYear();
                            let month = date_str.getMonth() +1;
                            let day = date_str.getDate();
                            return day + '.'+ month + '.'+ year;
                        }
                    }
                }*/
            },

            resetErrors(){
                this.errors = {
                    shop: null,
                    count: null,
                    price: null,
                    order_track: null,
                    product_type: null

                }
            },
            dec() {
                this.count--;
                if(parseInt(this.count)<=0){
                    this.count = 1;
                }
            },
            inc() {
                this.count++;
            },
            check(){
                if(parseInt(this.count)<=0){
                    this.count = 1;
                }


                if(parseInt(this.price)<=0){
                    this.price = 1;
                }
            },


            checkErrors(){
                if(!this.shop.trim()){
                    this.errors.shop = this.ExWindow ? this.ExWindow.translator('panel-errors.show_shop') : '';
                }
                if(!this.product_type.trim()){
                    this.errors.product_type = this.ExWindow ? this.ExWindow.translator('panel-errors.show_product_type') : '';
                }
                if(!this.count){
                    this.errors.count =  this.ExWindow ? this.ExWindow.translator('panel-errors.show_count') : '';
                }
                else if(isNaN(parseFloat(this.count))){
                    this.errors.count = this.ExWindow ? this.ExWindow.translator('panel-errors.show_count_digit') : '';
                }
                if(!this.price.trim()){
                    this.errors.price = this.ExWindow ? this.ExWindow.translator('panel-errors.show_price') : '';
                }
                else if(isNaN(parseFloat(this.price))){
                    this.errors.price = this.ExWindow ? this.ExWindow.translator('panel-errors.show_price_digit') : '';
                }
                if(!this.order_track.trim()){
                    this.errors.order_track = this.ExWindow ? this.ExWindow.translator('panel-errors.show_order_truck') : '';
                }
            },

            getByCountry() {
                console.log(this.country)
            },
            fullData(i_id){

                for(let i = 1; i <= 31; i++){
                    this.days.push({id: i, name: i, label: i});
                }

                if(this.selected_invoice_id==i_id){
                    this.selected_invoice_id = 0;
                }else{
                    this.selected_invoice_id = i_id;
                    var path = '/panel/get-invoice/' + this.selected_invoice_id;

                    axios.get(path)
                        .then(data => {
                            this.selected_invoice = data.data.data;
                            this.selected_invoice_count = this.selected_invoice.packages.length;

                            this.shop = data.data.data.packages[0].products.shop_name;
                            this.product_type = data.data.data.packages[0].products.product_type_name;
                            this.description = data.data.data.packages[0].products.description;
                            this.count = data.data.data.packages.length;
                            this.price = data.data.data.price;
                            this.order_track = data.data.data.order_tracking_number;
                            this.region = data.data.data.region_id;

                        });

                }

            },
            changePrice(shPrice, i_id) {
                console.log(shPrice);
                if (event.target.checked) {
                    this.allShippingPrices = parseFloat(this.allShippingPrices, 2) + parseFloat(shPrice, 2);
                    this.checkedBoxes.push(i_id)
                } else {
                    this.allShippingPrices = parseFloat(this.allShippingPrices, 2) - parseFloat(shPrice, 2);
                    for (var i = 0; i < this.checkedBoxes.length; i++) {
                        if (this.checkedBoxes[i] === i_id) {
                            this.checkedBoxes.splice(i, 1);
                        }
                    }
                }
                // this.allShippingPrices=this.allShippingPrices.toFixed(2);
            },
            selectAll() {
                console.log("asasa");
                for (let i in this.allInvoices) {
                    if (this.allInvoices[i].shipping_price !== null && parseInt(this.allInvoices[i].is_paid) === 0 && this.allInvoices[i].status_id !== 5 && this.allInvoices[i].status_id !== 7) {
                        this.allInvoices[i].checked = this.select_all
                        if (this.allInvoices[i].checked === true) {
                            if (!this.checkedBoxes.includes(this.allInvoices[i].id)) {
                                this.allShippingPrices = parseFloat(this.allShippingPrices, 2) + parseFloat(this.allInvoices[i].shipping_price, 2);
                                this.checkedBoxes.push(this.allInvoices[i].id);
                            }
                        } else {
                            if (this.checkedBoxes.includes(this.allInvoices[i].id)) {
                                this.allShippingPrices = parseFloat(this.allShippingPrices, 2) - parseFloat(this.allInvoices[i].shipping_price, 2);
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
            async getCountries() {
                let country;
                axios.get('/user-panel/get-countries')
                    .then(data => {
                        console.log(data.data.data)
                        country = data.data.data;
                    });
                this.countries = country;
            },
            getCountryData() {

            },
            accept() {
                var path = '/user-panel/pay';
                axios.post(path, {withCourier: this.withCourier, invoiceId: this.invoiceId}).then((response) => {
                    if (response.data.code === 1602) {
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
                const test = await this.getInvoices();
                this.allInvoices = test.data.data;
                this.allInvoices.map((loop) => {
                    if (loop.courier) {
                        return loop.courierPrice = loop.courier.delivery_type == 1 ? 4 : 10;
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

            async getTypeInvoices(type) {
                this.selected_type = type;
                if(type == 'waiting'){
                    const invoices = await this.getInvoices('waiting');

                    this.allInvoices = invoices.data.data;

                    this.selected_type_name = this.ExWindow && this.ExWindow.translator('panel-errors.orderedOrders');



                    this.selected_type_count = this.allWaitingInvoicesCount;

                }else if(type == 'foreign'){
                    const invoices = await this.getInvoices('foreign');

                    this.allInvoices = invoices.data.data;

                    this.selected_type_name = this.ExWindow && this.ExWindow.translator('panel-errors.inForeign');

                    this.selected_type_count = this.allForeignInvoicesCount;

                }else if(type == 'on_the_way'){
                    const invoices = await this.getInvoices('on_the_way');

                    this.allInvoices = invoices.data.data;

                    this.selected_type_name = this.ExWindow && this.ExWindow.translator('panel-errors.onTheWay');

                    this.selected_type_count = this.allOnTheWayInvoicesCount;


                }else if(type == 'home'){
                    const invoices = await this.getInvoices('home');

                    this.allInvoices = invoices.data.data;
                    this.selected_type_name = this.ExWindow && this.ExWindow.translator('panel-errors.bakuOfficce');

                    this.selected_type_count = this.allHomeInvoicesCount;


                }else if(type == 'completedOrder'){
                    const invoices = await this.getInvoices('completed');

                    this.allInvoices = invoices.data.data;

                    this.selected_type_name = this.ExWindow && this.ExWindow.translator('panel-errors.completedOrder');

                    this.selected_type_count = this.allCompletedInvoicesCount;


                }else if(type == 'has_courier'){
                    const invoices = await this.getInvoices('has_courier');

                    this.allInvoices = invoices.data.data;

                    this.selected_type_name = this.ExWindow && this.ExWindow.translator('panel-errors.has_courier');

                    this.selected_type_count = this.allAtCourierInvoicesCount;
                }else{
                    this.spinner = true;
                    const test = await this.getInvoices();
                    this.allInvoices = test.data.data;
                    this.allInvoices.map((loop) => {
                        if (loop.courier) {
                            return loop.courierPrice = loop.courier.delivery_type == 1 ? 4 : 10;
                        } else {
                            return loop.courierPrice = 0;
                        }
                    });
                    this.balance = test.data.balance;
                    this.index = test.data.index;
                    this.spinner = false;

                    this.selected_type_name = this.ExWindow && this.ExWindow.translator('panel-errors.all');

                    this.selected_type_count = this.allInvoicesCount;
                }

            },


            async getWaitingInvoices() {

                const test = await this.getInvoices('waiting');
                // this.allWaitingInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allWaitingInvoicesCount = 0;
                // this.allWaitingInvoicesCount = this.allWaitingInvoices.length;

            },
            async getForeignInvoices() {

                const test = await this.getInvoices('foreign');
                // this.allForeignInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allForeignInvoicesCount = 0;
                // this.allForeignInvoicesCount = this.allForeignInvoices.length;
            },
            async getOnTheWayInvoices() {

                const test = await this.getInvoices('on_the_way');
                // this.allOnTheWayInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allOnTheWayInvoicesCount = 0;
                // this.allOnTheWayInvoicesCount = this.allOnTheWayInvoices.length;
            },
            async getHomeInvoices() {

                const test = await this.getInvoices('home');
                // this.allHomeInvoices = test.data.data;
                this.allInvoices = test.data.data;
                this.allHomeInvoices.map((loop) => {
                    if (loop.courier) {
                        return loop.courierPrice = loop.courier.delivery_type == 1 ? 4 : 10;
                    } else {
                        return loop.courierPrice = 0;
                    }
                });
                // this.allHomeInvoicesCount = 0;
                // this.allHomeInvoicesCount += this.allHomeInvoices.length;
                this.balance = test.data.balance;
            },
            async getCompletedInvoices() {

                const test = await this.getInvoices('completed');
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

                const test = await this.getInvoices('has_courier');
                // this.allAtCourierInvoices = test.data.data;
                this.allInvoices = test.data.data;
                // this.allAtCourierInvoicesCount= 0;
                // this.allAtCourierInvoicesCount += this.allAtCourierInvoices.length;
            },
            getOrders(type) {
                var path = '/user-panel/get-orders/' + type;
                return axios.get(path);

            },
            getInvoices(type) {
                this.select_all = false;
                this.allShippingPrices = 0;
                this.checkedBoxes = [];

                var path = '/panel/get-invoices/' + type + '?country_id=' + this.country.id;
                return axios.get(path);

            },
            storageUrl(url) {
                if (url) {
                    return url;
                }
                return '';
            },
            getInvoicesCount() {
                var path = '/panel/get-invoices-count/?country_id=' + this.country.id;
                return axios.get(path);
            },
            async getCounts() {
                const data = await this.getInvoicesCount();
                this.allWaitingInvoicesCount = data.data.data.waiting;
                this.allForeignInvoicesCount = data.data.data.foreign;
                this.allOnTheWayInvoicesCount = data.data.data.on_the_way;
                this.allHomeInvoicesCount = data.data.data.home;
                this.allCompletedInvoicesCount = data.data.data.completed;
                this.allAtCourierInvoicesCount = data.data.data.has_courier;
                this.allInvoicesCount = data.data.data.all;

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
    .basket .block-head .navbar-right {
        margin-right: 0;
    }

    .basket .dropdown .drop-link {
        border: 1px solid #d7d7d7;
        border-radius: 5px;
        width: 172px;
        padding: 0 0 0 10px;
        position: relative;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .basket .dropdown .drop-link:hover {
        background: transparent;
    }

    .basket .dropdown .drop-link .fa-angle-down {
        height: 100%;
        top: 0;
        background: #f5f5f5;
        padding: 7px 11px;
        border-left: 1px solid #d7d7d7;
    }

    .basket .dropdown .badge {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #f95631;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .basket .dropdown-menu > li > a {
        display: flex;
        justify-content: space-between;
    }

    .ch-input-1.input-group {
        width: 167px;
    }

    .ch-input-2.input-group {
        width: 150px;
    }

    .basket .block-head h3 {
        display: inline-block;
    }

    .all-select {
        display: flex;
        justify-content: space-between;
    }

    .all-select .input-group {
        margin-right: 10px;
    }

    .all-select .input-group:last-child {
        margin-right: 0;
    }

    .invoice-pen .fa-pencil {
        color: #333;
    }

    .edit-order {
        padding: 20px 35px;
    }

    .edit .modal-header {
        border-bottom: none;
        padding: 20px 30px 10px;
    }

    .edit .modal-header h4 {
        font-weight: 900;
        text-transform: uppercase;
        display: inline-block;
        margin: 0;
    }

    .edit .close {
        opacity: 1;
    }

    .package.basket #tabContent .block .block-head {
        padding: 25px 30px 0;
        border-bottom: 1px solid #eaeaea;
    }

    .package.basket #tabContent .block {
        padding: 0;
    }

    .btn-effect.red {
        background: #ff0000;
        border-color: #ff0000;
    }

    .btn-effect.green {
        background: #00a651;
        border-color: #00a651;
    }

    .package .archive {
        padding-left: 25px;
        background: url("/front/new/img/inbox.png") no-repeat left;
        color: #333;
        float: right;
        margin: 15px 0;
    }

    .basket .block-inner {
        padding: 15px 30px 30px;
    }

    .package .block-border {
        clear: both;
        border: none
    }

    .package .block-border .table {
        margin-bottom: 0;
        border-top: none;
    }

    .package .block-border tr td a {
        color: #999;
        font-weight: normal;
    }

    .package .block-border tr td {
        vertical-align: middle;
        width: 18%;
    }

    .package .block-border tr td:last-child {
        width: 25%;
    }

    .package .block-border tr td p {
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 0;
    }

    .package .block-border tr td p span {
        font-weight: normal;
        font-size: 13px;
    }

    .package .block-border tr td .check-button {
        vertical-align: top;
        margin-top: 7px;
    }

    .package .block-border td .btn-effect {
        width: 53px;
        padding: 4px;
        font-size: 14px;
    }

    .block-border td .follow_order {
        background: #058fff;
        border-color: #058fff;
        padding-left: 20px;
        position: relative;
        width: 100% !important;
    }

    .block-border td .follow_order:after {
        content: url("/front/new/img/location.png");
        position: absolute;
        left: 5px;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    .table-list {
        margin-bottom: 20px;
        width: 100%;
    }

    .table-list.table-open {
        border: 1px solid #eee;
    }

    td.product {
        position: relative;
    }

    td.product p {
        width: 48%;
    }

    td.product p:first-child:after {
        content: '';
        height: 100%;
        width: 1px;
        background: #ddd;
        position: absolute;
        right: 50%;
        top: 0;
    }

    td.product p:last-child {
        padding-left: 8px;
    }

    .follow .modal-dialog {
        width: 872px;
    }

    .follow .close {
        opacity: 1;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .follow .table {
        width: 90%;
    }

    .follow .table td {
        border: none;
    }

    .follow .follow-order .follow-line li p {
        font-size: 14px;
    }

    .follow .follow-order .follow-line li p:last-child {
        display: block;
        text-align: left;
    }

    .package .follow .follow-order .follow-line {
        padding: 0;
    }

    .follow .follow-order .follow-line li span {
        margin: 0 45px 0 14px
    }

    @media only screen and (max-width: 1200px) {
        .package .block-border td .btn-effect {
            width: 100%;
            margin-bottom: 5px;
        }
    }

    @media only screen and (max-width: 991px) {
        .follow-order .follow-line li span:before {
            height: 95px;
            bottom: -64px
        }

        .follow-order .follow-line ul:before {
            top: -6px;
            left: -19px;
        }

        .follow .follow-order .follow-line li p {
            display: block;
        }

        .follow-order .follow-line ul:after {
            left: -18px;
        }

        .follow-order .follow-line li:first-child span:before {
            height: 74px;
        }

        .follow-order .follow-line li:last-child span:before {
            top: 13px;
            height: 85px;
        }

        .package .follow .table {
            border: none;
        }

        .follow .modal-dialog {
            width: auto;
        }

    }

    @media only screen and (max-width: 767px) {
        .follow .close img {
            filter: none;
        }

        .ch-input-1.input-group {
            width: 100%;
        }

        .ch-input-2.input-group {
            width: 48%;
        }

        .close img {
            filter: brightness(100%) invert();
        }

        .edit .btn-part .btn-effect {
            width: 100%;
        }
    }

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

        .package .block-border tr td {
            display: inline-block;
            width: 50%;
        }

        .package .block-border .table tr td:last-child {
            width: 100%;
        }

        .package .follow .table tr td:last-child {
            width: 50%;
        }
    }

    @media only screen and (max-width: 400px) {
        .package .block-border tr td {
            width: 100%;
            word-break: break-all;
        }

        .basket .block-inner {
            padding: 15px;
        }

        .basket .select-all .check-button {
            display: block;
        }

        .package .archive {
            float: none;
        }

        .ch-input-2.input-group {
            width: 100%;
        }
    }

    .sk-fade-in.sk-spinner.sk-circle {
        display: inline-block !important;
        left: 50%;
        margin-left: -50px;
    }
</style>
