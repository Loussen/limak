<template>
    <div class="col-md-9 col-sm-11 col-xs-11 order-body address">
        <div class="row">
            <div class="col-xs-12">
                <div class="block">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#turkey" aria-controls="home" role="tab" data-toggle="tab" class="order-img1">{{lang ? lang('common.turkey') : ''}}</a>
                        </li>
                        <li role="presentation">
                            <a href="#usa" aria-controls="home" role="tab" data-toggle="tab" class="order-img2">{{lang ? lang('common.usa') : ''}}</a>
                        </li>
                        <!--<li role="presentation">
                            <a href="#2" aria-controls="profile" role="tab" data-toggle="tab" class="order-img2">Amerika`dan</a>
                        </li>
                        <li role="presentation">
                            <a href="#3" aria-controls="messages" role="tab" data-toggle="tab" class="order-img3">Rusiya`dan</a>
                        </li>
                        <li role="presentation">
                            <a href="#4" aria-controls="settings" role="tab" data-toggle="tab" class="order-img4">Almaniya`dan</a>
                        </li>
                        <li class="last" role="presentation">
                            <a href="#5" aria-controls="settings" role="tab" data-toggle="tab" class="order-img5">Dubay`dan</a>
                        </li>-->
                    </ul>
                    <div class="tab-content" v-if="user">
                        <div role="tabpanel" class="tab-pane fade in active" id="turkey">
                            <ul>
                                <li>
                                    <b>{{lang ? lang('common.name_surname') : ''}}</b>
                                    <p>LİMAK İTHALAT VE İHRACAT LİMİTED şirketi</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.address_title') : ''}}</b>
                                    <p>LİMAK</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.address_line1') : ''}} (Tam olaraq kopyalayın)</b>
                                    <p>Halkalı Merkez Mahellesi1. Tuna caddesi. Üzümlü SK. 5/7 kod: {{user.uniqid}} {{user.name}} {{user.surname}}</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.district_city') : ''}}</b>
                                    <p>Istanbul</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.ilche') : ''}}</b>
                                    <p>Küçükçekmece</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.tax_center') : ''}}</b>
                                    <p>Halkalı</p>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <b>{{lang ? lang('link-order.district') : ''}}</b>
                                    <p>Halkalı</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.zip') : ''}}</b>
                                    <p>34303</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.country') : ''}}</b>
                                    <p>Türkiye</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.tr_passport') : ''}}</b>
                                    <p>35650276048</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.mobile') : ''}}</b>
                                    <p>5323575535</p>
                                </li>
                                <li>
                                    <b>{{lang ? lang('link-order.tax_num') : ''}}</b>
                                    <p>6081089593</p>
                                </li>
                            </ul>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="usa">
                            <ul>
                                <li>
                                    <b>Name</b>
                                    <p>AZ{{user.uniqid}} {{user.name}} {{user.surname}} </p>
                                </li>
                                <li>
                                    <b>Address Line</b>
                                    <p>1200 Interchange Blvd</p>
                                </li>
                                <li>
                                    <b>Address Line2</b>
                                    <p> Suite#AZ{{user.uniqid}} </p>
                                </li>
                                <li>
                                    <b>State</b>
                                    <p>DE</p>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <b>City</b>
                                    <p>Newark</p>
                                </li>
                                <li>
                                    <b>ZIP/Postal code</b>
                                    <p>19711</p>
                                </li>
                                <li>
                                    <b>Country</b>
                                    <p>United States</p>
                                </li>
                                <li>
                                    <b>Phone Number</b>
                                    <p>800-4315119</p>
                                </li>
                            </ul>
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
                user: null,
                lang: null
            }
        },
        methods: {
            async getUserData() {
                const test  = await this.requestUserData();
                this.user = test.data;
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
