<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu ">
                    <h4>Statik səhifələr</h4>
                    <ol class="breadcrumb">
                        <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                        <li class="active">Statik səhifələr</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="card shadow my-3 no-b col-3 mr-3" v-for="page in list">
                <router-link v-bind:to="'/staticPage/' + page.static_page_id">
                    <img v-if="page.file" class="card-img-top" v-bind:src="page.file" alt="">
                    <div class="card-img-overlay">

                    </div>
                    <div class="card-body">
                        <h5 class="mb-1">{{page.name}}</h5>
                        <span>{{page.description}}</span>
                    </div>
                </router-link>
            </div>
        </div>
        <modal :toggle="modalToggle" :modal="'Hello'" @modal="modalToggle = $event">
            <template slot="header">
                Səhifə əlavə et
            </template>
            .
            <template slot="body">
                <ul class="nav nav-tabs mx-3">
                    <li class="nav-item">
                        <a class="nav-link" :class="lang === 'az' ? 'active' : ''" @click="lang = 'az'">AZ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="lang === 'ru' ? 'active' : ''" @click="lang = 'ru'">RU</a>
                    </li>
                </ul>
                <template v-if="lang === 'az'">
                    <div class="form-group col-md-12">
                        <label for="name" class="col-form-label">Səhifənin adı: <span v-if="!formData.az.name">*</span></label>
                        <input type="text" class="form-control" id="name" title="Səhifənin menyuda əks olunacaq adı" placeholder="..." v-model="formData.az.name" @input="generate($event, 'az')">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="slug" class="col-form-label">URL:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">www.limak.az/</div>
                            </div>
                            <input disabled type="text" class="form-control" id="slug" title="URL" placeholder="..." v-model="formData.az.slug">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description" class="col-form-label">Səhifə haqqında:</label>
                        <textarea class="form-control" v-model="formData.az.description" id="description" title="Səhifəyə daxil olduqda ilk görünən kiçik məlumat mətni" placeholder="..."></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description" class="col-form-label">Label:</label>
                        <input class="form-control" v-model="formData.az.label" id="label" title="label" placeholder="label" />
                    </div>
                    <div class="form-group col-md-12"><span v-if="!formData.az.file">*</span>
                        <label for="thumnail" class="btn btn-primary" title="Səhifənin menyuda əks olunacaq şəkli">
                            <i class="fas fa-upload"></i> <strong>{{formData.az.file ? formData.az.file.fileShow : ''}}</strong>
                            <input type="file" accept="image/*" style="display: none" id="thumnail" placeholder="..." @change="file($event, 'az')">
                        </label>
                    </div>
                </template>
                <template v-if="lang === 'ru'">
                    <div class="form-group col-md-12">
                        <label for="name.ru" class="col-form-label">Səhifənin adı: <span v-if="!formData.ru.name">*</span></label>
                        <input type="text" class="form-control" id="name.ru" title="Səhifənin menyuda əks olunacaq adı" placeholder="..." v-model="formData.ru.name" @input="generate($event, 'ru')">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="slug.ru" class="col-form-label">URL:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">www.test.az/</div>
                            </div>
                            <input disabled type="text" class="form-control" id="slug.ru" title="URL" placeholder="..." v-model="formData.ru.slug">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description.ru" class="col-form-label">Səhifə haqqında:</label>
                        <textarea class="form-control" v-model="formData.ru.description" id="description.ru" title="Səhifəyə daxil olduqda ilk görünən kiçik məlumat mətni" placeholder="..."></textarea>
                    </div>
                    <!--<div class="form-group col-md-12"><span v-if="!formData.ru.file">*</span>-->
                        <!--<label for="thumnail.ru" class="btn btn-primary" title="Səhifənin menyuda əks olunacaq şəkli">-->
                            <!--<i class="icon-upload"></i> <strong>{{formData.ru.file ? formData.ru.file.fileShow : ''}}</strong>-->
                            <!--<input type="file" accept="image/*" style="display: none" id="thumnail.ru" placeholder="..." @change="file($event, 'ru')">-->
                        <!--</label>-->
                    <!--</div>-->
                </template>
            </template>
            <template slot="footer">
                <button class="btn btn-default" @click="modalToggle = false">Bağla</button>
                <button class="btn btn-primary" @click="submit()" v-bind:disabled="!(formData.az.name)">Göndər</button>
                <!--<button class="btn btn-primary" @click="submit()" v-bind:disabled="!(formData.az.name && formData.az.file && formData.az.label)">Göndər</button>-->
                <!--<button class="btn btn-primary" @click="submit()" v-bind:disabled="!(formData.az.name && formData.az.file && formData.ru.name)">Göndər</button>-->
            </template>
        </modal>
        <button class="floating-button btn" @click="add()" title="Yeni səhifə əlavə et"><i class="fas fa-plus"></i></i></button>
    </div>
