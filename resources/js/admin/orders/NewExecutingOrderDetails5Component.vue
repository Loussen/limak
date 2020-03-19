<template>
    <div class="row">
        <!--<div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <form action="...">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Axtarış">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <div class="input-group">
                                <select class="selectpicker">
                                    <option>Tarixə görə</option>
                                    <option>Müştəri koduna görə</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <button type="button" class="btn-effect">Axtar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>-->
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Məhsul ətraflı</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/orders">Sifarişlər</router-link></li>
                    <li><router-link to="/orders/executing"> İcra olunan sifarişlər</router-link></li>
                    <li class="active"> Məhsul</li>
                </ol>
            </div>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-12">
            <div class="balance block">
                <p class="btn-effect">TL Balansı <span>{{ user.balance_try }}</span></p>
                <p class="title">Alınacaq və ya geri qaytarılacaq məbləğ (TL - ilə)</p>
                <form >
                    <div class="row">
                        <div class="col-md-5 col-sm-6">
                            <div class="input-group">
                                <input type="number" class="form-control" @blur="calcAdditionalPriceTry($event)">
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-6">
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
                            <textarea class="form-control" v-model="addPriceDescTry" rows="5" placeholder="Səbəb"></textarea>
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
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="block customer-list">
                <ul class="list-user">
                    <li>Ad, Soyad <p>{{ user.name }} {{ user.surname }} <span v-if="corporate==1" class="btn btn-sm btn-success">Korporativ</span> | <span v-if="is_premium==1" class="btn btn-sm btn-success">Premium</span></p>                    </li>
                    <li>E-mail <p>{{ user.email}}</p></li>
                    <li>Mobil telefon <p v-if="user.user_contacts">{{ user.user_contacts[0].name }}</p></li>
                    <li>Ünvan <span class="red">Məhsulu alan zaman ünvanı kopyalamağı unutmayın!</span>
                        <p v-if="clients!=null">Halkalı Merkez Mahellesi. Üzümlü SK. 5/7 {{ idToUniq(clients.id) }} {{  clients.name }} {{  clients.surname }} </p>
                        <p v-else>Halkalı Merkez Mahellesi. Üzümlü SK. 5/7 {{ user.uniqid }} {{ user.name }} {{ user.surname }} </p>
                    </li>
                    <li class="button-list block-button-list">
                        <div class="form-group">
                            <button type="button" v-on:click="showInvoiceModal()"  class="btn-effect">Bəyannamə yüklə
                            </button>
                        </div>
                        <div class="input-group">
                            <select v-model="payment_type"  class="selectpicker">
                                <option value="1">KART</option>
                                <option value="2" >EFT HAVALE</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <button @click="show_back_to_card = true" type="button" class="btn-effect green">Karta gerİ qaytar</button>
                        </div>
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
                <p class="btn-effect">AZN Balansı <span>{{ user.balance }}</span></p>
                <p class="title">Alınacaq və ya geri qaytarılacaq məbləğ (TL - ilə)</p>
                <form >
                    <div class="row">
                        <div class="col-md-5 col-sm-6">
                            <div class="input-group">
                                <input type="number" class="form-control" @blur="calcAdditionalPrice($event)">
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-6">
                            <div class="input-group number-spinner">
                                            <span class="input-group-btn data-dwn">
                                                <button type="button" class="btn btn-default" data-dir="dwn" v-on:click="minusPercent()">-</button>
                                            </span>
                                <input type="text" v-model="percent" class="form-control text-center" readonly>
                                <span class="input-group-btn data-up">
                                    <button type="button" class="btn btn-default" data-dir="up" v-on:click="addPercentTry()">+</button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <b>{{addPriceResult}}</b>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" v-model="addPriceDesc" rows="5" placeholder="Səbəb"></textarea>
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
            </div>
        </div>

        <div class="inform col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th>
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox_0">
                                <label for="checkbox_0"></label>
                            </div>
                        </th>
                        <th>Sifariş nömrəsi</th>
                        <th>Link</th>
                        <th>Tam ödəniş</th>
                        <th>-5%</th>
                        <th>Kargo</th>
                        <th>Müştərinin ödəməli olduğu pul</th>
                        <th>Xərclənən pul</th>
                        <th>Balans</th>
                        <th>Qeyd</th>
                        <th>Qalıq</th>
