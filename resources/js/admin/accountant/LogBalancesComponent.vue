<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>

        <div class="col-md-12 tab-content">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Müştəri Adı, Soyadı, Kodu</th>
                        <th>Ödəniş</th>
                        <th>Qeyd</th>
                        <th>Tarix</th>
                        <th>Əvvəl:Sonra</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="log in logs.data">
                        <td>{{ log.u_name}} {{ log.u_surname}} <br>{{ log.uniqid}}</td>
                        <td>{{ log.money}} {{log.type}}</td>
                        <td>{{ log.message}}</td>
                        <td>{{ log.created_at}}</td>
                        <td>{{ log.old_balance}} {{log.type}} : {{ log.new_balance}} {{log.type}}</td>
                    </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getLogBalances( logs.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in logs.last_page">
                            <li v-if="page ==1 && logs.current_page==1" class="active">
                                <a @click.prevent="getLogBalances(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getLogBalances(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(logs.current_page - page)> 3 && Math.abs(logs.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(logs.current_page - page)<= 3 && logs.current_page == page" class="active">
                                <a @click.prevent="getLogBalances(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(logs.current_page - page)<= 3 ">
                                <a @click.prevent="getLogBalances(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == logs.last_page">
                                <a @click.prevent="getLogBalances(page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="logs.current_page < logs.last_page" @click.prevent="getLogBalances( logs.current_page + 1,seleced_balance)" href="#" aria-label="Next">
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
        name: "LogBalancesComponent",
        data: function(){
            return {
                logs: [],
            }
        },
        methods: {
           getLogBalances: function(page_id){
               axios.get('/cp/accountant/getLogBalances?page='+page_id)
                   .then(({data}) => {
                       this.logs = data.logs;
                   })
                   .catch(function (error) {
                       console.log(error);
                   });
           }
        },
        mounted(){
            this.getLogBalances(1);
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