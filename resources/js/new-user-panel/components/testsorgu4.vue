<template>
    <div class="col-md-9 col-sm-11 col-xs-11 offer courier">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12 questions-request" id="assistant-manager">
                    <h3>{{ ExWindow && ExWindow.translator('panel-errors.requests') }}</h3>
                    <div class="answer-questions" v-for="n in k" >
                        <div class="questions">
                            {{n}}
                            <div class="question-bubble-title" v-if="existTitle[n] === true">{{questions[n][0].title_name}}</div>
                            <div>
                                <span class="question-bubble"
                                      v-for="(item,index) in questions[n-1]"
                                      v-on:click="setActiveQuestions(index,item)"
                                      :class="{ active:isActiveQuestion(index) }"
                                >{{item.value}}</span>
                            </div>


                            <div v-if="n < maxStep && existChild[n] > 0 || n==1">
                                <div>
                                    <span class="nope" v-on:click="getDefaultQuestions(n+1,0,true)"><span style="font-size: 15px; margin-right: 5px;">&#128527;</span> Bashqa sual vermek isteyirsiniz?</span>
                                </div>
                            </div>

                        </div>

                        <div>

                            <div>
                                <div class="question" v-if="selectedQuestion[n-1]">
                                    <span class="selected-question">
                                        {{selectedQuestion[n-1]}}
                                    </span>
                                </div>

                                <div class="answer" v-if="selectedAnswer[n]">
                                    <span class="selected-answer" v-html="selectedAnswer[n]"></span>
                                </div>

                                <div class="accept" v-if="selectedAnswer[n] && showAcceptArr[n]">
                                    <div class="accept-question">
                                        <span>
                                            Cavab sizi qane etdi?
                                        </span>
                                    </div>
                                    <div class="accept-check">
                                        <span class="yes" v-on:click="getDefaultQuestions(n+1,true)"><span style="font-size: 15px; margin-right: 5px;">&#128515;</span> Bəli</span>
                                        <span class="no" v-on:click="getDefaultQuestions(n+1,false)"><span style="font-size: 15px; margin-right: 5px;">&#128543;</span> Xeyr</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="alert alert-success" role="alert" v-if="success">Sorguda ishtirak etdiyiniz uchun teshekkurler!</div>
                    <div class="alert alert-warning" role="alert" v-if="failed">Diger suallar uchun sorgu gondere bilersiniz</div>
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
            this.initialize();
            this.getComplaintTypes();
            this.getComplaints();
            this.getMaxStep();
            this.getDefaultQuestions(1,0);
            this.setShowAccept();
            this.setShowNope();
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
                questions: [],
                existTitle: [],
                question: [],
                answers: [],
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
                activeItem: [],
                activeItemQuestion: [],
                showMobileMess: false,
                showQuestionSelf: false,
                // showAnswer: false,
                selectedQuestion: [],
                selectedAnswer: [],
                existChild: [],
                lang: null,
                k: 1,
                maxStep: 1,
                showAcceptArr: [],
                nope: [],
                success: false,
                failed: false
            }
        },
        methods: {
            initialize() {
                this.lang = window.translator;
            },
            isActiveQuestion: function (menuItem) {
                return this.activeItemQuestion[this.k] === menuItem;
            },
            isActive: function (menuItem, step) {
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
            setActiveQuestions: function (menuItem, item) {
                if(this.showAcceptArr[this.k] || 1==1)
                {
                    console.log("Active Questions");
                    console.log(this.showAcceptArr);
                    axios.post(`/user-panel/get-question/`, {lang: default_locale, step: this.k, id: item.id})
                        .then(data => {
                            console.log("1 k = "+this.k);
                            this.question = data.data.data;
                            let checkChild = data.data.checkChild;

                            let stepChild = 1;
                            this.$set(this.selectedQuestion, this.k,item.value);
                            ++this.k;

                            if(checkChild && checkChild.id > 0)
                            {
                                console.log("Parentssssss");

                                if(this.k > 1)
                                    stepChild = this.k-1;
                                else
                                {
                                    stepChild = this.k;
                                    // this.k++;
                                }


                                console.log("2 k = "+this.k);
                            }
                            else
                            {
                                this.$set(this.selectedAnswer, this.k,item.answer);
                                this.$set(this.nope, this.k,false);
                            }


                            this.getDefaultQuestions(1,item.id);



                            let objDiv = document.getElementById("assistant-manager");
                            objDiv.scrollTop = objDiv.scrollHeight + 500;

                        });
                    // this.activeItemQuestion[step] = menuItem;
                }
            },
            showAccept: function(step) {
                this.$set(this.showAcceptArr, step,false);
                this.$set(this.showAcceptArr, step+1,true);
            },
            showNope: function(step) {
                this.$set(this.nope, step,false);
                this.$set(this.nope, step+1,true);
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
            },
            async getDefaultQuestions(step,p_id=0,otherQuestions=false) {

                if(/*this.k <= this.maxStep &&*/ this.success === false && this.failed === false || 1==1)
                {
                    await axios.post('/user-panel/get-questions/', {lang: default_locale, step: step, p_id: p_id})
                        .then(data => {
                            console.log("Stepppp"+step);
                            console.log(this.existChild);

                            if(otherQuestions === true)
                            {
                                this.$set(this.selectedQuestion, this.k,'Diger');
                                this.questions[this.k+1] = data.data.data;
                                this.$set(this.existChild, this.k-1,this.k-1);
                            }
                            else
                            {
                                this.questions[this.k] = data.data.data;
                            }

                            // console.log(this.questions[this.k][0].title.name+"qqqq");

                            this.existTitle[this.k] = false;
                            if(this.questions[this.k][0].title_name)
                            {
                                this.existTitle[this.k] = true;
                            }

                            console.log(this.existChild);
                            console.log(this.questions[this.k][0].title_name+this.k+"sasa");
                            console.log(this.questions[this.k][1].title_name+this.k+"sasaqq");

                            this.k++;

                            this.showAccept(step-1);
                            this.showNope(step-1);
                            console.log("Default Questions2");
                            console.log("First k = "+this.k);
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
                else if(this.success === true)
                {
                    this.showAccept(this.k);

                    console.log("Success");
                }
                else
                {
                    this.failed = true;

                    this.showAccept(this.k);
                    console.log("Redirect request form ...............");
                }
            },
            async setShowAccept() {
                this.$set(this.showAcceptArr, this.k,true);
            },
            async setShowNope() {
                this.$set(this.nope, this.k,true);
            },
            async getMaxStep() {
                await axios.get('/user-panel/get-max-step/')
                    .then(data => {
                        this.maxStep = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
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
    .question-bubble-title
    {
        cursor: initial !important;
    }
    .question-bubble, .question-bubble-title{
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 10px 30px;
        margin-right: 10px;
        background-color: #efefef;
        cursor: pointer;
        display: inline-block;
        margin-bottom: 10px;
    }
    .question-bubble.active
    {
        border: 1px solid #F95631;
        color: #F95631;
    }
    .selected-question{
        float: right;
        margin-top: 30px;
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 10px 30px;
        margin-right: 10px;
        background-color: #F95631;
        color: #fff;
        border-top-right-radius: 0;
    }
    .question, .answer{
        width: 100%;
        display: inline-block;
        word-break: break-word;
    }
    .selected-answer{
        float: left;
        margin-top: 30px;
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 10px 30px;
        margin-right: 10px;
        background-color: #efefef;
        border-top-left-radius: 0;
    }
    .accept-check .yes, .accept-check .no, .questions .nope{
        border-radius: 20px;
        padding: 10px 30px 10px 10px;
        margin-right: 10px;
        background-color: #efefef;
        display: inline-block;
        cursor: pointer;
    }
    .yes{
        border: 1px solid green;
    }
    .no{
        border: 1px solid red;
    }
    .accept-question{
        width: 100%;
        display: inline-block;
        margin-top: 15px;
    }
    .accept-question span{
        float: left;
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 10px 30px;
        margin-right: 10px;
        background-color: #efefef;
        border-top-left-radius: 0;
    }
    .questions .nope{
        background-color: #058fff;
        color: #fff;
    }
    .questions-request{
        max-height: 650px;
        overflow: auto;
    }
    .questions{
        margin-top: 10px;
    }


</style>