<template>
    <div class="container custom" v-if="this.roles.length >0">
        <div class="row">
            <div class="top-menu">
                <h4>Hesabat</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li class="active">Hesabat</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Tip</th>
                        <th>Say</th>
                        <th>Çəki</th>
                        <th>Qiymət</th>
                    </tr>
                    <tr>
                        <td>Türkiyə anbarı</td>
                        <td>{{data.tr.count}}</td>
                        <td>{{parseFloat(data.tr.weight).toFixed(3)}}</td>
                        <td>{{parseFloat(data.tr.price).toFixed(2)}}</td>
                    </tr>
                    <tr>
                        <td>Yolda</td>
                        <td v-if="data.way.count!=null">{{data.way.count}}</td>
                        <td v-else>0</td>
                        <td v-if="data.way.weight!=null">{{parseFloat(data.way.weight).toFixed(3)}}</td>
                        <td v-else>0</td>
                        <td v-if="data.way.price!=null">{{parseFloat(data.way.price).toFixed(2)}}</td>
                        <td v-else>0</td>
                    </tr>
                    <tr>
                        <td>Gömrük</td>
                        <td v-if="data.custom.count!=null">{{data.custom.count}}</td>
                        <td v-else>0</td>
                        <td v-if="data.custom.weight!=null">{{parseFloat(data.custom.weight).toFixed(3)}}</td>
                        <td v-else>0</td>
                        <td v-if="data.custom.price!=null">{{parseFloat(data.custom.price).toFixed(2)}}</td>
                        <td v-else>0</td>
                    </tr>
                    <tr>
                        <td>Bakı anbarı</td>
                        <td>{{data.az.count}}</td>
                        <td>{{parseFloat(data.az.weight).toFixed(3)}}</td>
                        <td>{{parseFloat(data.az.price).toFixed(2)}}</td>
                    </tr>
                    <tr>
                        <td>Ay üzrə</td>
                        <td>{{data.month.count}}</td>
                        <td>{{parseFloat(data.month.weight).toFixed(3)}}</td>
                        <td>{{parseFloat(data.month.price).toFixed(2)}}</td>
                    </tr>
                </table>


            </div>
            <div class="col-md-6">
                <h4>
                    <p>
                        {{data.million.date1}} tarixi ilə {{data.million.date2}} tarixi arasında Million ödənişləri
                    </p>
                    <p>Million - AZN balansi {{data.million.azn}} AZN</p>
                    <p>Million - TRY balansi {{data.million.try}} AZN</p>
                    <p>Million - Cemi {{parseFloat(data.million.try) + parseFloat(data.million.azn)}} AZN</p>
                </h4>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js'
    export default {
        name: "CustomComponent",
        data: function(){
            return {
                data : '',
            }
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','custom'))
            }
        },
        methods: {
            getPrices(){
                axios.get('/cp/statistic/getShippingPrices').then(({data}) => {
                    this.data = data.data;
                    console.log(this.data);
                })
            },
        },
        mounted() {
            this.getPrices()
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .btn-default{
        width: 200px;
    }
    .custom-blocks{
        padding-top: 50px;
    }
</style>