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
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <!--<div class=" form-group">
                            <label class="check-label">
                                Təcili
                                <input type="checkbox" v-model="filter.is_urgent" value="1">
                            </label>
                        </div>-->
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
                <h4>GƏLƏN SİFARİŞLƏR</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/orders">Sifarişlər</router-link></li>
                    <li class="active">Gələn sifarişlər</li>
                </ol>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th  v-bind="count_i=0"></th>
                        <th>Müştəri Adı, Soyadı</th>
                        <th>Müştəri kodu</th>
                        <th>Ümumi ödədiyi məbləğ</th>
                        <th>Sifarişin verilmə forması</th>
                        <th>Sifarişin tarixi</th>
                        <th>Ətraflı</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!--<tr v-for="order in incomingOrders">
                        <td> {{ order.users.name  }} {{ order.users.surname  }}</td>
                        <td>{{ order.users.uniqid  }}</td>
                        <td>{{ ((parseFloat(order.price)*1.05).toFixed(2))}} TL</td>
                        <td v-if="order.is_urgent">Təcili</td>
                        <td v-else>Standart</td>
                        <td>{{order.created_at}}</td>
                        <td>
                            <button :data-user-id="order.user_id"  type="button" class="btn-effect" data-toggle="modal"
                                    :data-target="'#user' +order.user_id">Bax
                            </button>
                        </td>
                    </tr>-->
                    <tr v-for="order in incomingOrders" :style="{ background: order.problem>0 ? '#f69679' : ((order.problem==0 && order.problem_text!=null) ? '#79e1c5' : '')}">

                    <td>{{count_i = count_i+1}}</td>
                        <td v-if="order.client_surname!=null && order.client_name"> {{ order.client_name  }} {{ order.client_surname  }}</td>
                        <td v-else>{{order.name}} {{order.surname}}</td>
                        <td>{{ order.uniqid  }}
                            <span v-if="order.client_surname!=null && order.client_name && order.client_id"> -C{{ order.client_id  }}</span>

                        </td>
                        <td>{{ ((parseFloat(order.price)*1.05).toFixed(2))}} TL</td>
                        <td v-if="order.is_premium==1"><button class="btn btn-success">Premium</button></td>
                        <td v-else>Standart</td>
                        <td>{{order.created_at | formatDate}}</td>
                        <td>
                            <button :data-user-id="order.user_id"  type="button" class="btn-effect" data-toggle="modal"
                                    :data-target="'#user' +order.user_id+'C'+order.client_id">Qəbul
                            </button>
                        </td>
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
            </div>
        </div>
        <div v-for="order in incomingOrders" class="modal fade look" :id=" 'user'+order.user_id+'C'+order.client_id" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body">
                        <h4>Sİfarİşİ qəbul et</h4>
                        <img src="/admin/new/img/gelen-sifarisler.png" alt="sifarish">
                        <a href="#" @click.prevent="acceptOrder(order.user_id,order.client_id)" class="btn-effect" >Qəbul et</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js'
    window.axios = require('axios');
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    export default {
        name: "IncominOrdersComponent",
        data () {
            return {
                filter:{
                    uniqid: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                    begin_date: '',
                    end_date: '',
                },
                isHidden: true,
                incomingOrders: ''
            }
        },
        methods:{
            acceptOrder: function(user_id,client_id=0){
                axios.post('/cp/acceptOrder/' + user_id,{client_id:client_id})
                    .then((response) => {
                        $('#user' + user_id+'C'+client_id).modal('hide');
                        location.href = '#/orders/executing'
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            checkForm () {
                this.errors = {};
                if (this.isEmpty(this.errors)) {

                    this.getData();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
            getData(){
                axios.get('/cp/incomingOrders',{params: this.filter})
                    .then((response) => {
                        this.incomingOrders = response.data.data;
                        console.log(this.incomingOrders)
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }

        },
        mounted(){
            this.getData();
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','buyer'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>
.top-search .check-label {
    margin-top: 35px;
    float: left;
    display: inline-block;
}

.top-search .btn-effect {
    width: 150px;
    float: right;
    margin-top: 15px;
}

    button.btn-effect{
        width: 105px !important;
    }

</style>