<template>
    <div class="container-fluid" v-if="this.roles.length >0">
        <Menu :region_id="region_id"></Menu>
        <div class="row mt-5">
            <div class="col-4 offset-4 mt-5">
                <h4 class="s-24 font-segoe text-center">Axtarış <i class="icon-box5 s-24"></i></h4>
                <input v-model="term" @keyup.enter="get(term)" type="search" class="form-control" placeholder="FİN, Seriya №, İstifadəçi - nömrəsi, adı , soyadı..." >
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
                        <th>Anbar</th>
                        <th>Bağlama</th>
                        <th>Ödənilmiş məbləğ (AZN)</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="InDepot45Days && InDepot45Days.length > 0">
                        <tr v-for="(product, index) of InDepot45Days">
                            <td>{{index + 1}}</td>
                            <td>{{product.name}} {{product.surname}}</td>
                            <td>{{product.pin}}</td>
                            <td>{{product.serial_number}}</td>
                            <td><!--{{product.balance}} - --> <span class="days-ago">{{ 45-product.daysAgo || 0 }}</span></td>
                            <td>{{product.depo}}</td>
                            <td>
                                <strong>{{product.uniqid}}</strong>
                                <p>{{ product.product_type_name }}</p>
                            </td>
                            <td>
                                {{product.shipping_price}}$ ({{(product.shipping_price*1.70).toFixed(2)}}AZN)
                            </td>
                            <!--<td>-->
                            <!--<button class="btn btn-success" @click="give(product)">Təhvil ver</button>-->
                            <!--</td>-->
                        </tr>
                    </template>
                    <!--<tr v-if="!list || list.length === 0">-->
                    <!--<td colspan="8">-->
                    <!--<div class="alert alert-warning text-center"><strong>Məlumat yoxdur!</strong></div>-->
                    <!--</td>-->
                    <!--</tr>-->
                    </tbody>
                </table>
            </div>
            <!--<div class="col-12 text-center">-->
            <!--<nav aria-label="Page navigation example" v-if="pagination">-->
            <!--<ul class="pagination">-->
            <!--<li class="page-item" v-if="pagination.prev_page_url">-->
            <!--<router-link v-bind:to="pagination.prev_page_url">-->
            <!--<a class="page-link" href="#" aria-label="Previous">-->
            <!--<span aria-hidden="true">&laquo;</span>-->
            <!--</a>-->
            <!--</router-link>-->
            <!--</li>-->
            <!--<li class="page-item" v-if="pagination.next_page_url">-->
            <!--<router-link v-bind:to="pagination.next_page_url">-->
            <!--<a class="page-link"  aria-label="Next">-->
            <!--<span aria-hidden="true">&raquo;</span>-->
            <!--</a>-->
            <!--</router-link>-->
            <!--</li>-->
            <!--</ul>-->
            <!--</nav>-->
            <!--</div>-->
        </div>
        <!--<div class="row" style="display: none">-->
        <!--<template v-if="product">-->
        <!--<div class="col-12" style="background: white" id="check">-->
        <!--<h4 style="text-align: center;font-family: Segoe UI, Helvetica;font-weight: bold; margin-top: 15px;">Təhfil verildi</h4>-->
        <!--<table style="margin-bottom: 15px">-->
        <!--<tr>-->
        <!--<td style="width: 250px">Ad , Soyad:</td>-->
        <!--<td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.name : '' : ''}} {{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.surname : '' : ''}}</td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--<td style="width: 250px">Ş/V FİN:</td>-->
        <!--<td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.pin : '' : ''}}</td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--<td style="width: 250px">Ş/V Seriya nömrəsi:</td>-->
        <!--<td>{{product.rel_user_products ? product.rel_user_products.users ? product.rel_user_products.users.serial_number : '' : ''}}</td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--<td style="width: 250px">Bağlama:</td>-->
        <!--<td>-->
        <!--<strong>5c1d4c0ddfb4f</strong>-->
        <!--<p>{{product.products ? product.products.product_type_name : ''}}</p>-->
        <!--</td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--<td style="width: 250px">Ödənilmiş məbləğ (AZN):</td>-->
        <!--<td>-->
        <!--{{product.delivery_price}}-->
        <!--</td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--<td style="width: 250px">Tarix:</td>-->
        <!--<td>-->
        <!--{{new Date()}}-->
        <!--</td>-->
        <!--</tr>-->
        <!--</table>-->
        <!--</div>-->
        <!--</template>-->
        <!--</div>-->
    </div>
</template>

<script>
    import Menu from "./DepotMenuComponent"
    import auth from '../auth.js'
    export default {
        name: "Depot45DaysComponent",
        data: function(){
            return {
                term : '',
                InDepot45Days: [],
                region_id:1,
            }
        },
        methods: {
            getRegion()
            {
                this.region_id = this.$route.params.id;
                axios.get('/cp/admin/getRegion',{params:{region_id:this.region_id}}).then(({data}) => {
                    this.region_id = data.region_id;
                    if(this.region_id==0){
                        this.$router.push('/');
                    }
                });
            },
            get45DaysProducts(){
                axios.get('/cp/depot/45days',{params:{region_id:this.region_id}}).then(({data}) => {
                    this.InDepot45Days = data;
                    console.log(data)
                })
            },
            get(term){
                if(term){
                    this.InDepot45Days = this.InDepot45Days.filter((product) =>{
                        return product.pin == term || product.serial_number == term || product.uniqid == term || product.name == term ||product.sur == term ;
                    })
                } else{
                    this.get45DaysProducts()
                }

            }
        },
        mounted() {
            this.getRegion();
            this.get45DaysProducts()
        },
        components :{
            Menu
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','storage_home'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>

</style>