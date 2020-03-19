<template>
    <div>
        <div class="sifarishi-izle right-side col-md-9 col-sm-12 col-xs-12">
            <div v-if="spinner" class="block col-md-12 col-sm-12 col-xs-12">
                <div  class="block-inner col-md-12 col-sm-12 col-xs-12">
                    <Spinner  name="circle" color="#ef865f"/>
                </div>
            </div>

            <track-panel @changeModalStatus="closeModal = $event" v-if="!spinner" v-for="(data, index) in Invoices" :invoiceData="data" :balance="balance" :modalToggleProp="closeModal" :kuryerCash="data.courierPrice" :key="index"  @payEvent="payEvent"></track-panel>
        </div>

        <div v-if="Invoices.length === 0 && !spinner"  class="sifarishi-izle right-side col-md-9 col-sm-12 col-xs-12">
            <div class="block col-md-12 col-sm-12 col-xs-12">
                <div class="no-data block-inner col-md-12 col-sm-12 col-xs-12">
                    <h2 v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'az'">
                        Məlumat yoxdur
                    </h2>
                    <h2 v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'ru'">
                        Нет информации
                    </h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import Spinner from 'vue-spinkit'
    import TrackPanel from "../../shared/TrackPanel";
    import swal from 'sweetalert2';
    export default {
        mounted() {
            this.getAllInvoices();
            this.$emit('changeBalance', {data: 100});
            this.ExWindow = window;
        },
        props: {

        },
        data: function (){
            return {
                Invoices: [],
                balance: 0,
                closeModal: false,
                spinner: false,
                ExWindow: null
            }
        },
        methods: {
            async getAllInvoices() {
                this.spinner = true;
               const test  = await this.getInvoices();
                this.Invoices = test.data.data;
                this.Invoices.map((loop) => {
                    if(loop.courier) {
                        return loop.courierPrice = loop.courier.delivery_type == 1? 4: 10;
                    } else {
                        return loop.courierPrice = 0;
                    }
                });
                this.balance = parseFloat(test.data.balance).toFixed(2);
                this.spinner = false;

            },
            getInvoices() {
                var path = '/user-panel/track';
                return  axios.get(path);

            },
            payEvent(event) {
                console.log(event);
                var path = '/user-panel/pay';
                axios.post(path, event).then((response) => {
                    if(response.data.code === 1602) {
                        swal('Artıq ödənilib!')
                    }
                    this.$emit('changeBalance', {});
                    setTimeout(() => {
                        console.log('before');
                        console.log(this.closeModal);

                        this.closeModal = false;
                        console.log('after');
                        console.log(this.closeModal);
                    },2000);
                    setTimeout(() => {
                        this.getAllInvoices();
                    },4000);
                }).catch((e) => {
                    console.log(e);
                });
            }
        },
        components: {
            TrackPanel,
            Spinner: Spinner
        }
    }

</script>
<style scoped>
    .kabinet .nav-tabs li {
        width: 83px;
    }
    .no-data {
        text-align: center !important;
        color: #ccc !important;
    }
    .no-data h2 {
        margin:0 !important;
    }
    .sk-fade-in.sk-spinner.sk-circle {
        display: inline-block !important;
        left: 50%;
        margin-left: -50px;
    }

</style>
