<template>
    <div class="accountant-menu-wrapper">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu">
                    <h4>Mühasib</h4>
                    <ol class="breadcrumb">
                        <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                        <li class="active"> {{ currentMenuName }}  </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <router-link class="block accountant-menu" to="/accountant">
                    <strong>Hesablar</strong>
                </router-link>
                <router-link class="block accountant-menu" to="/accountant/backToCard">
                    <strong>Karta geri qaytarılanlar</strong>
                </router-link>
                <router-link class="block accountant-menu" to="/accountant/customerBalance">
                    <strong>Müştəri balansı</strong>
                </router-link>
                <router-link class="block accountant-menu" to="/accountant/logBalances">
                    <strong>Balans loqları</strong>
                </router-link>
                <router-link class="block accountant-menu" to="/accountant/eft">
                    <strong>EFT/HƏVALƏ</strong>
                </router-link>
                <router-link class="block accountant-menu" to="/accountant/cashBack">
                    <strong>Nəğd geri ödəniş</strong>
                </router-link>
                <router-link class="block accountant-menu" to="/accountant/expenses">
                    <strong>Xərclər</strong>
                </router-link>
                <router-link class="block accountant-menu" to="/ordersStatistic">
                    <strong>Statistika</strong>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AccountantMenuComponent",
        computed: {
            currentMenuName: function(){
                switch (this.$route.path) {
                    case '/accountant/expenses':
                        return 'Xərclər'
                        break;
                    case '/accountant':
                        return 'Hesablar'
                        break;
                    case '/accountant/backToCard':
                        return 'Karta geri qaytarılanlar'
                        break;
                    case '/accountant/customerBalance':
                        return 'Müştəri balansı'
                        break;
                    case '/accountant/logBalances':
                        return 'Balans loqları'
                        break;
                    case '/accountant/eft':
                        return 'EFT/Həvalə'
                        break;
                    case '/accountant/eft':
                        return 'EFT/Həvalə'
                        break;
                    case '/accountant/cashBack':
                        return 'Nəğd geri ödəniş'
                        break;
                    case '/accountant/dispatch':
                        return 'Sevkiyyat'
                        break;
                    default:
                    // code block
                }
            }


        }
    }
</script>

<style scoped>
    .accountant-menu{
        padding: 15px 7px;
        width: calc( 12.35% - 25px );
        margin: 0 10px;
        display: inline-block;
    }
    .accountant-menu-wrapper{
        margin-bottom: 50px;
    }
    .router-link-exact-active{
        color: #f95732;
    }
</style>