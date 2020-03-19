<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }">
        <div class="limac-modal-dialog">
            <div class="limac-model-header">
                <h3>İnvoyslar</h3>
                <div v-on:click="closeModal()" class="limac-modal-close">
                    <span class="icon-close"></span>
                </div>
            </div>
            <div class="limac-modal-body">
                <div v-if="data !== null && data.length === 0" class="no-data">Məlumat yoxdur</div>
                <div v-if="data !== null && data.length > 0">
                    <div v-for="(invoice, key) in data">
                        <div class=""><h3 style="display: inline-block;margin-right: 10px;">Invoys - {{count(key)}}:</h3> <a :href="invoice.file" target="_blank" class="btn btn-primary btn-sm">Sənəd</a></div>
                        <div class="text-center"><h2 style="margin-bottom: 10px;">Məhsul</h2></div>
                        <table style="margin-bottom: 10px;" class="table table-striped">
                            <tr>
                                <th>Tip</th>
                                <!--<th>Qiymət</th>-->
                                <th>Say</th>
                                <th>Mağaza</th>
                                <th>Qeyd</th>
                            </tr>
                            <tr>
                                <td>{{invoice.products.products_type_id ? invoice.products.products_type.name : invoice.products.product_type_name}}</td>
                                <!--<td>{{invoice.products.price}}</td>-->
                                <td>{{invoice.products.quantity}}</td>
                                <td>{{invoice.products.shop_name}}</td>
                                <td>{{invoice.products.description}}</td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "products-modal",
        props: ['data', 'show'],
        methods: {
            closeModal() {
                this.$emit('close-modal');
            },
            count(index) {
                return index + 1;
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
        width: 1000px;
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
        -webkit-animation: spin-data-v-d1d35872 1s linear infinite;
        animation: spin-data-v-d1d35872 1s linear infinite;
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