<template>
    <div class="kuryer-kabinet">
        <div class="right-side col-md-9 col-sm-12 col-xs-12">
            <div class="block">
                <!--<form id="courier_form" @submit="checkForm" action="...">-->
                <form id="courier_form">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="phone">{{ExWindow ? ExWindow.translator('courier.phone') :
                                            ''}}</label>
                                        <input v-model="form.phone" type="text" class="form-control" id="phone"
                                               name="phone" placeholder="+994">
                                        <p style="color:red" v-if="errors[0]">{{errors[0].phone}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="city">{{ExWindow ? ExWindow.translator('courier.wantCity') :
                                            ''}}</label>
                                        <select v-model="form.city" class="form-control" name="city" id="city">
                                            <option value="Bakı">{{ExWindow ? ExWindow.translator('courier.baku') :
                                                ''}}
                                            </option>
                                            <option value="Sumqayıt">{{ExWindow ?
                                                ExWindow.translator('courier.sumqayit') : ''}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="region">{{ExWindow ? ExWindow.translator('courier.region') :
                                                    ''}}</label>
                                                <input v-model="form.region" type="text" class="form-control"
                                                       name="region" id="region"
                                                       :placeholder="ExWindow ? ExWindow.translator('courier.region') : ''">
                                                <p style="color:red" v-if="errors[4]">{{errors[4].region}}</p>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="village">{{ExWindow ? ExWindow.translator('courier.village')
                                                    : ''}}</label>
                                                <input v-model="form.village" type="text" class="form-control"
                                                       id="village" name="village"
                                                       :placeholder="ExWindow ? ExWindow.translator('courier.village') : ''">
                                                <p style="color:red" v-if="errors[7]">{{errors[7].village}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="street">{{ExWindow ? ExWindow.translator('courier.street') :
                                                    ''}}</label>
                                                <input v-model="form.street" type="text" class="form-control"
                                                       name="street" id="street"
                                                       :placeholder="ExWindow ? ExWindow.translator('courier.street') : ''">
                                                <p style="color:red" v-if="errors[5]">{{errors[5].street}}</p>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="unvan">{{ExWindow ? ExWindow.translator('courier.home') :
                                                    ''}}</label>
                                                <input v-model="form.home" type="text" class="form-control" name="unvan"
                                                       id="unvan"
                                                       :placeholder="ExWindow ? ExWindow.translator('courier.home') : ''">
                                                <p style="color:red" v-if="errors[6]">{{errors[6].home}}</p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group datetime" id='datetimepicker3'>
                                        <label for="time">Saat</label>
                                        <input type="text" class="selectpicker form-control" name="time" id="time"
                                               placeholder="Çatdırılma üçün uyğun saat seç">
                                        <span class="input-group-addon"> <span class="fa fa-clock-o"></span> </span>
                                    </div>
                                </div>
                                <div class="left-side col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="transport">Çatdırılma vasitəsini seç</label>
                                        <select class="selectpicker form-control" name="transport" id="transport">
                                            <option value="avtomobil">Avtomobil</option>
                                            <option value="poct">Poçt</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="delivery">{{ExWindow ? ExWindow.translator('courier.time') :
                                            ''}}</label>
                                        <select v-model="form.time" class="form-control" name="delivery" id="delivery">
                                            <!--<option value="">{{ExWindow ? ExWindow.translator('courier.choose') : ''}}</option>-->
                                            <option value="standart">{{ExWindow ? ExWindow.translator('courier.normal')
                                                : ''}}
                                            </option>
                                            <!--<option value="express">{{ExWindow ? ExWindow.translator('courier.express') : ''}}</option>-->
                                        </select>
                                        <p style="color:red" v-if="errors[2]">{{errors[2].time}}</p>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="products">{{ExWindow ? ExWindow.translator('courier.products') :
                                            ''}}</label>
                                        <div v-if="products">
                                            <multiselect id="products" v-model="form.products" :options="products"
                                                         :multiple="true" :close-on-select="false"
                                                         :clear-on-select="false" :preserve-search="true"
                                                         placeholder="Seçin" label="name" track-by="name"
                                                         :preselect-first="true">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span
                                                        class="multiselect__single"
                                                        v-if="values.length &amp;&amp; !isOpen">{{ values.length }} seçilib</span>
                                                </template>
                                            </multiselect>
                                            <p style="color:red" v-if="errors[3]">{{errors[3].products}}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                            <!--<p>Cəmi: <span class="all_count">$0,00</span></p>-->
                            <!--<button type="submit" class="btn-effect" data-toggle="modal" @click="checkForm($event)" data-target=".tesdiq">{{ExWindow ? ExWindow.translator('courier.accept') : ''}}-->
                            <button type="submit" class="btn-effect" @click="checkForm($event)">{{ExWindow ?
                                ExWindow.translator('courier.accept') : ''}}
                            </button>
                        </div>
                        <div class="follow col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="right-side col-md-9 col-sm-12 col-xs-12 my-4">
            <div class="block" style="padding-bottom: 20px !important;">
                <table class="table table-responsive table-hovered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ünvan</th>
                        <th>Məhsul</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody v-if="courierData.length != 0">
                    <tr v-for="(item, index) of courierData">
                        <td>{{index + 1}}</td>
                        <td>{{item.city}} {{item.district}} {{item.village}} {{item.street}} {{item.home}}</td>
                        <td>
                            <a href="javascript:void(0)" @click="showProduct(item)" class="btn-effect">Bax</a>
                            <!--{{item.shop_name}}-->
                            <!--{{ item.product_type_name }}-->
                        </td>
                        <td><span style="cursor:initial;font-weight: bold;"
                                  :class="{'text-success': item.has_courier === 1, 'text-info':item.has_courier !== 1}">{{item.has_courier === 1? 'Təyin edilib': 'Kuryer təyin edilməyib'}}</span>
                        </td>
                        <td><a v-if="item.is_paid === 0" style="cursor:pointer" @click="payCourier(item.id)"
                               class="btn btn-success">Ödə</a><span
                                style="padding:10px 5px;color:#fff;background:#f95732" v-if="item.is_paid === 1">Ödənilib</span>
                        </td>

                    </tr>
                    </tbody>
                    <tbody v-if="courierData.length == 0">
                    <tr>
                        <td colspan="4" style="text-align:center">Yoxdur</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <products-modal v-bind:productData="showProductData" v-bind:show="ProductsModal"
                        @close-modal="closeShowProduct"></products-modal>

    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import swal from 'sweetalert2';
    import ProductsModal from "./modals/ProductsModal";


    export default {
        components: {
            Multiselect, ProductsModal
        },
        name: "courier",
        mounted() {
            this.getInvoices();
            this.ExWindow = window;
            this.getCourierData();
        },
        data: function () {
            return {
                ExWindow: null,
                ProductsModal: false,
                showProductData: null,
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
                products: [],
                courierData: [],
                errors: []
            }
        },

        methods: {
            showProduct(item) {
                this.ProductsModal = true;
                var data = JSON.parse(JSON.stringify(item));
                this.showProductData = data;
            },
            getInvoices() {
                axios.get('/user-panel/get-invoices/home?invoice_courier=0')
                    .then(data => {
                        data.data.data.forEach(invoice => {
                            this.products.push({
                                name: invoice.product_type_name,
                                i_id: invoice.id
                            })
                        });
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            checkForm(e) {
                e.preventDefault();


                if (this.checkFormData() === 'true') {
                    // var form = document.getElementById('courier_form');
                    // var formData = new FormData(form);
                    axios.post('/user-panel/post-courier-data', this.form)
                        .then((response) => {
                            if (response.data.code == 500) {
                                swal(
                                    'Xanaları doldurun!',
                                    'error'
                                );
                            } else {
                                swal(
                                    'Tamamlandı',
                                    'Müvəffəqiyytlə əlavə edildi!',
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
                    console.log(this.errors)
                }
            },
            checkFormData() {
                this.errors = [];
                if (!this.form.phone) this.errors[0] = {phone: 'Nömrənizi qeyd edin'}
                if (!this.form.city) this.errors[1] = {city: 'Şəhər seçin.'}
                if (!this.form.time) this.errors[2] = {time: 'Çatdırılma vaxtını təyin edin'}
                // if (!this.form.village) this.errors[7] = {time: 'Qəsəbəni qeyd edin'}
                if (this.form.products.length === 0) this.errors[3] = {products: 'Məhsul seçin'}
                if (!this.form.region) this.errors[4] = {region: 'Rayonu qeyd edin'}
                if (!this.form.street) this.errors[5] = {street: 'Küçəni qeyd edin'}
                if (!this.form.street) this.errors[6] = {home: 'Evi qeyd edin'}
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

            payCourier(courierId) {
                swal({
                    title: 'Əminsizmi?',
                    text: "Ödəmə əməliyyatı!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Bəli!',
                    cancelButtonText: 'Xeyr!'
                }).then((result) => {

                    if (result.value) {
                        axios.post('/user-panel/pay-courier', {courierId: courierId})
                            .then((response) => {
                                if (response.data.code === 1601) {
                                    swal('Balansınız yetərli deyil!');
                                    setTimeout(() => {
                                        window.location = '/' + this.ExWindow.default_locale + '/user-panel#/balance'
                                    }, 500)
                                } else {
                                    swal(
                                        'Ödənildi!',
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
    @import "~vue-multiselect/dist/vue-multiselect.min.css";

    .multiselect__tags {
        height: 34px;
        color: #707c88;
        display: table-cell;
        float: left;
        width: 100%;
        margin-bottom: 0;
        position: relative;
        padding: 4px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        border-radius: 17px;
        border: 1px solid #717c88;
        background: transparent;
    }

    .multiselect__content-wrapper {
        top: 34px;
        border-color: #717c88;
    }

    .multiselect--above .multiselect__content-wrapper {
        height: 81px;
    }

    .multiselect__option--selected {
        background: #dad8d8;
    }

    .multiselect__option--highlight {
        background: #f95732;
    }

    .multiselect__option--selected.multiselect__option--highlight {
        background: #da2b03;
    }

    .multiselect__option--selected.multiselect__option--highlight:after {
        background: #da2b03;
    }

    .multiselect__option--highlight:after {
        background: #f95732;
    }

    .multiselect__tags {
        min-height: 34px;
    }

    .multiselect__select {
        height: 34px;
    }

    .multiselect__single {
        line-height: 24px;
    }
</style>
