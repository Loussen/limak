<template>
    <div>
        <div class="col-xs-12">
            <div class="top-menu">
                <h4>STATİSTİKA</h4>
                <ol class="breadcrumb">
                    <li>
                        <router-link to="/">
                            <i class="fa fa-home"></i>Ana səhifə
                        </router-link>
                    </li>
                    <li class="active">Statistika</li>
                </ol>
            </div>
        </div>
        <div class="tab-list col-xs-12">
            <ul class="nav nav-tabs list-style" role="tablist">
                <li class="active"><a href="#" class="block">Sifarişlər</a></li>
                <li><a href="#" class="block">Bağlamalar</a></li>
                <li><a href="#" class="block">Kuryer</a></li>
                <li><a href="#" class="block">Müştəri məlumatı</a></li>
                <li><a href="#" class="block">Daxil olan məlumat</a></li>
                <li><a href="#" class="block">Hesablar</a></li>
                <li><a href="#" class="block">Xərclər</a></li>
                <li><a href="#" class="block">Şikayətlər</a></li>
                <li><a href="#" class="block">Ödəmələr</a></li>
                <li><a href="#" class="block">Bütün göndərişlər</a></li>
                <li><a href="#" class="block">Anbar</a></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MenuComponent"
    }
</script>

<style scoped>

</style>