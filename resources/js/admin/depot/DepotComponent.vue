<template>
    <div class="container-fluid mt-5" v-if="this.roles.length >0">
        <Menu @onOpenPackages="openPackagesModal" :roles="roles" :region_id="region_id"></Menu>
        <div class="row bg-white pt-4">
            <audio id="sound1" src="/sounds/error.wav"></audio>
            <audio id="sound1_new" src="/sounds/error2.wav"></audio>
            <audio id="sound1_111" volume="1" src="/sounds/error.mp3"></audio>
            <audio id="sound2" volume="1" src="/sounds/success2.mp3"></audio>
            <audio id="sound2_new" volume="1" src="/sounds/ok.mp3"></audio>
            <audio id="sound2_222" volume="1" src="/sounds/success.wav"></audio>
            <div class="col-xs-12"> 
                <div class="col-md-2">
                    <button class="btn" :class="fillMode ? 'btn-warning' : 'btn-info'" @click="activate()">{{fillMode ? 'Anbar doldurma modunu sondur' : 'Anbar doldurma modunu aktivləşdir'}}</button>
                </div>
                <div class="col-md-4">
                    <input class="form-control" v-model="uniqid" @change="openProductModalByUserId(uniqid)">

                </div>
                <div class="col-md-2">
                    <router-link class="btn btn-effect" to="/depot/editor">Anbarı dəyiş</router-link>

                </div>
                <div class="col-md-2">
                    <button class="btn btn-effect" @click="sendAllSms()">Sms göndər</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-effect" @click="addDepotStatusForAll()">Anbara yüklə</button>
                </div>
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
        <div class="row  mb-3 bg-white p-3" v-for="(box) in boxes">
            <div class="col-12">
                <h4 class="text-left"><strong>Sektor: {{box.index}}</strong></h4>
            </div>
            <div class="col-12 py-1 box" v-for="storage in box.boxes">
                <!--<h5 class="text-center mb-2 mt-3"></h5>-->
                <div class="container-fluid">
                    <div class="row">
                        <template v-for="stBox in storage.storage">
                            <div class="storage-box px-0" @click="openProductModal(stBox.id)" :data-product="stBox.depot_invoice_count"  v-bind:title="'Tutum: ' + stBox.size + '\n' + 'Ölçü: ' + stBox.capacity" v-bind:class="stBox.capacity === 'sm' ? 'col-xs-1' : stBox.capacity === 'md' ? 'col-xs-1' : stBox.capacity === 'lg' ? 'col-xs-2' : stBox.capacity === 'xlg' ? 'col-xs-2' : ''">
                                <div class="storage-fill" :class="stBox.barcode_id === storageId ? stBox.size === depot_invoice_count ? 'selectedFull' : 'selected' : stBox.size === stBox.depot_invoice_count ? 'selectedFull' : ''">
                                    <strong>{{stBox.index}} {{stBox.number}} | {{stBox.depot_invoice_count}}/{{stBox.size}}</strong>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal"  role="dialog" style="display: block;" v-if="show_all_packages">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="show_all_packages = false;">&times;</button>
                        <h4 class="modal-title">Bağlamalar4</h4>
                    </div>
                    <div class="modal-body" style="height: 500px;overflow: auto;">
                        <table class="table table-hovered table-stripped bg-white">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>Adı Soyadı</th>
                                <th>Anbarda yeri</th>
                                <th>Bağlama</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(product, index) of packages">
                                <td>{{index + 1}}</td>
                                <td>{{product.name}} {{product.surname}} <br> <strong>{{product.uniqid}}</strong></td>
                                <td>{{product.barcode_id}}</td>
                                <td>{{product.product_type_name}}</td>
                                <td><input class="form-control" v-model="product.new_barcode_id" placeholder="anbar yerini daxil et"><button class="btn-effect" @click="changeStorage3(product)">dəyiş</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="show_all_packages = false;">Bağla</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="myModal" role="dialog" style="display: block;" v-if="product_modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="product_modal = false;">&times;</button>
                        <h4 class="modal-title">Bağlamalar <span v-if="products[0]">{{products[0].barcode_id}}</span>
                            <div class="col-md-3 pull-right"><input class="form-control" v-model="new_barcode_id" placeholder="anbar yerini daxil et"><button class="btn-effect"  v-if="products[0]" @click="changeStorageAll(products[0].barcode_id)">dəyiş</button></div>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hovered table-stripped bg-white">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>Adı Soyadı</th>
                                <th>Anbarda yeri</th>
                                <th>Bağlama</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) of products">
                                    <td>{{index + 1}}</td>
                                    <td>{{product.name}} {{product.surname}} <br> <strong>{{product.uniqid}}</strong></td>
                                    <td>{{product.barcode_id}}</td>
                                    <td>{{product.product_type_name}}</td>
                                    <td><input class="form-control" v-model="product.new_barcode_id" placeholder="anbar yerini daxil et"><button class="btn-effect" @click="changeStorage(product)">dəyiş</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="product_modal = false;">Bağla</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="myModal2" role="dialog" style="display: block;" v-if="product_modal2">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="product_modal2 = false;">&times;</button>
                        <h4 class="modal-title">Bağlamalar</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hovered table-stripped bg-white">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>Adı Soyadı</th>
                                <th>Anbarda yeri</th>
                                <th>Bağlama</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(product, index) of products">
                                <td>{{index + 1}}</td>
                                <td>{{product.name}} {{product.surname}} <br> <strong>{{product.uniqid}}</strong></td>
                                <td>{{product.barcode_id}}</td>
                                <td>{{product.product_type_name}}</td>
                                <td><input v-model="product.new_barcode_id" class="form-control" placeholder="anbar yerini daxil et"><button class="btn-effect" @click="changeStorage2(product)">dəyiş</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="product_modal2 = false;">Bağla</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert2';
    import {api} from "../../apis";
    import depotMain from "./DepotMainComponent";
    import Menu from "./DepotMenuComponent"
    import auth from '../auth.js'

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
                selected_depot: 0,
                selected_uniqid: 0,
                storageId: null,
                uniqid: null,
                productId: null,
                blockInput: false,
                product_modal: false,
                product_modal2: false,
                products: [],
                packages: [],
                show_all_packages: false,
                roles: [],
                region_id:1,
                new_barcode_id: ''
            }
        },
        methods: {
            getRegion()
            {
                this.region_id = this.$route.params.id;
                axios.get('/cp/admin/getRegion',{params:{region_id:this.region_id}}).then(({data}) => {
                    this.region_id = data.region_id;
                    if(this.region_id==0){
                        this.$router.push('/');
                    }
                });
            },

            openProductModal(depotId){
                axios.get('/cp/depot/byDepotId/'+depotId).then(({data}) => {
                    this.selected_depot = depotId;
                    this.products = data;
                    this.product_modal = true;
                });
            },
            openPackagesModal(){
                axios.get('/cp/depot/packages?region_id='+this.region_id,).then(({data}) => {
                    this.packages = data;
                    this.show_all_packages= true
                });

            },
            openProductModalByUserId(uniqid){
                axios.get('/cp/depot/byUserId/'+uniqid+'?region_id='+this.region_id).then(({data}) => {
                    this.selected_uniqid = uniqid;
                    this.products = data;
                    this.product_modal2 = true;
                });
            },
            changeStorage(product){
                axios.get('/cp/depot/changeInvoicePlace/'+product.invoice+'/'+product.new_barcode_id).then(({data}) => {
                    this.openProductModal(this.selected_depot);
                    this.initialize();
                });
            },
            changeStorageAll(old_barcode_id){
                alert(old_barcode_id+" - "+this.new_barcode_id);
                /*axios.get('/cp/depot/changeInvoicePlaceAll/'+product.barcode+'/'+new_barcode_id).then(({data}) => {
                    this.openProductModal(this.selected_depot);
                    this.initialize();
                });*/


                axios.post('/cp/depot/changeInvoicePlaceAll', {old_barcode:old_barcode_id,new_barcode:this.new_barcode_id })
                    .then(data => {
                        this.openProductModal(data.depo);
                        this.initialize();
                    })
                    .catch(err => {
                        console.log(err);
                    });

            },
            changeStorage2(product){
                axios.get('/cp/depot/changeInvoicePlace/'+product.invoice+'/'+product.new_barcode_id).then(({data}) => {
                    this.openProductModalByUserId(this.selected_uniqid);
                    this.initialize();
                });
            },

            changeStorage3(product){
                axios.get('/cp/depot/changeInvoicePlace/'+product.invoice+'/'+product.new_barcode_id).then(({data}) => {
                    this.openPackagesModal();
                    this.initialize();
                });
            },
            initialize() {
                this.getRegion();
                this.get();
                axios.get(api.invoiceDepotRole).then(data => {
                    this.roles = data.data;
                });
            },
            get() {
                axios.get(api.invoiceDepotList,{params:{region_id:this.region_id}}).then(response => {
                    this.boxes = response.data;
                })
            },
            sendAllSms(){

                axios.get("/cp/users/sendSmsForAll",{params:{region_id:this.region_id}}).then(response => {
                    swal({
                        type: 'success',
                        title: 'Göndərildi',
                    });
                })
            },
            addDepotStatusForAll(){

                axios.get("/cp/depot/addDepotStatusForAll",{params:{region_id:this.region_id}}).then(response => {
                    swal({
                        type: 'success',
                        title: 'Göndərildi',
                    });
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
                }, 100);
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
                            axios({url: api.invoiceDepotInsert, method: 'POST', data: {invoice_id: this.productId, depot_id: this.storageId, region_id: this.region_id}})
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
                if(soundObj=='sound2'){
                    if(this.hasAccess(Array('buyer'))){
                        soundObj = "sound2_new";
                    }else{
                        soundObj = "sound2";
                    }
                }else if(soundObj=='sound1'){
                    if(this.hasAccess(Array('buyer'))){
                        soundObj = "sound1_new";
                    }else{
                        soundObj = "sound1";
                    }
                }
                var sound = document.getElementById(soundObj);
                sound.play();
            },
            activate() {
                this.fillMode = !this.fillMode;
                if (this.fillMode) {
                   setTimeout(() => {
                       this.$refs.fillInput.focus();
                   }, 100);
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
            Menu,
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','storage_home'))
            }
        },
        mixins: [auth]
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
    .col-xs-1,.col-xs-2{
        padding: 0;
    }
    [data-product='0']{
        background: red;
    }
    .box{
        border: none;
    }
    .storage-fill{
        padding: 10px 20px;
        text-align: center;
    }
    .row.bg-white {
        background: #fff;
        margin: 30px 0;
        border-radius: 10px;
        padding: 25px;
    }
    h4.text-left {
        font-size: 22px;
        margin-bottom: 20px;
    }
    .btn.btn-effect{
        height: 32px;
    }
    .modal tr td input {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        width: 70%;
        display: inline-block;
    }

    .modal .table .btn-effect {
        height: 40px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        width: 56px;
        padding-left: 5px;
    }

    .modal .table .btn-effect:after {
        content: '';
    }

    .modal {
        background: rgba(0, 0, 0, .5);
    }

    #myModal .modal-body,#myModal2 .modal-body {
        overflow-y: scroll;
        max-height: 510px;
    }

    .bg-white .btn-info {
        border-radius: 20px;
    }
    .modal tr td input{
        width: 69%;
    }
</style>
