<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="top-search col-md-12 col-sm-12 col-xs-12"  v-if="!isHidden">
            <div class="block col-xs-12">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Müştəri kodu</label>
                            <input  class="form-control" placeholder="Müştəri kodu" v-model="filter.uniqid">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Müştəri Adı</label>
                            <input class="form-control" placeholder="Ad" v-model="filter.name">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Müştəri Soyadı</label>
                            <input class="form-control" placeholder="Soyad" v-model="filter.surname">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Telefon nömrəsi</label>
                            <input class="form-control" placeholder="Telefon nömrəsi"  v-model="filter.phone">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" placeholder="Email"  v-model="filter.email">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Sifariş nömrəsi</label>
                            <input class="form-control" placeholder="Sifariş nömrəsi"  v-model="filter.purchase_no">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Bu tarixdən</label>
                            <input type="date" class="form-control" placeholder="Bu tarixdən" v-model="filter.begin_date">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Bu tarixə</label>
                            <input type="date" class="form-control" placeholder="Bu tarixə" v-model="filter.end_date">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Qəbul edən adı </label>
                            <input class="form-control" placeholder="Qəbul edən adı"  v-model="filter.admin_name">
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
                <h4>İnvoys Olmayanlar</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/orders">Sifarişlər</router-link></li>
                    <li class="active">İnvoys olmayanlar</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab"
                                                              data-toggle="tab">Bütün invoysu olmayanlar</a></li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Xarici
                        anbardan gələnlər</a></li>
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th  v-bind="count_i=(notHavingInvoices.current_page_id-1)*10"></th>
                                <th>Invoys Nömrəsi</th>
                                <th>Qəbul edən</th>
                                <th>Sifarişçi Adı, Soyadı, Kodu</th>
                                <th>Sifarişçi E-maili</th>
                                <th>Sifarişçi Əlaqə Nömrələri</th>
                                <th>Sifarişçinin Ümumi Məbləği</th>
                                <th>Ətraflı</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in notHavingInvoices.invoices.data">
                                <td>{{count_i = count_i+1}}</td>
                                <td v-if="product.purchase_no!=null">{{ product.purchase_no }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.admin_name!=null">{{ product.admin_name }} {{ product.admin_surname }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.name!=null">{{ product.name }} {{ product.surname }} <b>{{ product.uniqid }}</b></td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.email!=null">{{ product.email }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.phone!=null">{{ product.phone }}</td>
                                <td v-else>Yoxdur</td>
                                <td>{{ product.price }} TL</td>
                                <td>
                                    <router-link class="btn-effect" v-bind:to="'/orders/invoice/' + product.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li ><a href="#" @click.prevent="getNoInvoices( notHavingInvoices.current_page_id - 1 )" aria-label="Previous"><span
                                        aria-hidden="true">ƏVVƏL</span></a></li>
                                <template v-for="page in notHavingInvoices.all_page_count">
                                    <li v-if="page == notHavingInvoices.current_page_id" class="active">
                                        <a @click.prevent="getNoInvoices(page)" href="#">{{ page }} <span class="sr-only">(current)</span></a>
                                    </li>
                                    <li v-else>
                                        <a @click.prevent="getNoInvoices(page)" href="#">{{ page }}</a>
                                    </li>
                                </template>
                                <li>
                                    <a @click.prevent="getNoInvoices( notHavingInvoices.current_page_id + 1)" href="#" aria-label="Next">
                                        <span aria-hidden="true">NÖVBƏTİ</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="profile">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Invoys Nömrəsi</th>
                                <th>Qəbul edən</th>
                                <th>Sifarişçi Adı, Soyadı, Kodu</th>
                                <th>Sifarişçi E-maili</th>
                                <th>Sifarişçi Əlaqə Nömrələri</th>
                                <th>Sifarişçinin Ümumi Məbləği</th>
                                <th>Ətraflı</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in noInvoicesForeign.invoices.data">
                                <td v-if="product.purchase_no!=null">{{ product.purchase_no }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.admin_name!=null">{{ product.admin_name }} {{ product.admin_surname }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.name!=null">{{ product.name }} {{ product.surname }} <b>{{ product.uniqid }}</b></td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.email!=null">{{ product.email }}</td>
                                <td v-else>Yoxdur</td>
                                <td v-if="product.phone!=null">{{ product.phone }}</td>
                                <td v-else>Yoxdur</td>
                                <td>{{ product.price }} TL</td>
                                <td>
                                    <router-link class="btn-effect" v-bind:to="'/orders/invoice/' + product.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li ><a href="#" @click.prevent="getnoInvoicesForeign( noInvoicesForeign.current_page_id - 1 )" aria-label="Previous"><span
                                        aria-hidden="true">ƏVVƏL</span></a></li>
                                <template v-for="page in noInvoicesForeign.all_page_count">
                                    <li v-if="page == noInvoicesForeign.current_page_id" class="active">
                                        <a @click.prevent="getnoInvoicesForeign(page)" href="#">{{ page }} <span class="sr-only">(current)</span></a>
                                    </li>
                                    <li v-else>
                                        <a @click.prevent="getnoInvoicesForeign(page)" href="#">{{ page }}</a>
                                    </li>
                                </template>
                                <li>
                                    <a @click.prevent="getnoInvoicesForeign( noInvoicesForeign.current_page_id + 1)" href="#" aria-label="Next">
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
        name: "NotHavingInvoiceComponent",
        data () {
            return {
                filter:{
                    uniqid: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                    purchase_no: '',
                    begin_date: '',
                    end_date: '',
                    admin_name: ''
                },

                isHidden: true,
                notHavingInvoices: {
                    invoices: '',
                    current_page_id: 1,
                    all_page_count: '',
                },
                noInvoicesForeign: {
                    invoices: '',
                    current_page_id: 1,
                    all_page_count: '',
                }
            }
        },
        methods:{
            getNoInvoices: function(page_id){
                this.getData(page_id);
            },
            getnoInvoicesForeign: function(page_id){
                this.getDataForeign(page_id);
            },

            checkForm () {
                this.errors = {};
                if (this.isEmpty(this.errors)) {

                    this.getData();
                    this.getDataForeign();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
            getData(page_id=1){
                axios.get('/cp/noInvoices?page='+page_id,{params: this.filter})
                    .then((response) => {
                        this.notHavingInvoices.invoices = response.data.data.invoices;
                        this.notHavingInvoices.all_page_count = parseInt(response.data.data.invoices.last_page);
                        this.notHavingInvoices.current_page_id = page_id;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getDataForeign(page_id=1){
                axios.get('/cp/noInvoicesForeign?page='+page_id,{params: this.filter})
                    .then((response) => {
                        this.noInvoicesForeign.invoices = response.data.data.invoices;
                        this.noInvoicesForeign.all_page_count = parseInt(response.data.data.invoices.last_page);
                        this.noInvoicesForeign.current_page_id = page_id;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        mounted(){
            this.getData();
            this.getDataForeign()
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','buyer','problematic_department'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>

</style>