<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content" style="width: 100% !important;">
                <div class="modal-header">
                    <button  v-on:click="closeModal()" type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <!--<div v-if="!productData" class="loading"></div>-->
                    <div v-if="productData">
                        <table class="table table-striped">
                            <thead>
                                <th>{{ this.ExWindow && this.ExWindow.translator('panel-errors.shop') }}</th>
                                <th>{{ this.ExWindow && this.ExWindow.translator('panel-errors.product') }}</th>
                                <th>{{ this.ExWindow && this.ExWindow.translator('panel-errors.invoices_count') }}</th>
                                <th>{{ this.ExWindow && this.ExWindow.translator('panel.price') }}</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div style="font-weight: bold;">{{productData.shop_name}}</div>
                                </td>
                                <td>
                                    <div style="font-weight: bold;">{{productData.product_name}}</div>
                                </td>
                                <td>
                                    <div style="font-weight: bold;">{{productData.count}}</div>
                                </td>
                                <td>
                                    <div style="font-weight: bold;">{{productData.price}} AZN</div>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>

    export default {
        mounted() {
            this.ExWindow = window;
        },
        data: function () {
            return {
                ExWindow: null,
            }
        },
        // courier_user: '',
        name: "products-modal",
        props: ['productData', 'show'],
        methods: {
            closeModal(){
                this.$emit('close-modal');
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
        z-index: 100;
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