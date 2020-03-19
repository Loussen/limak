<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <form action="...">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input v-on:keyup.13="checkForm()" type="text" class="form-control" placeholder="Müştəri kodu"  v-model="filter.uniqid">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Telefon"  v-model="filter.phone">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Ad"  v-model="filter.name">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Soyad"  v-model="filter.surname">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input type="date" class="form-control" placeholder="Doğum tarixi"  v-model="filter.birthdate">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="input-group">
                                        <input type="email" class="form-control" placeholder="E-mail" v-model="filter.email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <button type="button" class="btn-effect" @click="checkForm()">Axtar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>İSTİFADƏÇİ MƏLUMATLARI</h4>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-home"></i>Ana səhifə</a></li>
                    <li><a href="#">Müştəri xidməti</a></li>
                    <li class="active">İstifadəçi məlumatı</li>
                </ol>
            </div>
        </div>
        <div class="tab-list col-md-12 col-sm-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#" class="block" @click.prevent="getData(1,'all')">
                        <p>{{usersCount}}</p>Ümumi istifadəçilərin  sayı
                    </a>
                </li>
                <li role="presentation">
                    <a href="#" class="block" @click.prevent="getData(1,'man')">
                        <p>{{usersMan}}</p>Kişi
                    </a>
                </li>
                <li role="presentation">
                    <a href="#" class="block"  @click.prevent="getData(1,'woman')">
                        <p>{{usersWoman}}</p>Qadın
                    </a>
                </li>

                <li role="presentation">
                    <a href="#" class="block"  @click.prevent="getData(1,'month')">
                        <p>{{usersMonth}}</p>Cari ayda qeydiyyatdan  keçənlər
                    </a>
                </li>
                <li role="presentation">
                    <a href="#daily" class="block" @click.prevent="getData(1,'day')">
                        <p>{{usersDay}}</p>Gün ərzində qeydiyyatdan  keçənlər
                    </a>
                </li>
                <li role="presentation">
                    <a href="#" class="block">
                        <p>{{usersBalance}}</p>Ümumi istifadəçilərin balansı
                    </a>
                </li>
                <li role="presentation">
                    <a href="#" class="block"  @click.prevent="getData(1,'corporate')">
                        <p>{{usersCorporate}}</p>Korporativ
                    </a>
                </li>
                <li role="presentation">
                    <a href="#" class="block"  @click.prevent="getData(1,'is_premium')">
                        <p>{{usersPremium}}</p>Premium
                    </a>
                </li>
                <li role="presentation">
                    <a href="#" class="block"  @click.prevent="getData(1,'is_blocked')">
                        <p>{{usersBlocked}}</p>Blok olunmuşlar
                    </a>
                </li>
                <li role="presentation">
                    <a href="#" class="block"  @click.prevent="getData(1,'is_blacklist')">
                        <p>{{usersBlackList}}</p>Qara siyahı
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content qeydiyyat">
            <div role="tabpanel" class="tab-pane fade in active" id="umumi">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="block table-block">
                        <table class="table table-responsive table-striped">
                            <thead>
                            <tr>
                                <th>İstifadəçi Ad, Soyad</th>
                                <th>Kodu</th>
                                <th>Email</th>
                                <th>Telefonu</th>
                                <th>Vətəndaşlıq</th>
                                <th>Pin</th>
                                <th>Seriya nömrəsi</th>
                                <th>Ünvan</th>
                                <th>Doğum tarixi</th>
                                <th>Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="user in users.data">
                                <td>{{user.name}} {{user.surname}}</td>
                                <td>{{user.uniqid}}</td>
                                <td>{{user.email}}</td>
                                <td v-if="user.user_contacts.length > 0 && user.user_contacts!=null">{{user.user_contacts[0].name}}</td>
                                <td v-else>Yoxdur</td>
                                <td>{{user.nationality}}</td>
                                <td>{{user.pin}}</td>
                                <td>{{user.serial_number}}</td>
                                <td>{{user.address}}</td>
                                <td>{{user.birthdate}}</td>
                                <td>
                                    <button type="button" v-if="user.is_blocked == 0" class="btn-effect yellow" @click="blockUser(user.id, 1)">Blokla</button>
                                    <button type="button" v-else="" class="btn-effect green" @click="blockUser(user.id, 0)">Blokdan çıxart</button>
                                    <button type="button" class="btn-effect">Bax</button>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li ><a @click.prevent="getData( users.current_page - 1)" href="#" aria-label="Previous"><span
                                        aria-hidden="true">ƏVVƏL</span></a></li>
                                <template  v-for="page in users.last_page">
                                    <li v-if="page ==1 && users.current_page==1" class="active">
                                        <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="page ==1">
                                        <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="Math.abs(users.current_page - page)> 3 && Math.abs(users.current_page - page)<= 6 ">
                                        <a href="#">.</a>
                                    </li>
                                    <li v-else-if="Math.abs(users.current_page - page)<= 3 && users.current_page == page" class="active">
                                        <a @click.prevent="getData(  page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="Math.abs(users.current_page - page)<= 3 ">
                                        <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                                    </li>
                                    <li v-else-if="page == users.last_page">
                                        <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                                    </li>
                                </template>
                                <li>
                                    <a v-show="users.current_page < users.last_page" @click.prevent="getData(  users.current_page + 1)" href="#" aria-label="Next">
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
    import auth from '../auth.js'
    window.axios = require('axios');
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    export default {
        name: "UsersListComponent",
        data() {
            return {
                allData:'',
                users: '',
                usersCount: '',
                usersMan: '',
                usersWoman: '',
                usersMonth: '',
                usersDay: '',
                usersBalance: '',
                usersPremium: '',
                usersBlackList: '',
                usersBlocked: '',
                usersCorporate: '',
                filter: {
                    uniqid: '',
                    name: '',
                    surname: '',
                    email: '',
                    phone: '',
                    birthdate: '',
                },
            }
        },
        methods:{
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
            getData(page_id=1,type='all'){
                axios.get('/cp/users/index?page='+page_id+'&type='+type,{params:this.filter})
                    .then((response) => {
                        this.allData = response.data.data;
                        this.users = this.allData.users;
                        this.usersCount = this.allData.usersCount;
                        this.usersMan = this.allData.usersGender;
                        this.usersWoman = this.usersCount - this.usersMan;
                        this.usersMonth = this.allData.usersMonth;
                        this.usersDay = this.allData.usersDay;
                        this.usersCorporate = this.allData.usersCorporate;
                        this.usersPremium = this.allData.usersPremium;
                        this.usersBlackList = this.allData.usersBlackList;
                        this.usersBlocked = this.allData.usersBlocked;
                        this.usersBalance = this.allData.usersBalance;
                    })
            },

            blockUser(user_id, is_blocked){
                axios.post('/cp/users/blockUser',{user_id, is_blocked})
                    .then((response) => {
                        this.getData();
                    })
            }
        },
        mounted(){
            this.getData();
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','media','operator'))
            }
        },

        mixins: [auth]
    }

</script>

<style scoped>
    .qeydiyyat .table-block .btn-effect.green:after {
        content: none;
    }
    .qeydiyyat .table-block .btn-effect.green {
        width: 52%;
        padding-left: 7px;
    }
</style>
