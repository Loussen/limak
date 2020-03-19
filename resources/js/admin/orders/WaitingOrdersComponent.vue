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
                        <div class="form-group">
                            <label>Qəbul edən adı </label>
                            <input class="form-control" placeholder="Qəbul edən adı"  v-model="filter.admin_name">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <!--<div class=" form-group">
                            <label class="check-label">
                                Təcili
                                <input type="checkbox" v-model="filter.is_urgent" value="1">
                            </label>
                        </div>-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pull-right">
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
                <h4>GÖZLƏMƏDƏ OLAN SİFARİŞLƏR</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/orders">Sifarişlər</router-link></li>
                    <li class="active"> İcra olunan sifarişlər</li>
                </ol>
                <div class="row" style="margin: 0px 10px;">
                    <div v-for="(key, value) in regions" style="float: left;" class="col-xs-2">
                        <button class="btn-effect" @click="changeCurrentData(key)">{{ key }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th  v-bind="count_i=0"></th>
                        <th>Alıcı Adı, Soyadı</th>
                        <th>Müştəri Adı, Soyadı, Kodu</th>
                        <th>Müştəri E-maili</th>
                        <th>Müştəri Mobil Telefonu</th>
                        <th>Sifariş tarixi</th>
                        <th>Ətraflı</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order in executingOrders" :style="{ background: order.problem>0 ? '#f69679' : ((order.problem==0 && order.problem_text!=null) ? '#79e1c5' : '')}">

                    <td>{{count_i = count_i+1}}</td>
                        <td>{{ order.admin_name }} {{ order.admin_surname }}</td>
                        <td>
                            <span v-if="order.client_surname!=null && order.client_name!=null">
                                {{ order.client_name  }} {{ order.client_surname  }}
                                  <b>{{ order.uniqid }} C-{{order.client_id}}</b>
                            </span>
                            <span v-else>
                                {{ order.name }} {{ order.surname }} <b>{{ order.uniqid }}</b>
                            </span>
                        </td>
                        <td>{{ order.email }}</td>
                        <td>{{ order.phone }}</td>
                        <td>{{ order.created_at | formatDate}}</td>

                        <td>
                            <router-link class="btn-effect" v-bind:to="'/orders/executing/' + order.user_id + '/'+ order.region_id+'/'+order.client_id">Bax</router-link>

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
    </div>
</template>

<script>
    import auth from '../auth.js'
    export default {
        name: "executingOrderDetails",
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
                    admin_name: '',
                    type: 'waiting'
                },
                regions: [],
                regions_data: {},
                isHidden: true,
                current_data: '',
                executingOrders: ''
            }
        },
        methods:{
            details(user_id){
                this.$router.push({ name: 'executingOrderDetails', params: { id: user_id } })
            }
            ,
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
                axios.get('/cp/executingOrders',{params: this.filter})
                    .then((response) => {
                        this.executingOrders = response.data.data;
                        for(var i in this.executingOrders){
                            if(this.regions.indexOf(this.executingOrders[i].region_name) < 0){
                                this.regions.push(this.executingOrders[i].region_name)
                                this.regions[this.executingOrders[i].region_name] = []
                            }
                            this.regions[this.executingOrders[i].region_name].push(this.executingOrders[i]);
                        }

                        this.current_data = this.regions[this.regions[0]];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        mounted(){
            this.getData()
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
    .top-search .btn-effect {
        width: 120px;
        float: right;
        margin-top: 15px;
    }
</style>