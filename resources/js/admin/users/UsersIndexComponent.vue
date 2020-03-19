<template>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>MÜŞTƏRİ XİDMƏTİ</h4>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-home"></i>Ana səhifə</a></li>
                    <li class="active">Sifarişlər</li>
                </ol>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div v-if="hasAccess(Array('super_admin','media'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/users/list">
                        <div class="block">
                            <img src="/admin/new/img/qeydiyyatdan-kecenler.png" alt="mushteri">
                            <p>QEYDİYYATDAN KEÇƏNLƏR</p>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess('all')" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/users/info">
                        <div class="block">
                            <img src="/admin/new/img/istifadeci-melumati.png" alt="sifarish">
                            <p>İSTİFADƏÇİ MƏLUMATI</p>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/problems/complaints">
                        <div class="block">
                            <img src="/admin/new/img/mesajlar.png" alt="baglama">
                            <p>SORĞULAR</p>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/">
                        <div class="block">
                            <img src="/admin/new/img/smsler.png" alt="anbar">
                            <p>SMS-LƏR</p>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/">
                        <div class="block">
                            <img src="/admin/new/img/emaillar.png" alt="xeber">
                            <p>E-MAİL-LAR</p>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/">
                        <div class="block">
                            <img src="/admin/new/img/zengler.png" alt="static">
                            <p>ZƏNGLƏR</p>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js'
    export default {
        name: "UsersIndexComponent",
        mixins: [auth]

    }
</script>

<style scoped>

</style>