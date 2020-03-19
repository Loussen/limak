<template>
    <div class="row">
        <div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <form action="...">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Müştəri kodu"  v-model="filter.uniqid">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Korporativ Müştəri kodu"  v-model="filter.client_id">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Telefon"  v-model="filter.phone">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Ad"  v-model="filter.name">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Soyad"  v-model="filter.surname">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="date" class="form-control" placeholder="Doğum tarixi"  v-model="filter.birthdate">
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <br />
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="email" class="form-control" placeholder="E-mail" v-model="filter.email">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Seriya nömrəsi" v-model="filter.serial_number">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Fin kod" v-model="filter.pin">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <button type="button" class="btn-effect" @click="checkForm()">Axtar</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="col-xs-12 search-result">
                    <p>Axtarışın nəticəsi: {{users.length}} nəticə</p>
                    <ul>
                        <li v-for="userRow in users">
                            <a @click="getAllInfo(userRow.id)"><h3>№:{{userRow.id}} {{userRow.name}} {{userRow.surname}}</h3></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--<div class="col-xs-12 user-information">
            <div class="row">
                <div class="col-md-12">
                    <p>Axtarışın Nəticəsi</p>
                    <p v-for="userRow in users">
                      <span @click="getAllInfo(userRow.id)">
                        {{userRow.id}} {{userRow.name}}
                     </span>
                    </p>
                </div>
            </div>
        </div>-->
        <div v-if="user!=''">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu">
                    <h4>İSTİFADƏÇİ MƏLUMATLARI</h4>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-home"></i>Ana səhifə</a></li>
                        <li><a href="#"> Müştəri xidməti</a></li>
                        <li class="active">İstifadəçi məlumatı</li>
                    </ol>
                </div>
            </div>
            <div class="col-xs-12" v-if="user!=''">
                <div class="row">
                    <div class="col-md-2 col-sm-3 col-xs-12">
                        <div class="balance block">
                            <p style="cursor: pointer"  @click="getBalanceInfo('try')"  class="btn-effect">TL Balansı <span>{{ user.balance_try }}</span></p>
                            <template v-if="hasAccess(Array('super_admin','accountant'))">
                                <p class="title">Alınacaq və ya geri qaytarılacaq məbləğ (TL - ilə)</p>
                                <form >
                                    <div class="row">
                                        <div class="col-md-5 col-sm-5">
                                            <div class="input-group">
                                                <input type="number" class="form-control" @blur="calcAdditionalPriceTry($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-sm-7">
                                            <div class="input-group number-spinner">
                                            <span class="input-group-btn data-dwn">
                                                <button type="button" class="btn btn-default" data-dir="dwn" v-on:click="minusPercentTry()">-</button>
                                            </span>
                                                <input type="text" v-model="percentTry" class="form-control text-center" readonly>
                                                <span class="input-group-btn data-up">
                                    <button type="button" class="btn btn-default" data-dir="up" v-on:click="addPercentTry()">+</button>
                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <b>{{addPriceResultTry}}</b>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="form-control" v-model="addPriceDescTry" rows="2" placeholder="Səbəb"></textarea>
                                            <textarea class="form-control" v-model="addPriceDescTry2" rows="2" placeholder="Səbəb admin üçün"></textarea>
                                        </div>
                                        <div class="block-button-list col-md-12 col-sm-12">
                                            <div class="col-md-6 col-sm-6">
                                                <button type="button" class="btn-effect red-button" v-on:click="addBalanceTry('minus')">BALANSINDAN ÇIX</button>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <button type="button" class="btn-effect green" v-on:click="addBalanceTry('plus')">bALANSA ƏLAVƏ ET</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </template>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="block customer-list">
                            <ul class="list-user">
                                <li>
                                    <ul class="inline-list">
                                        <li>Ad, Soyad <p>{{user.name}} {{user.surname}}</p></li>
                                        <li v-if="user.corporate==1"><p class="btn btn-sm btn-success green">Korporativ müştəri</p></li>
                                        <li v-if="user.is_premium==1"><p class="btn btn-sm btn-success green">Premium müştəri</p></li>
                                        <li v-if="accessAdmin()===true">
                                            <p>
                                                <button type="button" v-if="user.is_blacklist == 0" class="btn-effect btn-sm green" @click="blackListUser(user.id, 1)">Qara siyahı et</button>
                                                <button type="button" v-else="" class="btn-sm btn-effect red" @click="blackListUser(user.id, 0)">Qara siyahıdan çıxart</button>
                                            </p>
                                        </li>
                                        <li v-else>
                                            Vəziyyəti
                                            <p v-if="user.is_blocked == 1">Qara siyahıdadır</p>
                                            <p v-else="">Qara siyahı deyil</p>
                                        </li>
                                        <li v-if="accessAdmin()===true">
                                            <p>
                                                <button type="button" v-if="user.is_blocked == 0" class="btn-effect yellow" @click="blockUser(user.id, 1)">Blokla</button>
                                                <button type="button" v-else="" class="btn-effect green" @click="blockUser(user.id, 0)">Blokdan çıxart</button>
                                            </p>
                                        </li>
                                        <li v-else>
                                            Vəziyyəti
                                            <p v-if="user.is_blocked == 1">Blokdadır</p>
                                            <p v-else="">Blok deyil</p>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <ul class="inline-list">
                                        <li>Müştəri kodu<p>{{user.uniqid}}</p></li>
                                        <li>Qeydiyyat tarixi<p>{{user.created_at}}</p></li>
                                        <li>Mobil telefonu<p>{{user.user_contacts[0].name}} </p></li>

                                        <li v-if="!editable_status_email">
                                            Email
                                            <p @click="openEditEmail(user)">{{user.email}} </p>
                                        </li>
                                        <li v-else>
                                            Email
                                            <input type="text" v-model="user.email">
                                            <button @click="changeEmail(user)">
                                                Dəyiş
                                            </button>
                                            <button @click="editable_status_email=''">
                                                Bağla
                                            </button>
                                        </li>

                                        <li v-if="!editable_status_pin">
                                            Fin kodu
                                            <p @click="openEditPin(user)">{{user.pin}} </p>
                                        </li>

                                        <li v-else>
                                            Fin kodu
                                            <input type="text" v-model="user.pin">
                                            <button @click="changePin(user)">
                                                Dəyiş
                                            </button>
                                            <button @click="editable_status_pin=''">
                                                Bağla
                                            </button>
                                        </li>
                                        <li>
                                            Anbar
                                            <p>{{arrayRegions[user.region_id]}}</p>
                                        </li>


                                    </ul>
                                </li>
                                <li>Ünvan
                                    <p>{{user.address}}</p></li>
                                <li>
                                    <ul class="inline-list">
                                        <li>
                                            Son 30 gündə çatdırılma haqqı
                                            <p>{{counts.last30day_delivery_price}}</p>
                                        </li>
                                        <li>
                                            Son 30 gündə xərclənməsi
                                            <p>{{counts.last30day_payment}}</p>
                                        </li>
                                        <li>
                                            Karta qaytarılan məbləğ
                                            <p v-if="counts.return_to_card>0">{{counts.return_to_card}}</p>
                                            <p v-else>Yoxdur</p>
                                        </li>
                                        <li>
                                            Nəğd geri ödəniş
                                            <p v-if="counts.return_to_cash>0">{{counts.return_to_cash}}</p>
                                            <p v-else>Yoxdur</p>
                                        </li>
                                        <li>
                                            <div class="input-group">
                                                <button @click="show_back_to_card = true" type="button" class="btn-effect green">Karta gerİ qaytar</button>
                                            </div>
                                        </li>
                                    </ul>

                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-3 col-xs-12">
                        <div class="block bildirish">
                            <h4>BİLDİRİŞ GÖNDƏR</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <select class="form-control" @change="changeSmsTemplate($event)" v-model="sms_template_id">
                                        <option v-bind:value="0"></option>
                                        <option v-for="sms_template in sms_templates" v-bind:value="sms_template.id">{{sms_template.name}}</option>
                                    </select>
                                    <textarea class="form-control" v-model="message" rows="10" placeholder="Bildiriş mətni"></textarea>
                                </div>
                                <div class="block-button-list col-md-12 col-sm-12">
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect blue" v-on:click="sendEmail()" >E-mail İlə</button>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect sms blue" v-on:click="sendSms()">SMS İlə</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-3 col-xs-12">
                        <div class="balance block">
                            <p style="cursor: pointer"  @click="getBalanceInfo('azn')"  class="btn-effect">AZN Balans <span>{{ user.balance }}</span></p>
                            <template v-if="hasAccess(Array('super_admin','accountant'))">
                                <p class="title">Alınacaq və ya geri qaytarılacaq məbləğ (TL - ilə)</p>
                                <form >
                                    <div class="row">
                                        <div class="col-md-5 col-sm-5">
                                            <div class="input-group">
                                                <input type="number" class="form-control" @blur="calcAdditionalPrice($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-sm-5">
                                            <div class="input-group number-spinner">
                                            <span class="input-group-btn data-dwn">
                                                <button type="button" class="btn btn-default" data-dir="dwn" v-on:click="minusPercent()">-</button>
                                            </span>
                                                <input type="text" v-model="percent" class="form-control text-center" readonly>
                                                <span class="input-group-btn data-up">
                                    <button type="button" class="btn btn-default" data-dir="up" v-on:click="addPercent()">+</button>
                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <b>{{addPriceResult}}</b>
                                        </div>
                                        <div class="col-md-12">
                                        <textarea class="form-control" v-model="addPriceDesc" rows="2" placeholder="Səbəb"></textarea>
                                            <textarea class="form-control" v-model="addPriceDesc2" rows="2" placeholder="Səbəb admin üçün"></textarea>

                                        </div>
                                        <div class="block-button-list col-md-12 col-sm-12">
                                            <div class="col-md-6 col-sm-6">
                                                <button type="button" class="btn-effect red-button" v-on:click="addBalance('minus')">BALANSINDAN ÇIX</button>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <button type="button" class="btn-effect green" v-on:click="addBalance('plus')">bALANSA ƏLAVƏ ET</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 user_info_list">
                <div class="row">
                    <div class="tab-list col-md-12 col-sm-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <!--<li role="presentation" class="active"><a href="javascript:void(0)" class="block" aria-controls="umumi"
                                                                      role="tab"
                                                                      data-toggle="tab">Bütün sİfarİşlər <span
                                    class="badge">{{counts.all}}</span></a></li>-->
                            <li role="presentation"><a @click="getUserOrders('incoming')" href="javascript:void(0)" class="block" aria-controls="gelen" role="tab"
                                                       data-toggle="tab">Gələn sİfarİşlər <span class="badge">{{counts.incoming}}</span></a>
                            </li>
                            <li role="presentation"><a @click="getUserOrders('executing')" href="javascript:void(0)" class="block" aria-controls="icra" role="tab"
                                                       data-toggle="tab">İcra olunMaqda olan şİfarİşlər <span
                                    class="badge">{{counts.executing}}</span></a>
                            </li>
                            <!-- <li role="presentation"><a  href="javascript:void(0)" class="block" aria-controls="baglama" role="tab"
                                                         data-toggle="tab">Bağlamaların sayı <span class="badge">{{counts.invoices}}</span></a>
                             </li>-->
                            <li role="presentation"><a @click="getUserInvoices('waiting')" href="javascript:void(0)" class="block" aria-controls="xarici" role="tab"
                                                       data-toggle="tab">Bütün Bağlamalar <span
                                    class="badge">{{counts.invoices}}</span></a>
                            </li><!--
                            <li role="presentation"><a @click="getUserInvoices('foreign_stock')" href="javascript:void(0)" class="block" aria-controls="xarici" role="tab"
                                                       data-toggle="tab">Xarİcİ anbarda <span
                                    class="badge">{{counts.foreign}}</span></a>
                            </li>
                            <li role="presentation"><a @click="getUserInvoices('on_the_way')" href="javascript:void(0)" class="block" aria-controls="yolda" role="tab"
                                                       data-toggle="tab">Yolda <span class="badge">{{counts.on_the_way}}</span></a>
                            </li>
                            <li role="presentation"><a @click="getUserInvoices('home_stock')" href="javascript:void(0)" class="block" aria-controls="baki" role="tab"
                                                       data-toggle="tab">Bakı anbarı <span class="badge">{{counts.home}}</span></a>
                            </li>
                            <li role="presentation"><a @click="getUserInvoices('has_courier')" href="javascript:void(0)" class="block" aria-controls="kuryerde"
                                                       role="tab" data-toggle="tab">Kuryerdə <span
                                    class="badge">{{counts.has_courier}}</span></a>
                            </li>
                            <li role="presentation"><a @click="getUserInvoices('completed')" href="javascript:void(0)" class="block" aria-controls="tehvil" role="tab"
                                                       data-toggle="tab">Təhvİl verİldİ <span
                                    class="badge">{{counts.completed}}</span></a>
                            </li>-->
                            <li role="presentation"><a @click="getUserOrders('basket')" class="block" aria-controls="sebet" role="tab"
                                                       data-toggle="tab">Səbət <span class="badge">{{counts.basket}}</span></a>
                            </li>
                            <li role="presentation"><a  @click="getUserOrders('reject')" class="block" aria-controls="reject" role="tab"
                                                        data-toggle="tab">İmtİna <span class="badge">{{counts.reject}}</span></a>
                            </li>

                            <li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="lost" role="tab"
                                                       data-toggle="tab">İtən bağlama <span class="badge">{{counts.lost_invoices}}</span></a>
                            </li>
                            <li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="qalan" role="tab"
                                                       data-toggle="tab">Anbarda qalan <span
                                    class="badge">{{counts.in_stock}}</span></a></li>
                            <!--<li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="kart" role="tab"
                                                       data-toggle="tab">Karta qaytarılan <span class="badge">{{counts.return_to_card}}</span></a>
                            </li>-->
                            <!--<li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="xerclenme"
                                                       role="tab" data-toggle="tab">Son 30 gündəkİ xərcləməsİ <span
                                    class="badge">{{counts.last30day_payment}}</span></a>
                            </li>
                            <li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="catdirilma"
                                                       role="tab" data-toggle="tab">Son 30 gündəkİ çatdırılma haqqı
                                <span class="badge">{{counts.last30day_delivery_price}}</span></a></li>
                            <li role="presentation"><a href="javascript:void(0)"class="block" aria-controls="negd" role="tab"
                                                       data-toggle="tab">Nəğd gerİ ödənİş <span class="badge">{{counts.return_to_cash}}</span></a>
                            </li>-->
                            <!--<li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="message" role="tab"
                                                       data-toggle="tab">Mesajlar <span class="badge">{{counts.messages}}</span></a></li>-->
                            <li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="sorgu" role="tab"
                                                       data-toggle="tab">Sorğular <span class="badge">{{counts.query}}</span></a>
                            </li>
                            <li role="presentation" v-if="user.corporate"><a href="javascript:void(0)" @click="getUserClients('clients')" class="block" aria-controls="clients" role="tab"
                                                       data-toggle="tab">Müştərilər <span class="badge">{{counts.clients}}</span></a>
                            </li>
                            <li role="presentation"><a href="javascript:void(0)" @click="getUserRePayments('repayments')" class="block" aria-controls="repayments" role="tab"
                                                                             data-toggle="tab">Karta qaytarmalar</a>
                            </li>
                            <!--<li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="sms" role="tab"
                                                       data-toggle="tab">SMS <span class="badge">{{counts.sms}}</span></a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="email" role="tab"
                                                       data-toggle="tab">E-mail <span class="badge">{{counts.email}}</span></a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="block" aria-controls="call" role="tab"
                                                       data-toggle="tab">Zənglər <span class="badge">{{counts.call}}</span></a></li>-->
                        </ul>
                    </div>
                </div>
                <div class="tab-content qeydiyyat" v-if="tableMode">
                    <UsersOrdersComponent v-bind:selected_type="selected_type" v-bind:user_id="user.id" v-if="selected_component=='orders'"></UsersOrdersComponent>
                    <UsersInvoicesComponent v-bind:selected_type="selected_type" v-bind:user_id="user.id" v-if="selected_component=='invoices'"></UsersInvoicesComponent>
                    <UsersClientsComponent v-bind:selected_type="selected_type" v-bind:user_id="user.id" v-if="selected_component=='clients'"></UsersClientsComponent>
                    <UsersRePaymentsComponent v-bind:selected_type="selected_type" v-bind:user_id="user.id" v-if="selected_component=='repayments'"></UsersRePaymentsComponent>
                </div>

            </div>
        </div>
        <div v-show="show_back_to_card" class="modal fade look in" id="select2"  style="display: block">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="show_back_to_card = false" aria-label="Close">&times;</button>
                        <h4>Karta geri qaytar</h4>

                    </div>
                    <div class="modal-body">
                        <!--<div class="row">-->
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Səbəb</label>
                                <input v-model="back_to_card_reason" type="text" class="form-control" placeholder="Səbəb">
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Qaytarılacaq məbləğ</label>
                                <input v-model="back_to_card_amount"   type="text" class="form-control" placeholder="Qaytarılacaq məbləğ">
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <button @click="sendBackToCard" type="button" class="btn-effect green" data-dismiss="modal">GÖNDƏR</button>
                        </div>
                        <div class="col-xs-12 errors">
                            <div v-for="error in back_to_card_errors" class="text-danger">{{ error }}</div>
                        </div>
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
        <div v-show="show_balance_info"  class="modal fade look in" id="modal3" role="dialog"  style="display: block;overflow: scroll">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="show_balance_info = false">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Mesaj</th>
                                <th>Köhnə balans</th>
                                <th>Məbləğ</th>
                                <th>Yeni balans</th>
                                <th>Tarix</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in balanceInfo">
                                <td>{{item.message}} {{item.message_admin}}</td>
                                <td>{{item.old_balance}}</td>
                                <td>{{item.money}}</td>
                                <td>{{item.new_balance}}</td>
                                <td style="text-align: right !important;">{{item.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    import UsersOrdersComponent from  "./components/UsersOrdersComponent";
    import UsersInvoicesComponent from  "./components/UsersInvoicesComponent";
    import UsersClientsComponent from  "./components/UsersClientsComponent";
    import UsersRePaymentsComponent from  "./components/UsersRePaymentsComponent";
    import auth from '../auth.js'
    import swal from 'sweetalert2';

    export default {
        name: "UsersAllInfoComponent",
        data(){
            return {
                //back to card
                show_back_to_card: false,
                show_balance_info: false,
                balanceInfo:'',
                back_to_card_reason: '',
                back_to_card_amount: 0,
                back_to_card_errors: [],
                backToCards: [],
                //end
                errors: '',
                users: '',
                user: '',
                orders: '',
                selected_component: 'all',
                selected_type: 'all',
                filter: {
                    uniqid: '',
                    client_id: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                    birthdate: '',
                    serial_number: '',
                    pin: '',
                },
                counts: {
                    all: '',
                    incoming: '',
                    executing: '',
                    basket: '',
                    waiting: '',
                    foreign: '',
                    on_the_way: '',
                    home: '',
                    completed: '',
                    has_courier: '',
                    delivered_by_courier: '',
                    reject: '',
                    invoices: '',
                    lost_invoices: '',
                    in_stock: '',
                    return_to_card: '',
                    return_to_cash: '',
                    last30day_payment: '',
                    last30day_delivery_price: '',
                    messages: '',
                    query: '',
                    sms: '',
                    email: '',
                    call: '',
                    clients: '',
                },
                tableMode: false,
                send_data: {},

                addPriceResult: '',
                percent: 5,
                addPrice: 0,
                finalAddPrice: 0,
                addPriceDesc: '',
                addPriceDesc2: '',

                addPriceResultTry: '',
                percentTry: 5,
                addPriceTry: 0,
                finalAddPriceTry: 0,
                addPriceDescTry: '',
                addPriceDescTry2: '',

                selected_items: [],
                payment_type: 1,
                message: '',
                sms_templates: '',
                sms_template_id: '',

                editable_status_pin: '',
                editable_status_email: '',
                arrayRegions : {1:"Bakı",2:"Gəncə",3:"Sumqayıt",4:"Zaqatala",5: "Lənkəran"}

            }
        },
        components:{
            UsersInvoicesComponent,
            UsersOrdersComponent,
            UsersClientsComponent,
            UsersRePaymentsComponent
        },
        methods:{
            splitMessage(str){
                if (str.indexOf("||") >= 0){
                    let data = str.split('||');
                    return data[0];
                }else return str;
            },
            getBalanceInfo(balance_type='azn'){
                this.show_balance_info = true;
                axios.get('/cp/users/balanceInfo/'+this.user.id+'?type='+balance_type)
                    .then((response) => {
                        this.balanceInfo=response.data.data;
                    });
            },

            openEditPin(user){
                if(this.accessAdmin()===true)
                    this.editable_status_pin = true;
            },

            changePin(user){
                axios.post('/cp/users/changePin', {
                    user_id: user.id,
                    pin: user.pin
                }).then(({ data }) => {
                    if(data.status==200){
                        this.editable_status_pin = '';
                    }
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            openEditEmail(user){
                if(this.accessAdmin()===true)
                    this.editable_status_email = true;
            },

            changeEmail(user){
                axios.post('/cp/users/changeEmail', {
                    user_id: user.id,
                    email: user.email
                }).then(({ data }) => {
                    if(data.status==200) {
                        this.editable_status_email = '';
                    }
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            addBalance(type) {
                let data = {};
                data.user_id = this.user.id;
                data.type = type;
                data.money = this.finalAddPrice;
                data.desc = this.addPriceDesc;
                data.desc2 = this.addPriceDesc2;

                if(this.finalAddPrice){
                    axios.post('/cp/users/addBalance', data).then((result) => {
                        if(!result.data.success) alert(result.data.message);
                        if(result.data.success) alert('Balans yeniləndi');
                        this.getAllInfo(this.user.id);
                    }).catch(err => {
                        alert('Server xətası');
                    });
                }
            },
            blockUser(user_id, is_blocked){
                swal.fire({
                    title: 'Əminsiniz?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yadda saxla!'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/cp/users/blockUser',{user_id, is_blocked})
                            .then((response) => {
                                this.getAllInfo(user_id)
                            })
                        swal.fire(
                            'OK!',
                            'Əməliyyat icra olundu.',
                            'success'
                        )
                    }
                })

            },
            blackListUser(user_id, is_blacklist){
                swal.fire({
                    title: 'Əminsiniz?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yadda saxla!'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/cp/users/blackListUser',{user_id, is_blacklist})
                            .then((response) => {
                                this.getAllInfo(user_id)
                            })
                        swal.fire(
                            'OK!',
                            'Əməliyyat icra olundu.',
                            'success'
                        )
                    }
                })

            },
            addBalanceTry(type) {
                let data = {};
                data.user_id = this.user.id;
                data.type = type;
                data.money = this.finalAddPriceTry;
                data.desc = this.addPriceDescTry;
                data.desc2 = this.addPriceDescTry2;

                if(this.finalAddPriceTry){
                    axios.post('/cp/users/addBalanceTry', data).then((result) => {
                        if(!result.data.success) alert(result.data.message);
                        if(result.data.success) alert('Balans yeniləndi');
                        this.getAllInfo(this.user.id);
                    }).catch(err => {
                        alert('Server xətası');
                    });
                }
            },


            sendBackToCard(){
                this.back_to_card_errors = [];
                if(!this.back_to_card_reason){
                    this.back_to_card_errors.push('Səbəb göstərilməyib')
                }
                if(!this.back_to_card_amount){
                    this.back_to_card_errors.push('Məbləğ göstərilməyib')
                }
                // if(this.backToCards.length == 0 ){
                //     this.back_to_card_errors.push('Heç bir məhsul seçilməyib')
                // }
                if( this.back_to_card_errors.length ==0  ){
                    axios.post('/cp/accounts/backToCard', {
                        products: null,
                        user: this.user.id,
                        reason: this.back_to_card_reason,
                        amount: this.back_to_card_amount,
                    }).then((data) => {
                        this.show_back_to_card = false;
                    });
                }
            },
            checkForm(){

                this.errors = {};
                if (this.isEmpty(this.errors)) {
                    this.getUsers();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
            getUsers(){
                this.users = '';
                this.user = '';
                axios.get('/cp/users/get',{params:this.filter})
                    .then((response) => {
                        this.allData = response.data.data;
                        this.users = this.allData.users;
                        if(this.allData.users.length==1){
                            this.getAllInfo(this.allData.users[0].id);
                            // console.log(this.allData.users[0].id);
                        }
                        // console.log(this.allData.users.length);
                    })
            },

            getAllInfo(id){
                this.tableMode = false;
                axios.get('/cp/users/getUser',{params:{id:id}})
                    .then((response) => {
                        let allData = response.data.data
                        this.user = allData.user;
                        // countslari umumilesdirmek lazimdir
                        this.counts.incoming = allData.incoming_count;
                        this.counts.executing = allData.executing_count;
                        this.counts.basket = allData.basket_count;
                        this.counts.reject = allData.deleted_count;
                        this.counts.waiting = allData.waiting_count;
                        this.counts.foreign = allData.foreign_count;
                        this.counts.on_the_way = allData.on_the_way_count;
                        this.counts.home = allData.home_count;
                        this.counts.completed = allData.completed_count;

                        this.counts.has_courier = allData.has_courier;
                        this.counts.delivered_by_courier = allData.delivered_by_courier;
                        this.counts.reject = allData.reject;
                        this.counts.invoices = allData.invoices;
                        this.counts.lost_invoices = allData.lost_invoices;
                        this.counts.in_stock = allData.in_stock;
                        this.counts.return_to_card = allData.return_to_card;
                        this.counts.return_to_cash = allData.return_to_cash;
                        this.counts.last30day_delivery_price = parseFloat(allData.last30day_delivery_price).toFixed(2);
                        this.counts.last30day_payment = parseFloat(allData.last30day_payment).toFixed(2);
                        this.counts.query = allData.query;
                        this.counts.messages = allData.messages;
                        this.counts.sms = allData.sms;
                        this.counts.email = allData.email;
                        this.counts.call = allData.calls;
                        this.counts.clients = allData.clients;
                        this.counts.all = allData.all;

                    })
            },


            getUserOrders(selected_type){
                this.tableMode = true;
                this.selected_component = 'orders';
                this.selected_type = selected_type;
                this.send_data.type = this.selected_type;
                this.send_data.user = this.user;
            },
            getUserInvoices(selected_type){
                this.tableMode = true;
                this.selected_component = 'invoices';
                this.selected_type = selected_type;
                this.send_data.type = this.selected_type;
                this.send_data.user = this.user;
            },
            getUserClients(selected_type){
                this.tableMode = true;
                this.selected_component = 'clients';
                this.selected_type = selected_type;
                this.send_data.type = this.selected_type;
                this.send_data.user = this.user;
            },

            getUserRePayments(selected_type){
                this.tableMode = true;
                this.selected_component = 'repayments';
                this.selected_type = selected_type;
                this.send_data.type = this.selected_type;
                this.send_data.user = this.user;
                console.log("ffff");
            },

            addBalance(type) {
                let data = {};
                data.user_id = this.user.id;
                data.type = type;
                data.money = this.finalAddPrice;
                data.desc = this.addPriceDesc;
                data.desc2 = this.addPriceDesc2;

                if(this.finalAddPrice){
                    axios.post('/cp/users/addBalance', data).then((result) => {
                        if(!result.data.success) alert(result.data.message);
                        if(result.data.success) alert('Balans yeniləndi');
                        this.getAllInfo(this.user.id);
                    }).catch(err => {
                        alert('Server xətası');
                    });
                }
            },

            getSmsTemplates(){
                axios.get('/cp/users/getSmsTemplates').then((data) => {
                    this.sms_templates = data.data.data;
                    console.log(this.sms_templates);
                }).catch(err => {
                    console.log(err);
                })
            },

            changeSmsTemplate(){
                let templateId = this.sms_template_id;
                if(templateId==0)
                {
                    this.message = '';
                }else{
                    this.message = this.sms_templates[templateId].text;
                }
            },

            sendSms(){
                axios.post('/cp/orders/sendSMS', {
                    text: this.message,
                    phone: this.user.user_contacts[0].name,
                    user_id: this.user.id
                }).then((data) => {
                    if (data.data.status === 200) {
                        this.show = false;
                        this.message = ''
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə göndərildi',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            },
            sendEmail(){
                axios.post('cp/orders/sendEmail', {
                    text: this.message,
                    email: this.user.email,
                    user_id: this.user.id
                }).then((data) => {
                    if (data.data.status === 200) {
                        this.show = false;
                        this.message = ''
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə göndərildi',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            },

            minusPercent(){
                this.finalAddPrice = (this.addPrice / (100 + this.percent) * 100).toFixed(2);
                this.addPriceResult = this.finalAddPrice + ' AZN';
            },

            addPercent(){
                this.finalAddPrice = (this.addPrice * (100 + this.percent) / 100).toFixed(2);
                this.addPriceResult = this.finalAddPrice + ' AZN';
            },

            minusPercentTry(){
                this.finalAddPriceTry = (this.addPriceTry / (100 + this.percent) * 100).toFixed(2);
                this.addPriceResultTry = this.finalAddPriceTry + ' TL';
            },

            addPercentTry(){
                this.finalAddPriceTry = (this.addPriceTry * (100 + this.percent) / 100).toFixed(2);
                this.addPriceResultTry = this.finalAddPriceTry + ' TL';
            },

            calcAdditionalPrice (event) {
                var value = event.target.value;
                if (value) {
                    axios.post('/admin/calc-additional-price', {
                        additionalPrice: value
                    }).then((result) => {
                        this.addPriceResult = result.data.withAzn + ' AZN';
                        this.addPrice = result.data.withAzn ;
                        this.finalAddPrice = result.data.withAzn ;
                    }).catch(err => {
                        alert('Server xətası');
                    });
                } else {
                    this.addPriceResult = '';
                }
            },

            calcAdditionalPriceTry (event) {
                var value = event.target.value;
                if (value) {
                    this.addPriceResultTry = value + ' TL';
                    this.addPriceTry = value ;
                    this.finalAddPriceTry = value ;
                } else {
                    this.addPriceResultTry = '';
                }
            },
            accessAdmin(){
                if(this.roles.length >0) {
                    return this.hasAccess(Array('super_admin','admin'))
                }
            },

        },
        mounted(){
            this.getSmsTemplates();
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .customer-list{
        height: auto !important;
    }
    #modal3 table td{
        text-align: left !important;
        height: auto !important;
    }
    #modal3 .modal-body{
        padding: 10px !important;
    }

</style>