</template>

<script>
    import {api} from "../../apis";
    import Modal from "../../shared/Modal.vue"
    import swal from 'sweetalert2'
    import {slugify, truncate} from "../../pipes/slug";

    export default {
        mounted() {
            this.initialize();
        },
        props: {

        },
        data: function (){
            return {
                list: [],
                modalToggle: false,
                lang: 'az',
                formData: {
                    az: {
                        name: '',
                        slug: '',
                        description: '',
                        file: null,
                        label: null
                    },
                    ru: {
                        name: '',
                        slug: '',
                        description: '',
                        file: null
                    }
                }
            }
        },
        methods: {
            initialize() {
                this.fetch();
            },
            load() {
                return axios.get(api.spList);
            },
            fetch() {
                this.load().then(response => {
                    this.list = response.data;
                });
            },
            add() {
                this.modalToggle = true;
            },
            generate(event, lang) {
                this.formData[lang].slug = slugify(event.target.value);
            },
            file(data, lang) {
                this.formData[lang].file = data.target.files[0];
                this.formData[lang].file.fileShow = truncate(this.formData[lang].file.name, 30);
            },
            submit() {
                const  formData = new FormData();
                formData.append('az[name]', this.formData.az.name);

                formData.append('az[slug]', this.formData.az.slug);
                formData.append('az[description]', this.formData.az.description);
                if (this.formData.az.file) {
                    formData.append('az[file]', this.formData.az.file);
                }
                if(this.formData.az.label) {
                    formData.append('label', this.formData.az.label);
                }
                if (this.formData.ru.name) {
                    formData.append('ru[name]', this.formData.ru.name);
                    formData.append('ru[slug]', this.formData.ru.slug);
                    formData.append('ru[description]', this.formData.ru.description);
                }

                swal({
                    title: 'Göndərir...',
                    onOpen: () => {
                        swal.showLoading()
                        axios({url: api.spPost, method: 'POST', data: formData}).then(response => {
                            swal.close();
                            this.fetch();
                            this.modalToggle = false;
                        });
                    }
                })
            }
        },
        components: {
            modal: Modal
        }
    }

</script>
<style>
    @import "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css";
    @import "https://use.fontawesome.com/releases/v5.7.2/css/all.css";
    .mb-3, .my-3 {
        margin-bottom: 1rem!important;
    }
    .shadow {
        box-shadow: 0 0 6px rgba(0,0,0,.1)!important;
    }
    h5, h6 {
        font-weight: 400;
        color: #86939e;
        line-height: 1.5;
        margin: 0;
        font-size: 18px;
    }
    .mr-3, .mx-3 {
        margin-right: 1rem!important;
    }
    .mt-3, .my-3 {
        margin-top: 1rem!important;
    }
    .mt-5{
        margin-top: 1.8rem !important;
    }
    #bs-example-navbar-collapse-1 {
        float: right;
        display: inline-block;
        flex-basis: auto;
    }
    .navbar>.container, .navbar>.container-fluid {
        justify-content: unset;
    }
    .navbar {
        padding: 0;
    }
    .btn:not(:disabled):not(.disabled) {
        cursor: pointer;
    }
    .floating-button {
        background: #03A9F4;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        text-align: center;
        color: #FFF;
        -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.5), 3px 3px 3px rgba(0, 0, 0, 0.25);
        box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.5), 3px 3px 3px rgba(0, 0, 0, 0.25);
        position: fixed;
        bottom: 48px;
        right: 48px;
        font-size: 1.5em;
        display: inline-block;
        cursor: pointer;
    }
    [class*=" icon-"], [class^=icon-] {
        font-family: paperIcons!important;
        speak: none;
        font-style: normal;
        font-weight: 400;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .icon-plus:before {
        content: "\f067";
    }
    .modal-dialog.modal-lg{
        top: 200px ;
    }
</style>
