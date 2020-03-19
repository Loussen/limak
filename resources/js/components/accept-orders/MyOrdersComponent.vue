<template>
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <router-link style="margin-bottom: 20px;" class="btn btn-primary" to="/">
                Gözləmədə olan sifarişlər
            </router-link>
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box text-left">
                            <div class="box-header">
                                <h3 class="box-title text-center">
                                    <strong>Aktual Sifarişlər</strong>
                                </h3>
                            </div>
                        </div>
                        <div v-if="data == null" class="loading"></div>
                        <div v-if="data != null && data.length === 0" class="no-data">Məlumat yoxdur</div>
                        <table class="table dtr-details" width="100%" v-if="data != null && data.length !== 0">
                            <tr>
                                <th>Sifarişçinin adı</th>
                                <th>Sifarişçinin soyadı</th>
                                <th>Sifarişçinin emaili</th>
                                <th>Sifarişçinin əlaqə nömrələri</th>
                                <th>Sifarişçin ümumi məbləği</th>
                                <th>Ətraflı</th>
                            </tr>
                            <tr v-for="transaction of data">
                                <td>{{transaction.users.name}}</td>
                                <td>{{transaction.users.surname}}</td>
                                <td>{{transaction.users.email}}</td>
                                <td>
                                    <div v-for="userContact of transaction.users.user_contacts">
                                        <i class="icon-phone" ></i>
                                        <span> {{userContact.name}}</span>
                                    </div>
                                </td>
                                <td>{{(transaction.price / tl).toFixed(2)}} TL</td>
                                <td>
                                    <router-link v-bind:to="'/my-order/' + transaction.id">
                                        <a class="btn btn-primary">
                                            <i class="icon-eye"></i>
                                        </a>
                                    </router-link>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        name: "my-orders-component",
        data() {
            return {
                data: null,
                tl: 0
            }
        },
        mounted() {
            this.getOrders();
        },
        methods: {
            getOrders() {
                axios.get('/admin/my-orders/list')
                .then((data) => {
                    this.data = data.data.data;
                    this.tl   = data.data.tl;
                })
                .catch(err => {
                    console.log(err);
                })
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

    button span {
        font-size: 13px;
    }

    button {
        padding: .295rem .46rem;
    }

    .user-data {
        margin: 25px 0;
    }

    .label {
        position: relative;
        top: 4px;
        padding: .326rem 0.75rem;
    }

    .no-data {
        text-align: center;
        margin-top: 20px;
        font-weight: 600;
        font-size: 18px;
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