<template>
    <div class="col-md-9 col-sm-11 col-xs-11 offer courier">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12">
                    <a class="btn btn-default">Baglamalar</a>
                    <a class="btn btn-default">Baglamalar</a>
                    <a class="btn btn-default">Baglamalar</a>
                    <a class="btn btn-default">Baglamalar</a>
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

        name: "Chat",

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