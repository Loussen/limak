<template>
   <div class="row">
        <div v-if="roles.length ==0" class="loading">
            <img :src="require('../loading.gif')" />
        </div>
        <div v-if="roles.length > 0">
            <div class="col-md-12  col-sm-12  col-xs-12">
                <div class="top-menu">
                    <h4>Kassalar</h4>
                    <ol class="breadcrumb">
                        <!--<li><a href="#"><i class="fa fa-home"></i>Admin</a></li>-->
                        <li class="active">Ana səhifə</li>
                    </ol>
                </div>
            </div>
            <div class="right-side col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div v-if="hasAccess(Array('accountant','super_admin','casher'))" class="block-list col-md-3 col-sm-4 col-xs-12" v-for="account in accounts">
                        <router-link :to="'/account/kassa/'+account.account_id">
                            <div class="block">
                                <img src="/admin/new/img/kassa.png" alt="xeber">
                                <p>{{account.name}}</p>
                                <span><img src="/admin/new/img/list.png" alt="endir"> {{ account.balance }}</span>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js'
    export default {
        name: 'KassaDashboard',
        data() {
          return {
              accounts: null,
              region_id:0
          }
        },
        methods: {
            getAccounts(){
                axios.get('/cp/cash/accounts').then((response) => {
                    this.accounts = response.data.accounts;
                    this.region_id = response.data.region_id;
                }).catch((e) => {
                    console.log(e);
                });
            }
        },
      mounted(){
          this.getAccounts();
      },
      mixins: [auth],
    }
</script>

<style>
    .loading{
        width: 100%;
        height: calc( 100vh - 80px );
        background: #fff;
        padding-top: 200px;
    }
    .loading img{
        display: block;
        margin: 0 auto;
    }
</style>
