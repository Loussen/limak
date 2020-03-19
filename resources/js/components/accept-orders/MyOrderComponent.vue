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
                                    <div class="calc-additional-price ">
                                        <button class="btn btn-info">Balans {{data.users.balance}}</button> <Br />
                                        <label> Alınacaq və ya geri qaytarılacaq məbləğ (TL - ilə): </label>
                                        <input type="number" class="form-control" placeholder="Misal: 100" @blur="calcAdditionalPrice($event)"/>
                                        <div style="font-weight: bold; margin-top: 5px;" id="result">{{addPriceResult}}</div>
                                        <textarea class="form-control" placeholder="Səbəb" v-model="addPriceDesc"  v-if="addPriceResult.length > 0">  </textarea>
                                        <button class="btn btn-danger" v-if="addPriceResult.length > 0" v-on:click="addBalance('minus')">Balansdan çıx</button>
                                        <button class="btn btn-info" v-if="addPriceResult.length > 0" v-on:click="addBalance('plus')">Balansa əlavə et</button>
                                    </div>
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
                        <button type="button" v-on:click="showInvoiceModal()" class="btn btn-outline-primary label">
                            <i class="icon-document-upload2"></i>
                            <span>Bəyənnamə yüklə</span>
                        </button>
                        <table class="table dtr-details" width="100%" v-if="data != null && data.length != 0">
                            <tr>
                                <th></th>

                                <th>Qiymət</th>
                                <th>Karqolu qiymət</th>
                                <th>Ödədiyimiz qiymət</th>
                                <th>Artiq qalan pul</th>
                                <th>Tip</th>
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
                                <td><input type="checkbox" v-model="product.selected" /> </td>

                                <td>{{product.price}} TL</td>
                                <td>{{(parseFloat(product.price) + parseFloat(product.extras.cargo_price ? product.extras.cargo_price : 0)).toFixed(2)}} TL</td>
                                <td><input style="width: 60px;" v-model="product.real_price" @change="setOverPrice(product)" ></td>
                                <td>{{ product.over_price }} </td>
                                <td>{{product.products_type_id ? product.products_type.name : product.product_type_name}}</td>
                                <td>{{product.quantity}}</td>
                                <td>{{product.shop_name}}</td>
                                <!--<td>{{product.extras.countries.translates[0].name}}</td>-->
                                <td><a :href="product.extras.link" target="_blank">Keçid</a></td>
                                <td>{{product.extras.brand}}</td>
                                <td>{{product.extras.color}}</td>
                                <td>{{product.extras.size}}</td>
                                <td>{{product.description}}</td>
                                <td>
                                    <div v-if="!(product.statuses.label === 'rejection' || product.statuses.label === 'rejection_accepted') && product.invoices.length === 0">
                                        <button v-on:click="rejection(product.id, 'rejection')" class="btn btn-outline-danger" type="button">
                                            <i class="icon-assignment_return"></i>
                                            <span>çatışmazlıq</span>
                                        </button>
                                    </div>

                                    <span v-if="product.statuses.label === 'rejection' || product.statuses.label === 'rejection_accepted'">
                                        Çatışmazlıq qeyd etmisiniz
                                    </span>

                                    <div v-if="product.invoices.length !== 0">
                                        <button class="btn btn-outline-primary" v-on:click="showFile(product)">
                                            <i class="icon-eye3"></i>
                                            <span>bax</span>
                                        </button>

                                        <button type="button" v-on:click="showUpdateInvoiceModal(product)" class="btn btn-outline-warning label">
                                            <i class="icon-document-text2"></i>
                                            <span>dəyiş</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>
            <div v-if="data !== null && data.statuses.label !== 'completed'" style="margin-top: 20px;" class="text-right">
                <button v-on:click="rejection(data.id, 'refusal')" class="btn btn-danger">
                    <i class="icon-delete"></i>
                    <span>İmtina</span>
                </button>

                <button v-on:click="ordered(data.id)" class="btn btn-success">
                    <i class="icon-delete"></i>
                    <span>Sifarişlər verildi</span>
                </button>

                <button v-on:disabled="disabledButton" v-on:click="finishOrder(data.id)" v-if="rejRefCompleteButton" class="btn btn-warning">
                    <i class="icon-check"></i>
                    <span>Çatışmazlıq ilə bitir</span>
                </button>
            </div>
        </div>
        <rej-ref-modal v-bind:accountants="accountants" v-bind:data="null" v-bind:show="show" @close-modal="closeModal" @send-rejection="sendActs($event)"></rej-ref-modal>
        <send-invoice-data-modal v-bind:productData="productData" v-bind:show="showSendInvoiceModal" @close-modal="closeInvoiceModal" @send-invoice-data="sendInvoiceData($event)"></send-invoice-data-modal>
        <update-invoice-data-modal v-bind:productData="productData2" v-bind:show="showUpdatesInvoiceModal" @close-modal="closeUpdateInvoiceModal" @update-invoice-data="updateInvoiceData($event)"></update-invoice-data-modal>
        <show-invoice-data-modal v-bind:productData="showProductData" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal"></show-invoice-data-modal>
    </div>
</template>

