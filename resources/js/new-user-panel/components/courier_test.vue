<template>
    <div class="col-md-9 col-sm-11 col-xs-11 courier">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12">
                    <h3>Kuryer</h3>
                    <div class="form-list">
                        <form action="...">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="invoice-day input-group border-radius">
                                        <v-select v-model="city" :options="courier_locations"
                                                  v-on:change="getCity"></v-select>
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
                                        <!--<label>-->
                                        <!--<input type="text" name="products" class="form-control inputText"-->
                                        <!--placeholder=" ">-->
                                        <!--<span>Məhsullar *</span>-->
                                        <!--</label>-->
                                        <ul class="select-outer" v-if="products">
                                            <li class="menu__item menu__item--dropdown form-control"
                                                v-bind:class="{'open' : dropDowns.ranking.open}">
                                                <p class="menu__link menu__link--toggle">
                                                    <span> Bağlamanı seçin</span>
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
                                    <span>Çatdırılma 12 saat ərzində həyata keçirilir.</span>
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
                                <th>Ünvan</th>
                                <th>Məhsul</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) of courierData">
                                <td>{{index + 1}}</td>
                                <td>{{item.city}} {{item.district}} {{item.village}} {{item.street}} {{item.home}}</td>
                                <td>
                                    <a href="javascript:void(0)" @click="showProduct(item)"
                                       class="btn btn-warning">Bax</a>
                                </td>
                                <td><span style="cursor:initial;font-weight: bold;"
                                          :class="{'text-success': item.has_courier === 1, 'text-info':item.has_courier !== 1}">{{item.has_courier === 1? 'Təyin edilib': 'Kuryer təyin edilməyib'}}</span>
                                </td>
                                <td><a v-if="item.is_paid === 0" style="cursor:pointer" @click="payCourier(item.id)"
                                       class="btn btn-success">Ödə</a><span
                                        style="padding:10px 5px;color:#fff;background:#f95732"
                                        v-if="item.is_paid === 1">Ödənilib</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
    import vSelect from 'vue-select';


    export default {
        computed: {
            courier_locations: function () {
                return this.locations;
            },
        },
        components: {
            Multiselect, ProductsModal, vSelect
        },
        name: "courier",

        mounted() {
            this.getInvoices();
            this.getCourierData();
            this.ExWindow = window;
            window.addEventListener('click',  (e) => {
                let array = Array.from(e.target.parentNode.classList);
                if(this.dropDowns.ranking.open === true && array.indexOf('select-item') < 0 && array.indexOf('check-button') < 0){
                    this.dropDowns.ranking.open = false;
                }else if(array.indexOf('menu__link') >= 0){
                    this.dropDowns.ranking.open = !this.dropDowns.ranking.open;
                }
            }, false)
        },
        data: function () {
            return {
                locations: [
                    {id: 0, name: 'Şəhər seçin', label: 'Şəhər seçin'},
                    {id: 1, name: 'Bakı', label: 'Bakı'},
                    {id: 2, name: 'Sumqayıt', label: 'Sumqayıt'}
                ],
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
                products: [{
                    name: 'Hamısı',
                    i_id: 0
                }],
                courierData: [],
                errors: [],
                city: {id: 0, name: 'Şəhər seçin', label: 'Şəhər seçin'},
                // choose_package: [
                //     {id: 0,name: 'Hamısını seç'},
                //     {id: 1, name: 'Salam2'},
                //     {id: 2, name: 'salam3'},
                // ],
                dropDowns: {
                    ranking: {open: false}
                }

            }
        },

        methods: {
            getCity() {
                if (this.city === null) {
                    this.locations = [
                        {id: 1, name: 'Bakı', label: 'Bakı'},
                        {id: 2, name: 'Sumqayıt', label: 'Sumqayıt'}
                    ];
                }
                this.form.city = this.city.name;
            },
            // handleSelect(event) {
            //     if (event.i_id === 0) {
            //         for (let item of this.products) {
            //             if (item.i_id > 0) {
            //                 this.form.products.push(item);
            //             }
            //         }
            //     }
            //     console.log(this.form.products)
            // },

            // handleRemove(event) {
            //     if (event.i_id === 0) {
            //         this.form.products.splice(0, this.form.products.length);
            //     }
            // },
            selectOneselectOne(item) {
                this.form.products.push(item)
                console.log(event.target)
                console.log(this.form.products)

            },
            showProduct(item) {
                this.ProductsModal = true;
                var data = JSON.parse(JSON.stringify(item));
                this.showProductData = data;
            },
            /* openSelect(event) {
                 let elem = event.target;
                 let outer = $(elem).parents('.select-outer');
                 $(outer).find('.select-inner').toggle();
                 this.showDropdown = !this.showDropdown
             },*/
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
            selectAll(item) {
                if (item.i_id === 0) {
                    this.products.map((order) => {
                        order.check = item.check;
                    })
                }
            },
            checkForm(e) {
                e.preventDefault();
                this.form.products = [];
                for (let i in this.products) {
                    if (this.products[i].check && this.products[i].i_id !== 0) this.form.products.push(this.products[i]);
                }
                if (this.checkFormData() === 'true') {

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
                    // console.log(this.errors)
                }
            },
            checkFormData() {
                this.errors = [];
                if (!this.form.phone) this.errors[0] = {phone: 'Nömrənizi qeyd edin'}
                if (!this.form.city) this.errors[1] = {city: 'Şəhər seçin.'}
                if (!this.form.time) this.errors[2] = {time: 'Çatdırılma vaxtını təyin edin'}
                // if (!this.form.village) this.errors[7] = {village: 'Qəsəbəni qeyd edin'}
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
