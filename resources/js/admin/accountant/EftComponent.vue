<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="col-md-12 tab-content">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Ödəniş ediləcək şəxsin adı</th>
                        <th>Müştərinin Adı, Soyadı, Kodu</th>
                        <th>Link</th>
                        <th>İBAN №</th>
                        <th>Hesab №</th>
                        <th>Vergi №</th>
                        <th>Ödəniləcək mbləğ</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="eft in EFTs.data">
                        <td>{{ eft.paymentTo }}</td>
                        <td>{{ eft.name }} {{ eft.surname }} <br>{{ eft.uniqid }}</td>
                        <td><a target="_blank" :href="eft.link">Keçid</a></td>
                        <td>{{ eft.iban }}</td>
                        <td>{{ eft.account }}</td>
                        <td>{{ eft.tax }}</td>
                        <td>{{ eft.payment_amount }}</td>
                        <td>
                            <router-link class="btn-effect" v-bind:to="'/account/logs/'">Ödə</router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getEft( EFTs.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in EFTs.last_page">
                            <li v-if="page ==1 && EFTs.current_page==1" class="active">
                                <a @click.prevent="getEft(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getEft(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(EFTs.current_page - page)> 3 && Math.abs(EFTs.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(EFTs.current_page - page)<= 3 && EFTs.current_page == page" class="active">
                                <a @click.prevent="getEft(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(EFTs.current_page - page)<= 3 ">
                                <a @click.prevent="getEft(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == EFTs.last_page">
                                <a @click.prevent="getEft(page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="EFTs.current_page < EFTs.last_page" @click.prevent="getEft( EFTs.current_page + 1)" href="#" aria-label="Next">
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
        name: "EftComponent",
        data: function(){
            return {
                EFTs: []
            }
        },
        methods: {
            getEft: function(page){
                axios.get(`cp/accounts/eft?page=${page}`).then( ( { data } ) => {
                    this.EFTs = data.eft;
                });
            },
        },
        mounted(){
           this.getEft(1)
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
    td {
        font-size: 14px !important;
    }
</style>