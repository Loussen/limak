<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>SUALLAR</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/questions_title"><i class="fa fa-question"></i>Başlıqlar</router-link></li>
                    <li class="active">Başlıq əlavə et</li>
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
                <template v-if="lang === 'az'">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Başlıq (AZ)" v-model="name_az">
                            <p style="color:red;" v-if="errors[0]">{{errors[0].name_az}}</p>
                        </div>
                    </div>
                </template>
                <template v-if="lang === 'ru'">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Başlıq (RU)" v-model="name_ru">
                            <p style="color:red;" v-if="errors[0]">{{errors[0].name_ru}}</p>
                        </div>
                    </div>
                </template>
                <div class="col-md-3 button-last text-left">
                    <button type="button" class="btn-effect" v-on:click="submit()">Əlavə et</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js';

    // import VueCkeditor from 'vue-ckeditor2';

    import swal from 'sweetalert2'

    window.axios = require('axios');
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    export default {
        name: "QuestionsTitleAdd",
        data () {
            return {
                name_az: null,
                name_ru: '',
                errors: [],
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
                    formData.append('name_az', this.name_az);
                    formData.append('name_ru', this.name_ru);

                    axios.post('/cp/questionsTitleAdd',
                        formData,
                    ).then(response => {
                        if(response.data.data === true)
                        {
                            swal.fire(
                                'Uğurlu',
                                'Yenisini əlavə edə bilərsiniz!',
                                'success'
                            );

                            this.name_az = null;
                            this.name_ru = null;
                        }
                        else
                        {
                            swal.fire(
                                'Xəta!',
                                'Yenidən cəhd edin',
                                'error'
                            )
                        }
                    })
                }
            },
            checkForm(){
                this.errors=[];
                if(!this.name_az) this.errors[0]={name_az:"Başlıq boş ola bilməz."};
                return (this.errors.length===0 ? 'true' : 'false');
            },

        },
        mounted(){
            // if (document.getElementById('ckeditor')) return; // was already loaded
            // console.log("ckeditor");
            // let scriptTag = document.createElement("script");
            // scriptTag.src = "https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js";
            // scriptTag.id = "ckeditor";
            // document.getElementsByTagName('head')[0].appendChild(scriptTag);
        },
        components: {
            // 'vue-ckeditor': VueCkeditor
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