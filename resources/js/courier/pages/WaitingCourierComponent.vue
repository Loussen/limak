<template>
    <div>
        <products-modal v-bind:show="show" v-bind:data="modalData" @close-modal="closeModal"></products-modal>
        <div class="content-wrapper animatedParent animateOnce">
            <div style="width: 100%;" class="container">
                <router-link style="margin-bottom: 20px;" class="btn btn-primary" to="/">
                    Daxil olan kuryer sifarişləri
                </router-link>
                <section class="paper-card">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box text-left">
                                <div class="box-header">
                                    <h3 class="box-title text-center"><strong>Göndərilmiş kuryerlər</strong></h3>
                                    <br>
                                </div>
                                <div class="box-body no-padding">
                                    <div v-if="data == null" class="loading"></div>
                                    <div v-if="data != null && data.length === 0" class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>
                                    <table class="table dtr-details" width="100%" v-if="data != null && data.length !== 0">
                                        <tr>
                                            <th>Sifarişçinin adı</th>
                                            <th>Sifarişçinin soyadı</th>
                                            <th>Sifarişçinin emaili</th>
                                            <th>Sifarişçinin nömrəsi</th>
                                            <th>Sifarişçinin ünvanı</th>
                                            <th>İnvoyslar</th>
                                            <th>Ətraflı</th>
                                        </tr>
                                        <tr v-for="order of data">
                                            <td>{{order.users.name}}</td>
                                            <td>{{order.users.surname}}</td>
                                            <td>{{order.users.email}}</td>
                                            <td>{{order.phone}}</td>
                                            <td>{{order.city}} {{order.district}} {{order.street}} {{order.village}} {{order.home}}</td>
                                            <td><button v-on:click="productsList(order.invoices)" class="btn btn-primary">Məhsullar</button></td>
                                            <td><button v-on:click="orderDelivered(order.id)" class="btn btn-warning">Sifariş çatdırıldı</button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script>
    import ProductsModal from "../components/ProductsModal";
    import swal from 'sweetalert2';

    export default {
        components: {ProductsModal},
        name: "waiting-courier-component",
        mounted() {
            this.getWaitingOrders();
        },
        data: function () {
            return {
                data: null,
                show: false,
                modalData: null
            }
        },
        methods: {
            getWaitingOrders() {
                axios.get('/admin/courier/orders-answer-waiting')
                    .then(data => {
                        this.data = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            orderDelivered(courierId) {
                swal({
                    title: 'Sifariş çatdı?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Bəli',
                    cancelButtonText: 'Xeyr'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/admin/courier/finish', {courierId: courierId})
                            .then(data => {
                                if (data.data.status === 200) {
                                    this.getWaitingOrders();
                                    this.swalFunc(data.data.message);
                                } else if (data.data.status === 201) {
                                    this.getWaitingOrders();
                                    this.swalFunc(data.data.message);
                                }
                            })
                            .catch(err => {
                                console.log(err);
                            });
                    }
                    if(result.dismiss) {
                        swal.closeModal();
                    }
                });
            },

            productsList(data) {
                this.show = true;
                this.modalData = data
            },

            closeModal() {
                this.show = false;
            },

            swalFunc(message, time = 1500, type = 'success') {
                swal({
                    type: type,
                    title: message,
                    showConfirmButton: true,
                    timer: time
                });
            }
        }
    }
</script>

<style scoped>
    .loading {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 26px;
        height: 26px;
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%);
    }

    .table td, .table th {
        font-size: 15px;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>