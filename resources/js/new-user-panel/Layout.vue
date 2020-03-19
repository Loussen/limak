<template>
    <div id="app-layout" class="panel-mobile">
        <section :class="this.$route.path == '/' || this.$route.path == '/order' ? 'spin-load' : ''" class="content ">
            <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="page-head col-xs-12">
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <h4 @click="ProductsModal = true">{{ExWindow ? ExWindow.translator('panel-errors.user') : ''}} <span>{{ExWindow ? ExWindow.translator('panel-errors.panel') : ''}}</span></h4>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-6">
                                <span class="user-code">{{ExWindow ? ExWindow.translator('panel-errors.user_code')+ ':' : ''}}  <b>{{User.uniqid}}</b></span>
                                <ol class="breadcrumb web">
                                    <li><a href="/">{{ExWindow ? ExWindow.translator('panel.home_page') : ''}} </a></li>
                                    <li class="active">{{ExWindow ? ExWindow.translator('panel.user_panel') : ''}}</li>
                                </ol>
                            </div>
                        </div>
                        <div id="sticky-anchor"></div>
                        <!--<div class="block mobile-breadcrumb">-->
                        <!--<img src="/front/img/xaricdeki-unvanlarim.png" alt="xaricdeki-unvanlarimtop">-->
                        <!--<span>{{ExWindow ? ExWindow.translator('panel-errors.foreign_addresses') : ''}}</span>-->
                        <!--</div>-->
                        <!--<div class="page-head">-->
                        <!--<div class="row">-->
                        <!--<div class="col-md-4 col-sm-12 col-xs-12">-->
                        <!--<h1>{{User.name}} {{ User.surname }}</h1>-->
                        <!--<p>{{ExWindow ? ExWindow.translator('panel-errors.user_code'): ''}}: <b>{{User.uniqid}}</b></p>-->
                        <!--</div>-->
                        <!--<div class="col-md-8 col-sm-12 col-xs-12">-->
                        <!--<a href="/order/link/insert" class="btn-effect">-->
                        <!--{{ExWindow ? ExWindow.translator('panel-errors.order') : ''}} <img src="/front/img/plus.png" alt="plus">-->
                        <!--</a>-->
                        <!--<a :href=" ExWindow ? '/' + ExWindow.default_locale + '/invoice' : ''" class="btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.add_invoice') : ''}}</a>-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--</div>-->
                        <div  class="panel-page col-xs-12">
                            <div class="row relative">
                                <div class="col-md-3 col-sm-1 col-xs-1">
                                    <div class="block left-side">
                                        <ul class="left-sidebar">
                                            <router-link @click.native="changeClass('cls0')" active-class="active" tag="li" to="/">
                                                <a class="panel-img">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.user_panel') : ''}}</span>
                                                </a>
                                            </router-link>
                                            <router-link active-class="active" tag="li" to="/foreign-address">
                                                <a class="address">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.foreign_addresses') : ''}}</span>
                                                </a>
                                            </router-link>
                                            <router-link  active-class="active" tag="li" to="/orders">
                                                <a class="basket">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.orders') : '' }}</span>
                                                </a>
                                            </router-link>
                                            <router-link  active-class="active"  tag="li" to="/invoices">
                                                <a class="package">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.invoices') : '' }}</span>
                                                </a>
                                            </router-link><!--
                                            <router-link  active-class="active"  tag="li" to="/track">
                                                <a class="route">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.track_order') : '' }}</span>
                                                </a>
                                            </router-link>-->
                                            <router-link  active-class="active" tag="li" to="/balance">
                                                <a class="coin">
                                                    <span>AZN {{ExWindow ? ExWindow.translator('panel-errors.balance') : '' }}</span>
                                                </a>
                                            </router-link>
                                            <router-link  active-class="active" tag="li" to="/balanceTl">
                                                <a class="coin">
                                                    <span>TL {{ExWindow ? ExWindow.translator('panel-errors.balance') : '' }}</span>
                                                </a>
                                            </router-link>
                                            <router-link  active-class="active" tag="li" to="/courier">
                                                <a class="courier">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.courier') : '' }}</span>
                                                </a>
                                            </router-link>
                                            <router-link  active-class="active" tag="li" to="/questions">
                                                <a class="offer">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.request') : '' }}</span>
                                                </a>
                                            </router-link>

                                            <router-link  active-class="active" tag="li" to="/settings">
                                                <a class="settings">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.settings') : '' }}</span>
                                                </a>
                                            </router-link>
                                            <router-link v-if="User.corporate==1"  active-class="active" tag="li" to="/clients">
                                                <a class="panel-img">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.corporate_clients') : '' }}</span>
                                                </a>
                                            </router-link>
                                            <li>
                                                <a href="/logout" class="logout">
                                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.logout') : '' }}</span>
                                                </a>
                                            </li>
                                            <!--<router-link  active-class="active"   tag="li" to="/orders"><a><span :class="clsArry.cls1? 'img-order-w':'img-order'" alt="sifarish"></span>{{ExWindow ? ExWindow.translator('panel-errors.orders') : '' }}</a> </router-link>-->
                                            <!--<router-link  active-class="active"  tag="li" to="/messenger"><a><span :class="clsArry.cls3?'img-messages-w':'img-messages'" alt="mesajlar"></span>{{ExWindow ? ExWindow.translator('panel-errors.messages') : '' }}</a> </router-link>-->
                                            <!--<router-link  active-class="active" tag="li" to="/basket"><a><span :class="clsArry.cls8?'img-invoice-w':'img-invoice'"alt="sebet"></span>{{ExWindow ? ExWindow.translator('panel-errors.basket') : '' }}</a> </router-link>-->
                                            <!--<router-link  active-class="active" tag="li" to="/balance"><a><span :class="clsArry.cls5?'img-balance-w':'img-balance'" alt="catdirilma"></span>{{ExWindow ? ExWindow.translator('panel-errors.balance') : '' }}</a> </router-link>-->
                                            <!--<router-link class-active="active" tag="li" to="/foreign-addr"><a><img src="/front/img/kuryer.png" alt="kuryer">{{ExWindow ? ExWindow.translator('panel-errors.kuryer') : '' }}</a> </router-link>-->
                                            <!--<router-link  @click.native="changeClass('cls7')" class-active="active" tag="li" to="/courier"><a><span :class="clsArry.cls7?'img-kuryer-w':'img-kuryer'" alt="kuryer"></span>{{ExWindow ? ExWindow.translator('panel-errors.courier') : '' }}</a> </router-link>-->
                                        </ul>
                                    </div>
                                    <div class="block price web">
                                        <currency></currency>
                                    </div>
                                    <div  class="block daily web">
                                        <daily-currency></daily-currency>
                                    </div>

                                    <!--<div class="block bottom-block">-->
                                        <!--<div class="head-block">-->
                                            <!--<h4><img src="/front/img/balansim.png" alt="balansim">Balansım&lt;!&ndash;{{ExWindow ? ExWindow.translator('panel-errors.balance_panel') : '' }}&ndash;&gt;</h4>-->
                                            <!--<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top"-->
                                                    <!--title="Bu balans Azərbaycanda bağlamanızın çatdırılma haqqını, eyni zamanda kuryer sifarişləriniz zamanı online ödəmə etmək üçün formalaşdırılıb. Siz bu balansdan, həmçinin, &quot;Sifariş et&quot; xidmətindən yararlanarkən alış-veriş zamanı yaranmış borcların qapadılmasında istifadə edə bilərsiniz. İstəyəcəyiniz təqdirdə, alış-verişinizdə yaranmış müsbət fərq də gələcək istifadə üçün balansınıza əlavə ediləcək.">-->
                                                <!--<img src="/front/img/question-icon-kabinet-right.png" alt="kabinet-icon"></button>-->
                                        <!--</div>-->
                                        <!--<div class="block-body">-->
                                            <!--<span>{{User.balance}} AZN</span>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="block bottom-block ayliq">-->
                                        <!--<div class="head-block">-->
                                            <!--<h4><img src="/front/img/ayliq-sifaris-icon.png" alt="ayliq">Son 30 gün</h4>-->
                                            <!--<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top"-->
                                                    <!--title="Bu, Sizin Limak vasitəsilə son 30 gündə ölkəyə gətirdiyiniz bağlamaların məhsul dəyəri-->
                                                <!--və çatdırılma haqqı üçün ödədiyiniz məbləğdir. Qeyd edək ki, qanunvericiliyə görə 30-->
                                                <!--gün ərzində şəxsi istifadə üçün gətirilmiş poçt yüklərinin dəyəri və bunun üçün ödənmiş-->
                                                <!--çatdırılma haqqı toplam 1000 dolları keçdikdə gömrük rüsumu tətbiq edilir.">-->
                                                <!--<img src="/front/img/question-icon-kabinet-right.png" alt="kabinet-icon"></button>-->
                                        <!--</div>-->
                                        <!--<div class="block-body">-->
                                            <!--<span>{{User.last30days}}$</span>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="block bottom-block">-->
                                        <!--<div class="head-block">-->
                                            <!--<h4><img src="/front/img/mezenne.png" alt="mezenne">{{ExWindow ? ExWindow.translator('panel-errors.currency') : '' }}</h4>-->
                                        <!--</div>-->
                                        <!--<div class="block-body-end">-->
                                            <!--<ul>-->
                                                <!--<li><img src="/front/img/flg-tr.png" alt="flg-tr"><span>1</span><span>TL</span></li>-->
                                                <!--<li><img src="/front/img/flg-az.png" alt="flg-az"><span>0.31</span><span>AZN</span></li>-->
                                                <!--<li><img src="/front/img/flg-usa.png" alt="flg-usa"><span>0.17</span><span>USD</span></li>-->
                                                <!--<li><img src="/front/img/flg-ru.png" alt="flg-ru"><span>11.31</span><span>RUB</span></li>-->
                                            <!--</ul>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                </div>
                                <div class="col-xs-11 mobile panel-button-top ">
                                    <router-link class="btn-effect"  active-class="active" to="/order">
                                        {{ExWindow ? ExWindow.translator('panel-errors.order') : '' }}
                                    </router-link>
                                    <router-link class="btn-effect blue"  active-class="active" to="/invoice">
                                        {{ExWindow ? ExWindow.translator('panel-errors.upload_invoice') : '' }}
                                    </router-link>

                                </div>
                                <router-view @changeBalance="changeBalance"></router-view>
                                <!--<div class="mobile-block col-sm-12 col-xs-12">-->
                                    <!--<div class="block bottom-block">-->
                                        <!--<div class="head-block">-->
                                            <!--<h4><img src="/front/img/balansim.png" alt="balansim">{{ExWindow ? ExWindow.translator('panel-errors.balance_panel') : '' }}</h4>-->
                                        <!--</div>-->
                                        <!--<div class="block-body">-->
                                            <!--<span>{{User.balance}} AZN</span>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="block bottom-block">-->
                                        <!--<div class="head-block">-->
                                            <!--<h4><img src="/front/img/mezenne.png" alt="mezenne">{{ExWindow ? ExWindow.translator('panel-errors.currency') : '' }}</h4>-->
                                        <!--</div>-->
                                        <!--<div class="block-body-end">-->
                                            <!--<ul>-->
                                                <!--<li><img src="/front/img/flg-tr.png" alt="flg-tr"><span>1</span><span>TL</span></li>-->
                                                <!--<li><img src="/front/img/flg-az.png" alt="flg-az"><span>0.31</span><span>AZN</span></li>-->
                                                <!--<li><img src="/front/img/flg-usa.png" alt="flg-usa"><span>0.17</span><span>USD</span></li>-->
                                                <!--<li><img src="/front/img/flg-ru.png" alt="flg-ru"><span>11.31</span><span>RUB</span></li>-->
                                            <!--</ul>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <gence-modal v-bind:productData="showProductData" v-bind:show="ProductsModal"
                            @close-modal="closeShowProduct"></gence-modal>
        </section>

    </div>