<!--
                        <th>Mağaza</th>
-->
                        <th>Say</th><!--
                        <th>Brend</th>
                        <th>Rəng</th>
                        <th>Ölçü</th>-->
                        <th>Ödəniş</th>
                        <th>Problem</th>
                        <th>Qeyd</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="product in user.products">
                        <td>
                            <div class="checkbox">
                                <input @change="handleSelectedProduct(product)" type="checkbox" v-model="product.selected" :id="'checkbox_'+product.id">
                                <label :for="'checkbox_'+product.id"></label>
                            </div>
                        </td>
                        <td>{{product.id + 1000000}}</td>
                        <td>
                            <a :href="product.extras.link2!=null?product.extras.link2:product.extras.link" target="_blank">{{product.extras.link}}</a>
                            <a @click="selected_product=product" @click.prevent="show_change_modal = true" href="#"><i class="fa fa-pencil"></i></a>
                        </td>
                        <td>{{((parseFloat(product.price)+parseFloat(product.extras.cargo_price ? product.extras.cargo_price : 0)) * 1.05).toFixed(2)}} TL</td>
                        <td>{{product.price}} TL</td>
                        <td>{{ parseFloat(product.extras.cargo_price ? product.extras.cargo_price : 0).toFixed(2) }} TL</td>
                        <td><input style="width: 60px;" v-model="product.real_price" @change="setOverPrice(product)" ></td>
                        <td><input style="width: 60px;" v-model="product.expenses" @change="setIncome(product)" ></td>
                        <td>{{ parseFloat(product.over_price*1.05).toFixed(2) }}</td>
                        <td>{{ product.description }}</td>
                        <td>{{ product.income }}</td>
<!--
                        <td>{{product.shop_name}}</td>
-->
                        <td>{{ product.quantity }}</td>
                        <!--
                                                <td>{{product.extras.brand}}</td>
                        -->
                        <!--
                                                <td>{{product.extras.color}}</td>
                        -->
<!--
                        <td>{{product.extras.size}}</td>
