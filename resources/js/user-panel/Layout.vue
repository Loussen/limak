<style>
    .router-link-active {
        background: #f95732;
    }
    .router-link-active a{
        color: #fff !important;
    }
    .nav-tabs .active.show {
        display: inline-block !important;
    }
    .img-foreign {
        background-image: url("/front/img/xaricdeki-unvanlarim.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-foreign-w {
        background-image: url("/front/img/xaricdeki-unvanlar-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-order {
        background-image: url("/front/img/sifarislerim.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-order-w {
        background-image: url("/front/img/sifarislerim-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-track {
        background-image: url("/front/img/sifarisi-izle.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-track-w {
        background-image: url("/front/img/sifarisi-izle-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-messages {
        background-image: url("/front/img/message.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-messages-w {
        background-image: url("/front/img/mesaj-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-invoice {
        background-image: url("/front/img/beyanname.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-invoice-w {
        background-image: url("/front/img/beyanname-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-balance {
        background-image: url("/front/img/balans.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-balance-w {
        background-image: url("/front/img/balans-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-setting {
        background-image: url("/front/img/tenzimlemeler.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-setting-w {
        background-image: url("/front/img/tenzimlemeler-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-kuryer {
        background-image: url("/front/img/kuryer.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
    .img-kuryer-w {
        background-image: url("/front/img/kuryer-w.png");
        background-repeat: no-repeat;
        padding: 0 33px;
        vertical-align: middle;
        background-position: bottom;
        display:inline-block;
        height: 25px;
    }
</style>

<template>
    <div id="app-layout">
        <section class="kabinet">
            <div class="container">
                <div class="block mobile-breadcrumb">
                    <img src="/front/img/xaricdeki-unvanlarim.png" alt="xaricdeki-unvanlarimtop">
                    <span>{{ExWindow ? ExWindow.translator('panel-errors.foreign_addresses') : ''}}</span>
                </div>
                <div class="page-head">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h1>{{User.name}} {{ User.surname }}</h1>
                            <p>{{ExWindow ? ExWindow.translator('panel-errors.user_code'): ''}}: <b>{{User.uniqid}}</b></p>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <a href="/order/link/insert" class="btn-effect">
                               {{ExWindow ? ExWindow.translator('panel-errors.order') : ''}} <img src="/front/img/plus.png" alt="plus">
                            </a>
                            <a :href=" ExWindow ? '/' + ExWindow.default_locale + '/invoice' : ''" class="btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.add_invoice') : ''}}</a>
                        </div>
                    </div>
                </div>
                <div class="unvanlar">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <div class="block">
                                <ul class="left-sidebar">
                                    <router-link @click.native="changeClass('cls0')" class-active="active" tag="li" to="/foreign-address">
                                        <a><span :class="clsArry.cls0? 'img-foreign-w':'img-foreign'"
                                                alt="xaricdeki-unvanlarim"></span>{{ExWindow ? ExWindow.translator('panel-errors.foreign_addresses') : ''}}</a></router-link>
                                    <router-link @click.native="changeClass('cls1')" class-active="active"  tag="li" to="/orders"><a><span :class="clsArry.cls1? 'img-order-w':'img-order'" alt="sifarish"></span>{{ExWindow ? ExWindow.translator('panel-errors.orders') : '' }}</a> </router-link>
                                     <router-link @click.native="changeClass('cls2')" class-active="active" tag="li" to="/track"><a><span :class="clsArry.cls2? 'img-track-w':'img-track'" alt="sifarishi-izle"></span>{{ExWindow ? ExWindow.translator('panel-errors.track_order') : '' }}</a> </router-link>
                                    <router-link  @click.native="changeClass('cls3')" class-active="active" tag="li" to="/messenger"><a><span :class="clsArry.cls3?'img-messages-w':'img-messages'" alt="mesajlar"></span>{{ExWindow ? ExWindow.translator('panel-errors.messages') : '' }}</a> </router-link>
                                    <router-link  @click.native="changeClass('cls4')" class-active="active" tag="li" to="/invoices"><a><span :class="clsArry.cls4?'img-invoice-w':'img-invoice'"alt="beyenname"></span>{{ExWindow ? ExWindow.translator('panel-errors.invoices') : '' }}</a> </router-link>
                                    <router-link  @click.native="changeClass('cls8')" class-active="active" tag="li" to="/basket"><a><span :class="clsArry.cls8?'img-invoice-w':'img-invoice'"alt="sebet"></span>{{ExWindow ? ExWindow.translator('panel-errors.basket') : '' }}</a> </router-link>
                                    <router-link  @click.native="changeClass('cls5')" class-active="active" tag="li" to="/balance"><a><span :class="clsArry.cls5?'img-balance-w':'img-balance'" alt="catdirilma"></span>{{ExWindow ? ExWindow.translator('panel-errors.balance') : '' }}</a> </router-link>
                                     <!--<router-link class-active="active" tag="li" to="/foreign-addr"><a><img src="/front/img/kuryer.png" alt="kuryer">{{ExWindow ? ExWindow.translator('panel-errors.kuryer') : '' }}</a> </router-link>-->
                                    <router-link  @click.native="changeClass('cls6')" class-active="active" tag="li" to="/settings"><a><span :class="clsArry.cls6?'img-setting-w':'img-setting'" alt="tenzimlemeler"></span>{{ExWindow ? ExWindow.translator('panel-errors.settings') : '' }}</a> </router-link>
                                    <router-link  @click.native="changeClass('cls7')" class-active="active" tag="li" to="/courier"><a><span :class="clsArry.cls7?'img-kuryer-w':'img-kuryer'" alt="kuryer"></span>{{ExWindow ? ExWindow.translator('panel-errors.courier') : '' }}</a> </router-link>
                                </ul>
                            </div>
                            <div class="block bottom-block">
                                <div class="head-block">
                                    <h4><img src="/front/img/balansim.png" alt="balansim">Balansım<!--{{ExWindow ? ExWindow.translator('panel-errors.balance_panel') : '' }}--></h4>
                                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top"
                                            title="Bu balans Azərbaycanda bağlamanızın çatdırılma haqqını, eyni zamanda kuryer sifarişləriniz zamanı online ödəmə etmək üçün formalaşdırılıb. Siz bu balansdan, həmçinin, &quot;Sifariş et&quot; xidmətindən yararlanarkən alış-veriş zamanı yaranmış borcların qapadılmasında istifadə edə bilərsiniz. İstəyəcəyiniz təqdirdə, alış-verişinizdə yaranmış müsbət fərq də gələcək istifadə üçün balansınıza əlavə ediləcək.">
                                        <img src="/front/img/question-icon-kabinet-right.png" alt="kabinet-icon"></button>
                                </div>
                                <div class="block-body">
                                    <span>{{User.balance}} AZN</span>
                                </div>
                            </div>
                            <div class="block bottom-block ayliq">
                                <div class="head-block">
                                    <h4><img src="/front/img/ayliq-sifaris-icon.png" alt="ayliq">Son 30 gün</h4>
                                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top"
                                            title="Bu, Sizin Limak vasitəsilə son 30 gündə ölkəyə gətirdiyiniz bağlamaların məhsul dəyəri
                                                və çatdırılma haqqı üçün ödədiyiniz məbləğdir. Qeyd edək ki, qanunvericiliyə görə 30
                                                gün ərzində şəxsi istifadə üçün gətirilmiş poçt yüklərinin dəyəri və bunun üçün ödənmiş
                                                çatdırılma haqqı toplam 1000 dolları keçdikdə gömrük rüsumu tətbiq edilir.">
                                        <img src="/front/img/question-icon-kabinet-right.png" alt="kabinet-icon"></button>
                                </div>
                                <div class="block-body">
                                    <span>{{User.last30days}}$</span>
                                </div>
                            </div>
                            <div class="block bottom-block">
                                <div class="head-block">
                                    <h4><img src="/front/img/mezenne.png" alt="mezenne">{{ExWindow ? ExWindow.translator('panel-errors.currency') : '' }}</h4>
                                </div>
                                <div class="block-body-end">
                                    <ul>
                                        <li><img src="/front/img/flg-tr.png" alt="flg-tr"><span>1</span><span>TL</span></li>
                                        <li><img src="/front/img/flg-az.png" alt="flg-az"><span>0.31</span><span>AZN</span></li>
                                        <li><img src="/front/img/flg-usa.png" alt="flg-usa"><span>0.17</span><span>USD</span></li>
                                        <li><img src="/front/img/flg-ru.png" alt="flg-ru"><span>11.31</span><span>RUB</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                            <router-view @changeBalance="changeBalance"></router-view>
                        <div class="mobile-block col-sm-12 col-xs-12">
                            <div class="block bottom-block">
                                <div class="head-block">
                                    <h4><img src="/front/img/balansim.png" alt="balansim">{{ExWindow ? ExWindow.translator('panel-errors.balance_panel') : '' }}</h4>
                                </div>
                                <div class="block-body">
                                    <span>{{User.balance}} AZN</span>
                                </div>
                            </div>
                            <div class="block bottom-block">
                                <div class="head-block">
                                    <h4><img src="/front/img/mezenne.png" alt="mezenne">{{ExWindow ? ExWindow.translator('panel-errors.currency') : '' }}</h4>
                                </div>
                                <div class="block-body-end">
                                    <ul>
                                        <li><img src="/front/img/flg-tr.png" alt="flg-tr"><span>1</span><span>TL</span></li>
                                        <li><img src="/front/img/flg-az.png" alt="flg-az"><span>0.31</span><span>AZN</span></li>
                                        <li><img src="/front/img/flg-usa.png" alt="flg-usa"><span>0.17</span><span>USD</span></li>
                                        <li><img src="/front/img/flg-ru.png" alt="flg-ru"><span>11.31</span><span>RUB</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>
<script>

    export default {
        mounted() {
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
                clsArry: {}
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
            }
        },
        components: {

        }
    }
</script>
