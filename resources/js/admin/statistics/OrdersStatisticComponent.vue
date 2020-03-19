<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="top-search col-md-12 col-sm-12 col-xs-12"  v-if="!isHidden">
            <div class="block col-xs-12">
                <!--<div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Müştəri kodu</label>
                            <input v-on:keyup.enter="checkForm()"  class="form-control" placeholder="Müştəri kodu" v-model="filter.uniqid">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Müştəri Adı</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Ad" v-model="filter.name">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Müştəri Soyadı</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Soyad" v-model="filter.surname">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Telefon nömrəsi</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Telefon nömrəsi"  v-model="filter.phone">
                        </div>
                    </div>
                </div>-->
                <div class="row">
                    <!--<div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Email"  v-model="filter.email">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Sifariş nömrəsi</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Sifariş nömrəsi"  v-model="filter.purchase_no">
                        </div>
                    </div>-->

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Bu tarixdən</label>
                            <input v-on:keyup.enter="checkForm()" type="date" class="form-control" placeholder="Bu tarixdən" v-model="filter.begin_date">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Bu tarixə</label>
                            <input v-on:keyup.enter="checkForm()" type="date" class="form-control" placeholder="Bu tarixə" v-model="filter.end_date">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select v-on:keyup.enter="checkForm()" class="form-control" id="status" v-model="filter.status">
                                <option value="0">Bütün statuslar</option>
                                <option v-for="status in statuses" :value="status.sid">{{ status.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!--<div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Mağaza adı</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Mağaza adı"  v-model="filter.shop_name">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Sifariş linki</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Sifariş linki"  v-model="filter.link">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Qəbul edən adı </label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Qəbul edən adı"  v-model="filter.admin_name">
                        </div>
                    </div>-->

                    <!--<div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="status">Region</label>
                            <select v-on:keyup.enter="checkForm()" class="form-control" id="region_id" v-model="filter.region_id">
                                <option value="0">Bütün regionlar</option>
                                <option v-for="region in regions" :value="region.id">{{ region.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="status">Ölkə</label>
                            <select v-on:keyup.enter="checkForm()" class="form-control" id="country_id" v-model="filter.country_id">
                                <option value="0">Bütün ölkələr</option>
                                <option v-for="country in countries" :value="country.id">{{ country.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="status">Premium</label>
                            <select v-on:keyup.enter="checkForm()" class="form-control" id="is_premium" v-model="filter.is_premium">
                                <option value="2">Hamısı</option>
                                <option :value="0">Standart</option>
                                <option :value="1">Premium</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>İzləmə nömrəsi</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="İzləmə nömrəsi"  v-model="filter.order_tracking_number">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Bağlama qiyməti</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Bağlama qiyməti"  v-model="filter.price">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="status">Mehsul tipi</label>
                            <select v-on:keyup.enter="checkForm()" class="form-control" id="liquid_type" v-model="filter.liquid_type">
                                <option value="2">Hamısı</option>
                                <option :value="0">Standart</option>
                                <option :value="1">Maye</option>
                            </select>
                        </div>
                    </div>-->
                    <div class="col-md-2 col-sm-3 col-xs-12 pull-right">
                        <button class="btn-effect" @click="checkForm()">Axtar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1 col-sm-3 col-xs-12 pull-right">
            <button class="btn btn-info" v-on:click="isHidden = !isHidden"><i class="fa fa-search"></i></button>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Statistika</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/orders">Sifarişlər</router-link></li>
                    <li class="active">Bütün bağlamalar</li>
                </ol>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th  v-bind="count_i=0"></th>
                                <th>Region</th>
                                <th>Çatdırılma qiyməri</th>
                                <th>Bağlama sayı</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in allOrders.orders">
                                <td>{{count_i = count_i+1}}</td>
                                <td>{{order.name}}</td>
                                <td>{{order.sum_shipping}}$</td>
                                <td>{{order.count}}</td>

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
    import swal from 'sweetalert2';
    import auth from '../auth.js'

    export default {
        name: "OrdersStatisticComponent",
        data () {
            return {
                filter:{
                    begin_date: '',
                    end_date: '',
                    status: 4,
                    /*uniqid: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                    purchase_no: '',
                    order_tracking_number: '',
                    price: '',

                    admin_name: '',
                    shop_name: '',
                    link: '',

                    region_id: 0,
                    country_id: 0,
                    is_premium: 2,
                    liquid_type: 2,*/
                },
                isHidden: true,
                allOrders: {
                    orders: '',
                },
                statuses: [],
                regions: [],
                countries: [],
                editable_status: []
            }
        },
        methods:{
            deleteInvoice(id){
                if(confirm("Silməyə əminsiniz?")){
                    axios.get('/cp/orders/delete-invoice/'+id,)
                        .then(({ data }) => {
                            swal({
                                title: 'Silindi.',
                            });
                            this.getAllInvoices();

                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }

            },
            openEditStatus(product){
                this.editable_status.push(product.id);
                this.editable_status[product.id] = true;
            },
            changeStatus(product){
                axios.post('/cp/orders/changeStatus', {
                    id: product.id,
                    status_id: product.status_id
                }).then(({ data }) => {
                        this.editable_status = [];
                        this.getAllInvoices();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getStatuses(){
                axios.get('/cp/orders/statuses')
                    .then(({ data }) => {
                        this.statuses = data.statuses;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getRegions(){
                axios.get('/cp/orders/regions')
                    .then(({ data }) => {
                        this.regions = data.regions;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getCountries(){
                axios.get('/cp/orders/countries')
                    .then(({ data }) => {
                        this.countries = data.countries;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            checkForm () {
                this.errors = {};
                if (this.isEmpty(this.errors)) {

                    this.getAllInvoices();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
            getAllInvoices: function(){
                axios.get('/cp/allInvoicesStatistic',{params: this.filter})
                    .then((response) => {
                        this.allOrders.orders = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

        },

        mounted(){
            this.getAllInvoices();
            this.getStatuses();
            this.getRegions();
            this.getCountries();
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','buyer','problematic_department','dispatcher','operator'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>

</style>