-->
                        <td>{{ product.rel_user_products.response_payment=='Payed with balance'?'Balans':'Kartnan' }}</td>

                        <td v-if="product.is_problem==1 && product.problem_text!=null" style="background: red;color:#FFF">
                            {{product.problem_text}}
                            <button class="btn btn-danger" @click="selected_product=product" @click.prevent="show_problem_modal = true">Problem</button>
                        </td>
                        <td v-else-if="product.is_problem==0 && product.problem_text!=null && product.problem_text!=''" style="background: green;color:#FFF">
                            {{product.problem_text}}
                            <button class="btn btn-danger" @click="selected_product=product" @click.prevent="show_problem_modal = true">Problem</button>
                        </td>
                        <td v-else>
                            <button class="btn btn-danger" @click="selected_product=product" @click.prevent="show_problem_modal = true">Problem</button>
                        </td>


                        <td><textarea class="form-control" v-model="product.comment"></textarea></td>
                        <td>
                            <!--<button type="button" class="btn-effect" v-on:click="showFile(product)">Bax</button>-->
                            <button type="button" class="btn-effect red" v-on:click="deleteProduct(product)">Sil</button>
                        </td>
                    </tr>
                    <tr v-if="user && user.products && user.products.length > 0" class="last_tr">
                        <th></th>
                        <th></th>
                        <th>Cəm</th>
                        <th>
                            {{ parseFloat(parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + parseFloat(cur.price)+NaNToZero(parseFloat(cur.extras.cargo_price));
                            }, 0)) * 1.05).toFixed(2)
                            }} TL
                        </th>
                        <th>
                            {{ parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + parseFloat(cur.price);
                            }, 0)).toFixed(2)
                            }} TL
                        </th>
                        <th>
                            {{ parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + NaNToZero(parseFloat(cur.extras.cargo_price));

                            }, 0)).toFixed(2)
                            }} TL
                        </th>
                        <th>
                            {{ parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + NaNToZero(parseFloat(cur.real_price));

                            },  0)).toFixed(2)
                            }} TL
                        </th>
                        <th>
                            {{ parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + NaNToZero(parseFloat(cur.expenses));

                            }, 0)).toFixed(2)
                            }} TL
                        </th>
                        <th>
                            {{ parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + NaNToZero(parseFloat(cur.over_price)*1.05);

                            }, 0)).toFixed(2)
                            }} TL
                        </th>
                        <th></th>
                        <th>
                            {{ parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + NaNToZero(parseFloat(cur.income));

                            }, 0)).toFixed(2)
                            }} TL
                        </th>
                        <th></th>
                        <th>
                            {{ parseFloat(user.products.reduce(function(prev, cur) {
                            return prev + NaNToZero(parseFloat(cur.quantity));

                            }, 0)).toFixed(2)
                            }}
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tbody>
                </table>
                <!--                <hr>-->
                <!--                <div>-->
                <!--                    <span>{{ user.products.reduce(function(prev, cur) {-->
                <!--                            return prev + parseFloat(cur.price);-->
                <!--                            }, 0)-->
                <!--                        }}</span>-->
                <!--                </div>-->
            </div>
        </div>
        <!--  <div class="end-list col-md-12 col-sm-12">
              <div class="block">
                  <button type="button" class="btn-effect red-button">İMTİNA</button>
                  <button type="button" data-toggle="modal"
                          data-target=".mesaj" class="btn-effect green">SİFARİŞLƏR VERİLDİ
                  </button>
              </div>
          </div>-->
        <div v-if="show_change_modal" id="change-link" class="modal fade in" role="dialog" style="display: block">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click.prevent="show_change_modal = false">&times;</button>
                        <h4 class="modal-title">Linki dəyiş</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label >Link:</label>
                            <input type="text" class="form-control" v-model="selected_product.extras.link" >
                        </div>
                        <div class="form-group">
                            <a @click.prevent="updateLink(selected_product.id)" href="#" class="btn btn-primary">Dəyiş</a>
                            <a @click.prevent="show_change_modal = false" href="#" class="btn btn-danger">Bağla</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div v-if="show_problem_modal" id="add-problem" class="modal fade in" role="dialog" style="display: block">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click.prevent="show_problem_modal = false">&times;</button>
                        <h4 class="modal-title">Problem var</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label >Problem var:</label>
                                <input type="checkbox" v-model="selected_product.is_problem" value="1">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label >Problem metn:</label>
                                <input type="text" class="form-control" v-model="selected_product.problem_text">
                            </div>
                        </div>

                        <div class="form-group">
                            <a @click.prevent="addProblem(selected_product.id)" href="#" class="btn btn-primary">Əlavə et</a>
                            <a @click.prevent="show_problem_modal = false" href="#" class="btn btn-danger">Bağla</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade look mesaj" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="/admin/new/img/Forma1.png"
                                                                                                         alt="close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Müştərİyə mesaj getsİn?</h4>
                        <img src="/admin/new/img/mesaj.png" alt="mesaj">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <button type="button" class="btn-effect red-button" data-dismiss="modal">Xeyİr</button>
                                <button type="button" class="btn-effect green" data-dismiss="modal">Bəlİ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade look beyanname" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="/admin/new/img/Forma1.png"
                                                                                                         alt="close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="...">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="code">Müştəri kodu</label>
                                        <input type="text" name="code" class="form-control" id="code"
                                               placeholder="Müştəri kodu">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="shop">Mağaza</label>
                                                <input type="text" name="shop" class="form-control" id="shop"
                                                       placeholder="Mağaza">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="tip">Məhsulun tipi</label>
                                                <select class="selectpicker form-control" name="cins" id="tip"
                                                        title="Məhsulun tipi">
                                                    <option value="kazak">Kazak</option>
                                                    <option value="t-shirt">T-shirt</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="count">Miqdar</label>
                                                <input type="email" class="form-control" name="count" id="count"
                                                       placeholder="Miqdar">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="value">Məhsulun qiyməti (TL)</label>
                                                <input type="text" class="form-control" id="value" name="value"
                                                       placeholder="Məhsulun qiyməti">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="comment1"></label>
                                        <textarea class="form-control" rows="10" id="comment1"
                                                  placeholder="Şərh"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                    <button type="button" id="send-invoice" class="btn-effect">Göndər</button>
                                    <label for="file" class="form-group btn-effect">
                                        <span>invoys</span>
                                        <input required="" type="file" class="form-control-file" id="file" name="invoice">
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="payment_type ==2"  class="modal fade look in" id="select" tabindex="-1"  aria-labelledby="mySmallModalLabel" style="display: block">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>EFT/HEVALE</h4>
                        <button type="button" class="close" @click="payment_type = 1" aria-label="Close"><img src="img/Forma 1.png"
                                                                                                              alt="close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>İBAN №</label>
                                    <input v-model="iban" type="text" class="form-control" placeholder="İBAN nömrəniz">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Hesab №</label>
                                    <input v-model="account" type="text" class="form-control" placeholder="Hesab nömrəniz">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Vergi №</label>
                                    <input v-model="tax" type="text" class="form-control" placeholder="Vergi nömrəniz">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Ödəniş ediləcək şəxsin adı</label>
                                    <input v-model="paymentTo" type="text" class="form-control" placeholder="Ödəniş ediləcək şəxsin adı">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Müştəri adı, soyadı, kodu</label>
                                    <input :value="this.user.name + ' '+ this.user.surname + ' '+ this.user.uniqid" type="text" disabled class="form-control" placeholder="Ad, Soyad, Kod">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Ödəniləcək məbləğ</label>
                                    <input disabled :value="selectedItemsPrice"   type="text" class="form-control" placeholder="Ödəniləcək məbləğ">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <button @click="sendEFT" type="button" class="btn-effect green" data-dismiss="modal">GÖNDƏR</button>
                            </div>
                        </div>
                    </div>
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
        <rej-ref-modal v-bind:accountants="accountants" v-bind:data="null" v-bind:show="show" @close-modal="closeModal" @send-rejection="sendActs($event)"></rej-ref-modal>
        <send-invoice-data-modal v-bind:productData="productData" v-bind:show="showSendInvoiceModal" @close-modal="closeInvoiceModal" @send-invoice-data="sendInvoiceData($event)"></send-invoice-data-modal>
        <update-invoice-data-modal v-bind:productData="productData2" v-bind:show="showUpdatesInvoiceModal" @close-modal="closeUpdateInvoiceModal" @update-invoice-data="updateInvoiceData($event)"></update-invoice-data-modal>
        <show-invoice-data-modal v-bind:productData="showProductData" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal"></show-invoice-data-modal>

    </div>
