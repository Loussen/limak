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
                        <th>Alış nömrəsi</th>
                        <th>Ödənilmiş məbləğ (AZN)</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="InDepot15Days && InDepot15Days.length > 0">
                        <tr v-for="(product, index) of InDepot15Days">
                            <td>{{index + 1}}</td>
                            <td>{{product.name}} {{product.surname}} {{ product.uniqid }}</td>
                            <td>{{product.pin}}</td>
                            <td>{{product.serial_number}}</td>
                            <td><!--{{product.balance}} - --> <span class="days-ago">{{ 15-product.daysAgo || 0 }}</span></td>
                            <td>{{product.depo}}</td>

                            <td>
                                <strong>{{product.purchase_no}}</strong>
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
        name: "Depot15DaysComponent",
        data: function(){
            return {
                term : '',
                InDepot15Days: [],
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
            penalForStayingİnDepot: function(daysAgo){
                return daysAgo < 45 ? daysAgo - 15 : 30
            },
            get15DaysProducts(){
                axios.get('/cp/depot/15days',{params:{region_id:this.region_id}}).then(({data}) => {
                    this.InDepot15Days = data;
                    console.log(data)
                })
            },
            get(term){
                if(term){
                    this.InDepot15Days = this.InDepot15Days.filter((product) =>{
                        return product.pin == term || product.serial_number == term || product.uniqid == term || product.name == term ||product.sur == term ;
                    })
                } else{
                    this.get15DaysProducts()
                }

            }
        },
        mounted() {
            this.getRegion();
            this.get15DaysProducts();
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
.days-ago{
    color: red
}
</style>