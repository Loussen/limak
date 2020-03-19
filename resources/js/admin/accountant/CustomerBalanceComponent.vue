<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <a href="#" @click.prevent="getUserBalances(1,'plus')" class="btn btn-default" :class="seleced_balance == 'plus' ? 'active' : null">+ balansı olanlar</a>
                </div>
                <div class="col-md-1">
                    <a @click.prevent="getUserBalances(1,'minus')"  href="#" class="btn btn-default" :class="seleced_balance == 'minus' ? 'active' : null">- balansı olanlar</a>
                </div>
            </div>
        </div>
        <div class="col-md-12 tab-content">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Müştəri Adı, Soyadı, Kodu</th>
                        <th>Balans qalığı</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="customer.uniqid" v-for="customer in balances.data">
                        <td>{{ customer.name}} {{ customer.surname}} <br>{{ customer.uniqid}}</td>
                        <td>{{ customer.balance}} AZN</td>
                    </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getUserBalances( balances.current_page - 1,seleced_balance)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in balances.last_page">
                            <li v-if="page ==1 && balances.current_page==1" class="active">
                                <a @click.prevent="getUserBalances(page,seleced_balance)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getUserBalances(page,seleced_balance)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(balances.current_page - page)> 3 && Math.abs(balances.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(balances.current_page - page)<= 3 && balances.current_page == page" class="active">
                                <a @click.prevent="getUserBalances(page,seleced_balance)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(balances.current_page - page)<= 3 ">
                                <a @click.prevent="getUserBalances(page,seleced_balance)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == balances.last_page">
                                <a @click.prevent="getUserBalances(page,seleced_balance)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="balances.current_page < balances.last_page" @click.prevent="getUserBalances( balances.current_page + 1,seleced_balance)" href="#" aria-label="Next">
                                <span aria-hidden="true">NÖVBƏTİ</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    import Menu from './AccountantMenuComponent'
    import auth from '../auth.js'
    export default {
        name: "CustomerBalanceComponent",
        data: function(){
            return {
                balances: [],
                seleced_balance : 'plus',
            }
        },
        methods: {
           getUserBalances: function(page_id,seleced_balance){
               this.seleced_balance = seleced_balance;
               axios.get('/cp/accountant/userBalance?page='+page_id+ '&&balance='+ seleced_balance)
                   .then(({data}) => {
                       this.balances = data.balances;
                   })
                   .catch(function (error) {
                       console.log(error);
                   });
           }
        },
        mounted(){
            this.getUserBalances(1,'plus');
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
    .table > tbody > tr > td:last-child, .table > thead > tr > th:last-child {
        text-align: left;
    }
    .table > tbody > tr > td:first-child, .table > thead > tr > th:first-child {
        width: 30%;
    }
    .tab-content{
        margin-top: 20px;
    }
    .btn-default.active{
        color: #f95732;
    }
</style>