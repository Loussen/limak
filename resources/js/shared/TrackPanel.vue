<template>
    <div>
        <div class="block col-md-12 col-sm-12 col-xs-12" v-if="invoiceData">
            <div v-on:click="togglePanel()" class="block-inner col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-9 col-sm-9 col-xs-7">
                    <h2>{{invoiceData.products.product_type_name === null ? invoiceData.products.products_type.name : invoiceData.products.product_type_name}}</h2>
                    <p>{{invoiceData.products.shop_name? invoiceData.products.shop_name:''}}<br><span>Sifariş tarixi:</span><b> {{invoiceData.products.created_at}}</b></p>
                </div>
                <div class="location-side col-md-3 col-sm-3 col-xs-5">
                    <button type="button" class="btn click-loc"><img src="/front/img/anbar-loc.png"
                                                                     alt="anbar-loc"></button>
                    <p>{{invoiceData.invoice_status.name}}</p>
                    <span>{{invoiceData.updated_at}}</span>
                </div>
            </div>
            <div v-if="isOpen" class="block-inner-after col-md-12 col-sm-12 col-xs-12">
                <div class="block-img">
                    <img v-bind:class="{ status_icons_green: ordered, 'status_icons_gray': !ordered }" src="/front/img/sifaris-verildi-new.png" alt="sifaris-verildi">
                    <p>{{ExWindow ? ExWindow.translator('panel-errors.ordered'): ''}}</p>
                    <!--<span>19.10.2018</span>-->
                </div>
                <div class="block-img">
                    <img v-bind:class="{ status_icons_green: foreign, 'status_icons_gray': !foreign }" src="/front/img/xaricdeki-anbar-new.png" alt="xaricdeki-anbar">
                    <p>{{ExWindow ? ExWindow.translator('panel-errors.in_foreign'): ''}} </p>
                    <!--<span>19.10.2018</span>-->
                </div>
                <div class="block-img">
                    <img v-bind:class="{ status_icons_green: home, 'status_icons_gray': !home }" src="/front/img/kuryer-catdirma-new.png" alt="kuryer-catdirma">
                    <p>{{ExWindow ? ExWindow.translator('panel-errors.at_home'): ''}}</p>
                    <!--<span>19.10.2018</span>-->
                </div>
                <div class="block-img">
                    <img v-bind:class="{ status_icons_green: invoiceData.courier, 'status_icons_gray': !invoiceData.courier }" src="/front/img/baki-anbari-new.png" alt="baki-anbari">
                    <p>{{ExWindow ? ExWindow.translator('panel-errors.courierDelivery'): ''}} </p>
                    <!--<span>19.10.2018</span>-->
                </div>
                <div class="block-img">
                    <button type="button" class="btn click-loc-after"><img v-bind:class="{ status_icons_green: completed, 'status_icons_gray': !completed }" src="/front/img/tehvil-verildi-new.png"
                                                                           alt="tehvil-verildi"></button>
                    <p>{{ExWindow ? ExWindow.translator('panel-errors.completed'): ''}}</p>
                    <!--<span>19.10.2018</span>-->
                </div>
                <table v-if="home || foreign" class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                        <th>{{ExWindow ? ExWindow.translator('panel-errors.mass'): ''}}</th>
                        <th>{{ExWindow ? ExWindow.translator('panel-errors.volume'): ''}}</th>
                        <th>{{ExWindow ? ExWindow.translator('panel-errors.curyer_price'): ''}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{invoiceData.products.created_at}}</td>
                        <td>{{invoiceData.weight}} kq</td>
                        <td>{{invoiceData.width}}sm - {{invoiceData.length}}sm - {{invoiceData.height}}sm</td>
                        <td>{{invoiceData.delivery_price}} USD ({{invoiceData.delivery_price *1.7}} AZN)</td>
                        <td v-if="!invoiceData.is_paid">
                            <router-link v-if="balance <  parseFloat(kuryerCash) + parseFloat(invoiceData.delivery_price)" :to="{ name: 'Balance', query: { order: invoiceData.id } }"><button style="width:100%" class="btn btn-success"> {{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}} </button></router-link>
                            <button  v-if="balance >=  parseFloat(kuryerCash) + parseFloat(invoiceData.delivery_price)" @click="openModal()" style="width:100%" class="btn btn-success"> {{ExWindow ? ExWindow.translator('panel-errors.pay'): ''}} </button>
                        </td>
                        <td v-if="invoiceData.is_paid">
                            <span class="invoice-paid"> {{ExWindow ? ExWindow.translator('panel-errors.paid'): ''}} </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <modal :size="'modal-md'" :toggle="modalToggle" :modal="'PaymentModal'" @modal="modalToggle = $event">
                    <template slot="header">
                        <h3 style="margin: 0; display: inline-block;">{{ExWindow ? ExWindow.translator('panel-errors.payment_panel'): ''}}</h3>
                    </template>
                    <template slot="body">
                        <div class="row">
                            <div v-if="kuryerCash > 0" class="col-md-12">
                                <h3>{{ExWindow ? ExWindow.translator('panel-errors.really_pay'): ''}}</h3>
                                <h6 style="color: red">{{ExWindow ? ExWindow.translator('panel-errors.payment_notice'): ''}}</h6>
                                <div class="form-check">
                                    <input @change="check($event)" v-model="withCourier" class="form-check-input" type="checkbox" id="defaultCheck1">
                                    <label style="font-size: 18px;margin-left: 5px;color: #36ad3a;" class="form-check-label" for="defaultCheck1">
                                        Bəli + <span style="color:rgb(54, 173, 102)" >{{kuryerCash}}</span> AZN
                                    </label>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <h3 style="margin-top: 0px;">{{ExWindow ? ExWindow.translator('panel-errors.delivery_payment'): ''}} - <span style="color: #f95732;" >{{invoiceData.delivery_price*1.70}}</span> AZN</h3>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <h3 style="margin-top: 0px;" v-if="withCourier" >{{ExWindow ? ExWindow.translator('panel-errors.total'): ''}} - <span style="color: #f95732;font-weight: 700;">{{ parseFloat(kuryerCash) + parseFloat(invoiceData.delivery_price*1.70)}}</span> AZN</h3>
                                <h3 style="margin-top: 0px;" v-if="!withCourier" >{{ExWindow ? ExWindow.translator('panel-errors.total'): ''}} - <span style="color: #f95732;font-weight: 700;" >{{parseFloat(invoiceData.delivery_price*1.70)}}</span> AZN</h3>
                            </div>
                        </div>
                    </template>
                    <template style="background:#f2f6ff" slot="footer">
                        <button class="btn btn-effect" @click="deni()">{{ExWindow ? ExWindow.translator('panel-errors.reject'): ''}}</button>
                        <button class="btn btn-effect" @click="accept()">{{ExWindow ? ExWindow.translator('panel-errors.accept'): ''}}</button>
                    </template>
                </modal>
            </div>
        </div>
    </div>

