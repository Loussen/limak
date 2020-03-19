<template>
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box text-left">
                            <div class="box-header">
                                <h3 class="box-title text-center">
                                    <strong>Məhsul - ətraflı</strong>
                                </h3>
                                <div v-if="data !== null" class="user-data">
                                    <div class="user-data-name-surname">
                                        <label> Ad, Soyad: </label>
                                        <h4>
                                            {{data.users.name}} {{data.users.surname}}
                                        </h4>
                                    </div>
                                    <div class="user-data-email">
                                        <label> Email: </label>
                                        <h4>
                                            {{data.users.email}}
                                        </h4>
                                    </div>
                                    <div class="user-data-email" v-if="data.users.user_contacts">
                                        <label> Nömrələr: </label>
                                        <h4>
                                            <span style="display: block; margin-bottom: 10px;" v-for="userContact of data.users.user_contacts">
                                                {{userContact.name}}
                                            </span>
                                        </h4>
                                    </div>
                                    <div class="user-data-email" v-if="data.users.user_contacts">
                                        <label> Ünvan: - <span style="color: red; font-weight: bold">Məhsulu alan zaman ünvanı kopyalamağı unutmayın!</span> </label>
                                        <h4>
                                            Halkalı Merkez Mahellesi. Üzümlü SK. 5/7 kod: {{data.users.uniqid}} {{data.users.name}} {{data.users.surname}} <button style="margin-left: 10px;" class="btn btn-xs btn-primary" @click.stop.prevent="copyTestingCode">Kopyala</button>
                                            <input type="hidden" id="copy" :value="'Halkalı Merkez Mahellesi. Üzümlü SK. 5/7 kod: ' + data.users.uniqid + ' ' + data.users.name + ' ' + data.users.surname">
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="data == null" class="loading"></div>
                        <div v-if="data != null && data.length == 0" class="no-data">Məlumat yoxdur</div>
                        <table class="table dtr-details" width="100%" v-if="data != null && data.length != 0">
                            <tr>
                                <th>Tip</th>
                                <th>Ümumi qiyməti</th>
                                <th>Kargo qiyməti</th>
                                <th>Məhsul qiyməti</th>
                                <th>Say</th>
                                <th>Mağaza</th>
                                <!--<th>Ölkə</th>-->
                                <th>Brend</th>
                                <th>Rəng</th>
                                <th>Ölçü</th>
                                <th>Qeyd</th>
                                <th>Link</th>
                                <th></th>
                            </tr>
                            <tr v-for="product in data.products">
                                <td>{{product.products_type_id ? product.products_type.name : product.product_type_name}}</td>
                                <td>{{product.price}}</td>
                                <td>{{product.cargo_price}}</td>
                                <td>{{product.original_price }}</td>
                                <td>{{product.quantity}}</td>
                                <td>{{product.shop_name}}</td>
                                <!--<td>{{product.extras.countries.translates[0].name}}</td>-->
                                <td>{{product.extras.brand}}</td>
                                <td>{{product.extras.color}}</td>
                                <td>{{product.extras.size}}</td>
                                <td>{{product.description}}</td>
                                <td>
                                    <a :href="product.extras.link" target="_blank">Keçid</a> <br />
                                    <input type="text" v-model="product.extras.link"  v-on:blur="changeText(product.extras)" name="link_text"/>
                                </td>
                                <td>
                                   <!-- <div v-if="!(product.statuses.label === 'rejection' || product.statuses.label === 'rejection_accepted') && product.invoices[0] && product.invoices[0].file === null">-->
                                    <div>
                                        <div v-if="!(product.statuses.label === 'rejection' || product.statuses.label === 'rejection_accepted') && product.invoices[0] && product.invoices[0].file === null">
                                            <label class="btn btn-outline-primary label">
                                                <i class="icon-document-upload2"></i>
                                                <span>yüklə</span>
                                                <input type="file" style="display: none;" v-on:change="submitFile($event, {productId: product.id, transactionId: data.id})"/>
                                            </label>
                                        </div>
                                        <button class="btn btn-outline-primary" v-on:click="showInvoice(product)">
                                            <i class="icon-eye3"></i>
                                            <span>Bəyənnaməyə bax</span>
                                        </button>
                                        <button style="top: 0;" type="button" v-on:click="showUpdateInvoiceModal(product)" class="btn btn-outline-warning label">
                                            <i class="icon-document-text2"></i>
                                            <span>Bəyənnaməni dəyiş</span>
                                        </button>
                                    </div>

                                    <div v-if="product.invoices[0] && product.invoices[0].file !== null">
                                        <button class="btn btn-outline-primary" v-on:click="showFile(product.invoices[0].file)">
                                            <i class="icon-eye3"></i>
                                            <span>invoysa bax</span>
                                        </button>

                                        <label class="btn btn-outline-warning label">
                                            <i class="icon-document-text2"></i>
                                            <span>invoysu dəyiş</span>
                                            <input type="file" style="display: none;" v-on:change="changeFile($event, {invoiceId: product.invoices[0].id, transactionId: data.id})"/>
                                        </label>
                                    </div>

                                    <span v-if="product.statuses.label === 'rejection' || product.statuses.label === 'rejection_accepted'">
                                        Çatışmazlıq qeyd etmisiniz
                                    </span>

                                    <!--<div v-if="product.invoices[0] && product.invoices[0].file !== null">
                                        <button class="btn btn-outline-primary" v-on:click="showFile(product.invoices[0].file)">
                                            <i class="icon-eye3"></i>
                                            <span>bax</span>
                                        </button>

                                        <label class="btn btn-outline-warning label">
                                            <i class="icon-document-text2"></i>
                                            <span>dəyiş</span>
                                            <input type="file" style="display: none;" v-on:change="changeFile($event, {invoiceId: product.invoices[0].id, transactionId: data.id})"/>
                                        </label>
                                    </div>-->
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>
            <div v-if="data !== null && data.statuses.label !== 'completed'" style="margin-top: 20px;" class="text-right">
                <button v-if="!data.is_ordered" v-on:click="ordered(data.id)" class="btn btn-warning">
                    <i class="icon-delete"></i>
                    <span>Sifarişlər verildi</span>
                </button>

                <button v-on:disabled="disabledButton" v-on:click="finishOrder(data.id)" v-if="acceptButton" class="btn btn-success">
                    <i class="icon-check"></i>
                    <span>Təsdiq et</span>
                </button>
            </div>
        </div>
        <rej-ref-modal v-bind:accountants="accountants" v-bind:data="null" v-bind:show="show" @close-modal="closeModal" @send-rejection="sendActs($event)"></rej-ref-modal>
        <update-invoice-data-modal v-bind:productData="productData2" v-bind:show="showUpdatesInvoiceModal" @close-modal="closeUpdateInvoiceModal" @update-invoice-data="updateInvoiceData($event)"></update-invoice-data-modal>
        <pdf-reader-modal v-bind:show="pdfReaderModal" v-bind:file="file" @close-modal="closePdfReaderModal"></pdf-reader-modal>
        <show-invoice-data-modal v-bind:productData="showProductData" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal"></show-invoice-data-modal>
    </div>
