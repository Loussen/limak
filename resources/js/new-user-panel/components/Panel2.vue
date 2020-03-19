<template>
    <div class="col-md-9 col-sm-11 col-xs-11">
        <div class="row relative">
            <div class="balance-block col-md-8 col-sm-12 col-xs-12">
                <div class="balance">
                    <picture>
                        <source media="(max-width: 600px)" class="img-responsive"
                                srcset="/front/new/img/balans-mob.png">
                        <img src="/front/new/img/balans.png" alt="balans" class="img-responsive">
                    </picture>
                    <div class="balance-count">
                        <span>{{ExWindow ? ExWindow.translator('panel-errors.balance') : '' }}</span>
                        <br>
                        <span class="count">{{User.balance}} <sup>M</sup></span>
                    </div>
                    <div class="balance-date">
                        <span>{{ExWindow ? ExWindow.translator('panel-errors.last_add_date') : '' }}</span> <br>
                        <span v-if="User.last_balance_add" class="time">{{ User.last_balance_add.updated_at }}</span>
                    </div>
                    <div class="balance-text">{{ExWindow ? ExWindow.translator('panel-errors.balance_text') : '' }}</div>
                    <router-link to="/balance">
                        <a href="#" class="border-btn btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.increase_balance') : '' }}</a>
                    </router-link>

                </div>
            </div>
            <div class="col-md-4 hidden-sm hidden-xs right-side">
                <div class="right-top block">
                    <last30days></last30days>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="block follow-order">
                    <div class="follow-head">
                        <h4>Ən son sifarişi izlə</h4>
                        <div class="right-side">
                            <router-link to="/track">Hamısına bax</router-link>
                            <ul>
                                <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li @click="changeSelectedInvoice(InvoiceData)" v-for="InvoiceData in Invoices" role="presentation" class="active">
                            <a style="background: url('/front/new/img/turkeyflg.png') no-repeat 15px;"
                                 aria-controls="home" role="tab" data-toggle="tab">
                                <template v-if="InvoiceData.products">{{ InvoiceData.products.product_type_name }}</template>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div v-if="selectedInvoice.id == InvoiceData.id" v-for="InvoiceData in Invoices" role="tabpanel" class="tab-pane fade in active" id="home">
                            <div class="panel-top">
                                <label class="check-button">
                                    <span class="web check-text"> Ödəniş üçün seç</span>
                                    <span class="mobile check-text">Seç</span>
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <ul class="list">
                                    <li>Ölkə: <span>Türkiyə</span></li>
                                    <li>Mağaza: <span v-if="InvoiceData.products">{{ InvoiceData.products.shop_name }}</span></li>
                                </ul>
                            </div>
                            <div class="all-fixed">
                                <div class="follow-line">
                                    <ul>
                                        <li  class="active">
                                            <span class="shopping-bag"></span>
                                            <p><b>Sifariş verildi</b>{{ orderDate(hasCompleted( InvoiceData.dates,1 ).action_date) }}</p>
                                        </li>
                                        <li :class="hasCompleted( InvoiceData.dates,2 ) ? 'active' : ''">
                                            <span class="order-follow"></span>
                                            <p><b>Xaricdəki anbar</b>{{ orderDate(hasCompleted( InvoiceData.dates,2 ).action_date) }}</p>
                                        </li>
                                        <li :class="hasCompleted( InvoiceData.dates,3 ) ? 'active' : ''">
                                            <span class="departures"></span>
                                            <p><b>Yoldadır</b>{{ orderDate(hasCompleted( InvoiceData.dates,3 ).action_date) }}</p>
                                        </li>
                                        <li :class="hasCompleted( InvoiceData.dates,4 ) ? 'active' : ''">
                                            <span class="warehouse"></span>
                                            <p><b>Bakı anbarı</b>{{ orderDate(hasCompleted( InvoiceData.dates,4 ).action_date) }}</p>
                                        </li>
                                        <li :class="hasCompleted( InvoiceData.dates,16 ) ? 'active' : ''">
                                            <span class="shipped"></span>
                                            <p><b>Kuryer çatdırma</b>{{ orderDate(hasCompleted( InvoiceData.dates,16 ).action_date) }}</p>
                                        </li>
                                        <li :class="hasCompleted( InvoiceData.dates,5 ) ? 'active' : ''">
                                            <span class="package"></span>
                                            <p><b>Təhvil verildi</b>{{ orderDate(hasCompleted( InvoiceData.dates,5 ).action_date) }}</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="properties">
                                    <h4 class="web">Sifarişin xüsusiyyətləri</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Bağlama sayı</th>
                                            <th>İçindəki</th>
                                            <th>Çəkisi</th>
                                            <th>Ölçüsü</th>
                                            <th>Çatdırılma qiyməti</th>
                                            <th class="web"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>4</td>
                                            <td><span v-if="InvoiceData.products">{{ InvoiceData.products.product_type_name }}</span></td>
                                            <td>{{ InvoiceData.weight }}</td>
                                            <td><span v-if="InvoiceData.width && InvoiceData.height">{{ InvoiceData.width }} sm * {{ InvoiceData.height }} sm</span></td>
                                            <td><span v-if=" currencies.length > 0 && InvoiceData.delivery_price">{{ InvoiceData.delivery_price }} $ ({{ (InvoiceData.delivery_price * currencies_object['usd-azn'].val).toFixed(2) }} AZN)</span></td>
                                            <td class="web"><a href="#" class="btn-effect border-btn">Ödə</a>
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
        </div>
        <div class="row mobile">
            <div class="col-xs-12">
                <!--<div class="block order-side text-center">
                    <p><b>Seçilən bağlama üçün ödəniş edin.</b></p>
                    <span>Cəmi 1 bağlama seçilmişdir</span>
                    <button type="button" class="btn-effect">Ödəniş et</button>
                </div>-->
                <div class="block price">
                    <currency></currency>
                </div>
                <div class="block daily">
                    <daily-currency></daily-currency>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import last30days from './shared/last30days';
    import currency from './shared/currency';
    import dailyCurrency from './shared/dailyCurrency';
    export default {
        name: "Panel",
        mounted(){
            this.ExWindow = window;
            this.getUserData();
            this.getAllInvoices().then(() =>{
                this.selectedInvoice = this.Invoices[0]
            });
            axios.get('/calculate-currency')
                .then(({data}) =>{
                    this.currencies = data.currencies
                })
        },
        data: function (){
            return {
                User: Object,
                ExWindow: null,
                currencies: [],
                Invoices: [],
                selectedInvoice: {},

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
            // selectedInvoice: function(){
            //     return this.Invoices[0]
            // }
        },
        methods: {
            changeSelectedInvoice(InvoiceData){
                this.selectedInvoice = InvoiceData;
                console.log(789)
            },
            async getUserData() {
                const test  = await this.requestUserData();
                this.User = test.data;
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';
                return  axios.get(path);

            },
            hasCompleted(dates,status){
                let selected;
                // let date = dates.filter((date) =>{
                //     date.status_id == status;
                // })
                for(let i =0; i < dates.length;i++){
                    if(dates[i].status_id == status){
                        selected = dates[i];
                    }
                }
                if(selected){
                    return selected;
                } else{
                    return false;
                }
                console.log(date)
            },
            orderDate(date){
                if(!date) return '';
                let date_str = new Date(date);
                let year = date_str.getFullYear();
                let month = date_str.getMonth() +1;
                let day = date_str.getDate();
                return day + '.'+ month + '.'+ year;
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

            },
            getInvoices() {
                var path = '/track';
                return  axios.get(path);

            },
            payEvent(event) {
                console.log(event);
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
            },
        },
        components: {
            last30days,
            currency,
            dailyCurrency
        }
    }
</script>

<style scoped>
    .follow-order .nav-tabs{
        display: flex;
    }
</style>