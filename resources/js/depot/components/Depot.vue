<template>

    <div class="container-fluid mt-5">

        <div class="row">
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
            </div>
            <router-link>
                Sms göndər
            </router-link>
        </div>
        <div class="row bg-white pt-4">
            <audio id="sound1" src="/sounds/error.wav"></audio>
            <audio id="sound2" src="/sounds/success.wav"></audio>
            <div class="col-3 offset-9">
                <button class="btn" :class="fillMode ? 'btn-warning' : 'btn-info'" @click="activate()">{{fillMode ? 'Anbar doldurma modunu sondur' : 'Anbar doldurma modunu aktivləşdir'}}</button>
            </div>
            <div class="col-6 offset-3" v-if="fillMode">
                <label for="fillInput"><strong>Anbara mal vur</strong></label>
                <input :ref="'fillInput'" v-bind:readonly="blockInput" title="Barkod oxudaraq anbara mal vur" id="fillInput" name="fillInput" v-model="barcode" @keyup="barcodefull()" type="search" class="form-control" placeholder="Malın alış nömrəsi və yaxud saxlanc kodu" @focusout="focusOut()">
                <div class="alert alert-info"><strong>Diqqət!</strong> Anbara mal vurmaq üçün: <br>
                    <ol>
                        <li>Anbar rəfinin üzərində olan barkodu oxuyucuya oxudun</li>
                        <li>Malın üzərində olan barkodu oxudun</li>
                        <li>Rəfin tutumundan artıq mal vurmaq qadağandır!</li>
                    </ol>
                </div>
                <div class="alert alert-warning" v-if="storageId">
                    <strong>Seçilmiş rəf: {{storageId}}</strong>
                </div>
                <div class="alert alert-warning" v-if="productId">
                    <strong>Seçilmiş malın alış nömrəsi: {{productId}}</strong>
                </div>
            </div>
        </div>
        <div class="row mb-3 bg-white p-3" v-for="(box) in boxes">
            <div class="col-12">
                <h4 class="text-center"><strong>Sektor: {{box.index}}</strong></h4>
            </div>
            <div class="col-12 py-1 box" v-for="storage in box.boxes">
                <!--<h5 class="text-center mb-2 mt-3"></h5>-->
                <div class="container-fluid">
                    <div class="row">
                        <template v-for="stBox in storage.storage">
                            <div class="storage-box px-0" v-bind:title="'Tutum: ' + stBox.size + '\n' + 'Ölçü: ' + stBox.capacity" v-bind:class="stBox.capacity === 'sm' ? 'col-1' : stBox.capacity === 'md' ? 'col-1' : stBox.capacity === 'lg' ? 'col-2' : stBox.capacity === 'xlg' ? 'col-2' : ''">
                                <div class="storage-fill" :class="stBox.barcode_id === storageId ? stBox.size === depot_invoice_count ? 'selectedFull' : 'selected' : stBox.size === stBox.depot_invoice_count ? 'selectedFull' : ''">
                                    <strong>{{stBox.index}} {{stBox.number}} | {{stBox.depot_invoice_count}}/{{stBox.size}}</strong>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
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
                boxes: [],
                fillMode: false,
                barcode: null,
                timeout: 0,
                storageId: null,
                productId: null,
                blockInput: false,
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
            get() {
                axios.get(api.invoiceDepotList).then(response => {
                    this.boxes = response.data;
                })
            },
            submit() {

            },
            barcodefull() {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    const length = this.barcode.length;
                    if (length < 9) {
                        this.storageSelected(this.barcode)
                    } else {
                        this.productSelected(this.barcode)
                    }
                    this.barcode = null;
                }, 200);
            },
            storageSelected(barcode) {
                this.storageId = barcode;
            },
            productSelected(barcode) {
                if (this.storageId) {
                    this.productId = barcode;
                    this.blockInput = true;
                    swal({
                        title: 'Qeyd olunur...',
                        onOpen: () => {
                            swal.showLoading();
                            axios({url: api.invoiceDepotInsert, method: 'POST', data: {invoice_id: this.productId, depot_id: this.storageId}})
                                .then(response => {
                                swal.close();
                                if (response && response.data.data) {
                                    this.blockInput = false;
                                    this.PlaySound("sound2");
                                    this.productId = null;
                                    this.decrement(this.storageId)
                                } else if(!response.data.data && response.data.error) {
                                    swal({
                                        type: 'error',
                                        title: 'Səhf!',
                                        text: response.data.error
                                    });
                                    this.PlaySound("sound1");
                                    this.blockInput = false;
                                    this.productId = null;
                                }
                            })
                                .catch(err => {
                                    swal({
                                        type: 'error',
                                        title: 'Səhflik baş verdi!',
                                    });
                                    console.log(err);
                                    this.PlaySound("sound1");
                                })
                        }
                    })
                } else {
                    swal({
                        type: 'error',
                        title: 'Xahiş olunur birinci rəfin üzərində yerləşən barkodu oxudasınız',
                    });
                    this.PlaySound("sound1");
                }
            },
            decrement() {
                this.boxes = this.boxes.map(box => {
                    box.boxes = box.boxes.map( storage=> {
                        storage.storage = storage.storage.map(
                            stBox => {
                                if (stBox.barcode_id === this.storageId) {
                                    stBox.size = stBox.size - 1;
                                    stBox.depot_invoice_count = stBox.depot_invoice_count + 1;
                                }
                                return stBox;
                            }
                        );
                        return storage;
                    });
                    return box;
                })
                this.focusOut();
            },
            PlaySound(soundObj) {
                var sound = document.getElementById(soundObj);
                sound.play();
            },
            activate() {
                this.fillMode = !this.fillMode;
                if (this.fillMode) {
                   setTimeout(() => {
                       this.$refs.fillInput.focus();
                   }, 200);
                }
            },
            focusOut() {
                if (this.fillMode) {
                    setTimeout(() => {
                        this.$refs.fillInput.focus();
                    }, 100);
                }
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
    .storage-fill{
        border: 1px solid gray;
        background: #f6f6f6;
    }
    .storage-fill.selected{
        border: 1px solid #2e801d;
        background: #b6f6b3;
    }
    .storage-fill.selectedFull{
        border: 1px solid #803237;
        background: #f6a69f;
        color: #802025;
    }
    .box{
        border: 1px solid gray;
        border-radius: 3px;
    }
</style>
