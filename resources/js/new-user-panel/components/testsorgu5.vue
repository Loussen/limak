<template>
    <div class="col-md-9 col-sm-11 col-xs-11 offer courier">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12 questions-request" id="assistant-manager">
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
                                <span class="question-bubble" style="cursor: initial;"
                                      v-show="checkAnswer[n] === true && n!=1"
                                      v-for="(item,index) in questions[n]"
                                      v-html="item.result"
                                ></span>
                            </div>


                            <div v-show="checkAnswer[n] === true && n<maxStep || checkOther == 1">
                                <div>
                                    <span class="nope" v-on:click="getQuestions(step,{id: 0}, true)"><span style="font-size: 15px; margin-right: 5px;">&#128527;</span> Bashqa sual vermek isteyirsiniz?</span>
                                </div>
                            </div>

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

        mounted() {
            this.initialize();
            this.ExWindow = window;
            this.getQuestions(1,{id : 0});
            this.getMaxStep();
            // this.getNextStepQuestionId();
        },
        data: function () {
            return {
                ExWindow: null,
                lang: null,
                k: 1,
                step: 2,
                maxStep: 1,
                questions: [],
                checkAnswer: [],
                checkOther: null,
                checkTitle: [],
                nextQuestionId: [],
                selectedQuestion: [],
                waitQuestions: [],
                waitSelectedQuestion: []
            }
        },
        methods: {
            initialize() {
                this.lang = window.translator;
            },
            getQuestions(step = 1, item, stepInc = false) {
                axios.post('/user-panel/get-questions/', {lang: default_locale, step: step, id: item.id})
                    .then(data => {
                        let response = data.data.data;
                        let checkChild = data.data.checkChild;
                        this.checkOther = data.data.checkOther;

                        if(checkChild !== null || this.checkOther !== null)
                        {
                            this.$set(this.checkAnswer, this.k, false);
                        }
                        else
                        {
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
                            this.step++;
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

                        // Wait Questions load
                        if(this.questions[this.k].length > 0)
                        {
                            this.$set(this.waitQuestions, this.k, true);

                            this.k++;
                        }

                        console.log(this.k);
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