<template>
    <div class="row">

        <div role="tabpanel" class="tab-pane fade in active" id="repayments">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th>İD</th>
                        <th>Kod</th>
                        <th>Məbləğ</th>
                        <th>Səbəb</th>
                        <th>Referans</th>
                        <th>Təsdiq kodu</th>
                        <th>Tarix</th>
                        <th>Qaytarma vaxtı</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="payment in repayments">
                        <td>{{payment.id}}</td>
                        <td>{{payment.payment_code}}</td>
                        <td>{{payment.repayment_amount}}</td>
                        <td>{{payment.reason}}</td>
                        <td>{{payment.referance}}</td>
                        <td>{{payment.confirmation_code}}</td>
                        <td>{{payment.created_at}}</td>
                        <td>{{payment.repayment_date}}</td>
                        <td v-if="payment.executer!=null">Qaytarılıb</td>
                        <td v-else>Gözləmədə</td>
                    </tr>
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getData( dataParams.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in dataParams.last_page">
                            <li v-if="page ==1 && dataParams.current_page==1" class="active">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)> 3 && Math.abs(dataParams.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)<= 3 && dataParams.current_page == page" class="active">
                                <a @click.prevent="getData(  page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)<= 3 ">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == dataParams.last_page">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="dataParams.current_page < dataParams.last_page" @click.prevent="getData(  dataParams.current_page + 1)" href="#" aria-label="Next">
                                <span aria-hidden="true">NÖVBƏTİ</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    export default {
        name: "UsersRePaymentsComponent",
        data(){
            return {
                repayments: '',
                current_type: '',
                dataParams: '',
            };
        },
        props: ['selected_type','user_id'],

        methods:{
            getData(page_id=1){
                axios.get('/cp/users/'+this.selected_type+'?page='+page_id,{params:{user_id:this.user_id}})
                        .then((response) => {
                        this.repayments = response.data.data.repayments.data;
                        this.dataParams = response.data.data.repayments;
                        console.log(this.dataParams);
                        this.current_type = this.selected_type;
                    })
            },

        },
        mounted(){
            this.getData();
        },
    }
</script>

<style scoped>

</style>