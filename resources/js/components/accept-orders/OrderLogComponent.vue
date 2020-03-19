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
                                </div>
                            </div>
                        </div>
                        <div v-if="data == null" class="loading"></div>
                        <div v-if="data != null && data.length == 0" class="no-data">Məlumat yoxdur</div>
                        <table class="table dtr-details" width="100%" v-if="data != null && data.length != 0">
                            <tr>
                                <th>Tip</th>
                                <th>Qiymət</th>
                                <th>Kargo qiyməti</th>
                                <th>Say</th>
                                <th>Mağaza</th>
                                <!--<th>Ölkə</th>-->
                                <th>Link</th>
                                <th>Brend</th>
                                <th>Rəng</th>
                                <th>Ölçü</th>
                                <th>Qeyd</th>
                                <th></th>
                            </tr>
                            <tr v-for="product in data.products">
                                <td>{{product.products_type_id ? product.products_type.name : product.product_type_name}}</td>
                                <td>{{product.price}}</td>
                                <td><span v-if="product.extras">{{product.extras.cargo_price}}</span></td>
                                <td>{{product.quantity}}</td>
                                <td>{{product.shop_name}}</td>
                                <!--<td>{{product.extras.countries.translates[0].name}}</td>-->
                                <td><span v-if="product.extras"><a :href="product.extras.link" target="_blank">Keçid</a></span></td>
                                <td><span v-if="product.extras">{{product.extras.brand}}</span></td>
                                <td><span v-if="product.extras">{{product.extras.color}}</span></td>
                                <td><span v-if="product.extras">{{product.extras.size}}</span></td>
                                <td>{{product.description}}</td>
                                <td>
                                    <span v-if="product.statuses.label === 'rejection' || product.statuses.label === 'rejection_accepted'">
                                        Çatışmazlıq qeyd etmisiniz
                                    </span>

                                    <div v-if="product.invoices.length !== 0">
                                        <button class="btn btn-outline-primary" v-on:click="showFile(product.invoices[0].file)">
                                            <i class="icon-eye3"></i>
                                            <span>bax</span>
                                        </button>
                                        <button class="btn btn-outline-primary" v-on:click="showInvoice(product)">
                                            <i class="icon-eye3"></i>
                                            <span>Bəyənnaməyə bax</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <pdf-reader-modal v-bind:show="pdfReaderModal" v-bind:file="file" @close-modal="closePdfReaderModal"></pdf-reader-modal>
        <show-invoice-data-modal v-bind:productData="showProductData" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal"></show-invoice-data-modal>
    </div>
</template>

<script>
    import RejRefModal from "./components/RejRefModal";
    import swal from 'sweetalert2';
    import PdfReaderModal from "./components/PdfReaderModal";
    import ShowInvoiceDataModal from "./components/showInvoiceDataModal";

    export default {
        components: {PdfReaderModal, ShowInvoiceDataModal},
        name: "MyOrderComponent",
        data: function () {
          return {
              data: null,
              show: false,
              showShowInvoiceModal: false,
              showProductData: null,
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
                axios.get('/admin/orders/detail/log/by-admin/' + this.$route.params.id)
                    .then((data) => {
                        if(data.data.data) {
                            this.data = data.data.data;
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
                            this.$router.push('/my-orders');
                        });
                    }
                }).catch(err => {
                    console.log(err);
                })
            },

            showInvoice(productData) {
                this.showShowInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.showProductData = data;
            },

            showFile(file) {
                this.pdfReaderModal = true;

                console.log(file);

                this.file = file;
            },

            closeModal() {
                this.show = false;
            },

            closePdfReaderModal() {
                this.pdfReaderModal = false;
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
