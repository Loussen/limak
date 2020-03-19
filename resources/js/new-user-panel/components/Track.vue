<template>
    <div class="col-md-9 col-sm-11 col-xs-11 package basket">
        <div class="row">
            <div class="col-xs-12 order-body">
                <div class="block">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#turkey" aria-controls="home" role="tab" data-toggle="tab" class="order-img1" @click="changeFilter(1)">
                                {{ExWindow ? ExWindow.translator('common.turkey') : '' }}
                            </a>
                        </li>
                        <li role="presentation" class="">
                            <a href="#usa" aria-controls="home" role="tab" data-toggle="tab" class="order-img2" @click="changeFilter(2)">
                                {{ExWindow ? ExWindow.translator('common.usa') : '' }}
                            </a>
                        </li>
                    </ul>
                    <div class="select-all">
                        <label class="check-button">
                            <span class="check-text">{{ExWindow ? ExWindow.translator('panel-errors.select_all') : '' }}</span>
                            <input value="1" v-model="select_all" type="checkbox">
                            <span @click="selectAll" class="checkmark"></span>
                        </label>
                        <!--<div class="right-side web-ex">
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
                        </div>-->
                        <!--<div class="filter">
                            <span>{{ExWindow ? ExWindow.translator('panel-errors.filter') : '' }}</span>
                            <div class="invoice-day input-group border-radius">
                                <v-select v-model="country"  :options="countries1" @change="changeFilter()"></v-select>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>

            <div class="col-xs-12" id="tabContent">
                <div class="block follow-order">
                    <div class="tab-content">
                        <div v-for="InvoiceData in Invoices" role="tabpanel" class="tab-pane fade in active" id="home">
                            <div class="panel-top">
                                <label v-if="InvoiceData.is_paid == 0 && parseFloat(InvoiceData.shipping_price) > 0"  class="check-button">
                                    <span class="web check-text"> {{ExWindow ? ExWindow.translator('panel-errors.select_for_payment') : '' }}</span>
                                    <span class="mobile check-text">{{ExWindow ? ExWindow.translator('panel-errors.select') : '' }}</span>
                                    <input :value="{id: InvoiceData.id,amount: InvoiceData.shipping_price}" v-model="selected"  type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <ul class="list">
                                    <li>{{ExWindow ? ExWindow.translator('panel-errors.country') : '' }}:
                                        <span>{{InvoiceData.country_name }}</span></li>
                                    <li>{{ExWindow ? ExWindow.translator('panel-errors.shop') : '' }}: <span >{{ InvoiceData.shop_names }}</span></li>
                                </ul>
                            </div>
                            <div class="all-fixed">
                                <div class="follow-line">
                                    <ul>
                                        <li  class="active">
                                            <span class="shopping-bag"></span>
                                            <p><b>{{ExWindow ? ExWindow.translator('panel-errors.ordered') : '' }}</b>{{ returnStatusDate( InvoiceData.dates,InvoiceData.statuses,1 ) }}</p>
                                        </li>
                                        <li :class="returnStatus( InvoiceData.statuses,2 ) ? 'active' : ''">
                                            <span class="order-follow"></span>
                                            <p><b>{{ExWindow ? ExWindow.translator('panel-errors.in_foreign') : '' }}</b>{{returnStatusDate( InvoiceData.dates,InvoiceData.statuses,2 ) }}</p>
                                        </li>
                                        <li :class="returnStatus( InvoiceData.statuses,3 ) ? 'active' : ''">
                                            <span class="departures"></span>
                                            <p><b>{{ExWindow ? ExWindow.translator('panel-errors.on_way') : '' }}</b>{{ returnStatusDate( InvoiceData.dates,InvoiceData.statuses,3 ) }}</p>
                                        </li>
                                        <li :class="returnStatus( InvoiceData.statuses,4 ) ? 'active' : ''">
                                            <span class="warehouse"></span>
                                            <p><b>{{ExWindow ? ExWindow.translator('panel-errors.at_home') : '' }}</b>{{ returnStatusDate( InvoiceData.dates,InvoiceData.statuses,4 ) }}</p>
                                        </li>
                                        <li :class="returnStatus( InvoiceData.statuses,6 ) ? 'active' : '' ">
                                            <span class="shipped"></span>
                                            <p><b>{{ExWindow ? ExWindow.translator('panel-errors.has_courier') : '' }}</b>{{ returnStatusDate( InvoiceData.dates,InvoiceData.statuses,6 ) }}</p>
                                        </li>
                                        <li :class="returnStatus( InvoiceData.statuses,5 ) ? 'active' : ''">
                                            <span class="package"></span>
                                            <p><b>{{ExWindow ? ExWindow.translator('panel-errors.completed') : '' }}</b>
                                                <!--{{ returnStatusDate( InvoiceData.dates,InvoiceData.statuses,5 ) }}-->
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="properties">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>{{ExWindow ? ExWindow.translator('panel-errors.invoices_count') : '' }}</th>
                                            <th>{{ExWindow ? ExWindow.translator('panel-errors.in_package') : '' }}</th>
                                            <th>{{ExWindow ? ExWindow.translator('panel-errors.weight') : '' }}</th>
                                            <th>{{ExWindow ? ExWindow.translator('panel-errors.size') : '' }}</th>
                                            <th>{{ExWindow ? ExWindow.translator('panel-errors.delivery_price') : '' }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ InvoiceData.i_count }}</td>
                                            <td><span>{{ InvoiceData.product_names }}</span></td>
                                            <td>{{ InvoiceData.weight }}</td>
                                            <td><span v-if="InvoiceData.width && InvoiceData.height">{{ InvoiceData.width }} sm * {{ InvoiceData.height }} sm</span></td>
                                            <td>
                                                <span v-if="InvoiceData.is_paid === 1 && parseFloat(InvoiceData.shipping_price) > 0" style="color: green;">Ödənilib</span>
                                                <span v-if="InvoiceData.is_paid === 0 && currencies.length > 0 && InvoiceData.shipping_price">
                                                {{ InvoiceData.shipping_price }} $ ({{ (InvoiceData.shipping_price * currencies_object['usd-azn'].val).toFixed(2) }} AZN)</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="block pay-part col-xs-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 sum">
                            <h5>{{ExWindow ? ExWindow.translator('panel-errors.pay_selected_package') : '' }}</h5>
                            <span>{{ExWindow ? ExWindow.translator('panel-errors.sum') : '' }} {{ selected.length }} {{ExWindow ? ExWindow.translator('panel-errors.package_chosed') : '' }}</span>
                            <p v-if="currencies.length > 0 && this.selected.length > 0">{{ sumDeliveryPrice() }} USD</p>
                            <p v-if="currencies.length > 0 && this.selected.length > 0">{{ (currencies_object['usd-azn'].val * sumDeliveryPrice() ).toFixed(2) }} AZN</p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                            <button type="button" @click="payPrice" class="btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.pay') : '' }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Spinner from 'vue-spinkit'
    import vSelect from 'vue-select';

    import swal from 'sweetalert2';
    export default {
        mounted() {
            this.getAllInvoices();
            this.$emit('changeBalance', {data: 100});
            this.ExWindow = window;
            axios.get('/calculate-currency')
                .then(({data}) =>{
                    this.currencies = data.currencies
                })
        },
        props: {
        },
        data: function (){
            return {
                country:{id:1,name: this.ExWindow ? this.ExWindow.translator('common.turkey') : '',label:this.ExWindow ? this.ExWindow.translator('common.turkey') : ''},
                selected: [],
                Invoices: [],
                balance: 0,
                closeModal: false,
                spinner: false,
                ExWindow: null,
                currencies: [],
                select_all: null,
            }
        },
        computed: {

            currencies_object: function(){
                let obj = {};
                this.currencies.forEach((cur) =>{
                    obj[cur.name] = cur;
                })
                return obj;
            },
            countries1: function(){
                return  [{id:1,name: this.ExWindow ? this.ExWindow.translator('common.turkey') : '',label:this.ExWindow ? this.ExWindow.translator('common.turkey') : ''},{id:2,name: this.ExWindow ? this.ExWindow.translator('common.usa') : '',label:this.ExWindow ? this.ExWindow.translator('common.usa') : ''}];
            },
        },
        methods: {
            changeFilter(country_id){
                if(this.country_id!=country_id){
                    $("#tabContent").fadeOut("5000");
                    $("#tabContent").fadeIn("5000");
                }
                this.country.id = country_id;
                this.getAllInvoices();
                //console.log(this.country);
            },
            returnStatus(statuses,status){
                if(statuses !== null){
                    let statuses1=statuses.split(",");
                    for(let i = 0; i < statuses1.length; i++){
                        if(statuses1[i]==status) return true;
                    }
                }
            },
            returnStatusDate(dates,statuses,status){
                if(dates !== null){
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
                }
            },
            payPrice(){

                let price = (this.currencies_object['usd-azn'].val * this.sumDeliveryPrice() ).toFixed(2);
                if(price>0) {
                    if (parseFloat(this.balance, 2) >= price) {
                        swal({
                            title: 'Əminsizmi?',
                            text: "Ödəmə əməliyyatı!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Bəli!',
                            cancelButtonText: 'Xeyr!'
                        }).then((result) => {
                            if(result.value===true){
                                let checkedBoxes = [];
                                for(let i = 0; i < this.selected.length; i++){
                                    checkedBoxes.push(this.selected[i].id)
                                }
                                axios.post('/user-panel/pay-invoice-price',{price:price, currency:'azn', invoices:checkedBoxes })
                                    .then(data=>{
                                        this.getAllInvoices();
                                        swal('Ödənildi!','success');
                                        this.select_all=null;
                                        this.selected=[];
                                    });
                            }
                        });
                    } else {
                        swal({
                            title: this.ExWindow && this.ExWindow.translator('panel-errors.insufficient_balance'),
                            text: this.ExWindow && this.ExWindow.translator('panel-errors.click_to_fill_balance'),
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: this.ExWindow ? this.ExWindow.translator('panel-errors.yes') : '',
                            cancelButtonText: this.ExWindow ? this.ExWindow.translator('panel-errors.no') : ''
                        }).then((result) => {
                            if (result.value) {
                                this.$router.push('/balance');
                            }
                        });
                    }
                }
            },
            sumDeliveryPrice(){
                let sum = 0
                for(let i = 0; i < this.selected.length; i++){
                    sum += parseFloat(this.selected[i].amount)
                }
                return sum;
            },
            selectAll() {
                if(!this.select_all){
                    this.selected = [];
                    for (let invoice of this.Invoices) {
                        if( invoice.is_paid == 0 && invoice.delivery_price > 0){
                            this.selected.push({
                                id: invoice.id,
                                amount: invoice.delivery_price
                            });
                        }
                    }
                } else{
                    this.selected = [];
                }

            },
            async getAllInvoices() {
                this.spinner = true;
                const test  = await this.getInvoices();
                this.Invoices = test.data.data;
                this.Invoices.map((loop) => {
                    if(loop.courier) {
                        return loop.courierPrice = loop.courier.delivery_type == 1? 4: 10;
                    } else {
                        return loop.courierPrice = 0;
                    }
                });
                this.balance = parseFloat(test.data.balance).toFixed(2);
                this.spinner = false;
                // console.log(this.Invoices)
            },
            getInvoices() {
                var path = '/site/track/undefined?country_id='+this.country.id;
                return  axios.get(path);

            },
            payEvent(event) {
                // console.log(event);
                var path = '/user-panel/pay';
                axios.post(path, event).then((response) => {
                    if(response.data.code === 1602) {
                        swal('Artıq ödənilib!')
                    }
                    this.$emit('changeBalance', {});
                    setTimeout(() => {
                        console.log('before');
                        console.log(this.closeModal);

                        this.closeModal = false;
                        console.log('after');
                        console.log(this.closeModal);
                    },2000);
                    setTimeout(() => {
                        this.getAllInvoices();
                    },4000);
                }).catch((e) => {
                    console.log(e);
                });
            }
        },
        components: {
            vSelect

        }
    }

</script>
<style scoped>


</style>
