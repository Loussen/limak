<template>
    <div class="row">
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
            </div>
        </div>
    </div>
</template>

<script>
    import Menu from  './AccountantMenuComponent'
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
        components: {
            Menu
        }
    }
</script>

<style scoped>

</style>