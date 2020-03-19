<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }">
        <div class="limac-modal-dialog">
            <div class="limac-model-header">
                <h3>Bəyənnamə geri göndər</h3>
                <div v-on:click="closeModal()" class="limac-modal-close">
                    <img src="https://limak.az/front/new/img/close-menu.png" alt="close">
                </div>
            </div>
            <div class="limac-modal-body">
                <div v-if="!productData" class="loading"></div>
                <div v-if="productData">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Mağaza</label>
                            <input type="text" class="form-control" v-model="formData.shop = productData.products.shop_name" placeholder="Misal: Zara"/>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Məhsulun tipi</label>
                            <input type="text" class="form-control" v-model="formData.productType = productData.products.products_type_id ? productData.products.products_type.name : productData.products.product_type_name" placeholder="Misal: Şalvar"/>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Məhsul sayı</label>
                            <input type="text" class="form-control" v-model="formData.packageCount = productData.products.quantity" placeholder="Misal: 1"/>
                        </div>
                        <div class="form-group col-md-3">

                            <label>Məhsulun qiyməti</label>
                            <input type="number" class="form-control" v-model="formData.productPrice = productData.price" placeholder="Misal: 123"/>
                        </div>
                        <div class="form-group col-md-3">

                            <label>Qaytarmaq qiyməti</label>
                            <input type="number" class="form-control" v-model="formData.returnPrice = productData.r_price" placeholder="Misal: 123"/>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Sifarişin çəkisi</label>
                            <input type="text" class="form-control" v-model="formData.weight = productData.weight"/>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Qeyd</label>
                            <input type="text" class="form-control" v-model="formData.comment= productData.products.comment"/>
                        </div>
                        <div class="form-group text-right col-md-12">
                            <button v-on:click="backInvoiceData()" class="btn btn-success" v-bind:disabled="checkDisabled()">Qaytar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "back-invoice-data-modal",
        props: ['productData', 'show'],
        data() {
            return {
                formData: {
                    shop: '',
                    productType: '',
                    packageCount: '',
                    productPrice: '',
                    returnPrice: '',
                    //orderTrackingNumber: '',
                    weight: '',
                    comment:''
                }
            }
        },
        methods: {
            closeModal() {
                this.$emit('close-modal');
            },

            backInvoiceData() {
                this.formData.invoiceId = this.productData.id;
                this.formData.productId = this.productData.products.id;
                this.$emit('back-invoice-data', this.formData);
            },
            checkDisabled() {
                var result = false;
                const objkeys = Object.keys(this.formData);
                objkeys.forEach((key) => {
                    if (this.formData[key] === '') {
                        result = true;
                    }
                });

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