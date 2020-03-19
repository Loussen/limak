<template>
    <div class="container-fluid" v-if="this.roles.length >0">
        <Menu :region_id="region_id"></Menu>
        <audio id="sound1" src="/sounds/error.wav"></audio>
        <audio id="sound1_new" src="/sounds/error2.wav"></audio>
        <audio id="sound1_111" volume="1" src="/sounds/error.mp3"></audio>
        <audio id="sound2" volume="1" src="/sounds/success2.mp3"></audio>
        <audio id="sound2_new" volume="1" src="/sounds/ok.mp3"></audio>
        <audio id="sound2_222" volume="1" src="/sounds/success.wav"></audio>
        <br />
        <br />
        <div class="col-xs-12">
            <div class="col-md-2">
                <button class="btn btn-effect" @click="sendAllSms()">Sms göndər</button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-effect" @click="addDepotStatusForAll()">Anbara yüklə</button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-3">
                <table class="table table-hovered table-stripped bg-white">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Invoys ID</th>
                        <th>Adı Soyadı</th>
                        <th>Bağlama</th>
                        <th>Məhsul sayı</th>
                        <th>Çəki</th>
                        <th>Anbarda yeri</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template>
                        <template  v-if="customProducts && customProducts.length > 0">
                            <tr v-for="(product, index) of customProducts">
                                <td>{{index + 1}}</td>
                                <td>{{product.invoice_id}}</td>
                                <td>{{product.name}} {{product.surname}}<br><strong>{{product.uniqid}}</strong></td>
                                <td>
                                    <p>{{ product.product_type_name }} </p>
                                </td>
                                <td>{{product.quantity}}</td>
                                <td> {{product.weight}} </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  v-model="product.depo">
                                            <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" @click="productSelected(product.depo,product.purchase_no)">Yüklə!</button>
                                          </span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>

                    <!--<tr v-if="!list || list.length === 0">-->
                    <!--<td colspan="8">-->
                    <!--<div class="alert alert-warning text-center"><strong>Məlumat yoxdur!</strong></div>-->
                    <!--</td>-->
                    <!--</tr>-->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
    import swal from 'sweetalert2';
    import Menu from "./DepotMenuComponent"
    import auth from '../auth.js'
    import {api} from "../../apis";
    export default {
        name: "DepotRegionComponent",
        data: function(){
            return {
                term : '',
                date1: '',
                date2: '',
                filteredProducts: [],
                customProducts: [],
                invoice_id: '',
                invoice_response: '',
                region_id:1,
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
            addCustomProduct(){
                axios.post('/cp/depot/addCustomProduct',{invoice_id: this.invoice_id,region_id:this.region_id}).then(({data}) => {
                    console.log(data);
                    this.invoice_response = data.result;
                    this.invoice_id = '';
                })
                    .catch(err => {
                    console.log(err);
                });
                this.getCustomProducts();
            },

            deleteCustomProduct(invoice_id){
                axios.post('/cp/depot/deleteCustomProduct',{invoice_id: invoice_id,region_id:this.region_id}).then(({data}) => {
                    console.log(data);
                    this.invoice_response = data.result;
                })
                    .catch(err => {
                        console.log(err);
                    });
                this.getCustomProducts();
            },

            getCustomProducts(){
                axios.get('/cp/depot/regionInvoices',{params:{region_id:this.region_id}}).then(({data}) => {
                    this.customProducts = data;
                })
            },

            get(term){
                if(term){
                    this.filteredProducts = this.customProducts.filter((product) =>{
                        return product.pin == term || product.serial_number == term || product.uniqid.includes(term) || product.name.toLowerCase().trim().includes(term.toLowerCase().trim()) || product.surname.toLowerCase().trim().includes(term.toLowerCase().trim());
                    })
                } else{
                    this.getCustomProducts()
                }

            },
            getByDate(){
                axios.get('/cp/depot/custom?date1='+this.date1+'&date2='+this.date2).then(({data}) => {
                    this.customProducts = data;
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
            productSelected(barcode,invoiceId) {
                if (barcode!=null) {
                    //this.blockInput = true;
                    swal({
                        title: 'Qeyd olunur...',
                        onOpen: () => {
                            swal.showLoading();
                            axios({url: api.invoiceDepotInsert, method: 'POST', data: {invoice_id: invoiceId, depot_id: barcode, region_id: this.region_id}})
                                .then(response => {
                                    swal.close();
                                    if (response && response.data.data) {
                                        this.PlaySound("sound2");
                                        this.getCustomProducts();
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
        },
        mounted() {
            this.getRegion();
            this.getCustomProducts()
        },
        components :{
            Menu
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','storage_home'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>

</style>