</template>

<script>
    $('.block-inner').unbind('click');
    $('.block-inner-after').unbind('click');
    $('.block-inner-after').css('display');
    import swal from 'sweetalert2';
    import Modal from '../shared/Modal'

    export default {
        mounted() {
            this.checkStatus(this.invoiceData.invoice_status.label);
            this.ExWindow = window;
        },
        watch: {
            modalToggleProp: function(val) {
                this.modalToggle = val;
                console.log(val);
                console.log(this.modalToggle);
            }
        },
        props: [
            'invoiceData',
            'balance',
            'kuryerCash',
            'modalToggleProp'
        ],
        data: function (){
            return {
                isOpen: false,
                ordered: false,
                foreign: false,
                home: false,
                // delivery: false,
                completed: false,
                withCourier: false,
                ExWindow: null,
                modalToggle: false
            }
        },
        methods: {
            check(event) {
                console.log(this.withCourier)
            },
            openModal() {
                setTimeout(() => {
                    this.modalToggle = true;
                    this.$emit('changeModalStatus', true);
                },100);

            },
            accept() {
                this.$emit('payEvent', {withCourier: this.withCourier, invoiceId: this.invoiceData.id});
                // this.modalToggle = false;
            },
            deni() {
                setTimeout(() => {
                    this.modalToggle = false;
                }, 100);
            },
            togglePanel() {
                this.isOpen = !this.isOpen;
            },
            closePanel() {
                this.isOpen = false;
                console.log('closed');
            },
            checkStatus (label) {
                switch (label) {
                    case 'waiting':
                        this.ordered = true;
                        break;
                    case 'foreign':
                        this.ordered = true;
                        this.foreign = true;
                        break;
                    case 'on_the_way':
                        this.ordered = true;
                        this.foreign = true;
                        break;
                    case 'home':
                        this.ordered = true;
                        this.foreign = true;
                        this.home = true;
                        break;
                    case 'completed':
                        this.ordered = true;
                        this.foreign = true;
                        this.home = true;
                        this.completed = true;
                        break;
                }
            }

        },
        components: {
            modal: Modal
        }
    }

</script>
<style scoped>
    .kabinet .nav-tabs li {
        width: 83px;
    }
    .sifarishi-izle .block-inner-after {
        display: initial;
    }
    .status_icons_green {
        background:#22b140 !important;
    }

    .status_icons_gray {
        background:#afb5bc !important;
    }
    @media  (max-width: 680px) {
        .block .block-inner h2 {
            font-size: 16px !important;
        }
        .invoice-paid {
            padding: 6px 5px !important;
        }
    }
    footer {
        background: #dafcff !important;
    }
    .invoice-paid {
        width:100%;background: #f95732;color:#fff;
        padding: 10px 30px;
    }
</style>
