<template>
    <div class="container mt-5">
        <div class="row mt-5">
            <div class="col-md-3 mt-5" v-if="roles.indexOf('casher') !== -1 || roles.indexOf('super_admin') !== -1">
                <router-link v-bind:to="'/cashier'">
                <div class="card p-3 mb-2 mt-2">
                    <div class="s-24 icon-cash-register">
                    </div>
                    <div class="p-t-b-10">
                        <h5>Kassa</h5>
                    </div>
                </div>
                </router-link>
            </div>
            <div class="col-md-3 mt-5" v-if="roles.indexOf('storage_home') !== -1 || roles.indexOf('super_admin') !== -1">
                <router-link v-bind:to="'/payed-products'">
                <div class="card p-3 mb-2 mt-2">
                    <div class="s-24 icon-note-list">
                    </div>
                    <div class="p-t-b-10">
                        <h5>Ödənilmiş mallar</h5>
                    </div>
                </div>
                </router-link>
            </div>
            <div class="col-md-3 mt-5" v-if="roles.indexOf('storage_home') !== -1 || roles.indexOf('super_admin') !== -1">
                <router-link v-bind:to="'/depot'">
                <div class="card p-3 mb-2 mt-2">
                    <div class="s-24 icon-inboxes2">
                    </div>
                    <div class="p-t-b-10">
                        <h5>Anbar</h5>
                    </div>
                </div>
                </router-link>
            </div>
            <div class="col-md-3 mt-5" v-if="roles.indexOf('super_admin') !== -1">
                <router-link v-bind:to="'/depot/editor'">
                <div class="card p-3 mb-2 mt-2">
                    <div class="s-24 icon-inboxes">
                    </div>
                    <div class="p-t-b-10">
                        <h5>Anbarı dəyiş 2</h5>
                    </div>
                </div>
                </router-link>
                <router-link>
                    Sms göndər
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert2';
    import {api} from "../../apis";

    export default {
        mounted() {
            this.initialize();
        },
        props: {

        },
        data: function (){
            return {
                roles: []
            }
        },
        methods: {
            initialize() {
                axios.get(api.invoiceDepotRole).then(data => {
                    this.roles = data.data;
                })
            }
        },
        components: {
        }
    }

</script>
<style scoped>
    .card {
        transition: 200ms;
        cursor: pointer;
    }
    .card:hover {
        border: 1px solid gray!important;
    }
</style>
