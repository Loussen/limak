<template>
    <div v-if="this.roles.length >0">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu">
                    <div class="row">
                        <div class="col-md-4">
                            <h4>{{region_name}}</h4>
                        </div>
                        <div class="col-md-4 text-center">
                            <span class="region_name">{{account.name}}</span>
                        </div>
                        <div class="col-md-4">
                            <ol class="breadcrumb">
                                <li>
                                    <router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link>
                                </li>
                                <li class="active">{{account.name}}</li>
                            </ol>
                        </div>
                    </div>


                </div>
            </div>
            <div class="parent-block col-xs-12">
                <div class="row">
                    <div class="left-block col-md-2 col-sm-2">
                        <div class="block">
                            <h4>KASSA MƏBLƏĞİ <span>TRY</span></h4>
                            <p>{{ cash_money_try }}</p>
                            <div v-if="hasAccess(Array('super_admin','accountant'))"  class="block-button-list col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect green" data-toggle="modal"
                                                data-target=".receiptTry">MƏDAXİL
                                        </button>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect red-button" data-toggle="modal"  @click="show_outlay_try = true"
                                                data-target=".outlayTry">MƏXARİC</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="center_block col-md-8 col-sm-8">
                        <div class="block kassa col-xs-12">
                            <form action="...">
                                <div class="row">
                                    <div class="col-xs-1">
                                        <img src="/admin/new/img/kassa.png" class="img-responsive" alt="kassa">
                                    </div>
                                    <div class="col-xs-11">
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" v-model="uniqid" placeholder="Müştəri kodu" v-on:keyup.13="searchUser()">
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
                    <div class="right-block col-md-2 col-sm-2">
                        <div class="block">
                            <h4>KASSA MƏBLƏĞİ <span>AZN</span></h4>
                            <p>{{ cash_money }}</p>
                            <div v-if="hasAccess(Array('super_admin','accountant'))"  class="block-button-list col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect green" data-toggle="modal"
                                                data-target=".receipt">MƏDAXİL
                                        </button>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect red-button" data-toggle="modal"  @click="show_outlay = true"
                                                data-target=".outlay">MƏXARİC</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade receiptTry" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Mədaxil TRY</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img
                                    src="/admin/new/img/Forma1.png"
                                    alt="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="receiptTry" class="form-control" placeholder="Məbləği daxil et TRY">
                            </div>
                            <button type="button" class="btn-effect" v-on:click="storeTry('plus')">mədaxil et</button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="show_outlay_try" class="modal fade outlayTry" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Məxaric TRY</h4>
                            <button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close"><img
                                    src="/admin/new/img/Forma1.png"
                                    alt="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="receiptTry" class="form-control" placeholder="Məbləği daxil et TRY">
                            </div>
                            <button type="button" class="btn-effect" v-on:click="storeTry('minus')">məxaric et</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade receipt" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-md" role="document">
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
                            <div class="form-group">
                                <input v-model="reason" class="form-control" placeholder="Səbəb">
                            </div>
                            <select v-model="log_type" name="log_type" id="log_type" class="form-control">
                                <option value="1">Ümumi kassa</option>
                                <option value="0">Səhv əməliyyat</option>
                            </select>
                            <button type="button" class="btn-effect" v-on:click="store('plus')">mədaxil et</button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="show_outlay" class="modal fade outlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-md" role="document">
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
                            <div class="form-group">
                                <input v-model="reason" class="form-control" placeholder="Səbəb">
                            </div>
                            <select v-model="log_type" name="log_type" id="log_type2" class="form-control">
                                <option value="1">Ümumi kassa</option>
                                <option value="0">Səhv əməliyyat</option>
                            </select>
                            <button type="button" class="btn-effect" v-on:click="store('minus')">məxaric et</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12"  v-if="!search">
            <div class="block kassa col-xs-12">
                     <div class="row">
                        <div class="col-xs-12">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <label>Başlama tarixi</label>
                                    <input type="date" class="form-control" v-model="filter.begin_date" v-on:keyup.enter="getCashLogs()" placeholder="Başlanğıc tarix" v-on:keyup.13="searchUser()">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <label>Bitmə tarixi</label>
                                    <input type="date" class="form-control" placeholder="Bitmə tarixi" v-model="filter.end_date" v-on:keyup.enter="getCashLogs()">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group">
                                    <label>Müştəri İD</label>
                                    <input type="text" class="form-control" placeholder="İD" v-model="filter.user_id" v-on:keyup.enter="getCashLogs()">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label> </label>
                                <button type="button" class="btn-effect" v-on:click="getCashLogs()">Axtar</button>
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
                    <div class="left-block col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="block">
                            <h4>BALANS <span>TRY</span></h4>
                            <p style="cursor: pointer" @click="getLogBalance">{{ user.balance_try}}</p>

                            <div class="block-button-list col-md-12 col-sm-12">
                                <div class="input-group number-spinner">
                                <span class="input-group-btn data-dwn">
                                    <button type="button" class="btn btn-default" data-dir="dwn" @click="addBalanceTry('minus')">-</button>
                                </span>
                                    <input type="text" class="form-control text-center" v-model="money_try">
                                    <span class="input-group-btn data-up">
                                    <button type="button" class="btn btn-default" data-dir="up" @click="addBalanceTry('plus')">+</button>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="center_block col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="block customer-list auto-height">
                            <ul class="list-user">
                                <li>
                                    <ul class="inline-list">
                                        <li>Ad, Soyad <p>{{user.name}} {{user.surname}}</p></li>
                                        <li v-if="user.corporate==1"> <p class="btn btn-sm btn-success">Korporativ müştəri</p></li>
                                        <li v-if="user.is_premium==1"> <p class="btn btn-sm btn-warning">Premium müştəri</p></li>
                                    </ul>
                                </li>
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
                            <button type="button" class="btn-effect blue" data-toggle="modal" data-target=".negd">Nəğd gerİ ödənİş
                            </button>
                        </div>
                    </div>
                    <div class="right-block col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="block">
                            <h4>BALANS <span>AZN</span></h4>
                            <p style="cursor: pointer" @click="getLogBalance">{{ user.balance}}</p>

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
                    <span v-if="user.all_weight!=null">Çəki: {{parseFloat(user.all_weight).toFixed(2)}} kq</span>
                    <!--<button type="button" class="btn-effect" @click="check()">Çek çıxart</button>-->
                    <button type="button" class="btn-effect" @click="openPaymentModel()" data-toggle="modal" data-target=".look">Ödə</button>
                </div>
                <div class="block table-block">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th></th>
                            <th>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkbox_0" v-model="select_all" @change="selectAll()">
                                    <label for="checkbox_0"></label>
                                </div>
                            </th>
                            <th>Invoys ID</th>
                            <th>Bağlamada olan məhsul sayı</th>
                            <th v-if="user.corporate==1">Müştəri</th>
                            <th>Tarix</th>
                            <th>Məhsullar</th>
                            <th>Çəki</th>
                            <th>Anbarda olan yeri</th>
                            <th>Çatdırılma qiyməti</th>
                            <th>Kuryer</th>
                            <th>Status</th>
                            <!--<th>Kuryer</th>-->
                            <!--<th>Ödəyəcəyi məbləğ</th>-->
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(product,i) in user.stock">
                            <td>{{ i+1 }}</td>
                            <td>
                                <div class="checkbox">
                                    <span v-if="product.status_id!=6">
                                        <input type="checkbox"  v-model="product.selected" :id="'checkbox_'+product.id">
                                        <label :for="'checkbox_'+product.id"></label>
                                    </span>
                                    <span v-else></span>
                                </div>
                            </td>
                            <td>{{ product.id}}</td>
                            <td>{{ product.quantity}}</td>
                            <td v-if="user.corporate==1">{{product.client_name}} {{product.client_surname}} {{product.client_id}}</td>
                            <td>{{product.action_date | formatDate}}</td>
                            <td>{{ product.product_type_name ? product.product_type_name : '' }}</td>
                            <td>{{ product.weight }} Kq</td>
                            <td>{{ (product.barcode_id)? product.barcode_id : ''}}</td>
                            <td v-if="product.is_paid == 0">{{ product.shipping_price }} USD ({{ (product.shipping_price * currency.usd).toFixed(2) }} AZN) </td>
                            <td v-else-if="product.is_paid == 1">Ödənilib</td>

                            <td v-if="product.c_paid == 1">Ödənib</td>
                            <td v-else-if="product.c_paid == 0">Ödənməyib</td>
                            <td v-else> </td>

                            <td v-if="product.status_id==4">{{region_name}} anbarında</td>
                            <td v-else-if="product.status_id==6">Kuryerdə</td>
                            <td v-else-if="product.status_id==7">Kuryer təhvil verdi</td>
                            <td v-else-if="product.status_id==8">Gömrükdə</td>
                            <td v-else>Tamamlanıb</td>

                            <!--<td>10 AZN</td>-->
                            <!--<td>10 AZN</td>-->
                            <td v-if="product.country_id==1">
                                   <img src="https://limak.az/front/new/img/order-turkey%20.png">
                            </td>
                            <td v-else-if="product.country_id==2">
                                <img src="https://limak.az/front/new/img/order-US.png" alt="">
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

            <div class="modal fade look cashModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
                                    <td class="td_text">{{ user.balance}}</td>
                                </tr>
                                <tr>
                                    <td>Çatdırılma məbləği</td>
                                    <td class="td_text">{{ user_payment.shipping_price}}</td>
                                </tr>
                                <tr>
                                    <td>Ödəməli olduğu məbləğ</td>
                                    <td class="td_text">{{ user_payment.total_price}}</td>
                                </tr>
                                <tr>
                                    <td>Kuryer məbləği</td>
                                    <td><input v-model="user_payment.courier_payment"  @change="setCourierPayment()" class="form-control" style="background-color: #988d8f; color: white" /></td>
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
                                    <td class="td_text">{{user_payment.remainder}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="col-xs-12 text-left"><button type="button" class="btn-effect green" @click="savePayment()" data-dismiss="modal">ÖDƏ</button></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12" style="opacity: 0" id="check" >
                <h2 v-if="user.corporate==1">Korporativ</h2>
                <h4 style="text-align: center;font-family: Segoe UI, Helvetica;font-weight: bold; margin-top: 15px;">Ödəniş qəbzi </h4>
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
                            <p style="font-weight: bold" v-if="depot_numbers!=null">{{ depot_numbers}}</p>
                            <p style="font-weight: bold" v-else></p>
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
                            {{ date.getDate() }}/{{ months[date.getMonth()] }}/{{ date.getFullYear() }} {{ date.getHours() }}:{{ date.getMinutes() }}
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
                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getCashLogs(logs.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in logs.last_page">
                            <li v-if="page ==1 && logs.current_page==1" class="active">
                                <a @click.prevent="getCashLogs( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getCashLogs( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(logs.current_page - page)> 3 && Math.abs(logs.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(logs.current_page- page)<= 3 && logs.current_page == page" class="active">
                                <a @click.prevent="getCashLogs( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(logs.current_page - page)<= 3 ">
                                <a @click.prevent="getCashLogs(  page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == logs.last_page">
                                <a @click.prevent="getCashLogs( page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="logs.current_page < logs.last_page" @click.prevent="getCashLogs( logs.current_page + 1)" href="#" aria-label="Next">
                                <span aria-hidden="true">NÖVBƏTİ</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
        <div v-if="show_log_balance"  class="modal fade in" style="display: block" role="dialog">
            <div class="modal-dialog" style="width: 900px;">

                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="show_log_balance=false" type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Hesabat</h4>
                    </div>
                    <div class="modal-body" style="height:500px;overflow:auto">
                        <div class="filter">
                            <span>Filter</span>
                            <div class="input-group border-radius">
                                <v-select @change="getLogBalance()" v-model="filter_type" label="name" :options="balances"></v-select>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Əməliyyat</th>
                                <th>Məbləğ</th>
                                <th>Tarix</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="data in logData" >
                                <td>{{data.message}}</td>
                                <td>{{data.money }} AZN</td>
                                <td>{{data.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button @click="show_log_balance=false" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade cash negd cashBackModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Nəğd gerİ dönüş</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="admin/img/Forma1.png" alt="close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Ödənilən məbləğ</th>
                                <th>Ödəmə səbəbi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input class="form-control" type="text" v-model="cash_back_amount"></td>
                                <td><textarea class="form-control" v-model="cash_back_reason"></textarea></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="col-xs-12 text-left">
                            <button data-dismiss="modal" type="button" class="btn-effect" @click="submitPayment()">TƏSDIQ ET</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {PrintElem} from "../../pipes/slug";
    import swal from 'sweetalert2';
    import auth from '../auth.js'
    import vSelect from 'vue-select'

    export default {
        name: "KassaComponent",
        components: {
            vSelect
        },
        data() {
            return {
                show_log_balance: false,
                logData: {},
                filter_type: {
                    id: 0,
                    name: 'Hamısı'
                },
                balances: [{id:0 ,name: 'Hamısı'},{id:1 ,name: 'Məxaric'} ,{id:2 ,name: 'Mədaxil'}],
                //modal payment
                cash_back_amount: '',
                cash_back_reason: '',
                //

                show_check: false,
                cash_money: 0,
                cash_money_try: 0,
                money: 0,
                money_try: 0,
                receipt: null,
                reason: null,
                receiptTry: null,
                outlay: null,
                outlayTry: null,
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
                    courier_payment: null,
                    total_price: null,
                    cash: null,
                    remainder: null,
                    paid: null,
                    invoices: [],
                    user_id: null,
                    account_id:null
                },
                user: {},
                show_outlay: false,
                show_outlay_try: false,
                select_all: false,
                date: new Date(),
                accountLogs: [],
                logs: '',
                account_id: '',
                account_real_id: '',
                account: '',
                region_id:'',
                region_name:'',
                filter: {
                    user_id: '',
                    begin_date: '',
                    end_date: '',
                },
                months : ["Yan", "Fev", "Mar", "Apr", "May", "Iyun", "Iyul",
                    "Avq", "Sen", "Okt", "Noy", "Dek"],
                log_type:1,
            };
        },
        mounted() {
            this.initialize();

        },
        methods: {

            getAccount()
            {
                this.account_id = this.$route.params.id;
                axios.get('/cp/kassa/account/'+this.account_id).then(({data}) => {
                    this.account = data.account;
                    if(data.account===null){
                        this.$router.push('/');
                    }else{
                        this.account_real_id = data.account.id;
                        this.region_id = data.account.region_id;
                        console.log(this.account_real_id);
                        this.getRegion();
                        this.getCashBalance();
                        this.getCashLogs();

                    }
                });


            },

            getRegion(){
                axios.get('/cp/admin/getRegion',{params:{region_id:this.region_id}}).then(({data}) => {
                    this.region_id = data.region_id;
                    if(this.region_id==0){
                        this.$router.push('/');
                    }

                    this.region_name = data.region_name;
                    console.log(this.region_name);
                });
            },
            getLogBalance() {
                this.show_log_balance = true;
                var path = '/cp/kassa/get-log-balance-by-user?type=' + this.filter_type.id+'&user='+this.user.id;
                axios.get(path).then((response) => {
                    this.logData = response.data;
                }).catch((e) => {
                    console.log(e);
                });
            },
            setCourierPayment(){

                let courier_payment = this.user_payment.courier_payment ?  parseFloat(this.user_payment.courier_payment) : 0;
                let shipping_price = (this.user.balance <0) ? parseFloat(this.user_payment.shipping_price - this.user.balance).toFixed(2) : parseFloat(this.user_payment.shipping_price).toFixed(2);
                this.user_payment.total_price = parseFloat(shipping_price) + courier_payment;
                this.user_payment.cash = parseFloat(this.user_payment.total_price - this.user_payment.balance_payment - this.user_payment.terminal_payment).toFixed(2);
            },
            submitPayment(){
                 axios.post('/cp/kassaBack?account_id='+this.account_real_id,
                     {
                         amount: this.cash_back_amount,
                         reason: this.cash_back_reason,
                         uniqid:this.user.id
                     })
                     .then((response)=>{
                        swal({
                            title: 'Kassa redaktə olundu',

                        });
                        this.getCashBalance();
                         $('.cashBackModal').fadeOut()
                     })
            },
            getCashLogs(page_id=1){
                axios.get('/cp/accounts/logs/'+this.account_real_id+'?page='+page_id,{params: this.filter})
                    .then((response) => {
                        this.accountLogs = response.data.logs.data
                        this.logs = response.data.logs
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
                axios.get('/cp/kassa/show?account_id='+this.account_real_id)
                    .then((response) => {
                        this.cash_money = response.data.balance;
                        this.cash_money_try = response.data.balance_try;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            store(type) {
                axios.post('/cp/kassa/store', {money: this.receipt, type: type, account_id: this.account_real_id,log_type: this.log_type, reason: this.reason})
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
            storeTry(type) {
                axios.post('/cp/kassa/storeTry', {money: this.receiptTry, type: type, account_id: this.account_real_id})
                    .then((response) => {
                        this.getCashBalance();
                        this.show_outlay = false;
                        $('.outlayTry').modal('hide');
                        $('.receiptTry').modal('hide');
                        this.receiptTry = '';
                        this.getCashBalance();
                    })
                    .catch(function (error) {
                    });
            },
            searchUser() {
                let params = '?uniqid='+this.uniqid+'&fullname='+this.fullname+'&region_id='+this.region_id;
                axios.get('/cp/invoicesByUser2'+params)
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
                        this.depot_numbers += this.user.stock[i].barcode_id +" ";

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

                if(parseFloat(this.user_payment.terminal_payment) > parseFloat(this.user_payment.total_price))
                    this.user_payment.terminal_payment = parseFloat(this.user_payment.total_price);

                this.user_payment.cash = (this.user_payment.total_price - this.user_payment.terminal_payment - this.user_payment.balance_payment).toFixed(2);
            },
            setPaid (){
                this.user_payment.remainder = (this.user_payment.paid - this.user_payment.cash).toFixed(2);
            },
            check(){
                console.log(this.date.getMonth());
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
                        this.depot_numbers += this.user.stock[i].barcode_id +" ";
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
                this.user_payment.account_id = this.account_real_id;
                axios({url: '/cp/kassa/pay', method: 'POST', data: this.user_payment}).then(response => {
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
                if(type=='minus'){
                    return swal({
                        title: 'Kassir müştəri balansından minus edə bilməz!',

                    });
                }
                let data = {
                    type: type,
                    money: this.money,
                    user_id: this.user.id,
                    account_id: this.account_real_id
                };
                axios.post('/cp/kassa/handleUserBalance', data)
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
            addBalanceTry(type) {
                if(this.money_try <= 0) return;
                let data = {
                    type: type,
                    money_try: this.money_try,
                    user_id: this.user.id,
                    account_id: this.account_real_id
                };
                axios.post('/cp/kassa/handleUserBalanceTry', data)
                    .then((response) => {
                        swal({
                            title: 'Balans redaktə olundu',

                        });
                        this.money_try = 0;
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
                    if(this.user.stock[i].status_id!==6){
                        this.user.stock[i].selected = this.select_all;
                    }
                }
            },
            initialize() {
                this.getAccount();
            },
        },

        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','accountant','casher'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .blue{
        position: absolute;
        width: auto;
        padding: 6px 12px;
        right: 30px;
        bottom: 50px;
    }
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


    .left-block .block h4 {
        font-size: 16px;
        text-align: left;
        margin-top: 0;
    }

    .left-block .block h4 span {
        float: right;
    }

    .left-block .block p {
        font-size: 36px;
        position: absolute;
        top: 44%;
        right: 50%;
        transform: translate(50%, -50%);
    }

    .left-block .block .btn-effect {
        font-size: 12px;
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

    .block-button-list{
        left: 0px !important;
    }

    @media only screen and (min-width: 768px) {
        .right-block {
            position: absolute;
            height: 100%;
            right: 0;
        }

        .left-block {
            position: absolute;
            height: 100%;
            left: 0;
        }

        .center_block{
            left: 16.66666%;
            height: 100%;
        }


        .parent-block {
            position: relative;
        }

        .right-block .block {
            height: 100%;
            padding: 20px;
        }

        .left-block .block {
            height: 100%;
            padding: 20px;
        }
    }

    .region_name{
        font-weight: bold;
        font-size: 40px;
        letter-spacing: 10px;
    }

    .content .top-menu {
        padding: 20px 0 10px 0 !important;
    }

    .td_text{
        font-size:20px;
        font-weight: bold;
    }
</style>