</template>

<script>
    import RejRefModal from "../components/RejRefModal";
    import swal from 'sweetalert2';
    import PdfReaderModal from "../components/PdfReaderModal";
    import SendInvoiceDataModal from "../components/SendInvoiceDataModal";
    import UpdateInvoiceDataModal from "../components/UpdateInvoiceDataModal";
    import ShowInvoiceDataModal from "../components/showInvoiceDataModal";
    export default {
        components: {
            ShowInvoiceDataModal,
            UpdateInvoiceDataModal,
            SendInvoiceDataModal,
            PdfReaderModal, RejRefModal
        },
        name: "ExecutingusersComponent",
        data () {
            return {
                user: '',
                message: '',
                data: null,
                productData: null,
                productData2: null,
                showProductData: null,
                orderedButton: false,
                rejRefCompleteButton: false,
                show: false,
                showSendInvoiceModal: false,
                showUpdatesInvoiceModal: false,
                showShowInvoiceModal: false,
                accountants: [],
                actType: '',
                id: null,
                pdfReaderModal: false,
                file: null,
                disabledButton: false,
                percent: 5,
                percentTry: 5,

                addPriceResult: '',
                addPrice: 0,
                finalAddPrice: 0,
                addPriceDesc: '',

                addPriceResultTry: '',
                addPriceTry: 0,
                finalAddPriceTry: 0,
                addPriceDescTry: '',

                selected_items: [],
                payment_type: 1,
                iban: '',
                account: '',
                tax: '',
                paymentTo: '',
                eft_products: [],
                show_back_to_card: false,
                backToCards: [],
                back_to_card_reason: '',
                back_to_card_amount: 0,
                back_to_card_errors: [],
                accounts: [],
                show_change_modal: false,
                show_problem_modal: false,
                selected_product: {},
                sms_templates: '',
                sms_template_id: '',
                clients: null,
                corporate: 0,
                is_premium: 0

            }
        },
        computed: {
            selectedItemsPrice: function(){
                let sum_price = this.eft_products.reduce((a,b) => {
                    return a + b.amount;
                },0)
                return sum_price.toFixed(2)
            }
        },
        methods:{
            idToUniq(id){
                id = id.toString();
              var new_id = id.padStart(6, 0);
              return 1+new_id;
            },
            NaNToZero(val){
                if(isNaN(val) || val == undefined){
                    return 0
                }
                return val
            },
            updateLink(id){
                axios.post('/cp/orders/update/'+id, {
                    link: this.selected_product.extras.link
                }).then((data) => {
                    this.show_change_modal = false;
                    this.selected_product = {}
                });
            },
            addProblem(id){
                axios.post('/cp/orders/problem/'+id, {
                    problem_text: this.selected_product.problem_text,
                    is_problem: this.selected_product.is_problem,
                }).then((data) => {
                    this.show_problem_modal = false;
                    this.selected_product = {};
                });
            },
            handleSelectedProduct(product){
                if(product.selected){
                    let amount = product.real_price ? parseFloat(product.real_price) : 0;
                    this.eft_products.push({
                        id: product.id,
                        amount: amount,
                        link: product.extras.link
                    });
                    this.backToCards.push({
                        id: product.id,
                        transaction: product.rel_user_products.transaction_id,
                        payment: product.price,
                    })
                } else{
                    let indexEFT = this.eft_products.findIndex((pr) =>{
                        return pr.id == product.id;
                    });
                    this.eft_products.splice(indexEFT, 1);

                    let indexBackToCard = this.backToCards.findIndex((pr) =>{
                        return pr.id == product.id;
                    });
                    this.backToCards.splice(indexBackToCard, 1);
                }
            },
            sendEFT(){
                if(this.user.id && this.paymentTo && this.iban && this.account && this.tax ){
                    axios.post('/cp/accounts/eft', {
                        products: this.eft_products,
                        user: this.user.id,
                        paymentTo: this.paymentTo,
                        iban: this.iban,
                        account: this.account,
                        tax: this.tax,
                        amount: this.amount,
                    }).then((data) => {
                        this.payment_type= 1,
                            this.paymentTo= '',
                            this.iban= '',
                            this.account = '',
                            this.tax= ''
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
                if(this.backToCards.length == 0 ){
                    this.back_to_card_errors.push('Heç bir məhsul seçilməyib')
                }
                if( this.back_to_card_errors.length ==0  ){
                    axios.post('/cp/accounts/backToCard', {
                        products: this.backToCards,
                        user: this.user.id,
                        reason: this.back_to_card_reason,
                        amount: this.back_to_card_amount,
                    }).then((data) => {
                        this.show_back_to_card = false;
                    });
                }
            },
            getOrderDetails(){
                //alert(this.$route.params.region);
                axios.get(`/cp/getOrderDetailByUser5/${this.$route.params.id}?region_id=${this.$route.params.region}&client_id=${this.$route.params.client_id}`)
                    .then((response) => {
                        this.user = response.data.data;
                        this.user.products = this.user.not_ordered_products;
                        if(this.user.products!=null){
                            this.product0 = this.user.products[0];
                            this.corporate = this.product0.corporate;
                            this.is_premium = this.product0.is_premium;
                            this.clients = this.product0.clients;
                        }

                    })
                    .catch(function (error) {
                    });
            },

            deleteProduct(product) {
                axios.delete(`/cp/orders/delete/`+product.id)
                    .then((response) => {
                        this.getOrderDetails();
                    })
                    .catch(function (error) {
                    });
            },

            copyTestingCode () {
                let testingCodeToCopy = document.querySelector('#copy');
                testingCodeToCopy.setAttribute('type', 'text');
                testingCodeToCopy.select();

                try {
                    var successful = document.execCommand('copy');
                    var msg = successful ? 'successful' : 'unsuccessful';
                } catch (err) {
                }

                /* unselect the range */
                testingCodeToCopy.setAttribute('type', 'hidden');
                window.getSelection().removeAllRanges();
            },

            rejection(id, actType) {
                this.show = true;
                this.actType = actType;
                this.id = id;
                axios.get('/admin/accountants')
                    .then((data) => {
                        this.accountants = data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    })

            },

            sendActs(data) {
                const url = this.actType === 'rejection' ? '/admin/rejection' : '/admin/refusal';
                axios.post(url, {
                    accountantId: data.accountantId,
                    note: data.note,
                    id: this.id
                }).then((data) => {
                    if (data.data.status === 200) {
                        this.show = false;
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə göndərildi',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            if (this.actType === 'rejection') {
                                this.getOrderDetails();
                            } else {
                                this.$router.push('/my-orders');
                            }
                        });
                    }
                });
            },

            finishOrder(transactionId) {
                this.disabledButton = true;
                axios.post('/admin/order/finish', {
                    transactionId: transactionId
                }).then((data) => {
                    if (data.data.status === 200) {
                        swal({
                            type: 'success',
                            title: 'Tamamlandı',
                            showConfirmButton: true,
                        }).then(() => {
                            this.$router.push('/my-orders');
                        });
                    }
                }).catch(err => {
                    console.log(err);
                })
            },

            ordered(id) {
                swal({
                    title: 'Sifariş verilməsi ilə bağlı sms və mail müştəriyə göndərilsin?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Bəli',
                    cancelButtonText: 'Xeyr'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/admin/order/ordered', {
                            id: id
                        }).then((data) => {
                            if (data.data.status === 200) {
                                this.getOrderDetails();
                                swal({
                                    type: 'success',
                                    title: 'Müvəffəqiyyətlə göndərildi',
                                    showConfirmButton: true,
                                });
                            }
                        })
                    }
                });
            },

            showInvoiceModal() {
                let selected_products = [];
                let productData = {
                    ids: [],
                    price: 0,
                    quantity: 0,
                    shop_name: ''
                };
                productData.ids = [];
                productData.expenses = 0;
                productData.price = 0;
                productData.customer_price = 0;
                console.log(this.user.products.length);
                for(let i in this.user.products){
                    if(this.user.products[i].selected === true){
                        if(i == 0){
                            productData = Object.assign(productData, this.user.products[i]);
                            productData.price = parseFloat(this.user.products[i].real_price === null ? 0: this.user.products[i].real_price);
                            productData.customer_price = parseFloat(this.user.products[i].price === null ? 0: this.user.products[i].price);
                            productData.expenses = parseFloat(this.user.products[i].expenses === null ? 0: this.user.products[i].expenses);
                        }else{
                            productData.price += parseFloat(this.user.products[i].real_price === null ? 0: this.user.products[i].real_price);
                            productData.expenses+= parseFloat(this.user.products[i].expenses === null ? 0: this.user.products[i].expenses);
                            productData.customer_price += parseFloat(this.user.products[i].price === null ? 0: this.user.products[i].price);
                            productData.quantity += this.user.products[i].quantity;
                        }
                        productData.ids.push(this.user.products[i].id);

                        //if(parseFloat(this.data.products[i].extras.cargo_price)>0) productData.price += this.data.products[i].price;
                        selected_products.push(this.user.products[i]);
                    }
                }

                if(productData.ids.length==0){
                    alert("Ay insan məhsul seç");
                }else{
                    if((productData.expenses-productData.customer_price)>30)
                    {
                        alert("30 tl-den artiq odenis");
                    }
                    axios.get(`/cp/accounts/forOrderPayments`)
                        .then((response) => {
                            productData.accounts = response.data.data;
                            this.productData = JSON.parse(JSON.stringify(productData));
                            console.log("sss");
                            console.log(this.productData);
                            this.showSendInvoiceModal = true;
                        })
                        .catch(function (error) {
                        });
                }




            },

            accountsForOrderPayments() {

            },

            showUpdateInvoiceModal(productData) {
                this.showUpdatesInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.productData2 = data;
            },

            sendInvoiceData (data) {
                axios.post('/admin/order/send/invoiceData', data)
                    .then((result) => {
                        if (result.data.status === 200) {
                            this.getOrderDetails();
                            this.showSendInvoiceModal = false;
                            swal({
                                type: 'success',
                                title: result.data.message,
                                showConfirmButton: true,
                            });
                        } else {
                        }
                    }).catch(err => {
                    // alert(err);
                });
            },

            updateInvoiceData (data) {
                axios.post('/admin/order/update/invoiceData', data)
                    .then((result) => {
                        if (result.data.status === 200) {
                            this.getOrderDetails();
                            this.showUpdatesInvoiceModal = false;
                            swal({
                                type: 'success',
                                title: result.data.message,
                                showConfirmButton: true,
                            });
                        } else {
                            //alert(result.data.message);
                        }
                    }).catch(err => {
                    //alert(err);
                });
            },

            showFile(productData) {
                this.showShowInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.showProductData = data;
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
                        //alert('Server xətası');
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

            saveRealPrice(product) {

                let data = {
                    product_id: product.id,
                    real_price: product.real_price,
                    over_price: product.over_price,
                    income: product.income,
                    expenses: product.expenses
                };
                axios.post('/admin/product/saveRealPrice', data).then((result) => {

                }).catch(err => {
                    alert('Server xətası');
                });
            },

            addBalance(type) {
                let data = {};
                data.user_id = this.user.id;
                data.type = type;
                data.money = this.finalAddPrice;
                let desc = '';
                if(this.clients!=null){
                     desc = this.clients.name+' '+ this.clients.surname+' ';
                }
                data.desc = desc+this.addPriceDesc;


                if(this.finalAddPrice){
                    axios.post('/cp/users/addBalance', data).then((result) => {
                        if(!result.data.success) alert(result.data.message);
                        if(result.data.success) alert('Balans yeniləndi');
                        this.getOrderDetails();

                    }).catch(err => {
                        alert('Server xətası');
                    });
                }
            },

            addBalanceTry(type) {
                let data = {};
                data.user_id = this.user.id;
                data.type = type;
                data.money = this.finalAddPriceTry;
                data.desc = this.addPriceDescTry;

                if(this.finalAddPriceTry){
                    axios.post('/cp/users/addBalanceTry', data).then((result) => {
                        if(!result.data.success) alert(result.data.message);
                        if(result.data.success) alert('Balans yeniləndi');
                        this.getOrderDetails();

                    }).catch(err => {
                        alert('Server xətası');
                    });
                }
            },

            sendSms(){
                axios.post('cp/orders/sendSMS', {
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

            closeModal() {
                this.show = false;
            },

            closeInvoiceModal() {
                this.showSendInvoiceModal = false;
            },

            closeUpdateInvoiceModal() {
                this.showUpdatesInvoiceModal = false;
            },

            closeShowInvoiceModal () {
                this.showShowInvoiceModal = false;
            },

            closePdfReaderModal() {
                this.pdfReaderModal = false;
            },

            setOverPrice(product){
                product.over_price = ((parseFloat(product.price) + parseFloat(product.extras.cargo_price ? product.extras.cargo_price : 0)).toFixed(2) - parseFloat(product.real_price)).toFixed(2);
                this.saveRealPrice(product);
            },

            setIncome(product){
                product.income = (parseFloat(product.expenses)  - parseFloat(product.expenses)).toFixed(2);
                this.saveRealPrice(product);
            }
        },
        mounted(){
            $(document).ready(function () {
                $('.selectpicker').selectpicker();
            });
            this.getOrderDetails();
            this.getSmsTemplates();
        },
    }

</script>

<style scoped>
    .errors{
        margin-top: 10px;
    }
    #change-link .btn{
        width: 49%;
    }
    #change-link{
        top: 200px;
    }

    .last_tr{
        border-top: 3px solid #000 !important;
    }

    .last_tr th{
        font-size: 12px !important;
    }
</style>