</template>
<script>
    import currency from './components/shared/currency';
    import dailyCurrency from './components/shared/dailyCurrency';
    import GenceModal from "./components/modals/GenceModal";
    export default {
        mounted() {
            this.checkCookie();
            this.getUserData();
            this.ExWindow = window;
            this.clsArry = {
                "cls0": false,
                "cls1": false,
                "cls2": false,
                "cls3": false,
                "cls4": false,
                "cls5": false,
                "cls6": false,
                "cls7": false,
                "cls8": false
            };
        },
        props: {

        },
        data: function (){
            return {
                User: Object,
                ExWindow: null,
                cls1: false,
                clsArry: {},
                ProductsModal: false,
                showProductData: null,
            }
        },
        methods: {
            async getUserData() {
                const test  = await this.requestUserData();
                this.User = test.data;
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';
                return  axios.get(path);

            },
            changeBalance() {
                this.getUserData();
            },

            changeClass(element) {
                if(this.clsArry[element]){

                }else {
                    for (var key in this.clsArry) {
                        if (this.clsArry.hasOwnProperty(key)) {
                            this.clsArry[key] = false;
                        }
                    }
                    this.clsArry[element] = true;
                }
            },
            closeShowProduct() {
                this.ProductsModal = false;
            },
            setCookie(cname, cvalue, exdays) {
                let d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                let expires = "expires="+ d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            },

            getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for(let i = 0; i <ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            checkCookie() {
                let gence = this.getCookie("usa_info");
                console.log(gence);
                if (gence == "") {
                    this.ProductsModal = true;
                    this.setCookie("usa_info", 1, 365);
                } else {
                    this.ProductsModal = false;
                }
            }

        },
        components: {
            currency,
            dailyCurrency,
            GenceModal
        }
    }
</script>
<style>

</style>

