<template>
    <div>
        <div class="right-side col-md-9 col-sm-12 col-xs-12">
            <div class="block tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#turkey" aria-controls="turkey"
                                                              role="tab" data-toggle="tab">{{lang ? lang('common.turkey') : ''}}</a></li>
                    <!--<li role="presentation"><a href="#azerbaycan" aria-controls="azerbaycan" role="tab"
                                               data-toggle="tab">Azrbaycan</a></li>
                    <li role="presentation"><a href="#rusiya" aria-controls="rusiya" role="tab"
                                               data-toggle="tab">Rusiya</a></li>
                    <li role="presentation"><a href="#usa" aria-controls="usa" role="tab"
                                               data-toggle="tab">USA</a></li>-->
                </ul>
            </div>
            <div class="tab-content" v-if="User">
                <div role="tabpanel" class="tab-pane fade in active" id="turkey">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>{{lang ? lang('link-order.normalDeliveryAddress') : ''}}</p>
                                    <span>LİMAK İTHALAT VE İHRACAT LİMİTED şirketi</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres başlığı</p>
                                    <span>LİMAK</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres satır 1</p>
                                    <span>Halkalı Merkez Mahellesi1. Tuna caddesi. Üzümlü SK. 5/7 kod: {{User.uniqid}} {{User.name}} {{User.surname}}</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İl-Şehir</p>
                                    <span>İstanbul</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İlçe</p>
                                    <span>Küçükçekmece</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Vergi dairesi</p>
                                    <span>Halkalı</span>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-1 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>Semt</p>
                                    <span>Halkalı</span>
                                </div>
                                <div class="unvan-text">
                                    <p>ZIP/Post kodu</p>
                                    <span>34303</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Ülke</p>
                                    <span>Türkiye</span>
                                </div>
                                <div class="unvan-text">
                                    <p>TC kimlik</p>
                                    <span>35650276048</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Cep telefon</p>
                                    <span>5323575535</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Vergi numarası</p>
                                    <span>6081089593</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="azerbaycan">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>Ad Soyad</p>
                                    <span>LİMAK İNTERNET HİZMETLERİ</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres başlığı</p>
                                    <span>LİMAK</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres satır 1</p>
                                    <span>Halkalı Merkez Mahellesi1. Üzümlü SK. 5/7 kod: {{User.uniqid}} {{User.name}} {{User.surname}}</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İl-Şehir</p>
                                    <span>İstanbul</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İlçe</p>
                                    <span>Küçükçekmece</span>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-1 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>Semt</p>
                                    <span>Halkalı</span>
                                </div>
                                <div class="unvan-text">
                                    <p>ZIP/Post kodu</p>
                                    <span>34303</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Ülke</p>
                                    <span>Türkiye</span>
                                </div>
                                <div class="unvan-text">
                                    <p>TC kimlik</p>
                                    <span>35650276048</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Cep telefon</p>
                                    <span>5323575535</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Vergi numarası</p>
                                    <span>6081089593</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="rusiya">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>Ad Soyad</p>
                                    <span>LİMAK İNTERNET HİZMETLERİ</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres başlığı</p>
                                    <span>LİMAK</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres satır 1</p>
                                    <span>Halkalı Merkez Mahellesi1. Üzümlü SK. 5/7 kod: {{User.uniqid}} {{User.name}} {{User.surname}}</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İl-Şehir</p>
                                    <span>İstanbul</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İlçe</p>
                                    <span>Küçükçekmece</span>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-1 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>Semt</p>
                                    <span>Halkalı</span>
                                </div>
                                <div class="unvan-text">
                                    <p>ZIP/Post kodu</p>
                                    <span>34303</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Ülke</p>
                                    <span>Türkiye</span>
                                </div>
                                <div class="unvan-text">
                                    <p>TC kimlik</p>
                                    <span>35650276048</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Cep telefon</p>
                                    <span>5323575535</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Vergi numarası</p>
                                    <span>6081089593</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="usa">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>Ad Soyad</p>
                                    <span>LİMAK İNTERNET HİZMETLERİ</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres başlığı</p>
                                    <span>LİMAK</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Adres satır 1</p>
                                    <span>Halkalı Merkez Mahellesi1. Üzümlü SK. 5/7 kod: {{User.uniqid}} {{User.name}} {{User.surname}}</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İl-Şehir</p>
                                    <span>İstanbul</span>
                                </div>
                                <div class="unvan-text">
                                    <p>İlçe</p>
                                    <span>Küçükçekmece</span>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-1 col-sm-6 col-xs-12">
                                <div class="unvan-text">
                                    <p>Semt</p>
                                    <span>Halkalı</span>
                                </div>
                                <div class="unvan-text">
                                    <p>ZIP/Post kodu</p>
                                    <span>34303</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Ülke</p>
                                    <span>Türkiye</span>
                                </div>
                                <div class="unvan-text">
                                    <p>TC kimlik</p>
                                    <span>35650276048</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Cep telefon</p>
                                    <span>5323575535</span>
                                </div>
                                <div class="unvan-text">
                                    <p>Vergi numarası</p>
                                    <span>6081089593</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import swal from 'sweetalert2';
    export default {
        mounted() {
            this.getUserData();
            this.lang = window.translator;
        },
        props: {

        },
        data: function (){
            return {
                messages: [],
                message: null,
                User: null,
                lang: null
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

            }
        },
        components: {
        }
    }

</script>
