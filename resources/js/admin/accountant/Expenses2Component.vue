<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <form action="...">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <datepicker format="yyyy-MM-dd" input-class="form-control" v-model="filter.begin_date" placeholder="Başlanğıç tarixi"></datepicker>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <datepicker format="yyyy-MM-dd" input-class="form-control" v-model="filter.end_date" placeholder="Bitmə tarixi"></datepicker>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <button type="button" class="btn-effect" @click="checkForm()">Axtar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>{{cat_name}}</h4>
                <ol class="breadcrumb">
                    <li><a @click="getExpenses(0)">Xərclərə qayıt</a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-12 tab-content">
            <div class="block table-block">
                <div>
                    <div class="col-md-3 block-th">Hesabın adı <button @click="add_expense_type = true" class="btn btn-default">+</button></div>
                    <div class="col-md-3 block-th">İlkin qalıq <button @click="add_expense = true" class="btn btn-default">+</button></div>
                    <div class="col-md-3 block-th">Dövriyyə</div>
                    <div class="col-md-3 block-th">Son qalıq</div>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Kod</th>
                        <th>Adı </th>
                        <th>Kredit</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Debit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="expense in expenses">
                        <td>{{ expense.code}}</td>
                        <td><a @click="getExpenses(expense.id)">{{ expense.parent ? expense.parent :expense.name}}</a></td>

                        <td>{{ expense.total_amount_first-expense.paid_amount_first >= 0 ?  expense.total_amount_first-expense.paid_amount_first : 0 }}</td>
                        <td>{{ expense.total_amount_first-expense.paid_amount_first <= 0 ?  expense.paid_amount_first-expense.total_amount_first : 0 }}</td>
                        <td>{{ expense.total_amount-expense.paid_amount >= 0 ?  expense.total_amount-expense.paid_amount : 0 }}</td>
                        <td>{{ expense.total_amount-expense.paid_amount <= 0 ?  expense.total_amount-expense.paid_amount : 0 }}</td>
                        <td>{{ (expense.total_amount_first+expense.total_amount)-(expense.paid_amount_first+expense.paid_amount) >= 0 ? (expense.total_amount_first+expense.total_amount)-(expense.paid_amount_first+expense.paid_amount) : 0 }}</td>
                        <td>{{ (expense.total_amount_first+expense.total_amount)-(expense.paid_amount_first+expense.paid_amount) <= 0 ? (expense.paid_amount_first+expense.paid_amount)-(expense.total_amount_first+expense.total_amount) : 0 }}</td>
                    </tr>
                    <!--<tr v-for="type in expenses_types.list">
                        <td>{{type.code}}</td>
                        <td>{{type.name}} </td>
                        <td>Debet</td>
                        <td>Kredit</td>
                        <td>Debet</td>
                        <td>Kredit</td>
                        <td>Debet</td>
                        <td>Kredit</td>
                    </tr>-->
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="add_expense_type" class="modal fade in active" id="add_expense_type" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="add_expense_type = false" type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Yeni xərc tipi əlavə et</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Kateqoriya:</label>
                                    <select v-model="expenses_types.cat_id" class="form-control">
                                        <option value="0">Əsas</option>
                                        <option v-if="expense_type.cat_id == 0" v-for="expense_type in expenses_types.list" :value="expense_type.id">{{ expense_type.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kod</label>
                                    <input v-model="expenses_types.code" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ad</label>
                                    <input v-model="expenses_types.name" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button @click="addExpenseType" class="btn btn-effect">Əlavə et</button>
                            </div>
                        </div>
                        <div class="row errors">
                            <div class="col-md-12">
                                <p v-for="error in expenses_types.errors" class="text-danger">{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div v-if="add_expense" class="modal fade in active" id="add_expense" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="add_expense = false" type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Yeni xərc əlavə et</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Xərc tipi:</label>
                                    <select v-model="expense.type" class="form-control">
                                        <optgroup v-for="expense_type in expenses_types.list" :label="expense_type.name">
                                            <option v-if="expense_type.list==null" :value="expense_type.id">{{ expense_type.name }}</option>
                                            <option v-else v-for="expense_type_child in expense_type.list" :value="expense_type_child.id">{{ expense_type_child.name }}</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hesab:</label>
                                    <select v-model="expense.account" class="form-control">
                                        <option :value="0">Debet</option>
                                        <option v-for="account in accounts" :value="account.id">{{ account.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ödənməli məbləğ</label>
                                    <input v-model="expense.amount" type="number" step="0.01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ödənən məbləğ</label>
                                    <input v-model="expense.paid" type="number" step="0.01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tarix</label>
                                    <input v-model="expense.date" type="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Şərh:</label>
                                    <textarea class="form-control" rows="3" v-model="expense.comment"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button @click="addExpense" class="btn btn-effect">Əlavə et</button>
                            </div>
                        </div>
                        <div class="row errors">
                            <div class="col-md-12">
                                <p v-for="error in expense.errors" class="text-danger">{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import Menu from './AccountantMenuComponent'
    import Datepicker from 'vuejs-datepicker'
    import auth from '../auth.js'


    export default {
        name: "ExpensesComponent",
        data: function(){
            return {
                accounts: [],
                expenses: [],
                add_expense_type: false,
                add_expense: false,
                expenses_types: {
                    name: '',
                    code: '',
                    cat_id: 0,
                    list: [],
                    errors: []
                },
                expense: {
                    type: null,
                    account: null,
                    amount: null,
                    comment: '',
                    date: '',
                    status: 'kredit',
                    errors: []
                },
                filter: {
                    id: '',
                    begin_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1).toLocaleString("en-US", {timeZone: "Asia/Baku"}),
                    end_date: new Date().toLocaleString("en-US", {timeZone: "Asia/Baku"}),
                },
                cat_id: '',
                cat_name: 'Bütün xərclər'
            }
        },
        methods: {
            addExpense(){
                this.expense.errors = [];
                if(!this.expense.amount > 0){
                    this.expense.errors.push('Məbləği qeyd edin');
                }
                if(this.expense.errors.length === 0 ){
                    axios.post('/cp/expense', {
                        expense: this.expense
                    })
                    .then(() => {
                        this.add_expense = false;
                        this.expense.amount = null;
                        this.expense.comment = '';
                        this.getExpenses();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                }
            },
            addExpenseType(){
                this.expenses_types.errors = [];
                if(!this.expenses_types.name){
                    this.expenses_types.errors.push('Hesab adı qeyd ediməyib')
                }
                if (!this.expenses_types.code){
                    this.expenses_types.errors.push('Hesab kodu qeyd ediməyib')
                }
                if(this.expenses_types.errors.length ==0){
                    axios.post('/cp/expense/type', {
                        cat_id: this.expenses_types.cat_id,
                        name: this.expenses_types.name,
                        code: this.expenses_types.code,
                    })
                        .then(() => {
                            this.add_expense_type = false;
                            this.expenses_types.code = '';
                            this.expenses_types.name = '';
                            this.getExpenseTypes();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            },
            getExpenseTypes(){
                axios.get('/cp/expense/types')
                    .then(({data}) => {
                        this.expenses_types.list = data;
                        console.log(this.expenses_types.list);
                        //this.expense.type = this.expenses_types.list[0].id;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },


            checkForm () {
                this.errors = {};
                if (this.isEmpty(this.errors)) {
                    this.getExpenses(this.filter.id);
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
            getExpenses(id=0){
                this.filter.id = id;
                if(id>0 && this.expenses[this.filter.id]!=null){
                    this.cat_name = this.expenses[this.filter.id].name;
                }else{
                    this.cat_name = 'Bütün Xərclər';
                }
                axios.get('/cp/expenses',{params:this.filter})
                    .then(({data}) => {
                        this.expenses = data
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },


            getAccounts(){
                axios.get('/cp/expense/accounts')
                    .then(({data}) => {
                        this.accounts = data;
                        this.expense.account = this.accounts[0].id;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        },
        mounted(){
           this.getExpenseTypes();
           this.getAccounts();
           this.getExpenses();
        },
        components: {
            Menu,
            Datepicker
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','accountant'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .table-block{
        padding-top: 0;
    }
    thead{
        background: #929292;
        color: #fff;
    }
    th, td{
        border-right: 1px solid #d8d8d8 !important;
        width: 12.5% !important;
    }
    th{
        padding: 13px 40px !important;
    }
    .block-th {
        /*display: inline-block;*/
        /*width: calc(25% - 3px);*/
        border-right: 1px solid #d8d8d8;
        padding: 15px 30px;
    }
    .table > tbody > tr > td:last-child, .table > thead > tr > th:last-child{
        text-align: left;
        padding-left: 10px !important;
    }
    .content .table .btn-effect{
        margin-left: 20px;
    }
    .btn-default{
        padding: 0px 6px;
        background: #f69679;
        border: none;
        margin-left: 10px;
        line-height: 15px;
        margin-bottom: 3px;
        color: #fff;
    }
    .btn-default:hover{
        background: #f69679;
        border: none;
        color: #fff;
    }
    #add_expense,#add_expense_type{
        display: block;
        top: 30%;
    }
    #add_expense{
        top: 15%;
    }
    #add_expense .errors{
        margin-top: 10px;
    }
    .tab-content .table > tbody > tr > td {
        padding: 10px 20px;
    }


</style>
