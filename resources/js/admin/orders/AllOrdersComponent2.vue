<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="top-search col-md-12 col-sm-12 col-xs-12"  v-if="!isHidden">
            <div class="block col-xs-12">
                <div class="row">
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
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
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
                    </div>

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
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
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
                    <div class="col-md-3 col-sm-6 col-xs-12">
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
                                <option value="0">Standart</option>
                                <option value="1">Premium</option>
                            </select>
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
                            <label>Sifariş İzləmə nömrəsi</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="İzləmə nömrəsi"  v-model="filter.order_tracking_number">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Teslimat nömrəsi</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Teslimat nömrəsi"  v-model="filter.delivery_number">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Çəki</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Çəki"  v-model="filter.weight">
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
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Məhsulun adı</label>
                            <input v-on:keyup.enter="checkForm()" class="form-control" placeholder="Məhsulun adı"  v-model="filter.product_type_name">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Yola çıxma tarixi</label>
                            <input v-on:keyup.enter="checkForm()" type="date" class="form-control" placeholder="Yola çıxma tarixi" v-model="filter.way_date">
                        </div>
                    </div>
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
                <h4>Tamamlanmış sifarişlər2</h4>
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
                                <th  v-bind="count_i=(allOrders.orders.current_page-1)*10"></th>
                                <th>Invoys Nömrəsi</th>
                                <th>Qəbul edən</th>
                                <th>Sifarişçi Adı, Soyadı, Kodu</th>
                                <th>Müştəri Adı, Soyadı, Kodu</th>
                                <th>Sifariş Tarixi</th>
                                <th>Mağaza adı</th>
                                <th>Ümumi Məbləği</th>
                                <th>Çatdırılma Məbləği</th>
                                <th>Ölkə</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th>Ətraflı</th>
                                <th>Sil</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in allOrders.orders.data">
                                <td>{{count_i = count_i+1}}</td>
                                <td v-if="product.purchase_no!=null">{{ product.purchase_no }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.admin_name!=null">{{ product.admin_name }} {{ product.admin_surname }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.name!=null">{{ product.name }} {{ product.surname }} <b>{{ product.uniqid }}</b></td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.corporate==1 && product.client_name!=null">{{ product.client_name }} {{ product.client_surname }} <b>1{{ product.client_id.toString().padStart(6, "0") }}</b></td>
                                <td v-else-if="product.person_name!=null">{{ product.person_name }} {{ product.person_surname }} ({{ product.person_id }})</td>
                                <td v-else>Yoxdur</td>
                                <td>{{ product.created_at | formatDate}} <b v-if="product.is_premium==1">Premium</b></td>
                                <td v-if="product.shop_name!=null">{{ product.shop_name }} <br /> ({{product.product_type_name}})</td>
                                <td v-else>Yoxdur</td>
                                <td>{{ product.price }} {{product.country_id==1?'TL':'USD'}}</td>
                                <td>{{ product.shipping_price }} USD </td>

                                <td  v-if="!editable_status_country[product.id]">
                                    <span @click="openEditStatusCountry(product)">{{product.country_name}} </span>
                                </td>
                                <td v-else>
                                    <select v-model="product.country_id">
                                        <option v-for="country in countries" :value="country.id">{{ country.name }}</option>
                                    </select>
                                    <button @click="changeCountry(product)">
                                        Dəyiş
                                    </button>
                                    <button @click="editable_status_country= []">
                                        bagla
                                    </button>
                                </td>

                                <td  v-if="!editable_status_region[product.id]">
                                    <span @click="openEditStatusRegion(product)">{{product.region_name}} </span>
<!--
                                    <span @click="openEditStatusRegion(product)" v-else>Yoxdur </span>
-->
                                </td>
                                <td v-else>
                                    <select v-model="product.region_id">
                                        <option v-for="region in regions" :value="region.id">{{ region.name }}</option>
                                    </select>
                                    <button @click="changeRegion(product)">
                                        Dəyiş
                                    </button>
                                    <button @click="editable_status_region= []">
                                        bagla
                                    </button>

                                </td>

                                <td  v-if="!editable_status[product.id]">
                                    <span @click="openEditStatus(product)">{{ product.status }} {{ product.edit_status }} </span>
                                </td>
                                <td v-else>
                                    <select v-model="product.status_id">
                                        <option v-for="status in statuses" :value="status.sid">{{ status.name }}</option>
                                    </select>
                                    <button @click="changeStatus(product)">
                                        Dəyiş
                                    </button>
                                    <button @click="editable_status= []">
                                        bagla
                                    </button>

                                </td>
                                <td>
                                    <router-link class="btn-effect" target="_blank" v-bind:to="'/orders/invoice/' + product.id">Bax</router-link>
                                </td>
                                <td >
                                    <button v-if="product.status_id==1" @click="deleteInvoice(product.id)" class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li ><a @click.prevent="getAllInvoices(allOrders.orders.current_page - 1)" href="#" aria-label="Previous"><span
                                        aria-hidden="true">ƏVVƏL</span></a></li>
                                <template  v-for="page in allOrders.orders.last_page">
                                    <li v-if="page ==1 && allOrders.orders.current_page==1" class="active">
                                        <a @click.prevent="getAllInvoices( page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="page ==1">
                                        <a @click.prevent="getAllInvoices( page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="Math.abs(allOrders.orders.current_page - page)> 3 && Math.abs(allOrders.orders.current_page - page)<= 6 ">
                                        <a href="#">.</a>
                                    </li>
                                    <li v-else-if="Math.abs(allOrders.orders.current_page - page)<= 3 && allOrders.orders.current_page == page" class="active">
                                        <a @click.prevent="getAllInvoices( page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="Math.abs(allOrders.orders.current_page - page)<= 3 ">
                                        <a @click.prevent="getAllInvoices(  page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="page == allOrders.orders.last_page">
                                        <a @click.prevent="getAllInvoices( page)" href="#">{{ page }}</a>
                                    </li>
                                </template>
                                <li>
                                    <a v-show="allOrders.orders.current_page < allOrders.orders.last_page" @click.prevent="getAllInvoices( allOrders.orders.current_page + 1)" href="#" aria-label="Next">
                                        <span aria-hidden="true">NÖVBƏTİ</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
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
        name: "AllOrdersComponent",
        data () {
            return {
                filter:{
                    uniqid: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                    purchase_no: '',
                    order_tracking_number: '',
                    delivery_number: '',
                    price: '',
                    begin_date: '',
                    end_date: '',
                    admin_name: '',
                    shop_name: '',
                    link: '',
                    status: 0,
                    region_id: 0,
                    country_id: 0,
                    is_premium: 2,
                    liquid_type: 2,
                    product_type_name: '',
                    way_date: '',
                    weight: '',
                },
                isHidden: true,
                allOrders: {
                    orders: '',
                },
                statuses: [],
                regions: [],
                countries: [],
                editable_status: [],
                editable_status_country: [],
                editable_status_region: [],
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
            openEditStatusRegion(product){
                this.editable_status_region.push(product.id);
                this.editable_status_region[product.id] = true;
            },
            openEditStatusCountry(product){
                this.editable_status_country.push(product.id);
                this.editable_status_country[product.id] = true;
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
            changeRegion(product){
                axios.post('/cp/orders/changeRegionCountry', {
                    id: product.id,
                    region_id: product.region_id
                }).then(({ data }) => {
                    this.editable_status_region = [];
                    this.getAllInvoices();
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            changeCountry(product){
                axios.post('/cp/orders/changeRegionCountry', {
                    id: product.id,
                    country_id: product.country_id
                }).then(({ data }) => {
                    this.editable_status_country = [];
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
            getAllInvoices: function(page_id=1){
                axios.get('/cp/allInvoices?page='+page_id,{params: this.filter})
                    .then((response) => {
                        this.allOrders.orders = response.data.data.invoices;
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
                this.ifNoAccessRedirect(Array('super_admin','admin'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>

</style>