<template>
    <div class="col-md-9 col-sm-11 col-xs-11 courier">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12">
                    <h3>{{ExWindow ? ExWindow.translator('panel-errors.courier') : ''}}</h3>
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
                                <div class="col-xs-12">
                                    <div class="textarea-form form-group">
                                        <textarea v-model="form.description" class="form-control" rows="4" id="comment" :placeholder="ExWindow ? ExWindow.translator('panel-errors.courier_desc') : ''"></textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12 button-part">
                                    <button type="submit" class="btn-effect" @click="checkForm($event)">{{ExWindow ?
                                        ExWindow.translator('courier.accept') : ''}}
                                    </button>
                                    <b><span>{{ExWindow ? ExWindow.translator('panel-errors.delivery_desc') : ''}}</span></b>
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
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.kuryer') : ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.product') : ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.price') : ''}}</th>
                                <th>{{ExWindow ? ExWindow.translator('panel-errors.status') : ''}}</th>
                                <th></th>
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
                                <td>
                                    {{item.price}} AZN
                                </td>
                                <td><span style="cursor:initial;font-weight: bold;"
                                          :class="{'text-success': item.has_courier === 1, 'text-info':item.has_courier !== 1}">{{item.has_courier === 1? ExWindow ? ExWindow.translator('panel-errors.courier_defined') : '': ExWindow ? ExWindow.translator('panel-errors.courier_not_defined') : ''}}</span>
                                </td>
                                <td><a v-if="item.is_paid === 0" style="cursor:pointer" @click="payCourier(item.id)"
                                       class="btn btn-success">{{ExWindow ? ExWindow.translator('panel-errors.pay') : ''}}</a><span
                                        style="padding:10px 5px;color:#fff;background:#f95732"
                                        v-if="item.is_paid === 1">{{ExWindow ? ExWindow.translator('panel-errors.paid') : ''}}</span>

                                </td>
                                <td>
                                    <a v-if="item.is_paid === 0 && item.has_courier !== 1" style="cursor:pointer" @click="cancelCourier(item.id)"
                                       class="btn btn-danger">{{'Sil'}}</a>
                                    <p v-if="item.status_id==7 && item.delivery_type==1 && item.has_courier == 1" class="text-success">{{ExWindow ? ExWindow.translator('panel-errors.completed') : ''}}</p>
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
        <courier-modal v-bind:show="ShowCourierModal"
                       @close-modal="closeShowCourier"></courier-modal>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import swal from 'sweetalert2';
    import ProductsModal from "./modals/ProductsModal";
    import vSelect from 'vue-select';
    import CourierModal from "./modals/CourierModal";


    export default {
        components: {
            Multiselect, ProductsModal, vSelect, CourierModal
        },
        name: "courier",

        mounted() {
            this.checkCookie();
            this.getInvoices();
            this.getCourierData();
            this.ExWindow = window;
            this.products[0].name = this.ExWindow && this.ExWindow.translator('panel-errors.all');
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
                {id: 1, name: 1, label: this.ExWindow && this.ExWindow.translator('panel-errors.baku')+' - 4 AZN'},
                {id: 3, name: 3, label: this.ExWindow && this.ExWindow.translator('panel-errors.khirdalan')+' - 6 AZN'},
                {id: 4, name: 4, label: this.ExWindow && this.ExWindow.translator('panel-errors.baku_villages')+' - 8 AZN'},
            ]


            this.city = {id: 0, name: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city'), label: this.ExWindow && this.ExWindow.translator('panel-errors.choose_city')}
        },
        data: function () {
            return {
                locations: [],
                ExWindow: null,
                ProductsModal: false,
                ShowCourierModal: false,
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
                    description: null
                },
                products: [{
                    name:  'Hamısı',
                    i_id: 0
                }],
                courierData: [],
                errors: [],
                city: {},
                dropDowns: {
                    ranking: {open: false}
                },
                payButton : ''

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
                    /*if(this.city.id == 1 ){
                        this.form.city = 'Bakı'
                    } else if(this.city.id == 2 ){
                        this.form.city = 'Sumqayıt'
                    }*/
                    this.form.city = this.city.id;
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
                                    products: [],
                                    description: ''
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
                if (!this.form.home) this.errors[6] = {home: this.ExWindow && this.ExWindow.translator('panel-errors.select_home')}
                return (this.errors.length === 0 ? 'true' : 'false')
            },
            getCourierData() {
                axios.get('/user-panel/get-courier-data2')
                    .then(data => {
                        this.courierData = data.data.data;
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
                    if (result.value && this.payButton!=courierId) {
                        this.payButton = courierId;
                        axios.post('/user-panel/pay-courier', {courierId: courierId})
                            .then((response) => {
                                if (response.data.code === 1601) {
                                    swal(this.ExWindow && this.ExWindow.translator('panel-errors.insufficient_balance'));
                                    setTimeout(() => {
                                        window.location = '/' + this.ExWindow.default_locale + '/site/user-panel#/balance'
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

            cancelCourier(courierId) {
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
                        axios.post('/user-panel/cancel-courier', {courierId: courierId})
                            .then((response) => {
                                if (response.data.code !== 200) {
                                    swal(response.data.data);
                                } else {
                                    swal(
                                        this.ExWindow && this.ExWindow.translator('panel-errors.payed'),
                                        response.data.data
                                    )
                                    this.getInvoices();
                                    this.getCourierData();
                                }
                            });
                    }
                })
            },
            closeShowProduct() {
                this.ProductsModal = false;
            },

            closeShowCourier() {
                this.ShowCourierModal = false;
            },
            setCookie(cname, cvalue, exdays) {
                let d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                let expires = "expires="+ d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            },
            getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for(let i = 0; i <ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            checkCookie() {

                let kuryer= this.getCookie("kuryer");
                console.log(kuryer);
                if (kuryer == "") {
                    this.ShowCourierModal = true;
                    this.setCookie("kuryer", 1, 365);
                } else {
                    this.ShowCourierModal = false;
                }
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
