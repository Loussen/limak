<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>SUALLAR</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/questions"><i class="fa fa-question"></i>Suallar</router-link></li>
                    <li class="active">Sual əlavə et</li>
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
                        <label for="type" class="col-form-label">Tip</label>
                        <select id="type" v-model="selected_type" class="form-control">
                            <option disabled selected value="">Sualın tipini seçin* ...</option>
                            <option value="1">Ancaq sual (Bu sualın alt sualı olmalıdır, əlavə etməyi unutma)</option>
                            <option value="2">Sual və cavab</option>
                        </select>
                        <p style="color:red;" v-if="errors[3]">{{errors[3].selected_type}}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="parent" class="col-form-label">Ana sual</label>
                        <select id="parent" v-model="selected_parent" class="form-control">
                            <option value="0">Ana sualı seçin...</option>
                            <option v-for="(item,index) in parentList" :value="item.questions_id">{{item.value}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12" v-show="selected_type != 2">
                    <div class="form-group">
                        <label for="title" class="col-form-label">Başlıq</label>
                        <select id="title" v-model="selected_title" class="form-control">
                            <option value="0">Başlıq seçin...</option>
                            <option v-for="(item,index) in titleList" :value="item.id">{{item.name_az}}</option>
                        </select>
                    </div>
                </div>
                <div v-show="lang === 'az'">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="question_az" class="col-form-label">Sual (AZ)</label>
                            <input type="text" id="question_az" class="form-control" placeholder="Sual (AZ) *" v-model="question_az">
                            <p style="color:red;" v-if="errors[0]">{{errors[0].question_az}}</p>
                        </div>
                    </div>
                    <div class="col-md-12" v-show="selected_type != 1">
                        <div class="form-group">
                            <label for="answer_az" class="col-form-label">Cavab (AZ)</label>
    <!--                        <input type="text" class="form-control" placeholder="Cavab" v-model="answer">-->
                            <vue-ckeditor
                                    id="answer_az"
                                    v-model="answer_az"
                                    :config="config"/>
                            <p style="color:red;" v-if="errors[1]">{{errors[1].answer_az}}</p>
                        </div>
                    </div>
                </div>
                <div v-show="lang === 'ru'">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="question_ru" class="col-form-label">Sual (RU)</label>
                            <input type="text" id="question_ru" class="form-control" placeholder="Sual (RU)" v-model="question_ru">
                            <p style="color:red;" v-if="errors[0]">{{errors[0].question_ru}}</p>
                        </div>
                    </div>
                    <div class="col-md-12" v-show="selected_type != 1">
                        <div class="form-group">
                            <label for="answer_ru" class="col-form-label">Cavab (RU)</label>
                            <!--                        <input type="text" class="form-control" placeholder="Cavab" v-model="answer">-->
                            <vue-ckeditor
                                    id="answer_ru"
                                    v-model="answer_ru"
                                    :config="config"/>

                            <p style="color:red;" v-if="errors[1]">{{errors[1].answer_ru}}</p>

                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="display: none;">
                    <div class="form-group">
                        <label for="step" class="col-form-label">Step</label>
                        <input type="text" class="form-control" id="step" placeholder="Step" v-model="step">
                        <p style="color:red;" v-if="errors[2]">{{errors[2].step}}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ordering" class="col-form-label">Sıra</label>
                        <input type="text" class="form-control" id="ordering" placeholder="Sıra" v-model="ordering">
                        <p style="color:red;" v-if="errors[4]">{{errors[4].ordering}}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="chat_show" class="col-form-label">Crispi linki</label>
                        <input type="checkbox" class="form-control" id="chat_show" placeholder="Crispi linki görsənsin" v-model="chat_show">
                    </div>
                </div>
                <div class="col-md-3 button-last text-left">
                    <button type="button" class="btn-effect" v-on:click="submit()">Əlavə et</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js';

    import VueCkeditor from 'vue-ckeditor2';

    import swal from 'sweetalert2';

    window.axios = require('axios');
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    export default {
        name: "QuestionsAdd",
        components: {
            'vue-ckeditor': VueCkeditor
        },
        mixins: [auth],
        data () {
            return {
                question_az: '',
                question_ru: '',
                answer_az: '',
                answer_ru: '',
                step: 1,
                ordering: 1,
                chat_show: false,
                selected_parent: 0,
                selected_title: 0,
                selected_type: '',
                errors: [],
                parentList: [],
                titleList: [],
                config: {
                    height: 300
                },
                lang: 'az'
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
                    formData.append('ordering', this.ordering);
                    formData.append('parent', this.selected_parent);
                    formData.append('title_id', this.selected_title);
                    formData.append('type', this.selected_type);
                    formData.append('chat_show', this.chat_show);

                    console.log(this.selected_type+" type");

                    axios.post('/cp/questionsAdd',
                        formData,
                    ).then(response => {
                        if(response.data.data === true)
                        {
                            swal.fire(
                                'Uğurlu',
                                'Yenisini əlavə edə bilərsiniz!',
                                'success'
                            );

                            console.log(this.answer_az+" Cavab");

                            this.question_az = '';
                            this.question_ru = '';
                            this.answer_az = '';
                            this.answer_ru = '';
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
                if(!this.answer_az && this.selected_type == 2) this.errors[1]={answer_az:"Cavab boş ola bilməz."};
                if(this.step <= 0) this.errors[2]={step:"Step boş ola bilməz."};
                if(this.ordering <= 0) this.errors[4]={ordering:"Sıralama boş ola bilməz."};
                if(this.selected_type <= 0) this.errors[3]={selected_type:"Tip boş ola bilməz."};
                return (this.errors.length===0 ? 'true' : 'false');
            },
            getParentsQuestions(){
                axios.get(`/cp/questionsParents/0`)
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
        mounted(){
            this.getParentsQuestions();
            this.getTitles();
            // let scriptTag = document.createElement("script");
            // scriptTag.src = "https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js";
            // scriptTag.id = "ckeditor";
            // document.getElementsByTagName('head')[0].appendChild(scriptTag);
        },
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