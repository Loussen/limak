<template>
    <div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu">
                    <h4>Kassa</h4>
                    <ol class="breadcrumb">
                        <li>
                            <router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link>
                        </li>
                        <li class="active">Kassa</li>
                    </ol>
                </div>
            </div>
            <div class="parent-block col-xs-12">
                <div class="row">
                    <div class="col-md-9 col-sm-9">
                        <div class="block kassa col-xs-12">
                            <form action="...">
                                <div class="row">
                                    <div class="col-xs-1">
                                        <img src="/admin/new/img/kassa.png" class="img-responsive" alt="kassa">
                                    </div>
                                    <div class="col-xs-11">
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" v-model="uniqid" placeholder="Müştəri kodu">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-3 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Nömrəsi">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-3 col-xs-12">
                                            <button type="button" class="btn-effect" v-on:click="searchUser()">Axtar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="right-block col-md-3 col-sm-3">
                        <div class="block">
                            <h4>KASSA MƏBLƏĞİ <span>AZN</span></h4>
                            <p>{{ cash_money }}</p>
                            <div class="block-button-list col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect green" data-toggle="modal"
                                                data-target=".receipt">MƏDAXİL
                                        </button>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect red-button" data-toggle="modal" @click="show_outlay = true"
                                                data-target=".outlay">MƏXARİC</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade receipt" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Mədaxil</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img
                                    src="/admin/new/img/Forma1.png"
                                    alt="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="receipt" class="form-control" placeholder="Məbləği daxil et">
                            </div>
                            <button type="button" class="btn-effect" v-on:click="store('plus')">mədaxil et</button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="show_outlay" class="modal fade outlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Məxaric</h4>
                            <button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close"><img
                                    src="/admin/new/img/Forma1.png"
                                    alt="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="receipt" class="form-control" placeholder="Məbləği daxil et">
                            </div>
                            <button type="button" class="btn-effect" v-on:click="store('minus')">məxaric et</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="search">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu">
                    <h4>İSTİFADƏÇİ MƏLUMATLARI</h4>
                    <ol class="breadcrumb">
                        <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                        <li><a hre @click="search = false"> Kassa</a></li>
                        <li class="active">Kassa məlumatı</li>
                    </ol>
                </div>
            </div>
            <div class="parent-block col-xs-12">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <div class="block customer-list auto-height">
                            <ul class="list-user">
                                <li>Ad, Soyad <p>{{user.name}} {{user.surname}}</p></li>
                                <li>
                                    <ul class="inline-list">
                                        <li>Müştəri kodu<p>{{user.uniqid}}</p></li>
                                        <li>Qeydiyyat tarixi<p>{{user.created_at}}</p></li>
                                        <li>Mobil telefonu<p>{{user.user_contacts[0].name}}</p></li>
                                        <li>E-mail <p>{{user.email}}</p></li>
                                        <li>Fin kodu <p>{{user.pin}}</p></li>
                                    </ul>
                                </li>
                                <li>Ünvan
                                    <p>{{user.address}}</p></li>
                            </ul>
                        </div>
                    </div>
                    <div class="right-block col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="block">
                            <h4>BALANS <span>AZN</span></h4>
                            <p>{{ user.balance}}</p>
                            <div class="block-button-list col-md-12 col-sm-12">
                                <div class="input-group number-spinner">
                                <span class="input-group-btn data-dwn">
                                    <button type="button" class="btn btn-default" data-dir="dwn" @click="addBalance('minus')">-</button>
                                </span>
                                    <input type="text" class="form-control text-center" v-model="money">
                                    <span class="input-group-btn data-up">
                                    <button type="button" class="btn btn-default" data-dir="up" @click="addBalance('plus')">+</button>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-side cash col-md-12 col-sm-12 col-xs-12">
                <div class="block text-right">
                    <button type="button" class="btn-effect" @click="check()">Çek çıxart</button>
                    <button type="button" class="btn-effect" @click="openPaymentModel()" data-toggle="modal" data-target=".look">Ödə</button>
                </div>
                <div class="block table-block">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkbox_0" v-model="select_all" @change="selectAll()">
                                    <label for="checkbox_0"></label>
                                </div>
                            </th>
                            <th>Bağlamada olan məhsul sayı</th>
                            <th>Məhsullar</th>
                            <th>Çəki</th>
                            <th>Anbarda olan yeri</th>
                            <th>Çatdırılma qiyməti</th>
                            <!--<th>Kuryer</th>-->
                            <!--<th>Ödəyəcəyi məbləğ</th>-->
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in user.stock">
                            <td>
                                <div class="checkbox">
                                    <input type="checkbox"  v-model="product.selected" :id="'checkbox_'+product.id">
                                    <label :for="'checkbox_'+product.id"></label>
                                </div>
                            </td>
                            <td>{{ product.products.quantity}}</td>
                            <td>{{ product.products.product_type_name ? product.products.product_type_name : '' }}</td>
                            <td>{{ product.weight }} Kq</td>
                            <td>{{ (product.depot_invoice)? product.depot_invoice.depot.barcode_id : ''}}</td>
                            <td v-if="product.is_paid == 0">{{ product.shipping_price }} USD ({{ (product.shipping_price * currency.usd).toFixed(2) }} AZN) </td>
                            <td v-if="product.is_paid == 1">Ödənilib</td>
                            <!--<td>10 AZN</td>-->
                            <!--<td>10 AZN</td>-->
                            <td>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!--<nav>
                        <ul class="pagination">
                            <li class="disabled"><a href="#" aria-label="Previous"><span
                                    aria-hidden="true">ƏVVƏL</span></a></li>
                            <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">NÖVBƏTİ</span>
                                </a>
                            </li>
                        </ul>
                    </nav>-->
                </div>
            </div>

            <div class="modal fade look cash" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Ödəyəcəyİ məbləğ</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="/admin/img/Forma1.png"
                                                                                                             alt="close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td>Balans</td>
                                    <td>{{ user.balance}}</td>
                                </tr>
                                <tr>
                                    <td>Çatdırılma məbləği</td>
                                    <td>{{ user_payment.shipping_price}}</td>
                                </tr>
                                <tr>
                                    <td>Ödəməli olduğu məbləğ</td>
                                    <td>{{ user_payment.total_price}}</td>
                                </tr>
                                <tr v-if="user.balance > 0">
                                    <td>Balansdan ödəyəcəyi məbləğ</td>
                                    <td><input v-model="user_payment.balance_payment" class="form-control" @change="setBalancePayment()" /></td>
                                </tr>
                                <tr style="background-color: #984b16; color: white">
                                    <td>Terminaldan ödəyəcəyi məbləğ</td>
                                    <td><input v-model="user_payment.terminal_payment" @change="setTerminalPayment()" class="form-control" /></td>
                                </tr>
                                <tr >
                                    <td>Nağd ödəyəcəyi məbləğ</td>
                                    <td><input v-model="user_payment.cash" class="form-control" readonly /></td>
                                </tr>
                                <tr>
                                    <td>Ödədiyi məbləğ</td>
                                    <td><input v-model="user_payment.paid" class="form-control" @change="setPaid()" /></td>
                                </tr>
                                <tr>
                                    <td>Qaytarılacaq pul</td>
                                    <td>{{user_payment.remainder}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="col-xs-12 text-left"><button type="button" class="btn-effect green" @click="savePayment()">ÖDƏ</button></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" style="opacity: 0" id="check" >
                <h4 style="text-align: center;font-family: Segoe UI, Helvetica;font-weight: bold; margin-top: 15px;">Ödəniş qəbzi</h4>
                <table style="margin-bottom: 15px">
                    <tr>
                        <td style="width: 250px">Ad , Soyad:</td>
                        <td>{{ user.name }} {{ user.surname }}</td>
                    </tr>
                    <tr>
                        <td style="width: 250px">Istifadechi kodu</td>
                        <td>{{ user.uniqid }}</td>
                    </tr>
                    <!--<tr>
                        <td style="width: 250px">Ş/V FİN:</td>
                        <td>{{ user.pin }}</td>
                    </tr>
                    <tr>
                        <td style="width: 250px">Ş/V Seriya nömrəsi:</td>
                        <td>{{ user.serial_number }}</td>
                    </tr>-->
                    <tr>
                        <td style="width: 250px">Anbardaki yeri:</td>
                        <td>
                            <p style="font-weight: bold">{{ depot_numbers}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 250px">Ödənilən məbləğ (AZN):</td>
                        <td>
                            {{user_payment.total_price}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 250px">Tarix:</td>
                        <td>
                            {{ date.getDate() }}/{{ date.getMonth() }}/{{ date.getFullYear() }} {{ date.getHours() }}:{{ date.getMinutes() }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="right-side col-md-12 col-sm-12 col-xs-12" v-if="!search">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Əməliyyat tipi</th>
                        <th>Ödənişdən qabaq</th>
                        <th>Ödəniş məbləği</th>
                        <th>Ödənişdən sonra</th>
                        <th>Admin</th>
                        <th>İstifadəçi</th>
                        <th>Tarix</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="log in accountLogs">
                        <td> {{ log.type }} </td>
                        <td>{{ log.before_payment }}</td>
                        <td>{{ log.payment}}</td>
                        <td>{{ log.after_payment }}</td>
                        <td v-if="log.admin">{{ log.admin.name }} {{ log.admin.surname }}</td>
                        <td v-else></td>
                        <td v-if="log.user">{{ log.user.name }} {{ log.user.surname }} <b>{{ log.user.uniqid}}</b></td>
                        <td v-else></td>
                        <td>{{ log.created_at}}</td>
                    </tr>
                    </tbody>
                </table>
                <!--<nav>-->
                <!--<ul class="pagination">-->
                <!--<li class="disabled"><a href="#" aria-label="Previous"><span-->
                <!--aria-hidden="true">ƏVVƏL</span></a></li>-->
                <!--<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>-->
                <!--<li><a href="#">2</a></li>-->
                <!--<li><a href="#">3</a></li>-->
                <!--<li><a href="#">4</a></li>-->
                <!--<li><a href="#">5</a></li>-->
                <!--<li>-->
                <!--<a href="#" aria-label="Next">-->
                <!--<span aria-hidden="true">NÖVBƏTİ</span>-->
                <!--</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</nav>-->
            </div>
        </div>
    </div>
</template>

<script>
    import {PrintElem} from "../../pipes/slug";
    import swal from 'sweetalert2';

    export default {
        name: "CashComponent",
        data() {
            return {
                show_check: false,
                cash_money: 0,
                money: 0,
                receipt: null,
                outlay: null,
                search: false,
                fullname: null,
                uniqid: null,
                depot_numbers: '',
                currency: {
                    usd: 1.7
                },
                user_payment: {
                    shipping_price: null,
                    balance_payment: null,
                    terminal_payment: null,
                    total_price: null,
                    cash: null,
                    remainder: null,
                    paid: null,
                    invoices: [],
                    user_id: null
                },
                user: {},
                show_outlay: false,
                select_all: false,
                date: new Date(),
                accountLogs: []
            };
        },
        methods: {
            getCashLogs(){
                axios.get(`/cp/accounts/logs/1`)
                    .then((response) => {
                        this.accountLogs = response.data.logs
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            resetUserPayment(){
                this.user_payment = {
                    shipping_price: null,
                        balance_payment: null,
                        terminal_payment: null,
                        total_price: null,
                        cash: null,
                        remainder: null,
                        paid: null,
                        invoices: [],
                        user_id: null
                };
            },
            getCashBalance(){
                axios.get('/cp/cash/show')
                    .then((response) => {
                        this.cash_money = response.data.balance;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            store(type) {
                axios.post('/cp/cash/store', {money: this.receipt, type: type})
                    .then((response) => {
                        this.getCashBalance();
                        this.show_outlay = false;
                        $('.outlay').modal('hide');
                        $('.receipt').modal('hide');
                        this.receipt = '';
                        this.getCashBalance();
                    })
                    .catch(function (error) {
                        });
                    },
            searchUser() {
                let params = '?uniqid='+this.uniqid+'&fullname='+this.fullname;
                axios.get('/cp/invoicesByUser'+params)
                    .then((response) => {
                        if(response.data.status == 200){
                            this.search=true;
                            this.user = response.data.data;
                        }

                    })
                    .catch(function (error) {
                        });
                    },

            openPaymentModel(){
                this.resetUserPayment();
                let shipping_price = 0;
                let last_price;
                this.depot_numbers = '';
                for(let i in this.user.stock){
                    if(this.user.stock[i].selected){
                        if(this.user.stock[i].is_paid == 0)
                            shipping_price = shipping_price + parseFloat(this.user.stock[i].shipping_price * this.currency.usd);
                        last_price=parseFloat(shipping_price).toFixed(2);
                        this.user_payment.invoices.push(this.user.stock[i].id);
                        this.depot_numbers += this.user.stock[i].depot_invoice.depot.barcode_id +" ";
                    }
                }

                this.user_payment.shipping_price = last_price;
                this.user_payment.total_price = (this.user.balance <0) ? (this.user_payment.shipping_price - this.user.balance).toFixed(2) : this.user_payment.shipping_price;
                this.user_payment.cash = this.user_payment.total_price;
                this.user_payment.user_id = this.user.id;
            },
            setBalancePayment (){
                if(parseFloat(this.user_payment.balance_payment) > parseFloat(this.user.balance)){
                    this.user_payment.balance_payment = this.user.balance;

                }

                if(parseFloat(this.user_payment.balance_payment) > parseFloat(this.user_payment.total_price)){
                    this.user_payment.balance_payment = this.user_payment.total_price;
                    this.user_payment.terminal_payment = 0;
                }

                this.user_payment.cash = (this.user_payment.total_price - this.user_payment.balance_payment - this.user_payment.terminal_payment).toFixed(2);
            },
            setTerminalPayment (){

                if(this.user_payment.terminal_payment > this.user_payment.total_price)
                    this.user_payment.terminal_payment = this.user_payment.total_price;

                this.user_payment.cash = (this.user_payment.total_price - this.user_payment.terminal_payment - this.user_payment.balance_payment).toFixed(2);
            },
            setPaid (){
                this.user_payment.remainder = (this.user_payment.paid - this.user_payment.total_price).toFixed(2);
            },
            check(){
                this.resetUserPayment();
                let shipping_price = 0;
                let last_price;
                this.depot_numbers = '';
                for(let i in this.user.stock){
                    if(this.user.stock[i].selected){
                        if(this.user.stock[i].is_paid == 0)
                            shipping_price = shipping_price + parseFloat(this.user.stock[i].shipping_price * this.currency.usd);
                        last_price=parseFloat(shipping_price).toFixed(2);
                        this.user_payment.invoices.push(this.user.stock[i].id);
                        this.depot_numbers += this.user.stock[i].depot_invoice.depot.barcode_id +" ";
                    }
                }

                this.user_payment.shipping_price = last_price;
                this.user_payment.total_price = (this.user.balance <0) ? this.user_payment.shipping_price - this.user.balance : this.user_payment.shipping_price;
                this.user_payment.cash = this.user_payment.total_price;

                let mywindow = window.open('', 'PRINT', 'height=400,width=600');
                setTimeout(function(){
                    mywindow.document.write('</head><body >');
                    mywindow.document.write(document.getElementById('check').innerHTML);
                    mywindow.document.write('</body></html>');

                    mywindow.document.close();
                    mywindow.focus();

                    mywindow.print();
                    mywindow.close();
                }, 500)
            },
            savePayment (){
                this.date = new Date();
                this.show_check = true;
                let mywindow = window.open('', 'PRINT', 'height=400,width=600');

                mywindow.document.write('</head><body >');
                mywindow.document.write(document.getElementById('check').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                mywindow.print();
                mywindow.close();

                axios({url: '/cp/cash/pay', method: 'POST', data: this.user_payment}).then(response => {
                    swal.close();
                    $('.cash').modal('hide');
                    this.searchUser();
                });
                return true;
            },
            get(term = '', page = 1) {
                this.term = term;
                const pageNumber = page ? page : this.pagination ? this.pagination.current_page : 1;
                axios.get(api.invoiceCashierList + '?term=' + term + '&page=' + pageNumber).then(data => {
                    if (data) {
                        this.pagination = data.data;
                        this.list = data.data.data;
                    }
                })
            },
            pay(product) {
                this.show_check = true;
                swal({
                    title: 'Yüklənir...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.invoiceCashierPay, method: 'POST', data: {id: product.id}}).then(response => {
                            swal.close();
                            setTimeout(() => {
                                PrintElem('check');
                            }, 200);
                            setTimeout(() => {
                                this.get(this.term);
                            }, 350)
                        });
                    }
                })
            },
            addBalance(type) {
                if(this.money <= 0) return;
                let data = {
                    type: type,
                    money: this.money,
                    user_id: this.user.id
                };
                axios.post('/cp/cash/handleUserBalance', data)
                    .then((response) => {
                        swal({
                            title: 'Balans redaktə olundu',

                        });
                        this.money = 0;
                        this.searchUser();
                        this.getCashBalance();
                    })
                    .catch(function (error) {
                    });
            },

            closeModal(){

            },

            selectAll(){
                for(let i in this.user.stock){
                    this.user.stock[i].selected = this.select_all;
                }
            }

        },
        mounted() {
            this.getCashBalance();
            this.getCashLogs();
        }
    }
</script>

<style scoped>
    .kassa .input-group, .kassa .btn-effect {
        margin: 15px 0 0;
    }

    .kassa {
        padding: 48px;
        margin-bottom: 0;
    }

    .kassa img {
        margin: auto;
    }

    .right-block .block h4 {
        font-size: 16px;
        text-align: left;
        margin-top: 0;
    }

    .right-block .block h4 span {
        float: right;
    }

    .right-block .block p {
        font-size: 36px;
        position: absolute;
        top: 44%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .right-block .block .btn-effect {
        font-size: 12px;
    }

    .modal-header h4 {
        display: inline-block;
    }

    .content .block.text-right .btn-effect {
        display: inline-block;
        width: 200px;
    }

    .block.text-right {
        text-align: right !important;
    }

    @media only screen and (min-width: 768px) {
        .right-block {
            position: absolute;
            height: 100%;
            right: 0;
        }

        .parent-block {
            position: relative;
        }

        .right-block .block {
            height: 100%;
            padding: 20px;
        }
    }

</style>