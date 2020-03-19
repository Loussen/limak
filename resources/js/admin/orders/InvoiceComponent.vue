<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <form action="...">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Axtarış">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <div class="input-group">
                                <select class="selectpicker">
                                    <option>Tarixə görə</option>
                                    <option>Müştəri koduna görə</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <button type="button" class="btn-effect">Axtar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Invoys ətraflı</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/orders">Sifarişlər</router-link></li>
                    <li class="active"> Invoys</li>
                </ol>
            </div> 
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="block customer-list">
                <ul v-if="data.users" class="list-user">
                    <li>Ad, Soyad <p v-if="data.users">{{data.users.name}} {{data.users.surname}}</p></li>
                    <li>E-mail <p v-if="data.users">{{data.users.email}}</p></li>
                    <li>Mobil telefon <p v-if="data.users.user_contacts">
                        <span style="display: block; margin-bottom: 10px;">
                            {{data.users.user_contacts[0].name}}
                        </span>
                    </p></li>
                    <li v-if="data.users">Ünvan <span class="red">Məhsulu alan zaman ünvanı kopyalamağı unutmayın!</span>
                        <p>
                            Halkalı Merkez Mahellesi. Üzümlü SK. 5/7 kod: {{data.users.uniqid}} {{data.users.name}} {{data.users.surname}} <button style="margin-left: 10px;" class="btn btn-xs btn-primary" @click.stop.prevent="copyTestingCode">Kopyala</button>
                            <input type="hidden" id="copy" :value="'Halkalı Merkez Mahellesi. Üzümlü SK. 5/7  kod: ' + data.users.uniqid + ' ' + data.users.name + ' ' + data.users.surname">
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="inform col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th>
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox_0">
                                <label for="checkbox_0"></label>
                            </div>
                        </th>
                        <th>Tam ödəniş</th>
                        <th>-5%</th>
                        <th>Kargo</th>
                        <th>Müştərinin ödədiyi pul</th>
                        <th>Xərclənən pul</th>
                        <th>Mağaza</th>
                        <th>Say</th>
                        <th>Bağlamaların içindəkilər</th>
                        <th>Status</th>
                        <th>Qeyd</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr >
                        <td>
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox_3">
                                <label for="checkbox_3"></label>
                            </div>
                        </td>
                        <td>{{ ((parseFloat(data.price)*1.05).toFixed(2))  }} TL</td>
                        <td>{{ parseFloat(data.price) }} TL</td>
                        <td v-if="data.products.extras!=null">{{ nullToZero(data.products.extras.cargo_price) }}  TL</td>
                        <td v-else></td>
                        <td>{{ data.products.real_price }}TL</td>
                        <td>{{ data.products.expenses }}TL</td>
                        <td>{{ data.products.shop_name }}</td>
                        <td>{{ data.products.quantity }}</td>
                        <td>{{ data.products.product_type_name}}</td>
                        <td>{{ data.invoice_status.name}}</td>
                        <td style="max-width: 200px">{{ data.products.comment }}</td>
                        <td class="upload-but count-end">
                            <a v-on:click="showInvoice(data)" class="btn-effect lite-green">Bəyənnaməyə bax</a><br>

                            <label class="form-group btn-effect blue">
                                <span>İnvoys yüklə</span>
                                <input type="file" class="form-control-file" v-on:change="submitFile($event, {invoiceId: data.id})"/>
                            </label>

                           <!-- <label for="file1" class="form-group btn-effect blue">
                                <span>İnvoys yüklə</span>
                                <input required="" type="file" class="form-control-file" id="file1" name="invoice">
                            </label>-->
                        </td>
                        <td>
                            <button style="top: 0;" type="button" v-on:click="showUpdateInvoiceModal(data)" class="btn btn-outline-warning btn-effect label">
                                <span> Dəyiş</span>
                            </button>
                                <button type="button" class="btn-effect yellow" v-on:click="showBackInvoiceModal(data)">Qaytar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <rej-ref-modal v-bind:accountants="accountants" v-bind:data="null" v-bind:show="show" @close-modal="closeModal" @send-rejection="sendActs($event)"></rej-ref-modal>
        <update-invoice-data-modal v-bind:productData="productData2" v-bind:show="showUpdatesInvoiceModal" @close-modal="closeUpdateInvoiceModal" @update-invoice-data="updateInvoiceData($event)"></update-invoice-data-modal>
        <back-invoice-data-modal v-bind:productData="productData2" v-bind:show="showShowBackInvoiceModal" @close-modal="closeBackInvoiceModal" @back-invoice-data="backInvoiceData($event)"></back-invoice-data-modal>
        <pdf-reader-modal v-bind:show="pdfReaderModal" v-bind:file="file" @close-modal="closePdfReaderModal"></pdf-reader-modal>
        <show-invoice-data-modal v-bind:productData="showProductData" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal"></show-invoice-data-modal>
    </div>
