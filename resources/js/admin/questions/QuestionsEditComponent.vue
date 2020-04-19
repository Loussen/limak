<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>SUALLAR</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/questions"><i class="fa fa-question"></i>Suallar</router-link></li>
                    <li class="active">Sualı dəyişdir</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="block list-block col-xs-12">
                <ul class="nav nav-tabs mx-3">
                    <li class="nav-item">
                        <a class="nav-link" :class="lang === 'az' ? 'active' : ''" @click="lang = 'az'">AZ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="lang === 'ru' ? 'active' : ''" @click="lang = 'ru'">RU</a>
                    </li>
                </ul>
                <div class="col-md-12">
                    <div class="form-group">
                        <select v-model="selected_type" class="form-control">
                            <option disabled value="">Sualın tipini seçin *...</option>
                            <option value="1" :selected="selected_type == 1">Ancaq sual</option>
                            <option value="2" :selected="selected_type == 2">Sual və cavab</option>
                        </select>
                        <p style="color:red;" v-if="errors[3]">{{errors[3].selected_type}}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <select v-model="selected_parent" class="form-control">
                            <option value="0">Ana sualı seçin...</option>
                            <option v-for="(item,index) in parentList" :selected="item.questions_id == selected_parent" :value="item.questions_id">{{item.value}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <select v-model="selected_title" class="form-control">
                            <option value="0">Başlıq seçin...</option>
                            <option v-for="(item,index) in titleList" :selected="item.id == selected_title" :value="item.id">{{item.name_az}}</option>
                        </select>
                    </div>
                </div>
                <template v-if="lang === 'az'">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Sual" v-model="question_az">
                            <p style="color:red;" v-if="errors[0]">{{errors[0].question_az}}</p>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="selected_type != 1">
                        <div class="form-group">
    <!--                        <input type="text" class="form-control" placeholder="Cavab" v-model="answer">-->
                            <vue-ckeditor
                                    v-model="answer_az"
                                    :config="config"/>
                            <p style="color:red;" v-if="errors[1]">{{errors[1].answer_az}}</p>

                        </div>
                    </div>
                </template>
                <template v-if="lang === 'ru'">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Sual" v-model="question_ru">
                            <p style="color:red;" v-if="errors[0]">{{errors[0].question_ru}}</p>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="selected_type != 1">
                        <div class="form-group">
                            <!--                        <input type="text" class="form-control" placeholder="Cavab" v-model="answer">-->
                            <vue-ckeditor
                                    v-model="answer_ru"
                                    :config="config"/>
                            <p style="color:red;" v-if="errors[1]">{{errors[1].answer_ru}}</p>

                        </div>
                    </div>
                </template>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Step" v-model="step">
                        <p style="color:red;" v-if="errors[2]">{{errors[2].step}}</p>
                    </div>
                </div>
                <div class="col-md-3 button-last text-left">
                    <button type="button" class="btn-effect" v-on:click="submit()">Dəyişdir</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js';

    import VueCkeditor from 'vue-ckeditor2';

    import swal from 'sweetalert2'

    window.axios = require('axios');
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    export default {
        name: "QuestionsAdd",
        data () {
            return {
                question_az: null,
                question_ru: null,
                answer_az: null,
                answer_ru: null,
                step: 1,
                selected_parent: 0,
                selected_title: 0,
                selected_type: 0,
                question_id: 0,
                parentList: [],
                titleList: [],
                errors: [],
                config: {
                    height: 300
                },
                lang: 'az',
                question: '',
                list: [],
            }
        },
        methods:{
            submit(){
                if(this.checkForm()==='true') {
                    let formData = new FormData();
                    formData.append('question_az', this.question_az);
                    formData.append('question_ru', this.question_ru);
                    formData.append('answer_az', this.answer_az);
                    formData.append('answer_ru', this.answer_ru);
                    formData.append('step', this.step);
                    formData.append('question_id', this.question_id);
                    formData.append('type', this.selected_type);
                    formData.append('parent', this.selected_parent);
                    formData.append('title_id', this.selected_title);

                    axios.post('/cp/questionsUpdate',
                        formData,
                    ).then(response => {
                        if(response.data.data === true)
                        {
                            swal.fire(
                                'Uğurlu',
                                'Dəyişikliklər qeydə alındı!',
                                'success'
                            );

                            // this.question_az = null;
                            // this.question_ru = null;
                            // this.answer_az = this.answer_ru = '';
                        }
                        else
                        {
                            swal.fire(
                                'Xəta!',
                                'Yenidən cəhd edin',
                                'error'
                            )
                        }
                        console.log(data)
                        // this.getComplaints();
                    })
                }
            },
            checkForm(){
                this.errors=[];
                if(!this.question_az) this.errors[0]={question_az:"Sual boş ola bilməz."};
                if(!this.answer_az) this.errors[1]={answer_az:"Cavab boş ola bilməz."};
                if(this.step <= 0) this.errors[2]={step:"Step boş ola bilməz."};
                return (this.errors.length===0 ? 'true' : 'false');
            },

            async getData(){
                await axios.get(`/cp/questionsEdit/${this.$route.params.id}`)
                    .then((response) => {
                        // this.question = response.data.data;
                        // console.log(this.question[1]['locale']);
                        let list = [];
                        response.data.data.map(function(value, key) {
                            list[value['locale']] = value;
                        });

                        if('ru' in list)
                        {
                            this.question_ru = list['ru']['value'];
                            this.answer_ru = list['ru']['answer'];
                        }

                        this.question_az = list['az']['value'];
                        this.answer_az = list['az']['answer'];
                        this.step = list['az']['step'];
                        this.question_id = list['az']['questions_id'];
                        this.selected_type = list['az']['type'];
                        this.selected_parent = list['az']['p_id'];
                        this.selected_title = list['az']['title_id'];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            async getParentsQuestions(){
                await axios.get(`/cp/questionsParents/`+this.question_id)
                    .then((response) => {
                        this.parentList = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getTitles(){
                axios.get(`/cp/questionsTitles`)
                    .then((response) => {
                        this.titleList = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        async mounted(){
            await this.getData();
            await this.getParentsQuestions();
            this.getTitles();
            if (document.getElementById('ckeditor')) return; // was already loaded
            console.log("ckeditor");
            let scriptTag = document.createElement("script");
            scriptTag.src = "https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js";
            scriptTag.id = "ckeditor";
            document.getElementsByTagName('head')[0].appendChild(scriptTag);
        },
        components: {
            'vue-ckeditor': VueCkeditor
        },
        mixins: [auth]
    }
</script>

<style>
    .nav-tabs{
        margin-bottom: 15px;
        border-bottom: 0;
    }

    .nav-tabs a.active{
        border: 1px solid #DDC;
        color: blue;
    }

    .nav-tabs a{
        color: #000;
        cursor: pointer;
    }
</style>