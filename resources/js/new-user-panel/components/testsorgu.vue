<template>
    <div class="col-md-9 col-sm-11 col-xs-11 offer courier">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12">
                    <h3>{{ ExWindow && ExWindow.translator('panel-errors.requests') }}</h3>
                    <div class="form-list-offer">
                        <form @submit="submitForm">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="input-group">
                                        <label class="select-class">
                                            {{ ExWindow && ExWindow.translator('panel-errors.request_section') }} *
                                        </label>
                                        <v-select v-model="type" :options="types" v-on:change="getType" name="offer"
                                        ></v-select>
                                        <p style="color: red;" v-if="errors[0] !== null">{{errors[0]}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="input-group">
                                        <label class="select-class">{{ ExWindow &&
                                            ExWindow.translator('panel-errors.request_reason') }} *</label>
                                        <v-select v-model="reason" :options="reasons" v-on:change="getReason"
                                                  name="reason"
                                                  :placeholder="ExWindow && ExWindow.translator('panel-errors.request_reason')"></v-select>
                                        <p style="color: red;" v-if="errors[1] !== null">{{errors[1]}}</p>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <label for="file" class="invoice-upload btn-linkk">
                                        <span>{{ ExWindow && ExWindow.translator('panel-errors.file_upload') }}</span>
                                        <input v-on:change="handleFileUpload(1)" ref="file" type="file"
                                               class="form-control-file" id="file" name="invoice">
                                    </label>
                                </div>
                                <div class="col-xs-12">
                                    <div class="input-group border-radius">
                                        <textarea v-model="form.description" name="notice"
                                                  :placeholder="ExWindow && ExWindow.translator('panel-errors.note')"
                                                  class="form-control"
                                                  rows="4"></textarea>
                                        <p style="color: red;" v-if="errors[2] !== null">{{errors[2]}}</p>

                                    </div>
                                </div>
                                <div class="col-xs-12 button-part">
                                    <button type="submit" class="btn-effect">{{ ExWindow &&
                                        ExWindow.translator('link-order.send') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="block follow-order order-body col-xs-12">
                    <div class="right-content ">
                        <div class="quiz properties">
                            <h3>{{ ExWindow && ExWindow.translator('panel-errors.complaints') }}</h3>
                            <div class="quiz-inner col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="offer-search">
                                            <form action="...">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text"
                                                           :placeholder="ExWindow && ExWindow.translator('panel-errors.search')"
                                                           class="form-control"
                                                           id="email">
                                                    <span><i class="fa fa-search" aria-hidden="true"></i></span>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="offer-quiz">
                                            <!--:class="index === 0 ? 'active' : ''" -->
                                            <!--@click="getData(item.id)"-->

                                            <div v-if="waitData === true" class="quiz-left"
                                                 v-for="(item,index) in complaints"
                                                 v-on:click="setActive(index,item)"
                                                 :class="{ active:isActive(index) }"
                                            >
                                                <h5>{{customSplit(item.complaint_type_id,1)}}</h5>
                                                <span>{{customSplit(item.complaint_reason_id,2)}}</span>
                                                <span class="response" :class="item.seen === 0 ? 'notif' : ''"
                                                      v-if="item.status===1">{{ ExWindow && ExWindow.translator('panel-errors.answered') }}</span>
                                                <span class="waiting" :class="item.seen === 0 ? 'notif' : ''"
                                                      v-if="item.status!==1">{{ ExWindow && ExWindow.translator('panel-errors.pending') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-8" :class="showMobileMess === true ? 'right0' : 'right2'">
                                        <div class="row">
                                            <div class="chat-part col-xs-12">
                                                <div class="row right-border">
                                                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-6">
                                                        <div class="offer-upload">
                                                            <h5>{{ ExWindow &&
                                                                ExWindow.translator('panel-errors.upload_file') }}:</h5>
                                                            <form class="form">
                                                                <div class="file-upload-wrapper" data-text="">

                                                                    <input v-on:change="handleFileUpload(2)" type="file"
                                                                           id="file1" name="invoice1" ref="file1"
                                                                           class="file-upload-field">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-5 col-sm-5 col-xs-6 text-right">
                                                        <div class="offer-date">
                                                    <span><i class="fa fa-calendar"
                                                             aria-hidden="true"></i> {{ today }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="quiz-right">
                                                    <div class="back-left mobile-xs">
                                                        <span @click="showMobileMess = false"><i class="fa fa-angle-left"
                                                                 aria-hidden="true"></i>Geri</span>
                                                    </div>

                                                    <div class="chat">
                                                        <div class="messages"
                                                             :class="item.type === 0 ? 'user' : 'admin'"
                                                             v-for="item in messages">
                                                            <div class="message">
                                                                {{item.message}}
                                                            </div>
                                                            <div class="preview" v-if="item.file !== null">
                                                                <img style="width: 200px"
                                                                     v-bind:src="'/complaints/'+item.file">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--<div class="user messages">-->
                                                    <!--<div class="preview"></div>-->
                                                    <!--</div>-->
                                                </div>
                                                <div class="input-send">
                                                    <form @submit="sendMessage" id="test" ref="test">
                                                        <div class="icon-addon addon-lg">
                                                            <input v-model="messageform.message" type="text"
                                                                   class="form-control">
                                                            <button type="submit" class="transparent"><img
                                                                    src="/front/new/img/send-button.png" alt="">
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
    import vSelect from 'vue-select';
    import swal from "sweetalert2";

    export default {
        components: {vSelect},

        name: "Questions",

        mounted() {
            this.getComplaintTypes();
            this.getComplaints();
            this.ExWindow = window;
            this.types = [
                {id: 1, label: this.ExWindow && this.ExWindow.translator('home.customer_service')},
                {id: 2, label: this.ExWindow && this.ExWindow.translator('panel-errors.courier')},
                {id: 3, label: this.ExWindow && this.ExWindow.translator('panel-errors.order_service')},
                {id: 4, label: this.ExWindow && this.ExWindow.translator('panel-errors.cashier')},
                {id: 5, label: this.ExWindow && this.ExWindow.translator('home.depot')},
                {id: 6, label: this.ExWindow && this.ExWindow.translator('panel-errors.site_use')},
            ];
            this.reasons = [
                {id: 1, label: this.ExWindow && this.ExWindow.translator('panel-errors.delay')},
                {id: 2, label: this.ExWindow && this.ExWindow.translator('panel-errors.behaviour')},
                {id: 3, label: this.ExWindow && this.ExWindow.translator('panel-errors.cargo_damage')},
                {id: 4, label: this.ExWindow && this.ExWindow.translator('panel-errors.wrong_order')},
                {id: 5, label: this.ExWindow && this.ExWindow.translator('panel-errors.lost_package')},
                {id: 6, label: this.ExWindow && this.ExWindow.translator('panel-errors.other')},
            ];
            setInterval(function () {
                if (this.messageform.user_complaint_id !== '') {
                    this.getComplaints()
                    this.getData(this.messageform.user_complaint_id);
                }
            }.bind(this), 15000);
        },
        props: {},
        computed: {
            today() {
                let date = new Date();
                return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
            }
        },
        data: function () {
            return {
                ExWindow: null,
                type: '',
                reason: '',
                // complaint_reasons:[],
                // complaint_types:[],
                complaint_types_arr: [],
                complaint_reasons_arr: [],
                complaints: [],
                errors: [],
                messages: [],

                form: {
                    complaint_type: '',
                    complaint_reason: '',
                    description: '',
                    file: ''
                },
                messageform: {
                    message: '',
                    user_complaint_id: ''
                },
                types: [],
                reasons: [],
                waitData: false,
                activeItem: '',
                showMobileMess: false
            }
        },
        methods: {

            isActive: function (menuItem) {
                return this.activeItem === menuItem;
            },
            setActive: function (menuItem, item) {
                console.log(window.innerWidth);
                axios.get(`/user-panel/change-seen-status/${item.id}`)
                    .then(data => {
                        this.getComplaints();
                        this.getData(item.id);
                        if(window.innerWidth < 767)
                            this.showMobileMess = true
                    });
                this.activeItem = menuItem
            },
            sendMessage() {
                this.errors = [];
                event.preventDefault();
                if (!this.messageform.message) this.errors[3] = "Mesaj yazın.";
                else {
                    let formData = new FormData();
                    formData.append('file', this.form.file);
                    formData.append('message', this.messageform.message);
                    formData.append('user_complaint_id', this.messageform.user_complaint_id);

                    axios.post('/user-panel/send-message',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(data => {
                        this.getData(this.messageform.user_complaint_id);
                        this.getComplaints();
                        this.form.file = '';
                        this.messageform.message = '';
                    });
                }
            },
            getData(id) {
                this.messageform.user_complaint_id = id;
                axios.get(`/user-panel/get-complaint-messages/${id}`)
                    .then(data => {
                        this.messages = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getType() {
                this.form.complaint_type = this.type.id;
            },
            getReason() {
                this.form.complaint_reason = this.reason.id;
            },
            async getComplaints() {
                await axios.get('/user-panel/get-complaints')
                    .then(data => {
                        this.complaints = data.data.data;
                        // if (this.complaints !== null)
                        // this.getData(this.complaints[0].id)
                    })
                    .catch(err => {
                        console.log(err);
                    });

            },
            submitForm() {
                event.preventDefault()
                let formData = new FormData();
                formData.append('file', this.form.file);
                formData.append('complaint_type', this.form.complaint_type);
                formData.append('complaint_reason', this.form.complaint_reason);
                formData.append('description', this.form.description);
                if (this.checkForm() === 'true') {
                    swal.showLoading();

                    axios.post('/user-panel/add-complaint',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(data => {
                        swal({
                            type: "info",
                            text: this.ExWindow && this.ExWindow.translator('panel-errors.added')
                        }).then(() => {
                            this.getComplaints();
                            this.form.complaint_type = '';
                            this.form.complaint_reason = '';
                            this.form.description = '';
                            this.form.file = '';
                        })

                    });


                }
                event.target.reset();

            },
            checkForm() {
                this.errors = [];
                if (!this.form.complaint_type) this.errors[0] = this.ExWindow && this.ExWindow.translator('panel-errors.choose_section')
                if (!this.form.complaint_reason) this.errors[1] = this.ExWindow && this.ExWindow.translator('panel-errors.choose_section')
                if (!this.form.description) this.errors[2] = this.ExWindow && this.ExWindow.translator('panel-errors.write_desc')
                return (this.errors.length === 0 ? 'true' : 'false');
            },
            handleFileUpload(item) {
                if (item === 1)
                    this.form.file = this.$refs.file.files[0];
                else
                    this.form.file = this.$refs.file1.files[0];

            },
            async getComplaintTypes() {
                await axios.get('/user-panel/complaint-types')
                    .then(data => {
                        let complaint_types = data.data.data.complaint_types;
                        for (let i in complaint_types) {
                            if (this.complaint_types_arr.indexOf(complaint_types[i].id) < 0)
                                this.complaint_types_arr[complaint_types[i].id] = complaint_types[i].key;
                        }

                        let complaint_reasons = data.data.data.complaint_reasons;
                        for (let i in complaint_reasons) {
                            if (this.complaint_reasons_arr.indexOf(complaint_reasons[i].id) < 0)
                                this.complaint_reasons_arr[complaint_reasons[i].id] = complaint_reasons[i].key;
                        }

                        this.waitData = true;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            customSplit(key, type) {
                return (type == 1 ?
                        this.complaint_types_arr[key]
                        :
                        this.complaint_reasons_arr[key]
                )
            }
        },

    }
</script>
<style>
    .right0{
        right: 0 !important;
    }
    .quiz-left {
        cursor: pointer;
    }
</style>