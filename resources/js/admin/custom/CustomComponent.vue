<template>
    <div class="container custom" v-if="this.roles.length >0">
        <div class="row">
            <div class="top-menu">
                <h4>Gömrük</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li class="active">Gömrük</li>
                </ol>
            </div>
        </div>
        <div class="row custom-blocks">
            <div class="col-md-3">
                <a href="/cp/customs/manifest" target="_blank" class="btn btn-default">Manifest Türkiye</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/xml" target="_blank" class="btn btn-default">XML Türkiye</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/xmlParts" target="_blank" class="btn btn-default">XML Hissələr</a>
            </div>
            <div class="col-md-3" v-if="hasAccess(Array('super_admin','accountant'))">
                <router-link to="/customs/prices" target="_blank" class="btn btn-default">Hesabat Türkiye</router-link>
            </div>
            <div class="clearfix"></div>
            <hr />
            <div class="col-md-3">
                <a href="/cp/customs/xmlUsa" target="_blank" class="btn btn-default">XML Amerika</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/manifestUsa" target="_blank" class="btn btn-default">Manifest Amerika</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/manifestUsaBaku" target="_blank" class="btn btn-default">Manifest Amerika Baki</a>
            </div>

            <div class="col-md-3"  v-if="hasAccess(Array('super_admin','accountant'))">
                <router-link to="/customs/pricesUsa" target="_blank" class="btn btn-default">Hesabat Amerika</router-link>
            </div>


            <div class="clearfix"></div>

            <hr />


            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=2&country_id=1" target="_blank" class="btn btn-default">Türkiye-Gəncə</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=3&country_id=1" target="_blank" class="btn btn-default">Türkiye-Sumqayıt</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=4&country_id=1" target="_blank" class="btn btn-default">Türkiye-Zaqatala</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=5&country_id=1" target="_blank" class="btn btn-default">Türkiye-Lənkəran</a>
            </div>

            <hr />


            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=2&country_id=2" target="_blank" class="btn btn-default">Amerika-Gəncə</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=3&country_id=2" target="_blank" class="btn btn-default">Amerika-Sumqayıt</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=4&country_id=2" target="_blank" class="btn btn-default">Amerika-Zaqatala</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/regions?region_id=5&country_id=2" target="_blank" class="btn btn-default">Amerika-Lənkəran</a>
            </div>

            <hr />
            <div class="col-md-3">
                <a href="/cp/customs/liquid?region_id=1" target="_blank" class="btn btn-default">Mayeler Baki</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/liquid?region_id=2" target="_blank" class="btn btn-default">Mayeler Gence</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/liquid?region_id=3" target="_blank" class="btn btn-default">Mayeler Sumqayit</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/liquid?region_id=4" target="_blank" class="btn btn-default">Mayeler Zaqatala</a>
            </div>
            <hr />
            <div class="col-md-3">
                <a href="/cp/customs/manifestLiquid" target="_blank" class="btn btn-default">Manifest Mayeler</a>
            </div>
            <div class="col-md-3">
                <a href="/cp/customs/xmlLiquid" target="_blank" class="btn btn-default">XML Mayeler</a>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js'
    export default {
        name: "CustomComponent",
        data: function(){
            return {
                data : '',
            }
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','custom'))
            }
        },
        methods: {
            getPrices(){
                axios.get('/cp/statistic/getShippingPrices').then(({data}) => {
                    this.data = data.data;
                    console.log(this.data);
                })
            },
        },
        mounted() {
            this.getPrices()
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .btn-default{
        width: 200px;
    }
    .custom-blocks{
        padding-top: 50px;
    }
</style>