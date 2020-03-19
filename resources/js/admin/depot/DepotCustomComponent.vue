<template>
    <div class="container-fluid" v-if="this.roles.length >0">
        <Menu :region_id="region_id"></Menu>
        <br />
        <div class="row mt-5">
            <h2 class="s-24 font-segoe text-center">Axtarış <i class="icon-box5 s-24"></i></h2>
            <div class="col-xs-3 offset-4 mt-5">
                <input v-model="term" v-on:keyup="get(term)" type="search" class="form-control" placeholder="FİN, Seriya №, İstifadəçi - nömrəsi, adı , soyadı..." >
            </div>
            <div class="col-xs-3 offset-4 mt-5">
                <input v-model="date1" type="date" class="form-control" placeholder="Tarix" >
            </div>
            <div class="col-xs-3 offset-4 mt-5">
                <input v-model="date2" type="date" class="form-control" placeholder="Tarix" >
            </div>
            <div class="col-xs-3 offset-4 mt-5">
                <button  class="btn-effect" @click="getByDate()">Axtar</button>
            </div>
        </div>
        <br />
        <hr />
        <br />
        <div class="row mt-5">
            <div class="col-xs-3 offset-4 mt-5">
                <input v-model="invoice_id" type="text" class="form-control" placeholder="Invoys id (Purchase No)" >
            </div>
            <div class="col-xs-3 offset-4 mt-5">
                <button  class="btn btn-info" @click="addCustomProduct()">Əlavə et</button>
                <span>{{invoice_response}}</span>
            </div>
        </div>
        <br />
        <br />
        <div class="row">
            <div class="col-12 mt-3">
                <table class="table table-hovered table-stripped bg-white">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Invoys ID</th>
                        <th>Adı Soyadı</th>
                        <th>Balans</th>
                        <th>Bağlama</th>
                        <th>Məhsul sayı</th>
                        <th>Çəki</th>
                        <th>Çatdırılma qiyməti (AZN)</th>
                        <th>Tarix</th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="!term">
                        <template  v-if="customProducts && customProducts.length > 0">
                            <tr v-for="(product, index) of customProducts">
                                <td>{{index + 1}}</td>
                                <td>{{product.invoice_id}}</td>
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
                                <td>{{ product.updated_at  }}</td>
                                <td><button class="btn btn-warning" @click="deleteCustomProduct(product.invoice_id)">X</button></td>
                            </tr>
                        </template>
                    </template>
                    <template v-else>
                        <template  v-if="filteredProducts && filteredProducts.length > 0">
                            <tr v-for="(product, index) of filteredProducts">
                                <td>{{index + 1}}</td>
                                <td>{{product.invoice_id}}</td
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
                                <td>{{ product.updated_at }}</td>
                                <td><button class="btn btn-warning" @click="deleteCustomProduct(product.invoice_id)">X</button></td>
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
        </div>

    </div>
</template>

<script>

    import Menu from "./DepotMenuComponent"
    import auth from '../auth.js'
    export default {
        name: "DepotCustomComponent",
        data: function(){
            return {
                term : '',
                date1: '',
                date2: '',
                filteredProducts: [],
                customProducts: [],
                invoice_id: '',
                invoice_response: '',
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
            addCustomProduct(){
                axios.post('/cp/depot/addCustomProduct',{invoice_id: this.invoice_id,region_id:this.region_id}).then(({data}) => {
                    console.log(data);
                    this.invoice_response = data.result;
                    this.invoice_id = '';
                })
                    .catch(err => {
                    console.log(err);
                });
                this.getCustomProducts();
            },

            deleteCustomProduct(invoice_id){
                axios.post('/cp/depot/deleteCustomProduct',{invoice_id: invoice_id,region_id:this.region_id}).then(({data}) => {
                    console.log(data);
                    this.invoice_response = data.result;
                })
                    .catch(err => {
                        console.log(err);
                    });
                this.getCustomProducts();
            },

            getCustomProducts(){
                axios.get('/cp/depot/custom',{params:{region_id:this.region_id}}).then(({data}) => {
                    this.customProducts = data;
                })
            },

            get(term){
                if(term){
                    this.filteredProducts = this.customProducts.filter((product) =>{
                        return product.pin == term || product.serial_number == term || product.uniqid.includes(term) || product.name.toLowerCase().trim().includes(term.toLowerCase().trim()) || product.surname.toLowerCase().trim().includes(term.toLowerCase().trim());
                    })
                } else{
                    this.getCustomProducts()
                }

            },
            getByDate(){
                axios.get('/cp/depot/custom?date1='+this.date1+'&date2='+this.date2).then(({data}) => {
                    this.customProducts = data;
                })

            },
        },
        mounted() {
            this.getRegion();
            this.getCustomProducts()
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