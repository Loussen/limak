<template>
    <div class="col-md-9 col-sm-11 col-xs-11 coin">
    <div class="row relative">
        <div class="balance-block col-md-5 col-sm-5 col-xs-12">
            <div class="balance">
                <img src="/front/new/img/balans-mob.png" alt="balans" class="img-responsive">
                <div class="balance-count">
                    <span>{{ExWindow ? ExWindow.translator('panel-errors.balance') : '' }}</span>
                    <br>
                    <span class="count">{{balance}} <sup>&#8378;</sup></span>
                </div>
                <div class="balance-date">
                    <span>{{ExWindow ? ExWindow.translator('panel-errors.last_add_date') : '' }}</span> <br>
                    <span class="time" v-if="logData[0]">{{logData[0].created_at.split( ' ')[0]}}</span>
                </div>
                <div class="balance-text">
                    {{ExWindow ? ExWindow.translator('panel-errors.tl_balance_use') : ''}}
                    <p><b>{{ExWindow ? ExWindow.translator('panel-errors.balance_text2') : ''}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-7 col-xs-12 right-side increase-balance">
            <div class="right-top block">
                <div class="row">
                    <div class="col-md-9 col-sm-9 col-xs-9">
                        <h4>{{ExWindow ? ExWindow.translator('panel-errors.milli_on'): ''}}</h4>
                        <form action="">
                            <div class="input-group border-radius">
                                <label>
                                    <input :value="userCode" type="text" name="code" class="form-control inputText" placeholder=" " readonly>
                                    <p>{{ExWindow ? ExWindow.translator('panel-errors.account_number'): ''}} </p>
                                </label>
                            </div>
                        </form>
                        <p>
                            {{ExWindow ? ExWindow.translator('panel-errors.milli_on_tl_text_1'): ''}}
                            {{ userCode }}
                            {{ExWindow ? ExWindow.translator('panel-errors.milli_on_tl_text_2'): ''}}
                            <br><br>
                            {{ExWindow ? ExWindow.translator('panel-errors.milli_on_tl_description'): ''}}
                        </p>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <img src="/front/new/img/terminal.png" alt="terminal" class=" center-block">
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-xs-12 basket">
                <div class="order-body">
                    <div class="block">
                        <div class="coin-table">
                            <div class="select-all">
                                <div class="filter">
                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.filter'): ''}}</span>
                                    <!--                                    <div class="input-group border-radius">-->
                                    <!--                                        <select @change="getLogBalance()" v-model="filter_type" class="form-control selectpicker" name="olke" title="Seçilməyib">-->
                                    <!--                                            <option :value="0">Hamısı</option>-->
                                    <!--                                            <option :value="2">Mədaxil</option>-->
                                    <!--                                            <option :value="1">Məxaric</option>-->
                                    <!--                                        </select>-->
                                    <!--                                    </div>-->
                                    <div class="input-group border-radius">
                                        <v-select @change="getLogBalance()" v-model="filter_type" label="name" :options="balances"></v-select>
                                    </div>
                                </div>
                                <div class="right-side">
                                    <button type="button" class="transparent">
                                        <img src="/front/new/img/excel.png" alt="excel">
                                        <span>{{ExWindow ? ExWindow.translator('panel-errors.save_in_excel'): ''}}</span>
                                    </button>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.operation'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.amount_cash'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.date'): ''}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="data in logData" >
                                    <td>{{data.message}}</td>
                                    <td>{{data.money }} Tl</td>
                                    <td>{{data.created_at}}</td>
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
    import pagination from '../components/shared/pagination'
    import vSelect from 'vue-select'
    export default {
        mounted() {
            this.getUserCode();
            this.getBalance();
            this.ExWindow = window;
            window.scrollTo(0,0);
            /*this.getProfit(1);
            this.getExpense(1);*/
            this.getLogBalance(0);
            this.balances =[{id:0 ,name: this.ExWindow ? this.ExWindow.translator('panel-errors.all'): '' },{id:1 ,name: this.ExWindow ? this.ExWindow.translator('panel-errors.outlay'): ''} ,{id:2 ,name: this.ExWindow ? this.ExWindow.translator('panel-errors.receipt'): ''}];
            this.filter_type.name = this.ExWindow ? this.ExWindow.translator('panel-errors.all'): ''
        },
        beforeDestroy() {

        },
        props: {

        },
        data: function (){
            return {
                balance: 0.00,
                ExWindow: null,
                profitData: {},
                expenseData: {},
                logData: {},
                filter_type: {
                    id: 0,
                    name: ''
                },
                balances: [],
                userCode: '',
            }
        },
        methods: {
            async getBalance() {
                const test  = await this.getBalanceData();
                this.balance = test.data;
            },
            getBalanceData() {
                var path = '/user-panel/get-try-balance';
                return  axios.get(path);

            },
            getProfit() {
                var path = '/user-panel/get-profit?page=' + this.profitData.current_page;
                return  axios.get(path).then((response) => {
                    console.log(response);
                    this.profitData = response.data.data;
                }).catch((e) => {
                    console.log(e);
                });
            },
            getExpense() {
                var path = '/user-panel/get-expense?page=' + this.expenseData.current_page;
                return  axios.get(path).then((response) => {
                    console.log(response);
                    this.expenseData = response.data;
                }).catch((e) => {
                    console.log(e);
                });
            },

            getLogBalance() {
                console.log('test')
                var path = '/user-panel/get-log-balance?type=' + this.filter_type.id + '&balance_type=try';
                axios.get(path).then((response) => {
                    this.logData = response.data;
                }).catch((e) => {
                    console.log(e);
                });
            },

            getUserCode() {
                var path = '/user-panel/get-user-code';
                axios.get(path).then((response) => {
                    this.userCode = response.data;
                }).catch((e) => {
                    console.log(e);
                });
            },
        },
        components: {
            pagination,
            vSelect
        }
    }

</script>
<style scoped>
    .kabinet .nav-tabs li {
        width: 83px;
    }
    .kuryer-kabinet .input-group-addon {
        position: absolute;
        background: transparent;
        right: 8px;
        top: 50%;
        z-index: 999;
        border: none;
        color: #707c88;
        font-size: 12px;
    }
    .kuryer-kabinet .input-group .form-control:not(:first-child):not(:last-child) {
        border-radius: 17px;
    }
    .count-end button {
        width: 140px;
        padding: 6px 0;
    }


</style>
