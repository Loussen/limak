<template>
    <section class="sign-in invoice content" v-if="User.is_premium==0">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-7">
                        <h4>Premium <b>İSTİFADƏÇİ</b></h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-5">
                        <ol class="breadcrumb web">
                            <li><a href="/">Ana səhifə </a></li>
                            <li class="active">Premium</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="/">... </a></li>
                            <li class="active">Premium</li>
                        </ol>
                    </div>
                </div>
                <div class="invoice-body services col-xs-12">
                    <div class="block col-xs-12">
                        <div class="col-sm-4 col-xs-12">
                            <h2>Təklif etdiyimiz xidmətlər</h2>
                            <ul>
                                <li>1 aylıq Premium istifadə</li>
                                <li>12  aylıq  Premium istifadə</li>
                                <li>Kuryer xidməti(Bakı şəhər daxili)</li>
                                <li>Gerigöndərişdə endirim</li>
                                <li>Türkiyədən çatdırlma (1 kq üçün)</li>
                                <li>Amerikadan çatdırlma (1 kq üçün)</li>
                                <li>Təcili sifariş</li>
                                <li>Broker xidməti</li>
                            </ul>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="service-block">
                                <div class="head-block">
                                    <h4>Standart <br> <b>istifadəçi </b></h4>
                                </div>
                                <div class="service-inner">
                                    <ul>
                                        <li><i class="fa fa-close"></i></li>
                                        <li><i class="fa fa-close"></i></li>
                                        <li>4 AZN</li>
                                        <li>Yox</li>
                                        <li>$4.5</li>
                                        <li>$6.5</li>
                                        <li><i class="fa fa-close"></i></li>
                                        <li>30 AZN</li>
                                    </ul>
                                    <button type="button" class="btn-effect border-btn">Davam et</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="service-block">
                                <div class="head-block">
                                    <h4>PREMIUM <br> <b>istifadəçi </b></h4>
                                </div>
                                <div class="service-inner">
                                    <ul>
                                        <li>$9.00</li>
                                        <li>$100.00</li>
                                        <li>2 AZN</li>
                                        <li>20% endirim</li>
                                        <li>$3.5</li>
                                        <li>$5.5</li>
                                        <li><i class="fa fa-check"></i></li>
                                        <li>20 AZN</li>
                                    </ul>

                                </div>
                                <div class="service-inner">
                                    <form method="post">
                                        <label data-v-170866b4="" class="select-class"> Müddət *</label>
                                        <v-select placeholder="Ay"
                                                  v-model="month" :options="month_array"
                                                  label="name"></v-select>
                                        <button type="button" class="btn-effect border-btn" @click="submit()">Premium et</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sign-in invoice content" v-else-if="User.is_premium==1">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-7">
                        <h4>Premium <b>İSTİFADƏÇİ</b></h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-5">
                        <ol class="breadcrumb web">
                            <li><a href="index.html">Ana səhifə </a></li>
                            <li class="active">Premium</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="index.html">... </a></li>
                            <li class="active">Premium</li>
                        </ol>
                    </div>
                </div>
                <div class="invoice-body services col-xs-12">
                    <div class="block col-xs-12">
                        Siz Premiumsuz
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import vSelect from 'vue-select';
    import swal from "sweetalert2";
    export default {
        name: "Premium",
        mounted(){
            this.ExWindow = window;
            this.getUserData();
            this.month_array = [
                {id: 1, name: '1 ay - 9$'},
                {id: 12,name:'12 ay - 100$'}
            ];
        },
        data: function (){
            return {
                User: Object,
                ExWindow: null,
                month:"1",
                month_array: []

            }
        },
        methods: {
            async getUserData() {
                const test  = await this.requestUserData();
                this.User = test.data;
                this.$emit('User', this.User);
                this.month = this.month_array[0];
                if(this.User.is_premium==1){
                    location.href = '/site/user-panel#/premium-panel';
                }
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';
                return  axios.get(path);

            },
            submit() {

                swal({
                        title: this.ExWindow ? this.ExWindow.translator('panel-errors.sending') : '',
                        onOpen: () => {
                            swal.showLoading();
                            swal.fire({
                                title: this.ExWindow ? this.ExWindow.translator('panel-errors.payment') : '',
                                text: this.ExWindow ? this.ExWindow.translator('panel-errors.payment_from_azn') : '',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: this.ExWindow ? this.ExWindow.translator('panel-errors.yes') : '',
                                cancelButtonText: this.ExWindow ? this.ExWindow.translator('panel-errors.no') : ''
                            }).then((result) => {
                                if (result.value) {
                                    axios({
                                        url: '/site/premium', method: 'POST',
                                        data: {
                                            month: this.month.id,
                                        }
                                    }).then(response => {
                                        console.log(response);
                                        if (response.data.status == 200) {
                                            swal.fire(
                                                'Hazirdi!',
                                                ''+response.data.message+'',
                                                'success'
                                            )

                                            location.href = '/site/user-panel#/premium-panel';

                                        } else {
                                            swal.fire(
                                                this.ExWindow ? this.ExWindow.translator('panel.attention') : '',
                                                ''+response.data.message+'',
                                                'error'
                                            )
                                        }

                                    });
                                }

                            })
                        }
                });
            }


        },
        components: {
            vSelect,
            modal: Modal,
        }
    }
</script>

<style scoped>

</style>