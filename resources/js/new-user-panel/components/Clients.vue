<template>
    <div class="col-md-9 col-sm-11 col-xs-11 coin clients">
        <div class="row">
            <div class="col-xs-12 basket">
                <div class="order-body">
                    <div class="block">
                        <div class="coin-table">
                            <div class="block-head">
                                <h3>{{ExWindow ? ExWindow.translator('panel-errors.corporate_clients') : ''}}
                                        <div class="right-side">
                                            <button type="button" class="btn btn-success" data-target="#edit_data" data-toggle="modal" @click="showEditModal(false)">
                                                {{ExWindow ? ExWindow.translator('panel-errors.add_client'): ''}}
                                            </button>
                                        </div>
                                </h3>
                            </div>

                            <table class="table table-bordered web-xs">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_name'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_email'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_phone'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_serial'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_pin'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_address'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_last30'): ''}}</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="client in clients" >
                                    <td>{{idToUniq(client.client_id) }} {{client.name}} {{client.surname}}</td>
                                    <td>{{client.email }}</td>
                                    <td>{{client.phone}}</td>
                                    <td>{{client.serial_number }}</td>
                                    <td>{{client.pin}}</td>
                                    <td>{{client.address }}</td>
                                    <td>{{client.last30 }}$</td>
                                    <td>
                                        <button type="button" data-target="#edit_data" data-toggle="modal"  @click="showEditModal(client)"><i
                                            class="fa fa-pencil"></i></button>
                                        <button type="button" data-target="#foreign_addresses" data-toggle="modal"  @click="showEditModal(client)"><i
                                                class="fa fa-eye"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered mobile-xs" v-for="client in clients">
                                <thead>
                                <tr>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_name'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_email'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_phone'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_serial'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_pin'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_address'): ''}}</th>
                                    <th>{{ExWindow ? ExWindow.translator('panel-errors.client_last30'): ''}}</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{client.name}} {{client.surname}}</td>
                                    <td>{{client.email }}</td>
                                    <td>{{client.phone}}</td>
                                    <td>{{client.serial_number }}</td>
                                    <td>{{client.pin}}</td>
                                    <td>{{client.address }}</td>
                                    <td>{{client.last30 }}$</td>
                                    <td>
                                        <button type="button" data-target="#edit_data" data-toggle="modal"  @click="showEditModal(client)"><i
                                                class="fa fa-pencil"></i></button>
                                        <button type="button" data-target="#foreign_addresses" data-toggle="modal"  @click="showEditModal(client)"><i
                                                class="fa fa-eye"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="foreign_addresses" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>{{ExWindow ? ExWindow.translator('panel-errors.foreign_addresses'): ''}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">
                                            <img src="/front/new/img/close-menu.png" alt="close">
                                        </span>
                                        </button>
                                    </h3>

                                </div>
                                <div class="modal-body order-body address">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="block">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active">
                                                        <a href="#turkey" aria-controls="home" role="tab" data-toggle="tab" class="order-img1">{{ExWindow ? ExWindow.translator('common.turkey') : ''}}</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#usa" aria-controls="home" role="tab" data-toggle="tab" class="order-img2">{{ExWindow ? ExWindow.translator('common.usa') : ''}}</a>
                                                    </li>
                                                    <!--<li role="presentation">
                                                        <a href="#2" aria-controls="profile" role="tab" data-toggle="tab" class="order-img2">Amerika`dan</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#3" aria-controls="messages" role="tab" data-toggle="tab" class="order-img3">Rusiya`dan</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#4" aria-controls="settings" role="tab" data-toggle="tab" class="order-img4">Almaniya`dan</a>
                                                    </li>
                                                    <li class="last" role="presentation">
                                                        <a href="#5" aria-controls="settings" role="tab" data-toggle="tab" class="order-img5">Dubay`dan</a>
                                                    </li>-->
                                                </ul>
                                                <div class="tab-content" v-if="form">
                                                    <div role="tabpanel" class="tab-pane fade in active" id="turkey">
                                                        <ul>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('common.name_surname') : ''}}</b>
                                                                <p>LİMAK İTHALAT VE İHRACAT LİMİTED şirketi</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.address_title') : ''}}</b>
                                                                <p>LİMAK</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.address_line1') : ''}}</b>
                                                                <p>Halkalı Merkez Mahellesi1. Tuna caddesi. Üzümlü SK. 5/7 kod: {{idToUniq(form.client_id)}} {{form.name}} {{form.surname}}</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.district_city') : ''}}</b>
                                                                <p>Istanbul</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.ilche') : ''}}</b>
                                                                <p>Küçükçekmece</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.tax_center') : ''}}</b>
                                                                <p>Halkalı</p>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.district') : ''}}</b>
                                                                <p>Halkalı</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.zip') : ''}}</b>
                                                                <p>34303</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.country') : ''}}</b>
                                                                <p>Türkiye</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.tr_passport') : ''}}</b>
                                                                <p>35650276048</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.mobile') : ''}}</b>
                                                                <p>5323575535</p>
                                                            </li>
                                                            <li>
                                                                <b>{{ExWindow ? ExWindow.translator('link-order.tax_num') : ''}}</b>
                                                                <p>6081089593</p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane" id="usa">
                                                        <ul>
                                                            <li>
                                                                <b>Name</b>
                                                                <p>AZ {{idToUniq(form.client_id)}}{{form.name}} {{form.surname}}</p>
                                                            </li>
                                                            <li>
                                                                <b>Address Line</b>
                                                                <p>1200 Interchange Blvd</p>
                                                            </li>
                                                            <li>
                                                                <b>Address Line2</b>
                                                                <p>Suite#AZ{{idToUniq(form.client_id)}} </p>
                                                            </li>
                                                            <li>
                                                                <b>State</b>
                                                                <p>DE</p>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <b>City</b>
                                                                <p>Newark</p>
                                                            </li>
                                                            <li>
                                                                <b>ZIP/Postal code</b>
                                                                <p>19711</p>
                                                            </li>
                                                            <li>
                                                                <b>Country</b>
                                                                <p>United States</p>
                                                            </li>
                                                            <li>
                                                                <b>Phone Number</b>
                                                                <p>800-4315119</p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="edit_data" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>
                                        <span v-if="form.client_id>0">{{ExWindow ? ExWindow.translator('panel-errors.edit_client'): ''}}</span>
                                        <span v-else>{{ExWindow ? ExWindow.translator('panel-errors.add_client'): ''}}</span>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">
                                            <img src="/front/new/img/close-menu.png" alt="close">
                                        </span>
                                        </button>
                                    </h3>

                                </div>
                                <div class="modal-body">
                                    <div v-if="error_message!=null">
                                        <p v-for="message in error_message"><b>{{message}}</b></p>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="name" class="form-control inputText"
                                                   v-model="form.name" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.client_firstname'): ''}}</span>
                                        </label>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="surname" class="form-control inputText"
                                                   v-model="form.surname" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.client_surname'): ''}}</span>
                                        </label>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="email" class="form-control inputText"
                                                   v-model="form.email" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.client_email'): ''}}</span>
                                        </label>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="phone" class="form-control inputText"
                                                   v-model="form.phone" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.client_phone'): ''}}</span>
                                        </label>
                                    </div>



                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="phone" class="form-control inputText"
                                                   v-model="form.serial_number" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.client_serial'): ''}}</span>
                                        </label>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="pin" class="form-control inputText"
                                                   v-model="form.pin" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.client_pin'): ''}}</span>
                                        </label>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="phone" class="form-control inputText"
                                                   v-model="form.address" placeholder=" ">
                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.address'): ''}}</span>
                                        </label>
                                    </div>


                                    <div class="modal-button text-right">
                                        <button @click="sendEditedClient()" class="btn-effect">{{ExWindow ? ExWindow.translator('panel-errors.save2'): ''}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>




</template>

<script>
    import last30days from './shared/last30days';
    import currency from './shared/currency';
    import dailyCurrency from './shared/dailyCurrency';
    import vSelect from 'vue-select'
    export default {
        name: "Clients",
        mounted(){
            this.ExWindow = window;
            this.getUserData();
            this.getClients()
        },
        data: function (){
            return {
                User: Object,
                ExWindow: null,
                currencies: [],
                Invoices: [],
                profitData: {},
                expenseData: {},
                logData: {},
                filter_type: {
                    id: 0,
                    name: ''
                },
                balances: [],
                clients: [],
                form: {
                    name: '',
                    surname: '',
                    serial_number: '',
                    pin: '',
                    email: '',
                    phone: '',
                    address: '',
                    uniqid: '',
                    client_id: 0,
                },
                error_message: null,
            }
        },
        methods: {
            showEditModal(commonData) {
                this.form.name = (commonData.name) ? commonData.name : '' ;
                this.form.surname = (commonData.surname) ? commonData.surname : '' ;
                this.form.serial_number = (commonData.serial_number) ? commonData.serial_number : '' ;
                this.form.pin = (commonData.pin) ? commonData.pin : '' ;
                this.form.email = (commonData.email) ? commonData.email : '' ;
                this.form.phone = (commonData.phone) ? commonData.phone : '' ;
                this.form.address = (commonData.address) ? commonData.address : '' ;
                this.form.uniqid = (commonData.uniqid) ? commonData.uniqid : '' ;
                this.form.client_id = (commonData.client_id) ? commonData.client_id : 0;
            },
            idToUniq(id){
                id = id.toString();
                var new_id = id.padStart(6, 0);
                return 1+new_id;
            },

            sendEditedClient() {
                if(this.form.client_id>0){
                    var path = '/panel/edit-client/';
                }else{
                    var path = '/panel/add-client/';
                }
                axios({url: path, method: 'POST', data: this.form}).then(data => {
                    this.error_message = data.data.message;
                    if(data.data.code==200){
                        /*this.showEditModal(false);
                        this.getClients();*/
                        location.reload();
                    }
                })
            },
            async getUserData() {
                const test  = await this.requestUserData();
                this.User = test.data;
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';
                return  axios.get(path);

            },

            getClients(){
                var path = '/panel/clients';
                return  axios.get(path).then((response) => {
                    if(response.data.code==200){
                        this.clients = response.data.data;
                    }else{
                        this.$router.push("/")
                    }
                }).catch((e) => {
                    console.log(e);
                });
            },


        },
        components: {
            last30days,
            currency,
            dailyCurrency,
            vSelect
        }
    }
</script>

<style scoped>

</style>