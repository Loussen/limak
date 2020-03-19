<template>
    <div class="container-fluid">

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
                            <h5>Anbarı dəyiş</h5>
                        </div>
                    </div>
                </router-link>
            </div>
            <router-link>
                Sms göndər
            </router-link>
        </div>
        <div class="row">
            <div class="offset-3 col-6 bg-white mt-5 p-3">
                <div class="form-group">
                    <label for="index">Indeks</label>
                    <input type="text" name="index" id="index" class="form-control" v-model="data.index">
                </div>
                <div class="form-group">
                    <label for="number">Sıra nömrəsi</label>
                    <input type="number" name="number" id="number" class="form-control" v-model="data.number">
                </div>
                <div class="form-group">
                    <label for="capacity">Ölçü</label>
                    <select name="capacity" id="capacity" class="form-control" v-model="data.capacity">
                        <option value="sm">sm</option>
                        <option value="md">md</option>
                        <option value="lg">lg</option>
                        <option value="xlg">xlg</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="size">Tutumu</label>
                    <input type="number" id="size" class="form-control" v-model="data.size">
                </div>
                <div class="form-group">
                    <label for="box">Mərtəbə</label>
                    <input type="number" id="box" class="form-control" v-model="data.box">
                </div>
                <div class="form-group">
                    <label for="barcode_id">Barcode İN - i </label>
                    <input type="text" id="barcode_id" class="form-control" v-model="data.barcode_id">
                </div>
                <div class="form-group text-right">
                    <button class="btn btn-success " @click="submit()" v-bind:disabled="!data.index || !data.barcode_id|| !data.capacity|| !data.size|| !data.number|| !data.box">Göndər</button>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 bg-white">
                <table class="table table-hovered">
                    <thead>
                    <tr>
                        <th>index nömrə</th>
                        <th>Ölçusu</th>
                        <th>Tutumu</th>
                        <th>Mərtəbə</th>
                        <th>Barcode İN - i</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) of list">
                        <td width="10%">
                            <div class="row">
                                <div class="col-6 px-1">
                                    <input type="text" class="form-control" v-model="item.index">
                                </div>
                                <div class="col-6 px-1">
                                    <input type="number" class="form-control" v-model="item.number">
                                </div>
                            </div></td>
                        <td>
                            <select name="capacity" class="form-control" v-model="item.capacity">
                                <option value="sm">sm</option>
                                <option value="md">md</option>
                                <option value="lg">lg</option>
                                <option value="xlg">xlg</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" v-model="item.size">
                        </td>
                        <td>
                            <input type="number" class="form-control" v-model="item.box">
                        </td>
                        <td>
                            <input type="text" class="form-control" v-model="item.barcode_id">
                        </td>
                        <td>
                            <button class="btn btn-success" @click="update(index)"><i class="icon-send"></i></button>
                            <button class="btn btn-danger" @click="remove(item.id)"><strong>Sil</strong></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
        data: function (){
            return {
                data: {
                    index: null,
                    number: null,
                    capacity: null,
                    size: 5,
                    box: null,
                    barcode_id: null,
                },
                list: [],
                roles: []
            }
        },
        methods: {
            initialize() {
                this.get();
                axios.get(api.invoiceDepotRole).then(data => {
                    this.roles = data.data;
                });
            },
            submit() {
                swal({
                    title: 'Yüklənir...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.invoiceDepotEditorInsert, method: 'POST', data: this.data }).then(response => {
                            swal.close();
                            this.get();
                        });
                    }
                })
            },
            get() {
                axios.get(api.invoiceDepotEditorList).then(response => {
                    this.list = response.data;
                })
            },
            remove(id) {
                swal({
                    title: 'Silinir...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.invoiceDepotEditorDelete, method: 'POST', data: {id: id} }).then(response => {
                            swal.close();
                            this.get();
                        });
                    }
                })
            },
            update(index) {
                const data = this.list[index];
                swal({
                    title: 'Yenilənir...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.invoiceDepotEditorUpdate, method: 'POST', data }).then(response => {
                            swal.close();
                            this.get();
                        });
                    }
                })
            }
        },
        components: {
        }
    }

</script>
<style>
    [disabled] {
        cursor: not-allowed;
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
