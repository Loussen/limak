<template>
    <div class="row">
        <div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <div class="row">
                    <div class="col-md-10 col-sm-10 col-xs-12">
                        <div class="row">
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Bu tarixdən</label>
                                    <input v-on:keyup.enter="checkForm()" type="date" class="form-control"  placeholder="Bu tarixdən" v-model="filter.begin_date">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Bu tarixə</label>
                                    <input v-on:keyup.enter="checkForm()" type="date" class="form-control" placeholder="Bu tarixə" v-model="filter.end_date">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <label>Müştəri İD</label>
                                    <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Müştəri kodu"  v-model="filter.user_id">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <label>Məbləğ</label>
                                    <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Məbləğ"  v-model="filter.payment">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Operator</label>
                                    <select v-on:keyup.enter="checkForm()" class="form-control" id="admin_id" v-model="filter.admin_id">
                                        <option value="0">Bütün operatorlar</option>
                                        <option v-for="admin in admins" :value="admin.id">{{ admin.name }} {{ admin.surname }}</option>
                                    </select>
                                </div>
                            </div>
                            <!--<div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <datepicker format="yyyy-MM-dd" input-class="form-control" v-model="filter.begin_date" placeholder="Başlanğıç tarixi"></datepicker>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <datepicker format="yyyy-MM-dd" input-class="form-control" v-model="filter.end_date" placeholder="Bitmə tarixi"></datepicker>
                                </div>
                            </div>-->
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-12">
                        <label> </label>
                        <button type="button" class="btn-effect" @click="checkForm()">Axtar</button>
                    </div>
                    <div class="col-md-1">
                        <label> </label>
                        <button type="button" class="btn-effect" @click="checkForm2()">Excel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>{{account.name}} hesabı ətraflı</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/accountant">Hesablar</router-link></li>
                    <li class="active">Hesab əməliyyatları</li>
                </ol>
            </div>
            <h4><b>{{begin_date}}</b> və <b>{{end_date}}</b> tarixləri arasında <br /><br />
                <b>ödəniş sayı: {{accountLogs.total}}</b> <br /> <br />
                <b v-for="payment in paymentLogs">{{payment.type}} ödəniş: {{payment.payment}}<br /> <br /></b>
            </h4>

        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ödəniş məbləği</th>
                        <th>Ödənişdən qabaq</th>
                        <th>Ödənişdən sonra</th>
                        <th>Səbəb</th>
                        <th>Admin</th>
                        <th>İstifadəçi</th>
                        <th>Tarix</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="log in accountLogs.data">
                        <td>{{log.id}}</td>
                        <td><span v-if="log.type=='minus'">-</span><span v-else-if="log.type=='plus'">+</span>{{ log.payment}}</td>
                        <td>{{ log.before_payment }}</td>
                        <td>{{ log.after_payment }}</td>
                        <td>{{ log.comment }}</td>
                        <td v-if="log.admin">{{ log.admin.name }} {{ log.admin.surname }}</td>
                        <td v-else></td>
                        <td v-if="log.user">{{ log.user.name }} {{ log.user.surname }} <br />{{ log.user.id }}</td>
                        <td v-else></td>
                        <td>{{ log.created_at}}</td>
                        <td  v-if="!editable_status[log.id]">
                            <span @click="openEditStatus(log)">{{account.name}} </span>
                        </td>
                        <td v-else>
                            <select v-model="log.account_id">
                                <option v-for="accountr in accounts" :value="accountr.id">{{ accountr.name }}</option>
                            </select>
                            <button @click="changeAccount(log)">
                                Dəyiş
                            </button>
                            <button @click="editable_status=[]">
                                bagla
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getLogs(accountLogs.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in accountLogs.last_page">
                            <li v-if="page ==1 && accountLogs.current_page==1" class="active">
                                <a @click.prevent="getLogs( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getLogs( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(accountLogs.current_page - page)> 3 && Math.abs(accountLogs.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(accountLogs.current_page - page)<= 3 && accountLogs.current_page == page" class="active">
                                <a @click.prevent="getLogs( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(accountLogs.current_page - page)<= 3 ">
                                <a @click.prevent="getLogs(  page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == accountLogs.last_page">
                                <a @click.prevent="getLogs( page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="accountLogs.current_page < accountLogs.last_page" @click.prevent="getLogs( accountLogs.current_page + 1)" href="#" aria-label="Next">
                                <span aria-hidden="true">NÖVBƏTİ</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!--<div v-for="order in incomingOrders" class="modal fade look" :id=" 'user'+order.user_id" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">-->
            <!--<div class="modal-dialog modal-sm" role="document">-->
                <!--<div class="modal-content">-->
                    <!--<div class="modal-header">-->
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="https://limak.az/admin/img/Forma 1.png" alt="close"></button>-->
                    <!--</div>-->
                    <!--<div class="modal-body">-->
                        <!--<h4>Sİfarİşİ qəbul et</h4>-->
                        <!--<img src="/admin/new/img/gelen-sifarisler.png" alt="sifarish">-->
                        <!--<a href="#" @click.prevent="acceptOrder(order.user_id)" class="btn-effect" >Qəbul et</a>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker'

    export default {
        name: "AccountLogsComponent",
        data: function(){
            return {
                account: null,
                paymentLogs: null,
                accountLogs: {},
                begin_date:'',
                end_date: '',
                filter: {
                    id: '',
                    //begin_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1).toLocaleString("en-US", {timeZone: "Asia/Baku"}),
                    begin_date:'',//new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDay()),
                    end_date: '',//new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDay()),
                    //end_date: new Date().toLocaleString("en-US", {timeZone: "Asia/Baku"}),
                    user_id: '',
                    admin_id: 0,
                    payment: ''
                },
                editable_status: [],
                admins: '',
                accounts: ''
            }
        },
        mounted(){
            this.getAccounts();
            this.getLogs();
            this.getAdmins()
        },
        methods: {
            getLogs(page_id=1){
                axios.get(`/cp/accounts/logsAccount/${this.$route.params.id}?page=${page_id}`,{params: this.filter})
                    .then((response) => {

                        this.accountLogs = response.data.logs;
                        this.paymentLogs = response.data.payment_logs;
                        this.account = response.data.account;
                        this.begin_date = response.data.begin_date;
                        this.end_date = response.data.end_date;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getPrint(){
                window.location.href = `/cp/accounts/logsAccountPrint/${this.$route.params.id}?begin_date=${this.filter.begin_date}&end_date=${this.filter.end_date}&user_id=${this.filter.user_id}&admin_id=${this.filter.admin_id}&payment=${this.filter.payment}`;
            },
            openEditStatus(log){
                this.editable_status.push(log.id);
                this.editable_status[log.id] = true;
            },
            getAccounts(){
                axios.get('/cp/accounts')
                    .then(({ data }) => {
                        this.accounts = data.accounts[3].accounts;
                        console.log(this.accounts);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            changeAccount(log){
                axios.post('/cp/accounts/changeAccountId', {
                    id: log.id,
                    account_id: log.account_id
                }).then(({ data }) => {
                    this.editable_status = [];
                    this.getLogs();
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getAdmins(){
                axios.get('/cp/admin/getAdmins')
                    .then(({ data }) => {
                        this.admins = data.admins;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            checkForm () {
                this.errors = {};
                if (this.isEmpty(this.errors)) {
                    this.getLogs();
                }
            },
            checkForm2 () {
                this.errors = {};
                if (this.isEmpty(this.errors)) {
                    this.getPrint();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
        },
        components: {
            Datepicker
        },
    }
</script>

<style scoped>

</style>