</template>

<script>
    import RejRefModal from "./components/RejRefModal";
    import swal from 'sweetalert2';
    import PdfReaderModal from "./components/PdfReaderModal";
    import UpdateInvoiceDataModal from "./components/UpdateInvoiceDataModal";
    import ShowInvoiceDataModal from "./components/showInvoiceDataModal";

    export default {
        components: {PdfReaderModal, RejRefModal, UpdateInvoiceDataModal, ShowInvoiceDataModal},
        name: "MyOrderComponent",
        data: function () {
          return {
              data: null,
              acceptButton: false,
              showUpdatesInvoiceModal: false,
              showShowInvoiceModal: false,
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
            this.getOrderDetails();
        },
        methods: {
            getOrderDetails() {
                axios.get('/admin/will-upload-invoice/detail/by-admin/' + this.$route.params.id)
                    .then((data) => {
                        if(data.data.data) {
                            this.data = data.data.data;
                            this.acceptButton = data.data.acceptButton;
                            this.data.products.map((product) => {
                                product.cargo_price = product.extras.cargo_price === null ? 0 : product.extras.cargo_price;
                                product.original_price = product.extras.cargo_price === null ? product.price : parseInt(product.price) - parseInt(product.extras.cargo_price)
                            })

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
                formData.append('productId', productData.productId);
                formData.append('transactionId', productData.transactionId);

                axios.post( '/admin/order/invoice-upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((data) => {
                    if (data.data.status === 200) {
                        this.getOrderDetails();
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


            changeText(extras) {
                let url = '/admin/changeExtrasLink';
                axios.post(url, {
                    id: extras.id,
                    link: extras.link,
                }).then((data) => {
                    if (data.data.status === 200) {
                        this.getOrderDetails();
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə dəyişdirildi',
                            showConfirmButton: true,
                            timer: 1500
                        });
                    }
                });
            },

            changeFile(e, data) {
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
                        this.getOrderDetails();
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
                                this.getOrderDetails();
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
                                this.getOrderDetails();
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

                data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.productData2 = data;
            },

            updateInvoiceData (data) {
                axios.post('/admin/order/update/invoiceData', data)
                    .then((result) => {
                        if (result.data.status === 200) {
                            this.getOrderDetails();
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

            showFile(file) {
                this.pdfReaderModal = true;

                console.log(file);

                this.file = file;
            },

            showInvoice(productData) {
                console.log(productData);
                this.showShowInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.showProductData = data;
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

            closeShowInvoiceModal () {
                this.showShowInvoiceModal = false;
            },
        }
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
