<template>
    <div class="col-md-9 col-sm-11 col-xs-11 offer courier">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12 questions-request" id="assistant-manager" ref="assistantManager">
                    <h3>Limak {{ ExWindow && ExWindow.translator('panel-errors.asistant') }}</h3>
                    <div class="answer-questions" v-for="n in k" v-if="waitQuestions[n] === true">
                        <div class="questions">
                            <div class="question-bubble-title"
                                 v-show="checkTitle[n] === true && questions[n][0].title_name && (checkAnswer[n] === false || n==1)">
                                {{questions[n][0].title_name}}
                            </div>


                            <div>
                                <span class="question-bubble"
                                      v-show="checkAnswer[n] === false || n==1"
                                      v-for="(item,index) in questions[n]"
                                      v-on:click="getQuestions(1,item)">
                                    {{item.result}}
                                </span>
                                <span class="question-bubble-answer" style="cursor: initial;"
                                      v-show="checkAnswer[n] === true && n!=1"
                                      v-for="(item,index) in questions[n]"
                                      v-html="item.result"
                                ></span>

                            </div>
                            <div v-show="chatShow[n]===true && checkAnswer[n] === true">
                                <span class="question-bubble-chat" onclick="$crisp.push(['do', 'chat:open'])">
                                    <span style="font-size: 15px; margin-right: 5px;">&#x26A1;</span> Chata qoşul
                                </span>
                            </div>


<!--                            <div v-show="/*checkAnswer[n] === true && n<maxStep || checkOther == 1*/ n==1">-->
<!--                                <div>-->
<!--                                    <span class="nope" v-on:click="getQuestions(10000,{id: 0},true)"><span style="font-size: 15px; margin-right: 5px;">&#128527;</span> Bashqa sual vermek isteyirsiniz?</span>-->
<!--                                </div>-->
<!--                            </div>-->

                        </div>

                        <div>

                                <div class="question" v-if="waitQuestions[n+1] === true">
                                    <span class="selected-question">
                                        {{selectedQuestion[n+1]}}
                                    </span>
                                </div>

<!--                                <div class="accept">-->
<!--                                    <div class="accept-question">-->
<!--                                        <span>-->
<!--                                            Cavab sizi qane etdi?-->
<!--                                        </span>-->
<!--                                    </div>-->
<!--                                    <div class="accept-check">-->
<!--                                        <span class="yes" v-on:click="getDefaultQuestions(n+1,true)"><span style="font-size: 15px; margin-right: 5px;">&#128515;</span> Bəli</span>-->
<!--                                        <span class="no" v-on:click="getDefaultQuestions(n+1,false)"><span style="font-size: 15px; margin-right: 5px;">&#128543;</span> Xeyr</span>-->
<!--                                    </div>-->
<!--                                </div>-->
                        </div>

                    </div>
                    <div id="scrollTo"></div>

                    <div class="nope-div" v-show="checkAnswerNope === true">
                        <button type="button" v-on:click="getQuestions(1,{id : 0}, true)" class="mt-auto btn btn-outline-primary nope"><span style="font-size: 15px; margin-right: 5px;">&#128527;</span> Başqa sual vermək istəyirsiniz?</button>
                    </div>

