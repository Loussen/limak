<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }">
        <div class="limac-modal-dialog">
            <div class="limac-model-header">
                <h3>Bəyənnamə yüklə</h3>
                <div v-on:click="closeModal()" class="limac-modal-close">
                    X
                </div>
            </div>
            <div class="limac-modal-body modal-body">
                <div v-if="!productData" class="loading"></div>
                <div v-if="productData">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Mağaza</label>
                            <input type="text" class="form-control" v-model="formData.shop = productData.shop_name" placeholder="Misal: Zara"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Məhsulun tipi</label>
                            <input type="text" class="form-control" v-model="formData.productType = productData.products_type_id ? productData.products_type.name : productData.product_type_name" placeholder="Misal: Şalvar"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Məhsul sayı</label>
                            <input type="text" class="form-control" v-model="formData.packageCount = productData.quantity" placeholder="Misal: 1"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Məhsulun qiyməti</label>
                            <input type="number" class="form-control" v-model="formData.productPrice = productData.price.toFixed(2)" placeholder="Misal: 123"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Xərclənən pul</label>
                            <input type="number" class="form-control" v-model="formData.expenses = productData.expenses.toFixed(2)" placeholder="Misal: 123"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Istifadə olunan kart</label>
                            <select class="form-control" v-model="formData.account" required>
                                <option v-for="item in productData.accounts" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Istifadəçinin ödədiyi qiymət</label>
                            <input type="number" class="form-control" v-model="formData.customer_price = productData.customer_price.toFixed(2)"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sifaris İzləmə nömrəsi</label>
                            <input type="text" class="form-control" v-model="formData.orderTrackingNumber" placeholder="Misal: 145678"/>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Sifarişin tarixi</label>
                            <input type="date" class="form-control" v-model="formData.orderDate"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Teslimat nömrəsi</label>
                            <input type="text" class="form-control" v-model="formData.deliveryNumber" placeholder="Misal: 145678"/>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Bağlamadakı məhsullar</label>
                            <textarea class="form-control" v-model="formData.description" placeholder="Şalvar,Köynək"></textarea>
<!--
                            <input type="text" class="form-control" v-model="formData.orderTrackingNumber" placeholder="Misal: 145678"/>
-->
                        </div>
                        <div class="form-group text-right col-md-12">
                            <button v-on:click="sendİnvoiceData()" class="btn btn-effect" v-bind:disabled="checkDisabled()">Göndər</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "send-invoice-data-modal",
        props: ['productData', 'show'],
        data() {
            return {
                formData: {
                    shop: '',
                    productType: '',
                    packageCount: '',
                    productPrice: '',
                    orderTrackingNumber: '',
                    deliveryNumber: '',
                    account: 21,
                    expenses: '',

                    orderDate: '',

                },
                myDate: '',
            }
        },
        mounted(){
            this.myDate = new Date();
            this.myDate = new Date(this.myDate.setDate(this.myDate.getDate()));
            this.myDate =  this.myDate.toISOString().split('T')[0];
            this.formData.orderDate = this.myDate;

        },
        methods: {
            closeModal() {
                this.$emit('close-modal');
            },

            sendİnvoiceData() {
                this.formData.productIds = this.productData.ids;
                if(this.formData.account == 21){
                    alert("Ay İnsan Istifadə olunan kart məlumatını düzgün yaz")
                }else{
                    this.$emit('send-invoice-data', this.formData);
                }
            },
            checkDisabled() {
                var result = false;
                if(this.formData.productPrice>0 && this.formData.expenses>0){
                    result = false;
                    const objkeys = Object.keys(this.formData);
                    objkeys.forEach((key) => {
                        if (this.formData[key] === '') {
                            result = true;
                        }
                    });
                }else{
                    result = true;
                }

                return result;
            }
        }
    }
</script>

<style scoped>
    .limac-modal {
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.4);
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0;
        -webkit-transition: 400ms;
        -moz-transition: 400ms;
        -ms-transition: 400ms;
        -o-transition: 400ms;
        transition: 400ms;
    }

    .limac-modal.show-modal {
        z-index: 9999;
        opacity: 1;
    }

    .limac-modal-dialog {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        width: 800px;
        max-height: 90%;
        overflow-y: auto;
        border-radius: 3px;
        padding: 15px;
        -webkit-transition: 200ms;
        -moz-transition: 200ms;
        -ms-transition: 200ms;
        -o-transition: 200ms;
        transition: 200ms;
    }

    .limac-modal.show-modal .limac-modal-dialog {
        transform: translate(-50%, 0);
    }

    .limac-modal-close {
        position: absolute;
        top: 13px;
        right: 13px;
        cursor: pointer;
    }

    .limac-modal-close span {
        font-size: 17px;
    }

    .limac-model-header h3 {
        padding-bottom: 12px;
        font-weight: bolder;
        text-align: center;
    }

    .table td {
        color: #000;
        font-weight: 400;
    }

    .loading {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 26px;
        height: 26px;
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 45px;
        left: 49%;
    }

    .limac-model-header h3 {
        text-align: left;
    }

    .content .limac-modal-body .btn-effect {
        width: 160px;
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