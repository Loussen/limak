<template>
    <div>
        <div class="sifarishlerim right-side col-md-9 col-sm-12 col-xs-12">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="hamisi">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <Spinner v-if="spinner" name="circle" color="#ef865f"/>
                        <div v-if="!spinner"  class="table-block">

                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.note'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.status'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.delete'): ''}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                <template v-for="commonData in allOrders" >
                                    <tr>
                                        <td v-if="commonData.extras!=null"><a target="_blank" :href="commonData.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                        <td v-else>Yoxdur</td>
                                        <td>{{commonData.description}}</td>
                                       <td>{{commonData.created_at}}</td>
                                        <td>Səbət</td>
                                        <td>{{commonData.price}} TL</td>
                                        <td><a @click="deleteProduct(commonData.id)"><img src="/front/img/Delete.png" alt="edit"></a></td>
                                  </tr>
                                    <!--<tr v-for="product in commonData.products" >
                                        <td><a target="_blank" :href="product.extras.link">{{ExWindow ? ExWindow.translator('panel-errors.link'): ''}}</a></td>
                                        <td>{{product.description}}</td>
                                        <td>{{product.created_at}}</td>
                                        <td>&lt;!&ndash;{{commonData.is_ordered == 1 && product.statuses.id != 6? 'Sifariş verildi' : product.statuses.name}}&ndash;&gt;</td>
                                        <td>{{product.price}} TL</td>
                                        <td><a href="#"><img src="/front/img/Edit.png" alt="edit"></a></td>
                                    </tr>-->
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <!--<div class="pay">-->
                            <!--<a href="#" class="btn-effect">Toplam ödə</a>-->
                            <!--<span>Ödəniş olunmayan linklər 30 dəqiqə sonra silinir.</span>-->
                        <!--</div>-->
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import swal from 'sweetalert2';
    import Spinner from 'vue-spinkit'
    export default {
        mounted() {
            this.getAllOrders();
            // this.getCompletedOrders();
            this.ExWindow = window;
        },
        props: {

        },
        data: function (){
            return {
                allOrders: [],
                allWaitingOrders: [],
                allOrderedOrders: [],
                allCompletedOrders: [],
                allProductRejectOrders: [],
                allTransactionRejectOrders: [],
                allOrdersCount : 0,
                allWaitingOrdersCount: 0,
                allOrderedOrdersCount: 0,
                allCompletedOrdersCount: 0,
                allProductRejectOrdersCount: 0,
                allTransactionRejectOrdersCount: 0,
                ExWindow: null,
                spinner: false
            }
        },
        methods: {
            async getAllOrders() {

                this.spinner = true;
                const test  = await this.getOrders('not-paid');
                this.allOrders = test.data;
                console.log(test);
                this.spinner = false
            },

            getOrders(type) {
                var path = '/user-panel/get-orders/' +type;
                return  axios.get(path);

            },

            deleteProduct(id) {
                var path = '/user-panel/delete-basket/';
                axios({url: path, method: 'POST', data: {id: id}}).then(data => {
                    console.log(data);
                    this.getAllOrders();
                })
            }
        },
        components: {
            Spinner: Spinner
        }
    }

</script>
<style scoped>
    @media (max-width: 680px) {
        ul.nav-tabs li {
            width:50% !important;
        }
    }
    .sk-fade-in.sk-spinner.sk-circle {
        display: inline-block !important;
        left: 50%;
        margin-left: -50px;
    }
    .nav-tabs li {
        width: initial !important;
    }
</style>
