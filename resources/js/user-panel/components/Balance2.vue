<template>
    <div>
        <div class="right-side col-md-9 col-sm-12 col-xs-12">
            <div class="row">
                <div class="balans-card col-md-4 col-sm-4 col-xs-12">
                    <img src="/front/img/balans-card.png" class="img-responsive" alt="balans-card">
                    <span class="balans">{{balance}} AZN</span>
                    <span class="balans-time">01.12.2018</span>
                </div>
                <div class="balans-artir col-md-8 col-sm-8 col-xs-12">
                    <div class="block">
                        <form method="POST" action="/user-panel/increase-balance">
                            <div style="margin-bottom: 27px;">
                                <label for="countlink" style="margin-bottom: 3px;">{{ExWindow ? ExWindow.translator('panel-errors.increase_for_payment'): ''}}</label>
                                <span>{{ExWindow ? ExWindow.translator('panel-errors.balance_desc') : ''}}</span>
                            </div>
                            <input required type="number" name="amount" class="form-control" id="countlink"
                                   placeholder="Məbləğ">
                            <button type="submit" class="btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.increase_balance'): ''}}</button>
                            <span>min: 1 AZN  -  max: 50AZN</span>
                        </form>
                    </div>
                </div>
            </div>
            <div class="block tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a v-on:click="getProfit()" href="#medaxil" aria-controls="medaxil"
                                                              role="tab" data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.import'): ''}}</a></li>
                    <li role="presentation"><a v-on:click="getExpense()" href="#mexaric" aria-controls="mexaric" role="tab"
                                               data-toggle="tab">{{ExWindow ? ExWindow.translator('panel-errors.export'): ''}}</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="medaxil">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.operation'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.amount_cash'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.date'): ''}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="data in profitData.data" >
                                    <td>{{ExWindow ? ExWindow.translator('panel-errors.import'): ''}}</td>
                                    <td>+ {{data.amount }} AZN</td>
                                    <td>{{data.updated_at}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <pagination  :pagination="profitData"
                                             @paginate="getProfit()"
                                             :offset="4">
                            </pagination>
                        </div>

                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="mexaric">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.operation_type'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.operation'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.amount_cash'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.date'): ''}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="data in expenseData.data">
                                    <td v-if="data.invoice_id"> {{ExWindow ? ExWindow.translator('panel-errors.courier_delivery'): ''}}</td>
                                    <td v-if="!data.invoice_id"> {{ExWindow ? ExWindow.translator('panel-errors.invoice_delivery'): ''}}</td>
                                    <td v-if="data.invoice_id" >
                                        {{data.product_type_name? data.product_type_name :data.name}}
                                    </td>
                                    <td v-if="!data.invoice_id" >
                                        {{data.city}}
                                        {{data.district}}
                                        {{data.street}}
                                        {{data.home}}
                                    </td>
                                    <td>- {{data.delivery_cash}} AZN ( {{ExWindow ? ExWindow.translator('panel-errors.balance_now'): ''}} : {{data.user_balance}} AZN)</td>
                                    <td>31.12.2018</td>
                                </tr>
                                </tbody>
                            </table>
                            <pagination  :pagination="expenseData"
                                         @paginate="getExpense()"
                                         :offset="4">
                            </pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import pagination from '../components/shared/pagination'
    export default {
        mounted() {
            this.getBalance();
            this.ExWindow = window;
            window.scrollTo(0,0);
            this.getProfit(1);
            this.getExpense(1);

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
                expenseData: {}
            }
        },
        methods: {
            async getBalance() {
                const test  = await this.getBalanceData();
                this.balance = test.data;
            },
            getBalanceData() {
                var path = '/user-panel/get-balance';
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
        },
        components: {
            pagination
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
