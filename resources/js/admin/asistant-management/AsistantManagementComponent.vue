<template>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>LIMAK ASISTANT</h4>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-home"></i>Ana səhifə</a></li>
                    <li class="active">Limak Asistant</li>
                </ol>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/questions">
                        <div class="block">
                            <img src="/admin/new/img/suallar.png" alt="suallar">
                            <p>SUALLAR</p>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess(Array('super_admin'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/questions_title">
                        <div class="block">
                            <img src="/admin/new/img/questions_title.png" alt="basliqlar">
                            <p>SUAL BAŞLIQLARI</p>
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
        name: "AsistantManagementComponent",
        mixins: [auth]

    }
</script>

<style scoped>

</style>