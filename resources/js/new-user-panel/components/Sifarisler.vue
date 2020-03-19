<template>
    <div class="col-md-9 col-sm-11 col-xs-11 basket">
        <div class="row">
            <div class="col-xs-12">
                <div class="block">
                    <div class="block-head">
                        <h3>Sifarişlərim</h3>
                    </div>
                    <div class="select-all">
                        <div class="right-side mobile-ex">
                            <button type="button" class="transparent">
                                <img src="/front/new/img/excel.png" alt="excel">
                                <span>{{ExWindow ? ExWindow.translator('panel-errors.save'): ''}}</span>
                            </button>
                        </div>
<!--
                        <button type="button" class="btn-effect pull-right" @click="openBasketModel()">{{ExWindow ? ExWindow.translator('panel-errors.add_basket'): ''}}</button>
-->
                        <hr>
                    </div>


                    <div class="block-border" v-for="commonData in allOrders">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <b v-if="commonData.admin_id!=null && commonData.is_problem==0" class="btn btn-success">İcradadır</b>
                                <b v-else-if="(commonData.admin_id!=null) && (commonData.is_problem==1)" class="btn btn-danger">Problem</b>
                                <b v-else class="btn btn-warning">Gözləmədə</b>
                                <br />
                                <p v-if="((commonData.admin_id!=null && commonData.is_problem==1) || commonData.admin_id==null)">
                                    <button type="button" data-target="#edit_data" data-toggle="modal" @click="showEditModal(commonData)" style="border:1px solid #ccc;border-radius: 10px;padding: 10px;">
                                        <i class="fa fa-pencil"></i> Düzəliş et</button>
                                </p>

                            </div>
                            <div class="center-block col-md-9 col-sm-9 col-xs-12">
                                <ul>
                                    <li class="all-width">
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</b></p>
                                        <a v-if="commonData.extras!=null" target="_blank"
                                           :href="commonData.extras.link">{{commonData.extras.link}}</a>
                                        <a v-else></a>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.basket_quantity'): ''}}</b></p>
                                        <span>{{commonData.quantity}}</span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</b></p>
                                        <span>{{commonData.quantity*commonData.price}} TL</span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.orderDate'): ''}}</b></p>
                                        <span>{{commonData.created_at}}</span>
                                    </li>
                                    <li>
                                        <p><b>{{ExWindow ? ExWindow.translator('panel-errors.volume'): ''}}</b></p>
                                        <span v-if="commonData.description!=null">{{commonData.description}}</span>
                                    </li>
                                    <li v-if="commonData.admin_id!=null && commonData.is_problem==1">
                                        <p><b>Problem</b></p>
                                        <span v-if="commonData.description!=null">{{commonData.problem_text}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!--<div class="sum">
                        <span>{{ExWindow ? ExWindow.translator('panel-errors.all_sum'): ''}}</span>
                        <p>{{allPrices.toFixed(2)}} TL</p>
                        <p>{{(allPrices*tryToAzn).toFixed(2)}} AZN</p>
                    </div>-->

                    <div id="edit_data" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">
                                            <img src="/front/new/img/close-menu.png" alt="close">
                                        </span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="input-group border-radius">
                                        <span>{{ExWindow ? ExWindow.translator('panel-errors.price'): ''}}</span>
                                        {{basket.price}}
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="link" class="form-control inputText"
                                                   v-model="basket.link" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.orderLink'): ''}}</span>
                                        </label>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="count" class="form-control inputText"
                                                   v-model="basket.quantity" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.basket_quantity'): ''}}</span>
                                        </label>
                                    </div>

                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="size" class="form-control inputText"
                                                   v-model="basket.description" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.volume'): ''}}</span>
                                        </label>
                                    </div>
                                    <div class="modal-button text-right">
                                        <button data-dismiss="modal" @click="sendEditedBasket()" class="btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.save2'): ''}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <modal :size="'modal-lg'" :toggle="modalTogglePayment" :modal="'payment'">
                        <template slot="header">
                            <h3>{{ExWindow ? ExWindow.translator('panel-errors.payment_panel'): ''}}</h3>
                        </template>
                        <template slot="body">
                            <div id="iframe_payment" ></div>
                        </template>
                    </modal>

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
            this.getCurrency();
            // this.getCompletedOrders();
            this.ExWindow = window;
        },
        props: {},
        data: function () {
            return {
                allOrders: [],
                checkedBoxes:[],
                allPrices:0,
                percent: 1.05,
                select_all: false,

                allWaitingOrders: [],
                allOrderedOrders: [],
                allCompletedOrders: [],
                allProductRejectOrders: [],
                allTransactionRejectOrders: [],
                allOrdersCount: 0,
                allWaitingOrdersCount: 0,
                allOrderedOrdersCount: 0,
                allCompletedOrdersCount: 0,
                allProductRejectOrdersCount: 0,
                allTransactionRejectOrdersCount: 0,
                modalTogglePayment: false,
                ExWindow: null,
                spinner: false,
                basket: {
                    link: '',
                    price: '',
                    quantity: '',
                    size: ''
                },
                tryToAzn:''
            }
        },
        methods: {
            /*payPrice(){
                
                if(this.allPrices>0){

                    swal({
                        title: this.ExWindow ? this.ExWindow.translator('panel-errors.sending'): '',
                        onOpen: () => {
                            swal.showLoading();
                            axios.post('/user-panel/pay-basket-price',{price:this.allPrices.toFixed(2), products:this.checkedBoxes })
                                .then(response=>{
                                    swal.close();
                                    $('#iframe_payment').html(response.data);
                                    this.modalTogglePayment = true;
                                    // window.location.href = '/az/user-panel#/orders'
                                });
                        }
                    });
                    this.modalTogglePayment = false;


                }
            },*/
            changePrice(shPrice,i_id){
                if(event.target.checked) {
                    this.allPrices = parseFloat((parseFloat(this.allPrices,2) + parseFloat(shPrice, 2)) * this.percent, 2);
                    this.checkedBoxes.push(i_id)
                }
                else {
                    this.allPrices = parseFloat(parseFloat(this.allPrices,2) - parseFloat(shPrice * this.percent , 2), 2);
                    for( var i = 0; i < this.checkedBoxes.length; i++){
                        if ( this.checkedBoxes[i] === i_id) {
                            this.checkedBoxes.splice(i, 1);
                        }
                    }
                }
            },
            selectAll() {
                for(let i in this.allOrders) {
                    if(this.allOrders[i].price !== null){
                        this.allOrders[i].checked = this.select_all
                        if (this.allOrders[i].checked === true) {
                            if(!this.checkedBoxes.includes(this.allOrders[i].id)) {
                                this.allPrices =  (parseFloat((parseFloat(this.allPrices,2) + parseFloat(this.allOrders[i].price, 2)) * this.percent)).toFixed(2);
                                this.checkedBoxes.push(this.allOrders[i].id);
                            }
                        }
                        else {
                            if(this.checkedBoxes.includes(this.allOrders[i].id)) {
                                this.allPrices = (parseFloat(this.allPrices,2) - parseFloat(this.allOrders[i].price * this.percent , 2)).toFixed(2);
                                for (var p = 0; p < this.checkedBoxes.length; p++) {
                                    if (this.checkedBoxes[p] === this.allOrders[i].id) {
                                        this.checkedBoxes.splice(p, 1);
                                    }
                                }
                            }
                        }
                    }
                }
            },
            getCurrency(){
                axios.get('/get-currency/try-azn')
                    .then(data=>{
                        this.tryToAzn = data.data
                    });
            },
            async getAllOrders() {

                this.spinner = true;
                const test = await this.getOrders('paid');
                this.allOrders = test.data;
                console.log(test);
                this.spinner = false
            },
            showEditModal(commonData) {
                this.basket.link = (commonData.extras.link) ? commonData.extras.link : '' ;
                this.basket.id = commonData.id;
                this.basket.price = commonData.price;
                this.basket.size = (commonData.extras.size) ? commonData.extras.size : '';
                this.basket.quantity = commonData.quantity;
                this.basket.description = commonData.description;
            },

            getOrders(type) {
                var path = '/user-panel/get-orders/' + type;
                return axios.get(path);

            },

            sendEditedBasket() {
                var path = '/user-panel/update-product/';
                axios({url: path, method: 'POST', data: this.basket}).then(data => {
                    console.log(data);
                    this.getAllOrders();
                })
            },
            // selectAll() {
            //     for(let i in this.allOrders) {
            //         this.allOrders[i].checked = this.select_all
            //     }
            // }

        },
        components: {
            Spinner: Spinner,
            modal: Modal,

        }
    }

</script>
<style scoped>
    @media (max-width: 680px) {
        ul.nav-tabs li {
            width: 50% !important;
        }
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-header .close {
        opacity: 1;
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
