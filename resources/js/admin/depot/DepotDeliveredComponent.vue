<template>
    <div class="container-fluid" v-if="this.roles.length >0">
        <Menu :region_id="region_id"></Menu>
        <div class="row mt-5">
            <h4 class="s-24 font-segoe text-center">Axtarış <i class="icon-box5 s-24"></i></h4>
            <div class="col-xs-2 offset-4 mt-5">
                <input v-model="user_id" type="text" class="form-control" placeholder="Müştəri İD-si" >
            </div>
            <div class="col-xs-3 offset-4 mt-5">
                <input v-model="term" v-on:keyup="get(term)" type="search" class="form-control" placeholder="FİN, Seriya №, İstifadəçi - nömrəsi, adı , soyadı..." >
            </div>
            <div class="col-xs-2 offset-4 mt-5">
                <input v-model="date1" type="date" class="form-control" placeholder="Tarix" >
            </div>
            <div class="col-xs-2 offset-4 mt-5">
                <input v-model="date2" type="date" class="form-control" placeholder="Tarix" >
            </div>
            <div class="col-xs-3 offset-4 mt-5">
                <button  class="btn-effect" @click="getByDate()">Axtar</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <table class="table table-hovered table-stripped bg-white">
                    <thead>
                    <tr>
                        <th  v-bind="count_i=(deliveredProducts.current_page-1)*30">№</th>
                        <th>Adı Soyadı</th>
                        <th>Balans</th>
                        <th>Bağlama</th>
                        <th>Məhsul sayı</th>
                        <th>Çəki</th>
                        <th>Ödənilmiş məbləğ (AZN)</th>
                        <th>Anbardakı yeri</th>
                        <th>Tarix</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="!term">
                        <template  v-if="deliveredProducts && deliveredProducts.data.length > 0">
                            <tr v-for="(product, index) of deliveredProducts.data">
                                <td>{{count_i = count_i+1}}</td>
                                <td>{{product.name}} {{product.surname}}<br><strong>{{product.uniqid}}</strong></td>
                                <td>{{product.balance}}</td>
                                <td>
                                    <p>{{ product.product_type_name }} </p>
                                </td>
                                <td>{{product.quantity}}</td>
                                <td> {{product.weight}} </td>
                                <td>
                                    {{product.shipping_price}}$ ({{(product.shipping_price*1.70).toFixed(2)}}AZN)
                                </td>
                                <td>{{ product.depo  }}</td>
                                <td>{{ product.updated_at  }}</td>
                            </tr>
                        </template>
                    </template>
                    <template v-else>
                        <template  v-if="filteredProducts && filteredProducts.length > 0">
                            <tr v-for="(product, index) of filteredProducts">
                                <td>{{index + 1}}</td>
                                <td>{{product.name}} {{product.surname}}<br><strong>{{product.uniqid}}</strong></td>
                                <td>{{product.balance}}</td>
                                <td>
                                    <p>{{ product.product_type_name }}</p>
                                </td>
                                <td>{{product.quantity}}</td>
                                <td> {{product.weight}} </td>
                                <td>
                                    {{product.shipping_price}}$ ({{(product.shipping_price*1.70).toFixed(2)}}AZN)
                                </td>
                                <td>{{ product.depo }}</td>
                                <td>{{ product.updated_at }}</td>
                            </tr>
                        </template>
                    </template>
                    <!--<tr v-if="!list || list.length === 0">-->
                        <!--<td colspan="8">-->
                            <!--<div class="alert alert-warning text-center"><strong>Məlumat yoxdur!</strong></div>-->
                        <!--</td>-->
                    <!--</tr>-->
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination">
                    <li ><a @click.prevent="getDeliveredProducts(deliveredProducts.current_page - 1)" href="#" aria-label="Previous"><span
                            aria-hidden="true">ƏVVƏL</span></a></li>
                    <template  v-for="page in deliveredProducts.last_page">
                        <li v-if="page ==1 && deliveredProducts.current_page==1" class="active">
                            <a @click.prevent="getDeliveredProducts( page)" href="#">{{ page }}</a>
                        </li>
                        <li v-else-if="page ==1">
                            <a @click.prevent="getDeliveredProducts( page)" href="#">{{ page }}</a>
                        </li>
                        <li v-else-if="Math.abs(deliveredProducts.current_page - page)> 3 && Math.abs(deliveredProducts.current_page - page)<= 6 ">
                            <a href="#">.</a>
                        </li>
                        <li v-else-if="Math.abs(deliveredProducts.current_page- page)<= 3 && deliveredProducts.current_page == page" class="active">
                            <a @click.prevent="getDeliveredProducts( page)" href="#">{{ page }}</a>
                        </li>
                        <li v-else-if="Math.abs(deliveredProducts.current_page - page)<= 3 ">
                            <a @click.prevent="getDeliveredProducts(  page)" href="#">{{ page }}</a>
                        </li>
                        <li v-else-if="page == deliveredProducts.last_page">
                            <a @click.prevent="getDeliveredProducts( page)" href="#">{{ page }}</a>
                        </li>
                    </template>
                    <li>
                        <a v-show="deliveredProducts.current_page < deliveredProducts.last_page" @click.prevent="getDeliveredProducts( deliveredProducts.current_page + 1)" href="#" aria-label="Next">
                            <span aria-hidden="true">NÖVBƏTİ</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

    </div>
</template>

<script>

    import Menu from "./DepotMenuComponent"
    import auth from '../auth.js'
    export default {
        name: "DepotDeliveredComponent",
        data: function(){
            return {
                term : '',
                date1: '',
                date2: '',
                deliveredProducts: [],
                filteredProducts: [],
                region_id:1,
                user_id:'',
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
            getDeliveredProducts(page_id=1){
                axios.get('/cp/depot/delivered?page='+page_id,{params:{region_id:this.region_id,user_id:this.user_id}}).then(({data}) => {
                    this.deliveredProducts = data;
                })
            },
            get(term){
                if(term){
                    axios.get('/cp/depot/deliveredAll',{params:{region_id:this.region_id}}).then(({data}) => {
                        this.deliveredProducts = data;
                    })

                    this.filteredProducts = this.deliveredProducts.filter((product) =>{
                        return product.pin == term || product.serial_number == term || product.uniqid.includes(term) || product.name.toLowerCase().trim().includes(term.toLowerCase().trim()) || product.surname.toLowerCase().trim().includes(term.toLowerCase().trim());
                    })
                } else{
                    this.getDeliveredProducts()
                }

            },
            getByDate(){
                axios.get('/cp/depot/delivered?date1='+this.date1+'&date2='+this.date2,{params:{region_id:this.region_id,user_id:this.user_id}}).then(({data}) => {
                    this.deliveredProducts = data;
                })

            },
        },
        mounted() {
            this.getRegion();
            this.getDeliveredProducts()
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