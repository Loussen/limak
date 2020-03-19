<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="col-md-12 tab-content">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Ödənilən məbləğ</th>
                        <th>Ödəyən şəxs</th>
                        <th>Ödəmə tarixi</th>
                        <th>Ödəmə səbəbi</th>
                        <th>Ödənilən şəxs</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="cashback in cashbacks.data">
                        <td>{{ cashback.repayment_amount }}</td>
                        <td>{{ cashback.name }} {{ cashback.surname }}</td>
                        <td>{{ cashback.created_at }}</td>
                        <td>{{ cashback.reason }}</td>
                        <td>{{ cashback.paymentTo }}</td>
                    </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getCashbacks( cashbacks.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in cashbacks.last_page">
                            <li v-if="page ==1 && cashbacks.current_page==1" class="active">
                                <a @click.prevent="getCashbacks(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getCashbacks(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(cashbacks.current_page - page)> 3 && Math.abs(cashbacks.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(cashbacks.current_page - page)<= 3 && cashbacks.current_page == page" class="active">
                                <a @click.prevent="getCashbacks(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(cashbacks.current_page - page)<= 3 ">
                                <a @click.prevent="getCashbacks(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == cashbacks.last_page">
                                <a @click.prevent="getCashbacks(page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="cashbacks.current_page < cashbacks.last_page" @click.prevent="getCashbacks( cashbacks.current_page + 1)" href="#" aria-label="Next">
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
        import auth from '../auth.js'
        import Menu from './AccountantMenuComponent'
        export default {
            name: "CashBackComponent",
            data: function(){
                return {
                    cashbacks: []
                }
            },
            methods: {
                getCashbacks: function(page){
                    axios.get(`/cp/accounts/repayments/4?page=${page}`).then( ( { data } ) => {
                        this.cashbacks = data.repayments;
                    });
                },
            },
            mounted(){
                this.getCashbacks(1)
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