</template>

<script>
    import RejRefModal from "../components/RejRefModal";
    import swal from 'sweetalert2';
    import PdfReaderModal from "../components/PdfReaderModal";
    import UpdateInvoiceDataModal from "../components/UpdateInvoiceDataModal";
    import BackInvoiceDataModal from "../components/BackInvoiceDataModal";
    import ShowInvoiceDataModal from "../components/showInvoiceDataModal";
    import auth from '../auth.js'

    export default {
        components: {PdfReaderModal, RejRefModal, UpdateInvoiceDataModal, ShowInvoiceDataModal,BackInvoiceDataModal},
        name: "NotHavingInvoiceUserComponent",
        data: function () {
            return {
                data: {
                    users: {
                        user_contacts: null
                    },
                    products: {

                    }
                },
                acceptButton: false,
                showUpdatesInvoiceModal: false,
                showShowInvoiceModal: false,
                showShowBackInvoiceModal: false,
                productData2: null,
                showProductData: null,
                show: false,
                accountants: [],
                actType: '',
                id: null,
                pdfReaderModal: false,
                file: null,
                disabledButton: false
            }
        },
        mounted() {
            this.getInvoiceDetails();
            console.log(this.data)
        },
        methods: {
            nullToZero(val){
                if(val === null){
                    return 0
                }
                return val;
            },
            getInvoiceDetails() {
                axios.get('/cp/getInvoiceProducts/' + this.$route.params.id)
                    .then((data) => {
                        if(data) {
                            console.log(data.data.data)
                            this.data = data.data.data;
                            this.acceptButton = data.data.acceptButton;
                        } else {
                            this.data = [];
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    })
            },

            copyTestingCode () {
                let testingCodeToCopy = document.querySelector('#copy');
                testingCodeToCopy.setAttribute('type', 'text');
                testingCodeToCopy.select();

                try {
                    var successful = document.execCommand('copy');
                    var msg = successful ? 'successful' : 'unsuccessful';
                } catch (err) {
                }

                /* unselect the range */
                testingCodeToCopy.setAttribute('type', 'hidden');
                window.getSelection().removeAllRanges();
            },

            submitFile(e, productData) {
                let formData = new FormData();
                formData.append('file', e.target.files[0]);
                formData.append('invoiceId', productData.invoiceId);
                //console.log(formData);
                axios.post( '/admin/order/invoice-upload_new',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((data) => {
                    if (data.data.status === 200) {
                        this.getInvoiceDetails();
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə yükləndi',
                            showConfirmButton: true,
                            timer: 1500
                        });
                    }
                })
                    .catch(err => {
                        console.log('FAILURE!!');
                    });
            },

            changeFile(e, data)  {
                let formData = new FormData();
                formData.append('file', e.target.files[0]);
                formData.append('invoiceId', data.invoiceId);
                formData.append('transactionId', data.transactionId);

                axios.post( '/admin/order/invoice-upload/change',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((data) => {
                    if (data.data.status === 200) {
                        this.getInvoiceDetails();
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə dəyişdirildi',
                            showConfirmButton: true,
                            timer: 1500
                        });
                    }
                })
                    .catch(err => {
                        console.log('FAILURE!!');
                    });
            },

            rejection(id, actType) {
                this.show = true;
                this.actType = actType;
                this.id = id;
                axios.get('/admin/accountants')
                    .then((data) => {
                        this.accountants = data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    })

            },

            sendActs(data) {
                const url = this.actType === 'rejection' ? '/admin/rejection' : '/admin/refusal';
                axios.post(url, {
                    accountantId: data.accountantId,
                    note: data.note,
                    id: this.id
                }).then((data) => {
                    if (data.data.status === 200) {
                        this.show = false;
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə göndərildi',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            if (this.actType === 'rejection') {
                                this.getInvoiceDetails();
                            } else {
                                this.$router.push('/my-orders');
                            }
                        });
                    }
                });
            },

            ordered(id) {
                swal({
                    title: 'Sifariş verilməsi ilə bağlı sms və mail müştəriyə göndərilsin?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Bəli',
                    cancelButtonText: 'Xeyr'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/admin/order/ordered', {
                            id: id
                        }).then((data) => {
                            if (data.data.status === 200) {
                                this.getInvoiceDetails();
                                swal({
                                    type: 'success',
                                    title: 'Müvəffəqiyyətlə göndərildi',
                                    showConfirmButton: true,
                                });
                            }
                        })
                    }
                });
            },

            finishOrder(transactionId) {
                this.disabledButton = true;
                axios.post('/admin/order/finish', {
                    transactionId: transactionId
                }).then((data) => {
                    if (data.data.status === 200) {
                        swal({
                            type: 'success',
                            title: 'Tamamlandı',
                            showConfirmButton: true,
                        }).then(() => {
                            this.$router.push('/invoice-upload');
                        });
                    }
                }).catch(err => {
                    console.log(err);
                })
            },

            showUpdateInvoiceModal(productData) {
                this.showUpdatesInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                //data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.productData2 = data;
            },

            showBackInvoiceModal(productData) {
                this.showShowBackInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                //data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.productData2 = data;
            },

            updateInvoiceData (data) {
                axios.post('/admin/order/update/invoiceData', data)
                    .then((result) => {
                        if (result.data.status === 200) {
                            this.getInvoiceDetails();
                            this.showUpdatesInvoiceModal = false;
                            swal({
                                type: 'success',
                                title: result.data.message,
                                showConfirmButton: true,
                            });
                        } else {
                            alert(result.data.message);
                        }
                    }).catch(err => {
                    alert(err);
                });
            },
            backInvoiceData (data) {
                //alert("test");
                axios.post('/cp/orders/backInvoice', data)
                    .then((result) => {
                        if (result.data.status === 200) {
                            //this.getInvoiceDetails();
                            this.showShowBackInvoiceModal = false;
                            swal({
                                type: 'success',
                                title: result.data.message,
                                showConfirmButton: true,
                            });
                        } else {
                            alert(result.data.message);
                        }
                    }).catch(err => {
                    alert(err);
                });
            },

            showFile(file) {
                this.pdfReaderModal = true;



                this.file = file;
            },

            showInvoice(productData) {
                if(productData.package_id!==null) {
                    axios.get('/cp/orders/getProductsByPackage/' + productData.package_id)
                        .then((result) => {
                            if (result.data.status === 200) {
                                productData.links = result.data.data

                                this.showShowInvoiceModal = true;
                                var data = JSON.parse(JSON.stringify(productData));
                                console.log(data)
                                this.showProductData = data;
                            } else {
                                alert(result.data.message);
                            }
                        }).catch(err => {
                        alert(err);
                    });
                }else{
                    productData.links = null

                    this.showShowInvoiceModal = true;
                    var data = JSON.parse(JSON.stringify(productData));
                    this.showProductData = data;
                }
            },

            closeModal() {
                this.show = false;
            },

            closePdfReaderModal() {
                this.pdfReaderModal = false;
            },

            closeUpdateInvoiceModal() {
                this.showUpdatesInvoiceModal = false;
            },

            closeBackInvoiceModal() {
                this.showShowBackInvoiceModal = false;
            },

            closeShowInvoiceModal () {
                this.showShowInvoiceModal = false;
            },
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','buyer','problematic_department','operator'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .loading {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 26px;
        height: 26px;
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%);
    }

    button span {
        font-size: 13px;
    }

    button {
        padding: .295rem .46rem;
    }

    .user-data {
        margin: 25px 0;
    }

    .label {
        position: relative;
        top: 4px;
        padding: .326rem 0.75rem;
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
