<template>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Kassa hesabı ətraflı</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/accounts">Hesablar</router-link></li>
                    <li class="active">Hesab əməliyyatları</li>
                </ol>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Əməliyyat tipi</th>
                        <th>Ödənişdən qabaq</th>
                        <th>Ödəniş məbləği</th>
                        <th>Ödənişdən sonra</th>
                        <th>Admin</th>
                        <th>İstifadəçi</th>
                        <th>Tarix</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="log in accountLogs">
                        <td> {{ log.type }} </td>
                        <td>{{ log.before_payment }}</td>
                        <td>{{ log.payment}}</td>
                        <td>{{ log.after_payment }}</td>
                        <td v-if="log.admin">{{ log.admin.name }} {{ log.admin.surname }}</td>
                        <td v-else></td>
                        <td v-if="log.user">{{ log.user.name }} {{ log.user.surname }}</td>
                        <td v-else></td>
                        <td>{{ log.created_at}}</td>
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
        <!--<div v-for="order in incomingOrders" class="modal fade look" :id=" 'user'+order.user_id" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">-->
            <!--<div class="modal-dialog modal-sm" role="document">-->
                <!--<div class="modal-content">-->
                    <!--<div class="modal-header">-->
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="https://limak.az/admin/img/Forma 1.png" alt="close"></button>-->
                    <!--</div>-->
                    <!--<div class="modal-body">-->
                        <!--<h4>Sİfarİşİ qəbul et</h4>-->
                        <!--<img src="/admin/new/img/gelen-sifarisler.png" alt="sifarish">-->
                        <!--<a href="#" @click.prevent="acceptOrder(order.user_id)" class="btn-effect" >Qəbul et</a>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
    </div>
</template>

<script>
    export default {
        name: "CashLogsComponent",
        data: function(){
            return {
                accountLogs: {}
            }
        },
        mounted(){
            axios.get(`/cp/accounts/logs/${this.$route.params.id}`)
                .then((response) => {

                    this.accountLogs = response.data.logs
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    }
</script>

<style scoped>

</style>