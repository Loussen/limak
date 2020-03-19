<template>
    <div class="limac-modal" v-bind:class="{ 'show-modal': show }">
        <div class="limac-modal-dialog">
            <div class="limac-model-header">
                <h3>Ətraflı</h3>
                <div v-on:click="closeModal()" class="limac-modal-close">
                    <span class="icon-close"></span>
                </div>
            </div>
            <div class="limac-modal-body">
                <div v-if="data == null" class="loading"></div>
                <table class="table dtr-details" width="100%" v-if="data != null">
                    <tr>
                        <th>Tip</th>
                        <th>Qiymət</th>
                        <th>Say</th>
                        <th>Mağaza</th>
                        <!--<th>Ölkə</th>-->
                        <th>Link</th>
                        <th>Brend</th>
                        <th>Rəng</th>
                        <th>Ölçü</th>
                        <th>Qeyd</th>
                    </tr>
                    <tr v-for="product in data.products">
                        <td>{{product.products_type_id ? product.products_type.name : product.product_type_name}}</td>
                        <td>{{product.price}}</td>
                        <td>{{product.quantity}}</td>
                        <td>{{product.shop_name}}</td>
                        <!--<td>{{product.extras.countries.translates[0].name}}</td>-->
                        <td><a :href="product.extras.link" target="_blank">Keçid</a></td>
                        <td>{{product.extras.brand}}</td>
                        <td>{{product.extras.color}}</td>
                        <td>{{product.extras.size}}</td>
                        <td>{{product.description}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "accept-modal",
        props: ['data', 'show'],
        data: function () {
          return {
          }
        },

        mounted() {
        },

        methods: {
            closeModal() {
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
