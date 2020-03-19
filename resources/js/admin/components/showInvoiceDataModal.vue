<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }">
        <div class="limac-modal-dialog">
            <div class="limac-model-header">
                <h3>Bəyənnamə - ətraflı</h3>
                <div v-on:click="closeModal()" class="limac-modal-close">
                    <img src="https://limak.az/admin/img/Forma 1.png" alt="close">
                </div>
            </div>
            <div class="limac-modal-body">
                <div v-if="!productData" class="loading"></div>
                
                <div v-if="productData">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    Mağaza
                                    <div style="font-weight: bold;">{{productData.products.shop_name}}</div>
                                </td>
                                <td>
                                    Məhsulun tipi
                                    <div style="font-weight: bold;">{{productData.products.products_type_id ? productData.products.products_type.name : productData.products.product_type_name}}</div>
                                </td>
                                <td>
                                    Ölçü
                                    <div style="font-weight: bold;">{{productData.width}} {{productData.height}} {{productData.length}}</div>
                                </td>
                                <td>
                                    Çəki
                                    <div style="font-weight: bold;">{{productData.weight}} </div>
                                </td>

                                <td>
                                    Məhsul sayı
                                    <div style="font-weight: bold;">{{productData.products.quantity}}</div>
                                </td>
                                <td>
                                    Bağlamanın qiyməti
                                    <div style="font-weight: bold;">{{productData.price}} TL</div>
                                </td>
                                <td>
                                    İzləmə nömrəsi
                                    <div style="font-weight: bold;">{{productData.order_tracking_number}}</div>
                                </td>
                                <td>
                                    Teslimat nömrəsi
                                    <div style="font-weight: bold;">{{productData.delivery_number}}</div>
                                </td>
                                <td>
                                    Sifarişin tarixi
                                    <div style="font-weight: bold;">{{productData.order_date}}</div>
                                </td>
                                <td>
                                    Invoys
                                    <div style="font-weight: bold;"><a :href="productData.file" target="_blank" v-if="productData.file"> Bax </a></div>
                                </td>
                            </tr>

                            <tr v-if="productData.links !== null" v-for="item in productData.links">
                                <td colspan="6" align="left" style="text-align: left !important;">
                                    LINK
                                    <a :href="item.products.extras.link" target="_blank" style="font-weight: bold;" v-if="item.products.extras">{{item.products.extras.link}}</a>
                                </td>
                                <td>{{item.products.description}}</td>
                                <td>{{item.products.price}} TL</td>
                            </tr>
                            <tr>

                            </tr>
                        <tr v-if="productData.dates!=null"  v-for="date in productData.dates">
                             <td colspan="6" style="text-align: left !important;" align="left">
                                 {{date.action_date}}- {{date.status.name}}
                             </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "show-invoice-data-modal",
        props: ['productData', 'show'],
        methods: {
            closeModal() {
                this.$emit('close-modal');
            },
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
        width: 1200px;
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