<template>
    <div class="col-md-9 col-sm-11 col-xs-11  order-body address courier">
        <div class="row">
            <div class="col-xs-12">
                <div class="block">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#courier" aria-controls="home" role="tab" data-toggle="tab">Bakı və Sumqayıt üzrə</a>
                        </li>
                        <li role="presentation">
                            <a href="#transfer" aria-controls="profile" role="tab" data-toggle="tab">Regionlar üzrə</a>
                        </li>
                        <!--<li role="presentation">
                            <a href="#3" aria-controls="messages" role="tab" data-toggle="tab" class="order-img3">Rusiya`dan</a>
                        </li>
                        <li role="presentation">
                            <a href="#4" aria-controls="settings" role="tab" data-toggle="tab" class="order-img4">Almaniya`dan</a>
                        </li>
                        <li class="last" role="presentation">
                            <a href="#5" aria-controls="settings" role="tab" data-toggle="tab" class="order-img5">Dubay`dan</a>
                        </li>-->
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="courier">

                            <div class="form-list">
                                <form action="...">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="invoice-day input-group border-radius">
                                                <v-select v-model="city" :options="locations"
                                                ></v-select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.region" type="text" name="region"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.region') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[4]">{{errors[4].region}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.village" type="text" name="village"
                                                           class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.village') : ''}} </span>
                                                </label>
                                                <!--<p style="color:red" v-if="errors[7]">{{errors[7].village}}</p>-->

                                            </div>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.street" type="text" name="street"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.street') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[5]">{{errors[5].street}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.home" type="text" name="home"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.home') :''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[6]">{{errors[6].home}}</p>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.phone" type="number" name="tel"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.phone') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[0]">{{errors[0].phone}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <ul class="select-outer" v-if="products">
                                                    <li class="menu__item menu__item--dropdown form-control"
                                                        v-bind:class="{'open' : dropDowns.ranking.open}">
                                                        <p class="menu__link menu__link--toggle">
                                                            <span> {{ExWindow ? ExWindow.translator('panel-errors.choose_package') : ''}}</span>
                                                        </p>

                                                        <ul class="dropdown-menu">
                                                            <li v-for="item in products" class="select-item"><label
                                                                    data-v-8f91fc86="" class="check-button"><span
                                                                    data-v-8f91fc86="" class="check-text">{{item.name}}</span>
                                                                <input data-v-8f91fc86="" type="checkbox"
                                                                       @change="selectAll(item)" v-model="item.check"> <span
                                                                        data-v-8f91fc86="" class="checkmark"></span></label>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <p style="color:red" v-if="errors[3]">{{errors[3].products}}</p>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 button-part">
                                            <button type="submit" class="btn-effect" @click="checkForm($event)">{{ExWindow ?
                                                ExWindow.translator('courier.accept') : ''}}
                                            </button>
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.delivery_desc') : ''}}</span>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div v-if="courierData.length != 0" class="row" style='margin-top:30px'>
                                <div class="col-xs-12">
                                    <div class="block">
                                        <div class="coin-table">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.delivery_desc') : ''}}</th>
                                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.product') : ''}}</th>
                                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.status') : ''}}</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(item, index) of courierData">
                                                    <td>{{index + 1}}</td>
                                                    <td>{{item.city}} {{item.district}} {{item.village}} {{item.street}} {{item.home}}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" @click="showProduct(item)"
                                                           class="btn btn-warning">{{ExWindow ? ExWindow.translator('panel-errors.look') : ''}}</a>
                                                    </td>
                                                    <td><span style="cursor:initial;font-weight: bold;"
                                                              :class="{'text-success': item.has_courier === 1, 'text-info':item.has_courier !== 1}">{{item.has_courier === 1? ExWindow ? ExWindow.translator('panel-errors.courier_defined') : '': ExWindow ? ExWindow.translator('panel-errors.courier_not_defined') : ''}}</span>
                                                    </td>
                                                    <td><a v-if="item.is_paid === 0" style="cursor:pointer" @click="payCourier(item.id)"
                                                           class="btn btn-success">{{ExWindow ? ExWindow.translator('panel-errors.pay') : ''}}</a><span
                                                            style="padding:10px 5px;color:#fff;background:#f95732"
                                                            v-if="item.is_paid === 1">{{ExWindow ? ExWindow.translator('panel-errors.paid') : ''}}</span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!---------------------------------------------------------------------------------------------->
                        <div role="tabpanel" class="tab-pane fade" id="transfer">
                            <div class="form-list">
                                <form action="...">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="invoice-day input-group border-radius">
                                                <label class="select-class">{{this.ExWindow && this.ExWindow.translator('panel-errors.choose_city')}}</label>
                                                <v-select v-model="formTransfer.city" :options="regionLocations" v-on:change="changeRegion()"></v-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12" v-if="hasRegion>0">
                                            <div class="invoice-day input-group border-radius">
                                                <label class="select-class">{{this.ExWindow && this.ExWindow.translator('panel-errors.choose_postindex')}}</label>
                                                <v-select v-model="formTransfer.post_index" :options="regionIndexes"></v-select>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="formTransfer.region" type="text" name="region"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.region') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[4]">{{errors[4].region}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="formTransfer.village" type="text" name="village"
                                                           class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.village') : ''}} </span>
                                                </label>
                                                <!--<p style="color:red" v-if="errors[7]">{{errors[7].village}}</p>-->

                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="formTransfer.street" type="text" name="street"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.street') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[5]">{{errors[5].street}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="formTransfer.home" type="text" name="home"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.home') :''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[6]">{{errors[6].home}}</p>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="formTransfer.phone" type="number" name="tel"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.phone') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[0]">{{errors[0].phone}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <ul class="select-outer" v-if="products">
                                                    <li class="menu__item menu__item--dropdown form-control"
                                                        v-bind:class="{'open' : dropDowns.ranking.open}">
                                                        <p class="menu__link menu__link--toggle">
                                                            <span> {{ExWindow ? ExWindow.translator('panel-errors.choose_package') : ''}}</span>
                                                        </p>

                                                        <ul class="dropdown-menu">
                                                            <li v-for="item in products" class="select-item" v-on:change="getCargoPrice(item.id)"><label
                                                                    data-v-8f91fc86="" class="check-button"><span
                                                                    data-v-8f91fc86="" class="check-text">{{item.name}}</span>
                                                                <input data-v-8f91fc86="" type="checkbox"
                                                                       @change="selectAll(item)" v-model="item.check"> <span
                                                                        data-v-8f91fc86="" class="checkmark"></span></label>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <p style="color:red" v-if="errors[3]">{{errors[3].products}}</p>
                                                </ul>
                                            </div>
                                        </div>
                                        <hr />



                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>Poçt xidməti (AZN)*</label>
                                                <input v-model="cargoPrice" type="text" class="form-control inputText" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>Çatdırılma qiyməti (AZN)* </label>
                                                <input v-model="shippingPrice" type="text" class="form-control inputText" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>Ümumi çəki (kq)*</label>
                                                <input v-model="allWeight" type="text" class="form-control inputText" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label class="select-class">Ümumi qiymət (AZN)*</label>
                                                <input v-model="sumPrice" type="text" class="form-control inputText" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 button-part">
                                            <button type="submit" class="btn-effect" @click="checkTransferForm($event)">{{ExWindow ? ExWindow.translator('courier.pay') : ''}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div v-if="transferData.length != 0" class="row" style='margin-top:30px'>
                                <div class="col-xs-12">
                                    <div class="block">
                                        <div class="coin-table">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.delivery_desc') : ''}}</th>
                                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.product') : ''}}</th>
                                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.status') : ''}}</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(item, index) of transferData">
                                                    <td>{{index + 1}}</td>
                                                    <td>{{item.city}} {{item.district}} {{item.village}} {{item.street}} {{item.home}}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" @click="showProduct(item)"
                                                           class="btn btn-warning">{{ExWindow ? ExWindow.translator('panel-errors.look') : ''}}</a>
                                                    </td>
                                                    <td><span style="cursor:initial;font-weight: bold;"
                                                              :class="{'text-success': item.status_id === 1, 'text-info':item.status_id !== 1}">
                                                        {{item.status_id === 0? ExWindow ? ExWindow.translator('panel-errors.waiting') : '': ExWindow ? ExWindow.translator('panel-errors.in_post') : ''}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            style="padding:10px 5px;color:#fff;background:#f95732"
                                                            v-if="item.is_paid === 1">{{ExWindow ? ExWindow.translator('panel-errors.paid') : ''}}</span>
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
            </div>
        </div>
        <products-modal v-bind:productData="showProductData" v-bind:show="ProductsModal" @close-modal="closeShowProduct"></products-modal>

        <!--
                <div class="row">
                    <div class="col-xs-12 courier-log">
                        <div class="block col-xs-12">
                            <h3>Kuryer</h3>
                            <div class="form-list">
                                <form action="...">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="invoice-day input-group border-radius">
                                                <v-select v-model="city" :options="locations"
                                                          ></v-select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.region" type="text" name="region"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.region') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[4]">{{errors[4].region}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.village" type="text" name="village"
                                                           class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.village') : ''}} </span>
                                                </label>
                                                &lt;!&ndash;<p style="color:red" v-if="errors[7]">{{errors[7].village}}</p>&ndash;&gt;

                                            </div>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.street" type="text" name="street"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.street') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[5]">{{errors[5].street}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.home" type="text" name="home"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.home') :''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[6]">{{errors[6].home}}</p>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input v-model="form.phone" type="number" name="tel"
                                                           class="form-control inputText"
                                                           placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('courier.phone') : ''}} *</span>
                                                </label>
                                                <p style="color:red" v-if="errors[0]">{{errors[0].phone}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group border-radius">
                                                <ul class="select-outer" v-if="products">
                                                    <li class="menu__item menu__item&#45;&#45;dropdown form-control"
                                                        v-bind:class="{'open' : dropDowns.ranking.open}">
                                                        <p class="menu__link menu__link&#45;&#45;toggle">
                                                            <span> {{ExWindow ? ExWindow.translator('panel-errors.choose_package') : ''}}</span>
                                                        </p>

                                                        <ul class="dropdown-menu">
                                                            <li v-for="item in products" class="select-item"><label
                                                                    data-v-8f91fc86="" class="check-button"><span
                                                                    data-v-8f91fc86="" class="check-text">{{item.name}}</span>
                                                                <input data-v-8f91fc86="" type="checkbox"
                                                                       @change="selectAll(item)" v-model="item.check"> <span
                                                                        data-v-8f91fc86="" class="checkmark"></span></label>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <p style="color:red" v-if="errors[3]">{{errors[3].products}}</p>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 button-part">
                                            <button type="submit" class="btn-effect" @click="checkForm($event)">{{ExWindow ?
                                                ExWindow.translator('courier.accept') : ''}}
                                            </button>
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.delivery_desc') : ''}}</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="courierData.length != 0" class="row" style='margin-top:30px'>
                    <div class="col-xs-12">
                        <div class="block">
                            <div class="coin-table">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ExWindow ? ExWindow.translator('panel-errors.delivery_desc') : ''}}</th>
                                        <th>{{ExWindow ? ExWindow.translator('panel-errors.product') : ''}}</th>
                                        <th>{{ExWindow ? ExWindow.translator('panel-errors.status') : ''}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) of courierData">
                                        <td>{{index + 1}}</td>
                                        <td>{{item.city}} {{item.district}} {{item.village}} {{item.street}} {{item.home}}</td>
                                        <td>
                                            <a href="javascript:void(0)" @click="showProduct(item)"
                                               class="btn btn-warning">{{ExWindow ? ExWindow.translator('panel-errors.look') : ''}}</a>
                                        </td>
                                        <td><span style="cursor:initial;font-weight: bold;"
                                                  :class="{'text-success': item.has_courier === 1, 'text-info':item.has_courier !== 1}">{{item.has_courier === 1? ExWindow ? ExWindow.translator('panel-errors.courier_defined') : '': ExWindow ? ExWindow.translator('panel-errors.courier_not_defined') : ''}}</span>
                                        </td>
                                        <td><a v-if="item.is_paid === 0" style="cursor:pointer" @click="payCourier(item.id)"
                                               class="btn btn-success">{{ExWindow ? ExWindow.translator('panel-errors.pay') : ''}}</a><span
                                                style="padding:10px 5px;color:#fff;background:#f95732"
                                                v-if="item.is_paid === 1">{{ExWindow ? ExWindow.translator('panel-errors.paid') : ''}}</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <products-modal v-bind:productData="showProductData" v-bind:show="ProductsModal"
                                @close-modal="closeShowProduct"></products-modal>-->
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import swal from 'sweetalert2';
    import ProductsModal from "./modals/ProductsModal";
    import vSelect from 'vue-select';


    export default {
        components: {
            Multiselect, ProductsModal, vSelect
        },
        name: "kuryer",

        mounted() {
            this.getInvoices();
            this.getCourierData();
            this.getTransferData();
            this.getRegions();
            this.ExWindow = window;
            window.addEventListener('click',  (e) => {
                let array = Array.from(e.target.parentNode.classList);
                if(this.dropDowns.ranking.open === true && array.indexOf('select-item') < 0 && array.indexOf('check-button') < 0){
                    this.dropDowns.ranking.open = false;
                }else if(array.indexOf('menu__link') >= 0){
                    this.dropDowns.ranking.open = !this.dropDowns.ranking.open;
                }
            }, false)
                this.locations = [
                {id: 0, name: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city'), label: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city')},
                {id: 1, name: this.ExWindow && this.ExWindow.translator('panel-errors.baku'), label: this.ExWindow && this.ExWindow.translator('panel-errors.baku')},
                {id: 2, name: this.ExWindow && this.ExWindow.translator('panel-errors.sumgait'), label: this.ExWindow && this.ExWindow.translator('panel-errors.sumgait')}
            ]
            this.city = {id: 0, name: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city'), label: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city')}
            this.regionLocations = [
                {i: 0, name: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city'), label: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city')},
                {id: 1, name: 'Gence', label: 'Gəncə'},
                {id: 2, name: 'Qazax', label: 'Qazax'}
            ]
        },
        data: function () {
            return {
                locations: [],
                regionLocations: [],
                regionIndexes: [],
                ExWindow: null,
                ProductsModal: false,
                showProductData: null,
                hasRegion: null,
                invoices: null,
                userBalance: null,
                usd: null,
                sumPrice: null,
                shippingPrice: null,
                cargoPrice: null,
                cargoPriceWeight: 1.6,
                allWeight: null,
                form: {
                    phone: null,
                    city: 'Bakı',
                    region: null,
                    village: null,
                    street: null,
                    home: null,
                    time: 'standart',
                    products: [],
                },
                formTransfer: {
                    phone: null,
                    city: 0,
                    region: null,
                    village: null,
                    street: null,
                    home: null,
                    post_index: 0,
                    time: 'standart',
                    products: [],
                },
                products: [{
                    name: 'Hamısı',
                    i_id: 0
                }],
                courierData: [],
                transferData: [],
                errors: [],
                city: {},
                dropDowns: {
                    ranking: {open: false}
                }

            }
        },

        methods: {
            // getCity() {
            //     if (this.city === null) {
            //         this.locations = [
            //             {id: 1, name: 'Bakı', label: 'Bakı'},
            //             {id: 2, name: 'Sumqayıt', label: 'Sumqayıt'}
            //         ];
            //     }
            //     this.form.city = this.city.name;
            // },

            getRegions(){
                axios.get('/site/getPostRegions')
                    .then(data => {
                        let list=[];
                        $.each(data.data.data, function(key, value) {
                            list.push(value);
                        });
                        this.regionLocations = list;

                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            changeRegion() {
                this.hasRegion = this.formTransfer.city.id;
                if(this.hasRegion>0){
                    this.formTransfer.post_index = 0;
                    axios.get('/site/getPostIndexes/'+this.hasRegion)
                        .then(data => {
                            let list=[];
                            $.each(data.data.data, function(key, value) {
                                list.push(value);
                            });
                            this.regionIndexes = list;
                            //console.log(this.regionIndexes);
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
            },
            checkTransferForm(e) {

                e.preventDefault();
                this.formTransfer.products = [];
                for (let i in this.products) {
                    if (this.products[i].check && this.products[i].i_id !== 0) this.formTransfer.products.push(this.products[i]);
                }
                if (this.checkTransferFormData() === 'true') {
                    this.formTransfer.sumPrice = this.sumPrice;
                    this.formTransfer.sumWeight = this.allWeight;
                    this.formTransfer.transferPrice = this.cargoPrice;
                    this.formTransfer.shippingPrice = this.shippingPrice;

                    swal({
                        title: this.ExWindow ? this.ExWindow.translator('panel-errors.sending') : '',
                        onOpen: () => {
                            swal.showLoading();
                                axios.post('/user-panel/post-transfer-data', this.formTransfer)
                                    .then((response) => {
                                        swal.close();
                                        if (response.data.code == 500) {
                                            swal(
                                                this.ExWindow && this.ExWindow.translator('panel-errors.fill_inputs'),
                                                'error'
                                            );
                                        } else if(response.data.code == 300){
                                            swal({
                                                title: 'Diqqət',
                                                text: this.ExWindow && this.ExWindow.translator('panel-errors.insufficient_balance'),
                                                type: 'warning'
                                            }).then((redButton) => {
                                                if (redButton) {
                                                    return this.$router.push('/balance');
                                                }
                                            });
                                        }
                                        else {
                                            swal(
                                                this.ExWindow && this.ExWindow.translator('panel-errors.completed2'),
                                                this.ExWindow && this.ExWindow.translator('panel-errors.completed_successfully'),
                                                'success'
                                            );


                                            this.formTransfer = {
                                                phone: '',
                                                city: 'Baku',
                                                region: '',
                                                village: '',
                                                street: '',
                                                home: '',
                                                time: 'standart',
                                                products: [],
                                            };

                                            this.sumPrice = '';
                                            this.cargoPrice = '';
                                            this.shippingPrice = '';
                                            this.allWeight = '';
                                            this.products = [];
                                            this.getInvoices();
                                            this.getCourierData();
                                        }
                                    })
                                    .catch(function (error) {
                                        console.log(error);
                                    });
                        }
                    })
                } else {
                    // console.log(this.errors)
                }
            },
            checkTransferFormData() {
                this.errors = [];
                if (!this.formTransfer.phone) this.errors[0] = {phone: this.ExWindow && this.ExWindow.translator('panel-errors.fill_number')}
                if (!this.formTransfer.city) this.errors[1] = {city: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city')}
                if (!this.formTransfer.time) this.errors[2] = {time: this.ExWindow && this.ExWindow.translator('panel-errors.select_delivery_time')}
                // if (!this.form.village) this.errors[7] = {village: 'Qəsəbəni qeyd edin'}
                if (this.formTransfer.products.length === 0) this.errors[3] = {products: this.ExWindow && this.ExWindow.translator('panel-errors.select_product')}
                if (!this.formTransfer.region) this.errors[4] = {region: this.ExWindow && this.ExWindow.translator('panel-errors.select_district')}
                if (!this.formTransfer.street) this.errors[5] = {street: this.ExWindow && this.ExWindow.translator('panel-errors.select_street')}
                if (!this.formTransfer.home) this.errors[6] = {home: this.ExWindow && this.ExWindow.translator('panel-errors.select_home')}
                return (this.errors.length === 0 ? 'true' : 'false')
            },

            getCargoPrice()
            {
                this.shippingPrice = 0;
                this.cargoPrice = 0;
                this.allWeight = 0;
                this.sumPrice = 0;
                this.formTransfer.products = [];
                for (let i in this.products) {
                    if (this.products[i].check && this.products[i].i_id !== 0){
                        this.formTransfer.products.push(this.products[i]);
                        if(this.products[i].is_paid==0){
                            this.shippingPrice = (parseFloat(this.shippingPrice)+parseFloat(this.products[i].shipping_price)).toFixed(2);
                        }
                        this.allWeight = (parseFloat(this.allWeight)+parseFloat(this.products[i].weight)).toFixed(3);
                        this.cargoPrice = (parseFloat(this.cargoPrice)+parseFloat(this.allWeight)*parseFloat(this.cargoPriceWeight)).toFixed(2);
                    }
                };
                this.shippingPrice = parseFloat(this.shippingPrice*this.usd).toFixed(2);
                this.sumPrice = (parseFloat(this.cargoPrice)+parseFloat(this.shippingPrice)).toFixed(2);

            },



            //////////////////////////////////////////////////////////////////////////////////

            selectOneselectOne(item) {
                this.form.products.push(item)
                /*console.log(event.target)
                console.log(this.form.products)*/

            },
            showProduct(item) {
                this.ProductsModal = true;
                var data = JSON.parse(JSON.stringify(item));
                this.showProductData = data;
            },

            getInvoices() {
                axios.get('/user-panel/get-invoices/home?invoice_courier=0')
                    .then(data => {
                        //this.invoices = data.data.data;
                        this.userBalance = data.data.balance;
                        this.usd = data.data.index.usd;
                        data.data.data.forEach(invoice => {
                            this.products.push({
                                name: invoice.product_type_name,
                                i_id: invoice.id,
                                weight: invoice.weight,
                                shipping_price: parseFloat(invoice.sum_i_shprice).toFixed(2),
                                is_paid: invoice.i_is_paid,
                            });

                        });
                    })
                    .catch(err => {
                        console.log(err);
                    });

            },
            selectAll(item) {
                if (item.i_id === 0) {
                    this.products.map((order) => {
                        order.check = item.check;
                    })
                }
                this.getCargoPrice();
            },
            checkForm(e) {
                e.preventDefault();
                this.form.products = [];
                for (let i in this.products) {
                    if (this.products[i].check && this.products[i].i_id !== 0) this.form.products.push(this.products[i]);
                }
                if (this.checkFormData() === 'true') {
                    if(this.city.id == 1 ){
                        this.form.city = 'Bakı'
                    } else if(this.city.id == 2 ){
                        this.form.city = 'Sumqayıt'
                    }
                    axios.post('/user-panel/post-courier-data', this.form)
                        .then((response) => {
                            if (response.data.code == 500) {
                                swal(
                                    this.ExWindow && this.ExWindow.translator('panel-errors.fill_inputs'),
                                    'error'
                                );
                            } else {
                                swal(
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed2'),
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed_successfully'),
                                    'success'
                                );
                                this.form = {
                                    phone: '',
                                    city: 'Baku',
                                    region: '',
                                    village: '',
                                    street: '',
                                    home: '',
                                    time: 'standart',
                                    products: []
                                };
                                this.products = [];
                                this.getInvoices();
                                this.getCourierData();
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else {
                    // console.log(this.errors)
                }
            },
            checkFormData() {
                this.errors = [];
                if (!this.form.phone) this.errors[0] = {phone: this.ExWindow && this.ExWindow.translator('panel-errors.fill_number')}
                if (!this.form.city) this.errors[1] = {city: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city')}
                if (!this.form.time) this.errors[2] = {time: this.ExWindow && this.ExWindow.translator('panel-errors.select_delivery_time')}
                // if (!this.form.village) this.errors[7] = {village: 'Qəsəbəni qeyd edin'}
                if (this.form.products.length === 0) this.errors[3] = {products: this.ExWindow && this.ExWindow.translator('panel-errors.select_product')}
                if (!this.form.region) this.errors[4] = {region: this.ExWindow && this.ExWindow.translator('panel-errors.select_district')}
                if (!this.form.street) this.errors[5] = {street: this.ExWindow && this.ExWindow.translator('panel-errors.select_street')}
                if (!this.form.street) this.errors[6] = {home: this.ExWindow && this.ExWindow.translator('panel-errors.select_home')}
                return (this.errors.length === 0 ? 'true' : 'false')
            },
            getCourierData() {
                axios.get('/user-panel/get-courier-data')
                    .then(data => {
                        this.courierData = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            getTransferData() {
                axios.get('/user-panel/get-transfer-data')
                    .then(data => {
                        this.transferData = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            payCourier(courierId) {
                swal({
                    title: this.ExWindow && this.ExWindow.translator('panel-errors.are_you_sure'),
                    text: this.ExWindow && this.ExWindow.translator('panel-errors.payment_process'),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.yes'),
                    cancelButtonText: this.ExWindow && this.ExWindow.translator('panel-errors.no')
                }).then((result) => {

                    if (result.value) {
                        axios.post('/user-panel/pay-courier', {courierId: courierId})
                            .then((response) => {
                                if (response.data.code === 1601) {
                                    swal(this.ExWindow && this.ExWindow.translator('panel-errors.insufficient_balance'));
                                    setTimeout(() => {
                                        window.location = '/' + this.ExWindow.default_locale + '/user-panel#/balance'
                                    }, 500)
                                } else {
                                    swal(
                                        this.ExWindow && this.ExWindow.translator('panel-errors.payed'),
                                        'success'
                                    )
                                    this.$emit('changeBalance', {});
                                    this.getInvoices();
                                    this.getCourierData();
                                }
                            });
                    }
                })
            },
            closeShowProduct() {
                this.ProductsModal = false;
            }
        }
    }
</script>

<style>

    .select-outer {
        position: relative;
        list-style: none;
        padding: 0;
    }

    .select-outer .menu__item {
        padding: 0;
    }

    .menu__item--dropdown {
        margin: 0;
        height: 100%;
        cursor: pointer;
        line-height: 24px;
        padding: 10px;
        position: relative;
        border: 1px solid #d2d4d5;
    }

    .menu__item--dropdown p {
        margin: 0;
    }

    .menu__item--dropdown p span {
        margin: 0;
        padding: 10px;
        display: block;
        line-height: 20px;
    }

    .select-outer .dropdown-menu {
        width: 100%;
        padding: 0;
        margin: 0;
    }

    .select-outer .dropdown-menu li {
        cursor: pointer;
        padding: 10px;
        position: relative;
        border-bottom: 1px solid #d2d4d5;
    }

    .select-outer .dropdown-menu li:last-child, .select-outer .dropdown-menu li:first-child {
        border: none;
    }

    .select-item label span.check-text {
        width: 100%;
        padding: 0;
        position: relative;
    }

    .check-button .checkmark {
        top: 50%;
        transform: translateY(-50%);
    }

</style>
