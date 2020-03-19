<template>
    <div>
        <accept-modal v-bind:data="modalData" v-bind:show="show" @close-modal="closeModal"></accept-modal>
        <div class="content-wrapper animatedParent animateOnce">
            <div style="width: 100%;" class="container">
                <router-link style="margin-bottom: 20px;" class="btn btn-primary" to="/my-orders">
                    Aktual sifarişlərim
                </router-link>
                <section class="paper-card">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box text-left">
                                <div class="box-header">
                                    <h3 class="box-title text-center"><strong>Gözləmədə olan sifarişlər</strong></h3>
                                    <hr>
                                </div>
                                <div class="box-body no-padding">
                                    <div v-if="list == null" class="loading"></div>
                                    <div class="row" v-if="list != null && list.length > 0" v-bind:class="{ 'justify-center': list.length >= 4 }">
                                        <div v-for="item of list" class="card-container">
                                            <div class="card box-shadow" v-bind:class="{'border-danger': item.delivery_type === 'express'}">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{item.users.name}} {{item.users.surname}}</h5>
                                                    <h6 class="card-price">
                                                        <span class="amount">{{item.price}}</span>
                                                        <span class="manat">&#8380;</span>
                                                    </h6>
                                                    <div class="card-content">
                                                        <dl>
                                                            <template v-for="product in item.products">
                                                                <dt>{{product.products_type? product.products_type.name : product.product_type_name}}</dt>
                                                                <dd>{{product.shop_name}}</dd>
                                                            </template>
                                                        </dl>
                                                    </div>
                                                    <div class="card-buttons">
                                                        <button v-on:click="showOrderMore(item.id)" class="btn btn-outline-primary show-more">
                                                            <span class="icon-eye"></span>
                                                        </button>

                                                        <button v-on:click="acceptOrder(item.id)" class="btn btn-outline-success accept-order">
                                                            <span v-if="!loading" class="icon-check"></span>
                                                            <span v-if="loading" class="icon-spinner"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="empty-content" v-if="list != null && list.length === 0">
                                        <p class="text-center" style="font-size: 18px;font-weight: bold;">Sifariş yoxdur</p>
                                    </div>
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
    import AcceptModal from "./components/AcceptModal";
    import io from 'socket.io-client';
    import swal from 'sweetalert2';
    export default {
        components: {AcceptModal},
        mounted() {
            this.getOrders();
            this.getAdminId();
        },
        data: function () {
            return {
                list: null,
                adminId: null,
                loading: false,
                show: false,
                modalData: null
            }
        },
        created() {
            let socket = io(`https://limak.az/app`);
            // let socket = io(`http://localhost:3333`);

            socket.on(`private-test:App\\Events\\OrderAccepted`, data => {
                if (data.data) {
                    this.getOrders();
                }
            });
        },
        methods: {
            getOrders() {
                axios.get('/admin/orders')
                    .then((response) => {
                        this.list = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            getAdminId() {
                axios.get('/admin/get-admin-data')
                    .then((response) => {
                        this.adminId = response.data.id;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            acceptOrder(id) {
                this.loading = true;
                axios.post('/admin/accept-order', {
                    adminId: this.adminId,
                    relUserProductId: id
                }).then((response) => {
                    if (response.data.code === 200) {
                        this.loading = false;
                        swal({
                            title: 'Sifarişi qəbul etdiniz!',
                            text: "",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'İcra et',
                            cancelButtonText: 'Davam et'
                        }).then((result) => {
                            if (result.value) {
                                this.$router.push('my-order/' + id);
                            }

                            if(result.dismiss) {
                                swal.closeModal();
                            }
                        });
                    } else if (response.data.code === 500) {
                        alert(response.message);
                    }
                    else if (response.data.code === 203) {
                        this.loading = false;
                        swal({
                            type: 'error',
                            title: response.data.message,
                            showConfirmButton: true,
                            timer: 5500
                        });
                    }

                }).catch((error) => {
                    console.log(error);
                });
            },

            showOrderMore(id) {

                this.show = true;
                axios.get('/admin/orders/detail/' + id)
                    .then((response) => {
                        this.modalData = response.data.data
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },

            closeModal() {
                this.show = false;
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
        -webkit-animation: spin-data-v-d1d35872 1s linear infinite;
        animation: spin-data-v-d1d35872 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%);
    }

    .box-shadow {
        -webkit-box-shadow: 2px 3px 9px rgba(0,0,0,0.4);
        -moz-box-shadow: 2px 3px 9px rgba(0,0,0,0.4);
        box-shadow: 2px 3px 9px rgba(0,0,0,0.4);
    }

    .card-title {
        font-size: 18px;
        color: #000;
    }

    .card-price {
        font-size: 20px;
        position: absolute;
        top: 16px;
        right: 20px;
        font-weight: 500;
    }

    .card-content {
        padding: 1.25rem 0 0;
    }

    .justify-center {
        justify-content: center;
    }

    .card-body {
        width: 360px;
    }

    .card-container {
        padding: 15px;
    }

    dl {
        height: 88px;
        overflow-y: auto;
    }

    dt, dd {
        display: inline-block;
    }

    dt {
        width: 30%;
    }

    dd {
        width: 59%;
    }

    dl::-webkit-scrollbar {
        width: 4px;
    }

    /* Track */
    dl::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    dl::-webkit-scrollbar-thumb {
        background: #516bfb;
    }

    /* Handle on hover */
    dl::-webkit-scrollbar-thumb:hover {
        background: #3f51b5;
    }

    .accept-order:hover {
        border-color: #7dc855;
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
