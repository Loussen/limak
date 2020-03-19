<template>
   <div class="row">
        <div v-if="roles.length ==0" class="loading">
            <img :src="require('../loading.gif')" />
        </div>
        <div v-if="roles.length > 0">
            <div class="col-md-12  col-sm-12  col-xs-12">
                <div class="top-menu">
                    <h4>Anbar</h4>
                    <ol class="breadcrumb">
                        <!--<li><a href="#"><i class="fa fa-home"></i>Admin</a></li>-->
                        <li class="active">Ana səhifə</li>
                    </ol>
                </div>
            </div>
            <div class="right-side col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div v-if="hasAccess(Array('storage_home','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12" v-for="region in regions">
                        <router-link :to='/depot/+region.id'>
                            <div class="block">
                                <img src="/admin/new/img/anbar.png" alt="anbar">
                                <p>{{region.name}} ANBARI</p>
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
        name: 'DepotDashboard',
        data() {
          return {
              regions: null,
              region_id:0
          }
        },
        methods: {
            getRegions(){
                axios.get('/cp/admin/getRegions').then((response) => {
                    this.regions = response.data.regions;
                    this.region_id = response.data.region_id;
                }).catch((e) => {
                    console.log(e);
                });
            }
        },
      mounted(){
          this.getRegions();
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
