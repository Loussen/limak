<template>
    <div class="container-fluid">
        <Menu></Menu>
        <div class="row mt-5">
            <div class="col-4 offset-4 mt-5">
                <h4 class="s-24 font-segoe text-center">Axtarış <i class="icon-box5 s-24"></i></h4>
                <input type="search" class="form-control" placeholder="FİN, Seriya №, İstifadəçi - nömrəsi, adı , soyadı..." v-model="term" @keyup.enter="get(term)">
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <table class="table table-hovered table-stripped bg-white">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Adı Soyadı</th>
                        <th>Ş/V FİN</th>
                        <th>Ş/V Seriya nömrəsi</th>
                        <th>Balans</th>
                        <th>Bağlama</th>
                        <th>Ödənilmiş məbləğ (AZN)</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="list && list.length > 0">
                        <tr v-for="(product, index) of list">
                            <td>{{index + 1}}</td>
                            <td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.name : '' : ''}} {{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.surname : '' : ''}}</td>
                            <td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.pin : '' : ''}}</td>
                            <td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.serial_number : '' : ''}}</td>
                            <td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.balance : '' : ''}}</td>

                            <td>
                                <strong>{{product.rel_user_products.users.uniqid}}</strong>
                                <p>{{product.products ? product.products.product_type_name : ''}}</p>
                            </td>
                            <td>
                                {{product.delivery_price}}$ ({{(product.delivery_price*1.70).toFixed(2)}}AZN)
                            </td>
                            <td>
                                <button class="btn btn-success" @click="give(product)">Təhvil ver</button>
                            </td>
                        </tr>
                    </template>
                    <tr v-if="!list || list.length === 0">
                        <td colspan="8">
                            <div class="alert alert-warning text-center"><strong>Məlumat yoxdur!</strong></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 text-center">
                <nav aria-label="Page navigation example" v-if="pagination">
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.prev_page_url">
                            <router-link v-bind:to="pagination.prev_page_url">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </router-link>
                        </li>
                        <li class="page-item" v-if="pagination.next_page_url">
                            <router-link v-bind:to="pagination.next_page_url">
                                <a class="page-link"  aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </router-link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row" style="display: none">
            <template v-if="product">
                <div class="col-12" style="background: white" id="check">
                    <h4 style="text-align: center;font-family: Segoe UI, Helvetica;font-weight: bold; margin-top: 15px;">Təhfil verildi</h4>
                    <table style="margin-bottom: 15px">
                        <tr>
                            <td style="width: 250px">Ad , Soyad:</td>
                            <td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.name : '' : ''}} {{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.surname : '' : ''}}</td>
                        </tr>
                        <tr>
                            <td style="width: 250px">Ş/V FİN:</td>
                            <td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.pin : '' : ''}}</td>
                        </tr>
                        <tr>
                            <td style="width: 250px">Ş/V Seriya nömrəsi:</td>
                            <td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.serial_number : '' : ''}}</td>
                        </tr>
                        <tr>
                            <td style="width: 250px">Bağlama:</td>
                            <td>
                                <strong>5c1d4c0ddfb4f</strong>
                                <p>{{product.products ? product.products.product_type_name : ''}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 250px">Ödənilmiş məbləğ (AZN):</td>
                            <td>
                                {{product.delivery_price}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 250px">Tarix:</td>
                            <td>
                                {{new Date()}}
                            </td>
                        </tr>
                    </table>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert2';
    import {api} from "../../apis";
    import {PrintElem} from "../../pipes/slug";
    import Menu from "./DepotMenuComponent"

    export default {
        mounted() {
            this.initialize();
        },
        data: function (){
            return {
                list: [],
                pagination: null,
                product: null,
                term: null,
                roles: []
            }
        },
        methods: {
            initialize() {
                this.get();
                axios.get(api.invoiceDepotRole).then(data => {
                    this.roles = data.data;
                })
            },
            get(term = '', page = 1) {
                this.term = term;
                const pageNumber = page ? page : this.pagination ? this.pagination.current_page : 1;
                axios.get(api.invoiceCashierList + '?term=' + term + '&page=' + pageNumber).then(data => {
                    if (data) {
                        this.pagination = data.data;
                        this.list = data.data.data;
                        console.log(this.list);
                    }
                })
            },
            give(product) {
                this.product = product;
                swal({
                    title: 'Yüklənir...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.invoiceDepotFinish, method: 'POST', data: {id: product.id}}).then(response => {
                            console.log(response);
                            swal.close();
                            setTimeout(() => {
                                PrintElem('check');
                            }, 200);
                            setTimeout(() => {
                                this.get(this.term);
                            }, 350)
                        });
                    }
                })
            }
        },
        components: {
            Menu
        }
    }

</script>
<style>
    .font-segoe{
        font-family: Segoe UI, Helvetica;
    }
</style>
