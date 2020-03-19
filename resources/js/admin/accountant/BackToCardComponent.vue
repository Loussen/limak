<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <form action="...">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <div class="row">
                                <div class="col-md-3 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Müştəri kodu"  v-model="filter.uniqid">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Ad"  v-model="filter.name">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Soyad"  v-model="filter.surname">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="email" class="form-control" placeholder="E-mail" v-model="filter.email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <button type="button" class="btn-effect" @click="checkForm()">Axtar</button>
                        </div>
                    </div>
                </form>
                <div class="col-xs-12 search-result">
                    <p>Axtarışın nəticəsi: {{users.length}} nəticə</p>
                    <ul>
                        <li v-for="userRow in users">
                            <a @click="getRepayments(selectedRepayment, 1, executing,userRow.id)"><h3>№:{{userRow.id}} {{userRow.name}} {{userRow.surname}}</h3></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li @click="getRepayments(1,1,5,user_id)" role="presentation" :class="selectedRepayment ==1 ? 'active' : null"><a @click.prevent href="#" >PayTR</a></li>
                    <li @click="getRepayments(2,1,5,user_id)" role="presentation" :class="selectedRepayment ==2 ? 'active' : null"><a @click.prevent  href="#">Milli kart</a></li>
                    <li @click="getRepayments(3,1,5,user_id)" role="presentation" :class="selectedRepayment ==3 ? 'active' : null"><a @click.prevent  href="#">Bank</a></li>
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="tab-content courier">
                <div role="tabpanel" class="tab-pane active" id="paytr">
                    <ul class="nav nav-tabs" role="tablist">
                        <li @click="getRepayments(1,1,5,user_id)" role="presentation" :class="executing ==5 ? 'active' : null"><a @click.prevent  href="#">Icra olunmayan</a></li>
                        <li @click="getRepayments(1,1,4,user_id)" role="presentation" :class="executing ==4 ? 'active' : null"><a @click.prevent href="#" >Icra olunan</a></li>
                    </ul>
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th >Admin</th>
                                <th>Müştəri Adı, Soyadı, Kodu</th>
                                <th>Müştəri telefonu, E-maili</th>
                                <th>{{ getRepaymentName(this.selectedRepayment) }}</th>
                                <th>Ödənilmiş məbləğ</th>
                                <th>Ödənilmiş məbləğ +5%</th>
                                <th>Qaytarılacaq məbləğ</th>
                                <th>Səbəb</th>
                                <th>Tarix</th>
                                <th>Ətraflı</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="repayment in repayments.data" >
                                <td>{{ repayment.admin_name }} {{ repayment.admin_surname }}</td>
                                <td>{{ repayment.name }} {{ repayment.surname }} <br>{{ repayment.uniqid }}</td>
                                <td>{{ repayment.phone }}<br>{{ repayment.email }}</td>

                                <td>{{ repayment.payment_code }}</td>
                                <td>{{ repayment.payment_amount }} TL</td>
                                <td>{{ (repayment.payment_amount*1.05).toFixed(2) }} TL</td>
                                <td>{{ repayment.repayment_amount }} TL</td>
                                <td>{{ repayment.reason }}</td>
                                <td>{{ repayment.repayment_date | formatDate}}</td>
                                <td>
                                    <router-link class="btn-effect" v-bind:to="'/accountant/backToCard/'+ repayment.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li ><a @click.prevent="getRepayments( selectedRepayment, repayments.current_page - 1, executing,user_id)" href="#" aria-label="Previous"><span
                                        aria-hidden="true">ƏVVƏL</span></a></li>
                                <template  v-for="page in repayments.last_page">
                                    <li v-if="page ==1 && repayments.current_page==1" class="active">
                                        <a @click.prevent="getRepayments( selectedRepayment, page, executing,user_id)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="page ==1">
                                        <a @click.prevent="getRepayments( selectedRepayment, page, executing,user_id)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="Math.abs(repayments.current_page - page)> 3 && Math.abs(repayments.current_page - page)<= 6 ">
                                        <a href="#">.</a>
                                    </li>
                                    <li v-else-if="Math.abs(repayments.current_page - page)<= 3 && repayments.current_page == page" class="active">
                                        <a @click.prevent="getRepayments( selectedRepayment, page, executing,user_id)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="Math.abs(repayments.current_page - page)<= 3 ">
                                        <a @click.prevent="getRepayments( selectedRepayment, page, executing,user_id)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="page == repayments.last_page">
                                        <a @click.prevent="getRepayments( selectedRepayment, page, executing,user_id)" href="#">{{ page }}</a>
                                    </li>
                                </template>
                                <li>
                                    <a v-show="repayments.current_page < repayments.last_page" @click.prevent="getRepayments( selectedRepayment,  repayments.current_page + 1, executing,user_id)" href="#" aria-label="Next">
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
    import Menu from './AccountantMenuComponent'
    import auth from '../auth.js'
    export default {
        name: "BackToCardComponent",
        data: function(){
            return {
                selectedRepayment: 1,
                executing: 5,
                repayments: [],
                users: '',
                user: '',
                user_id: 0,
                filter: {
                    uniqid: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                },

            }
        },
        methods: {
            checkForm(){

                this.errors = {};
                if (this.isEmpty(this.errors)) {
                    this.getUsers();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
            getUsers(){
                this.users = '';
                this.user = '';
                axios.get('/cp/users/get',{params:this.filter})
                    .then((response) => {
                        this.allData = response.data.data;
                        this.users = this.allData.users;
                        if(this.allData.users.length==1){
                            this.getRepayments(this.selectedRepayment, 1, this.executing,this.allData.users[0].id);
                            // console.log(this.allData.users[0].id);
                        }
                        // console.log(this.allData.users.length);
                    })
            },

            getRepayments: function(type,page,executing,id){
                axios.get(`/cp/accounts/repayments/${type}?page=${page}&executing=${executing}&user_id=${id}`).then( ( { data } ) => {
                    console.log(data)
                    this.selectedRepayment = type;
                    this.executing = executing;
                    this.repayments = data.repayments;
                    this.user_id = id;
                });
            },
            getRepaymentName: function(type){
                if(type ==1) return 'PayTR';
                else if (type ==2) return 'Milli kart';
                else if (type ==3) return 'Bank';
            }
        },
        mounted(){
            this.getRepayments(1,1,5,this.user_id)
        },
        components: {
            Menu
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','accountant'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>

</style>