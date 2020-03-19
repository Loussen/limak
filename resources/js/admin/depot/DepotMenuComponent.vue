<template>
    <div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu">
                    <ol class="breadcrumb">
                        <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                        <li class="active">Anbar</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row menu-blocks">
            <div class="menu-block menu-block-1">
                <div class="top_block">
                    <div @click="$emit('onOpenPackages')" class="left_block">
                        <span>Bağlama sayı</span>
                        <strong>{{ menuDetails.depots_count }} </strong>
                    </div>
                    <div class="right_block">
                        <span>Boş yer sayı</span>
                        <strong> {{ menuDetails.empty_places }} </strong>
                    </div>
                </div>
                <div class="bottom_block">
                    <router-link :to="{ path: '/depot/'+ this.region_id}">
                        <strong>Anbar</strong>
                    </router-link>
                </div>
            </div>
            <div class="menu-block">
                <div class="top_block"><strong>{{ menuDetails.payed_products }}</strong></div>
                <div class="bottom_block">
                    <router-link :to="{ path: '/depot/'+ this.region_id + '/payed'}">
                        <strong>Ödənlmiş mallar</strong>
                    </router-link>
                </div>
            </div>
            <div class="menu-block">
                <div class="top_block"><strong>{{ menuDetails.deliverd_products }}</strong></div>
                <div class="bottom_block">
                    <router-link :to="{ path: '/depot/'+ this.region_id + '/delivered'}">
                        <strong>Təhvil verilən bağlamalar</strong>
                    </router-link>
                </div>
            </div>
            <div class="menu-block">
                <div class="top_block"><strong>{{ menuDetails.inDepot15Days }}</strong></div>
                <div class="bottom_block">
                    <router-link :to="{ path: '/depot/'+ this.region_id + '/15days'}">
                        <strong>15 gündən artlq qalan bağlamalar</strong>
                    </router-link>
                </div>
            </div>
            <div class="menu-block">
                <div class="top_block"><strong>{{ menuDetails.inDepot45Days }}</strong></div>
                <div class="bottom_block">
                    <router-link :to="{ path: '/depot/'+ this.region_id + '/45days'}">
                        <strong>45 gündən artlq qalan bağlamalar</strong>
                    </router-link>
                </div>
            </div>
            <div class="menu-block">
                <div class="top_block"><strong>{{ menuDetails.custom_products }}</strong></div>
                <div class="bottom_block">
                    <router-link :to="{ path: '/depot/'+ this.region_id + '/custom'}">
                        <strong>Saxlanılan mallar</strong>
                    </router-link>
                </div>
            </div>
            <div class="menu-block" v-if="this.region_id>1">
                <div class="top_block"><strong>{{ menuDetails.region_products }}</strong></div>
                <div class="bottom_block">
                    <router-link :to="{ path: '/depot/'+ this.region_id + '/region'}">
                        <strong>Gömrükdə</strong>
                    </router-link>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "DepotMenuComponent",
        data: function(){
            return {
                menuDetails: {}
            }
        },
        methods : {
            initialize(){
                axios.get('/cp/depotDetails',{params:{region_id:this.region_id}}).then( ( {data} ) => {
                    this.menuDetails = data;
                });
            }
        },
        props: ['roles','region_id'],
        mounted(){
            this.initialize();
            /*setInterval(() => {
                this.initialize();
            }, 3000)*/
        }
    }
</script>

<style scoped>
    .router-link-exact-active{
        color: #f95732;
    }
    .menu-wrapper a{
        color: #2a2b2f;
    }
    .menu-wrapper .col-md-3{
        min-width: 250px;
    }
    .menu-block{
        background: #fff;
        display: block;
        width: calc( 14% - 18px );
        height: 190px;
        border-radius: 8px;
        margin: 0 10px;
        text-align: center;
        text-transform: uppercase;
        float: left;
    }
    .top_block {
        height: 70%;
        border-bottom: 1px solid #eef3f6;
        font-size: 25px;
        padding-top: 50px;
    }
    .bottom_block {
        height: 30%;
        padding-top: 15px;
    }
    .menu-block-1 .top_block {
        padding-top: 40px;
    }
    .menu-block-1 .top_block .left_block{
        border-right: 1px solid #eef3f6;
    }
    .menu-block-1 .top_block div{
        width: 50%;
        height: 100%;
        float: left;
    }
    .menu-block-1 .top_block span{
        display: block;
        font-size: 12px;
    }
    .left_block{
        cursor: pointer;
    }
</style>