<script>
    import RejRefModal from "./components/RejRefModal";
    import swal from 'sweetalert2';
    import PdfReaderModal from "./components/PdfReaderModal";
    import SendInvoiceDataModal from "./components/SendInvoiceDataModal";
    import UpdateInvoiceDataModal from "./components/UpdateInvoiceDataModal";
    import ShowInvoiceDataModal from "./components/showInvoiceDataModal";

    export default {
        components: {
            ShowInvoiceDataModal,
            UpdateInvoiceDataModal,
            SendInvoiceDataModal,
            PdfReaderModal, RejRefModal},
        name: "MyOrderComponent",
        data: function () {
          return {
              data: null,
              productData: null,
              productData2: null,
              showProductData: null,
              orderedButton: false,
              rejRefCompleteButton: false,
              show: false,
              showSendInvoiceModal: false,
              showUpdatesInvoiceModal: false,
              showShowInvoiceModal: false,
              accountants: [],
              actType: '',
              id: null,
              pdfReaderModal: false,
              file: null,
              disabledButton: false,
              addPriceResult: '',
              addPrice: 0,
              addPriceDesc: '',
              selected_items: []
          }
        },
        mounted() {
            this.getOrderDetails();
        },
        methods: {
            getOrderDetails() {
                axios.get('/admin/orders/detail/by-admin/' + this.$route.params.id)
                    .then((data) => {
                        if(data.data.data) {
                            this.data = data.data.data;
                            this.orderedButton = data.data.acceptButton;
                            this.rejRefCompleteButton = data.data.rejRefCompleteButton;
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

            showInvoiceModal() {
                let selected_products = [];
                let productData = {
                    ids: [],
                    price: 0,
                    quantity: 0,
                    shop_name: ''
                };
                productData.ids = [];
                productData.peice = 0;
                for(let i in this.data.products){
                    if(this.data.products[i].selected === true){
                        if(i == 0){
                            productData = Object.assign(productData, this.data.products[i]);
                            productData.price = parseFloat(this.data.products[i].real_price === null ? 0: this.data.products[i].real_price);
                        }else{
                            productData.price += parseFloat(this.data.products[i].real_price === null ? 0: this.data.products[i].real_price);
                            productData.quantity += this.data.products[i].quantity;
                        }
                        productData.ids.push(this.data.products[i].id);

                        //if(parseFloat(this.data.products[i].extras.cargo_price)>0) productData.price += this.data.products[i].price;
                        selected_products.push(this.data.products[i]);
                    }
                }
                this.showSendInvoiceModal = true;

                this.productData = JSON.parse(JSON.stringify(productData));
            },

            showUpdateInvoiceModal(productData) {
                this.showUpdatesInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.productData2 = data;
            },

            sendInvoiceData (data) {
                axios.post('/admin/order/send/invoiceData', data)
                    .then((result) => {
                        if (result.data.status === 200) {
                            this.getOrderDetails();
                            this.showSendInvoiceModal = false;
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

            showFile(productData) {
                this.showShowInvoiceModal = true;
                var data = JSON.parse(JSON.stringify(productData));

                data.invoices[0].order_date = data.invoices[0].order_date.split(' ')[0];

                this.showProductData = data;
            },

            calcAdditionalPrice (event) {
                var value = event.target.value;
                if (value) {
                    axios.post('/admin/calc-additional-price', {
                        additionalPrice: value
                    }).then((result) => {
                        this.addPriceResult = result.data.withTl + ' TL və ya ' + result.data.withAzn + ' AZN';
                        this.addPrice = result.data.withAzn ;
                    }).catch(err => {
                        alert('Server xətası');
                    });
                } else {
                    this.addPriceResult = '';
                }
            },

            saveRealPrice(product) {

                let data = {
                    product_id: product.id,
                    real_price: product.real_price,
                    over_price: product.over_price
                };
                axios.post('/admin/product/saveRealPrice', data).then((result) => {

                }).catch(err => {
                    alert('Server xətası');
                });
            },

            addBalance(type) {
                let data = {};
                data.user_id = this.data.user_id;
                data.type = type;
                data.money = this.addPrice;
                data.rel_product_id = this.id;
                data.desc = this.addPriceDesc;

                axios.post('/admin/addBalance', data).then((result) => {
                    if(!result.data.success) alert(result.data.message);
                    if(result.data.success) alert('Balans yeniləndi');
                }).catch(err => {
                    alert('Server xətası');
                });
            },

            closeModal() {
                this.show = false;
            },

            closeInvoiceModal() {
                this.showSendInvoiceModal = false;
            },

            closeUpdateInvoiceModal() {
                this.showUpdatesInvoiceModal = false;
            },

            closeShowInvoiceModal () {
                this.showShowInvoiceModal = false;
            },

            closePdfReaderModal() {
                this.pdfReaderModal = false;
            },

            setOverPrice(product){
                product.over_price = ((parseFloat(product.price) + parseFloat(product.extras.cargo_price ? product.extras.cargo_price : 0)).toFixed(2) - parseFloat(product.real_price)).toFixed(2);
                this.saveRealPrice(product);
            }
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
        top: 0;
        padding: .326rem 0.75rem;
    }

    .calc-additional-price {
        position: absolute;
        right: 0;
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