<!--                    <div class="alert alert-success" role="alert">Sorguda ishtirak etdiyiniz uchun teshekkurler!</div>-->
<!--                    <div class="alert alert-warning" role="alert">Diger suallar uchun sorgu gondere bilersiniz</div>-->


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

        updated () {
            // this.scrollToEnd();
            // this.scrollToDiv();
        },
        mounted() {
            this.initialize();
            this.ExWindow = window;
            this.getQuestions(1,{id : 0});
            this.getMaxStep();
            // this.scrollToEnd();
            // this.scrollToDiv();
            // this.getNextStepQuestionId();
        },
        data: function () {
            return {
                ExWindow: null,
                lang: null,
                k: 1,
                // step: 2,
                maxStep: 1,
                questions: [],
                checkAnswer: [],
                checkAnswerNope: false,
                checkOther: null,
                checkTitle: [],
                nextQuestionId: [],
                selectedQuestion: [],
                waitQuestions: [],
                waitSelectedQuestion: [],
                chatShow: []
            }
        },
        methods: {
            initialize() {
                this.lang = window.translator;
            },
            scrollToEnd () {
                var content = this.$refs.assistantManager;
                content.scrollTop = content.scrollHeight;
            },
            scrollToSmoothly (pos, time){
                /*Time is only applicable for scrolling upwards*/
                /*Code written by hev1*/
                /*pos is the y-position to scroll to (in pixels)*/
                if(isNaN(pos)){
                    throw "Position must be a number";
                }
                if(pos<0){
                    throw "Position can not be negative";
                }
                var currentPos = window.scrollY||window.screenTop;
                if(currentPos<pos){
                    var t = 10;
                    for(let i = currentPos; i <= pos; i+=10){
                        t+=10;
                        setTimeout(function(){
                            window.scrollTo(0, i);
                        }, t/2);
                    }
                } else {
                    time = time || 2;
                    var i = currentPos;
                    var x;
                    x = setInterval(function(){
                        window.scrollTo(0, i);
                        i -= 10;
                        if(i<=pos){
                            clearInterval(x);
                        }
                    }, time);
                }
            },
            scrollToDiv(){
                var elem = document.querySelector("#scrollTo");
                this.scrollToSmoothly(elem.offsetTop);
            },
            getQuestions(step = 1, item, stepInc = false) {
                axios.post('/user-panel/get-questions/', {lang: default_locale, step: step, id: item.id})
                    .then(data => {
                        let response = data.data.data;
                        let checkChild = data.data.checkChild;
                        this.checkOther = data.data.checkOther;

                        if(checkChild !== null || this.checkOther !== null)
                        {
                            this.checkAnswerNope = false;
                            this.$set(this.checkAnswer, this.k, false);
                        }
                        else
                        {
                            this.checkAnswerNope = true;
                            this.$set(this.checkAnswer, this.k, true);
                        }

                        this.questions[this.k] = response;

                        // Selected questions
                        if(stepInc === false)
                        {
                            this.selectedQuestion[this.k] = item.result;
                        }
                        else
                        {
                            this.selectedQuestion[this.k] = "Digər";
                            // this.step++;
                        }

                        if(typeof this.selectedQuestion[this.k] !== 'undefined')
                        {
                            if(this.selectedQuestion[this.k].length > 0)
                            {
                                this.$set(this.waitSelectedQuestion, this.k, true);
                            }
                        }

                        // Title check
                        this.checkTitle[this.k] = false;
                        if(typeof this.questions[this.k][0].title_name !== 'undefined')
                        {
                            this.checkTitle[this.k] = true;
                        }

                        for(var i=0; i < response.length; i++)
                        {
                            this.checkChatShow(response[i].chat_show)
                        }

                        // Wait Questions load
                        if(this.questions[this.k].length > 0)
                        {
                            this.$set(this.waitQuestions, this.k, true);

                            this.k++;
                        }

                        console.log(response);

                        $("#assistant-manager").animate({ scrollTop: $('#assistant-manager').prop("scrollHeight")}, 500);

                        // this.scrollToDiv();

                        // var container = document.getElementById("assistant-manager"),
                        //     scrollTo = document.getElementById("scrollTo");
                        //
                        // container.scrollTop = scrollTo.offsetTop - container.offsetTop + 500;

                        // let objDiv = document.getElementById("assistant-manager");
                        // objDiv.scrollTop = objDiv.scrollHeight + objDiv.scrollHeight + objDiv.scrollHeight;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getMaxStep() {
                axios.get('/user-panel/get-max-step/')
                    .then(data => {
                        this.maxStep = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            checkChatShow(item){
                if(item == 1)
                    this.$set(this.chatShow, this.k, true);
                else
                    this.$set(this.chatShow, this.k, false);
            }
            // getNextStepQuestionId() {
            //     axios.post('/user-panel/get-next-step/', {step: this.step})
            //         .then(data => {
            //             this.nextQuestionId[this.k] = data.data.data;
            //         })
            //         .catch(err => {
            //            console.log(err);
            //         });
            // }
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
    .question-bubble, .question-bubble-title, .question-bubble-answer, .question-bubble-chat{
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 10px 30px;
        margin-right: 10px;
        /*background-color: #efefef;*/
        cursor: pointer;
        display: inline-block;
        margin-bottom: 10px;
    }
    .question-bubble-title
    {
        border-top-left-radius: 0;
    }
    .question-bubble
    {
        background-color: #fcfcf7;
        border: 1px solid #e6e6e6;
    }
    .question-bubble:hover
    {
        border: 1px solid #F95631;
        color: #F95631;
    }
    .question-bubble-chat:hover
    {
        border: 1px solid #058fff;
        color: #058fff;
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
    .accept-check .yes, .accept-check .no, .questions-request .nope{
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
    .questions-request .nope{
        background-color: #058fff;
        color: #fff;
    }
    .questions-request{
        max-height: 1000px;
        overflow: auto;
    }
    .questions{
        margin-top: 10px;
    }

    .nope-div
    {
        text-align: center;
        margin-top: 15px;
        border-top: 1px solid #e6e6e6;
        padding-top: 20px;
    }

    /*.answer-questions*/
    /*{*/
    /*    border: 1px solid #F57558;*/
    /*}*/


</style>