<template>
    <div class="col-md-9 col-sm-11 col-xs-11 coin">
        <div class="row relative">
            <div class="balance-block col-md-8 col-sm-12 col-xs-12">
                <div class="balance">
                    <picture>
                        <source media="(max-width: 600px)" class="img-responsive"
                                srcset="/front/new/img/balans-mob.png">
                        <img src="/front/new/img/balans.png" alt="balans" class="img-responsive">
                    </picture>
                    <div class="balance-count">
                        <span>{{ExWindow ? ExWindow.translator('panel-errors.premium_last_day') : '' }}</span>
                        <br>
                        <span class="count">{{logData.day}} <sup>{{ExWindow ? ExWindow.translator('panel-errors.day') : '' }}</sup></span>
                    </div>
                    <div class="balance-date">
                        <span>{{ExWindow ? ExWindow.translator('panel-errors.premium_nextday') : '' }}</span> <br>
                        <span class="time" v-if="logData">{{logData.end}}</span>
                    </div>
                    <div class="balance-text">
                        {{ExWindow ? ExWindow.translator('panel-errors.premium_text') : '' }}
                        <p><b>
                            {{ExWindow ? ExWindow.translator('panel-errors.premium_text2') : '' }}
                        </b></p>
                    </div>
                    <router-link to="/balance">
                        <a href="#" class="border-btn btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.premium_submit') : '' }}</a>
                    </router-link>

                </div>
            </div>
            <div class="col-md-4 col-xs-12 right-side">
                <div class="right-top block">
                    <last30dayskg></last30dayskg>
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


        <div class="row">
            <div class="col-xs-12 basket">
                <div class="order-body">
                    <div class="block">
                        <div class="coin-table">
                            <div class="select-all">
                                <h4>Hərəkət cədvəli</h4>
                                <!--<div class="filter">
                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.filter'): ''}}</span>
                                    <div class="input-group border-radius">
                                        <v-select @change="getLogBalance()" v-model="filter_type" label="name" :options="balances"></v-select>
                                    </div>
                                </div>
                                <div class="right-side">
                                    <button type="button" class="transparent">
                                        <img src="/front/new/img/excel.png" alt="excel">
                                        <span>{{ExWindow ? ExWindow.translator('panel-errors.save_in_excel'): ''}}</span>
                                    </button>
                                </div>-->
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.premium_begin_date'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.premium_money'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.premium_month'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.premium_end_date'): ''}}</th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="data in logData.result" >
                                    <td>{{data.created_at}}</td>
                                    <td>{{data.money}}</td>
                                    <td>{{data.month}}</td>
                                    <td>{{data.updated_at }}</td>
                                </tr>
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
    import last30dayskg from './shared/last30dayskg';
    import currency from './shared/currency';
    import dailyCurrency from './shared/dailyCurrency';
    import vSelect from 'vue-select'
    export default {
        name: "PremiumPanel",
        mounted(){
            this.ExWindow = window;
            this.balances =[{id:0 ,name: this.ExWindow ? this.ExWindow.translator('panel-errors.all'): '' },{id:1 ,name: this.ExWindow ? this.ExWindow.translator('panel-errors.outlay'): ''} ,{id:2 ,name: this.ExWindow ? this.ExWindow.translator('panel-errors.receipt'): ''}]
            //this.getUserData();
            this.getLogPremium();
            this.filter_type.name = this.ExWindow ? this.ExWindow.translator('panel-errors.all'): ''
           /* axios.get('/calculate-currency')
                .then(({data}) =>{
                    this.currencies = data.currencies
                })*/
        },
        data: function (){
            return {
                User: Object,
                ExWindow: null,
                currencies: [],
                Invoices: [],
                profitData: {},
                expenseData: {},
                logData: {},
                filter_type: {
                    id: 0,
                    name: ''
                },
                balances: [],
            }
        },
        methods: {
            async getUserData() {
                const test  = await this.requestUserData();
                this.User = test.data;
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';
                return  axios.get(path);

            },


            async getBalance() {
                const test  = await this.getBalanceData();
                this.balance = test.data;
            },
            getBalanceData() {
                var path = '/user-panel/get-balance';
                return  axios.get(path);

            },

            getLogPremium() {
                var path = '/user-panel/get-log-premium';
                axios.get(path).then((response) => {
                    this.logData = response.data;
                }).catch((e) => {
                    console.log(e);
                });
            },


        },
        components: {
            last30dayskg,
            currency,
            dailyCurrency,
            vSelect
        }
    }
</script>

<style scoped>
    @media only screen and (max-width: 991px) {
        .coin .balance-block {
            margin-bottom: 20px;
        }
    }
</style>