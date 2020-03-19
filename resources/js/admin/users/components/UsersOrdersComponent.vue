<template>
    <div class="row">

        <div role="tabpanel" class="tab-pane fade in active" id="gelen">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-responsive table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Link</th>
                        <th>Mağaza</th>
                        <th>Ölçü</th>
                        <th>Tip</th>
                        <th>Rəng</th>
                        <th>Admin</th>
                        <th>Kargo</th>
                        <th>Qiymət</th>
                        <th>Sifariş №</th>
                        <th>Sifariş tarixi</th>
                        <th>Problem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="product in products">
                        <td><a target="_blank" :href="product.link">{{product.link}}</a></td>
                        <td>{{product.shop_name}}</td>
                        <td>{{product.description}}</td>
                        <td>{{product.product_type_name}}</td>
                        <td>{{product.color}}</td>
                        <th>{{product.admin_name}}</th>
                        <th>{{product.cargo_price}}</th>
                        <td>{{product.price}}</td>
                        <td>{{product.id+1000000}}</td>
                        <td>{{product.created_at}}</td>
                        <td>
                            <span v-if="product.is_problem==1">Var</span>
                            <span v-else>Yoxdur</span><br />
                            {{product.problem_text}}
                        </td>
                    </tr>
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getData( dataParams.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in dataParams.last_page">
                            <li v-if="page ==1 && dataParams.current_page==1" class="active">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)> 3 && Math.abs(dataParams.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)<= 3 && dataParams.current_page == page" class="active">
                                <a @click.prevent="getData(  page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)<= 3 ">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == dataParams.last_page">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="dataParams.current_page < dataParams.last_page" @click.prevent="getData(  dataParams.current_page + 1)" href="#" aria-label="Next">
                                <span aria-hidden="true">NÖVBƏTİ</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    export default {
        name: "UsersOrdersComponent",
        data(){
            return {
                products: '',
                current_type: '',
                dataParams: '',
            };
        },
        props: ['selected_type','user_id'],

        methods:{
            getData(page_id=1){
                axios.get('/cp/users/'+this.selected_type+'?page='+page_id,{params:{user_id:this.user_id}})
                        .then((response) => {
                        this.products = response.data.data.orders.data;
                        this.dataParams = response.data.data.orders;
                        console.log(this.dataParams);
                        this.current_type = this.selected_type;
                    })
            },

        },
        mounted(){
            this.getData();
            setInterval(() => {
                if(this.current_type != this.selected_type)
                    this.getData();
            }, 300);
        },
    }
</script>

<style scoped>

</style>