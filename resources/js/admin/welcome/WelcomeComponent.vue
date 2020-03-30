<template>
   <div class="row">
        <div v-if="roles.length ==0" class="loading">
            <img :src="require('../loading.gif')" />
        </div>
        <div v-if="roles.length > 0">
            <div class="col-md-12  col-sm-12  col-xs-12">
                <div class="top-menu">
                    <h4>Ana səhİfə</h4>
                    <ol class="breadcrumb">
                        <!--<li><a href="#"><i class="fa fa-home"></i>Admin</a></li>-->
                        <li class="active">Ana səhifə</li>
                    </ol>
                </div>
            </div>
            <div class="right-side col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div v-if="hasAccess('all')" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/userManagement">
                            <div class="block">
                                <img src="/admin/new/img/musteri-xidmeti.png" alt="mushteri">
                                <p>Müştərİ XİDMƏTİ</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('buyer','problematic_department','super_admin','dispatcher'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/orders">
                            <div class="block">
                                <img src="/admin/new/img/sifarisler.png" alt="sifarish">
                                <p>sİFaRİŞLƏR</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('accountant','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/accountant">
                            <div class="block">
                                <img src="/admin/new/img/baglamalar.png" alt="baglama">
                                <p>Mühasib</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('storage_home','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link :to="/depot/+region_id">
                            <div class="block">
                                <img src="/admin/new/img/anbar.png" alt="anbar">
                                <p>ANBAR</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('media','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <a href="/cp/news">
                            <div class="block">
                                <img src="/admin/new/img/xeberler.png" alt="xeber">
                                <p>XƏBƏRLƏR</p>
                            </div>
                        </a>
                    </div>
                    <div v-if="hasAccess(Array('media','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/staticPage">
                            <div class="block">
                                <img src="/admin/new/img/static-sehifeler.png" alt="static">
                                <p>STATİK SƏHİFƏLƏR</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('accountant','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/statistics">
                            <div class="block">
                                <img src="/admin/new/img/statistika.png" alt="xeber">
                                <p>STATİSTİKA</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('super_admin','problematic_department'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/problems/complaints">
                            <div class="block">
                                <img src="/admin/new/img/problemlerin-helli.png" alt="xeber">
                                <p>ProblemlərİN HƏLLİ</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('media','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <a href="/cp/shops?country_id=1">
                            <div class="block">
                                <img src="/admin/new/img/magazalar.png" alt="xeber">
                                <p>MAĞAZALAR</p>
                            </div>
                        </a>
                    </div>
                    <div v-if="hasAccess(Array('super_admin','dispatcher'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/kuryer">
                            <div class="block">
                                <img src="/admin/new/img/kuryer.png" alt="kuryer">
                                <p>KURYER</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('casher'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/kuryer-completed">
                            <div class="block">
                                <img src="/admin/new/img/kuryer.png" alt="kuryer">
                                <p>KURYER</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('accountant','super_admin','casher'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/account/kassa">
                            <div class="block">
                                <img src="/admin/new/img/kassa.png" alt="xeber">
                                <p>KASSA</p>
                            </div>
                        </router-link>
                    </div>
                    <div v-if="hasAccess(Array('custom','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/customs">
                            <div class="block">
                                <img src="/admin/new/img/gomruk.png" alt="xeber">
                                <p>gÖMRÜK</p>
                            </div>
                        </router-link>
                    </div>
                    <!--<div v-if="hasAccess(Array('courier','super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/courier-panel">
                            <div class="block">
                                <img src="/admin/new/img/kuryer.png" alt="kuryer-panel">
                                <p>KURYER PANEL</p>
                            </div>
                        </router-link>
                    </div>-->
                    <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/user-management">
                            <div class="block">
                                <img src="/admin/new/img/musteri-xidmeti.png" alt="mushteri">
                                <p>İstifadəçi tənzimlənmələri</p>
                            </div>
                        </router-link>
                    </div>
<!--                    <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">-->
<!--                        <a href="/cp/questions">-->
<!--                            <div class="block">-->
<!--                                <img src="/admin/new/img/suallar.png" alt="suallar">-->
<!--                                <p>Suallar</p>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </div>-->
                    <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-3 col-sm-4 col-xs-12">
                        <router-link to="/questions">
                            <div class="block">
                                <img src="/admin/new/img/suallar.png" alt="suallar">
                                <p>Suallar</p>
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
      name: 'welcome',
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
                    if(this.region_id==0){
                        this.region_id = '';
                    }
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
