<template>
    <div class="container" v-if="data && data[0]">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu ">
                    <h4>Statik səhifələr</h4>
                    <ol class="breadcrumb">
                        <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                        <li><router-link to="/staticPage"><i class="fa fa-home"></i>statik səhifələr</router-link></li>
                        <li class="active">Ümumi</li>
                    </ol>
                </div>
            </div>
        </div>
        <ul class="nav nav-pills pb-4 pt-3">
            <li role="presentation" class="active btn btn-outline-primary">
                <router-link v-bind:to="'/staticPage/' + id"><strong style="color: white"> Ümumi</strong></router-link>
            </li>
            <li role="presentation" class="btn btn-outline-primary">
                <router-link v-bind:to="'/staticPage/' + id + '/generator'"><strong style="color: #03a9f4">Generator</strong></router-link>
            </li>
        </ul>
        <div class="row justify-content-center">
            <div class="col-12"><img v-bind:src="data[0].file" v-bind:alt="data[0].name"></div>
            <div class="form-group col-md-12"><span v-if="data[0].file">*</span>
                <label for="thumnail" class="btn btn-primary" title="Səhifənin menyuda əks olunacaq şəkli">
                    <i class="icon-upload fas fa-upload"></i> <strong>{{data[0].file ? data[0].file.fileShow : ''}}</strong>
                    <input type="file" accept="image/*" style="display: none" id="thumnail" placeholder="..." @change="file($event)">
                </label>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="text-center jumbotron py-4" v-for="(item, index) in data">
                    <input type="text" class="form-control" v-model="item.name" @input="generate($event, index)">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">www.limak.az/</div>
                        </div>
                        <input type="text" class="form-control" id="slug.ru" title="URL" placeholder="..." v-model="item.slug">
                    </div>
                    <span class="badge badge-info my-3"><strong>{{item.created_at}}</strong></span>
                    <textarea name="" id="" rows="10" class="form-control" v-model="item.description"></textarea>
                    <input type="text" class="form-control" v-model="item.label" placeholder="label">
                </div>
            </div>
            <button class="btn btn-success mb-5" @click="submit()">Yadda saxla</button>
        </div>
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
                "id": null,
                data: null,
            }
        },
        methods: {
            initialize() {
                this.id = this.$route.params.id;
                this.fetch();
            },
            load() {
                return axios.get(api.spShow + this.id);
            },
            fetch() {
                this.load().then(response => {
                    this.data = response.data;
                });
            },
            submit() {
                const  formData = new FormData();
                formData.append('az[id]', this.data[0].static_page_id);
                formData.append('az[trId]', this.data[0].id);
                formData.append('az[name]', this.data[0].name);
                formData.append('az[slug]', this.data[0].slug);
                formData.append('az[description]', this.data[0].description);
                formData.append('label', this.data[0].label);
                formData.append('az[file]', this.data[0].file);
                if (this.data[1]) {
                    formData.append('ru[id]', this.data[1].static_page_id);
                    formData.append('ru[trId]', this.data[1].id);
                    formData.append('ru[name]', this.data[1].name);
                    formData.append('ru[slug]', this.data[1].slug);
                    formData.append('ru[description]', this.data[1].description);
                }
                swal({
                    title: 'Göndərir...',
                    onOpen: () => {
                        swal.showLoading()
                        axios({url: api.spPut, method: 'POST', data: formData}).then(response => {
                            swal.close();
                            this.fetch();
                        });
                    }
                })
            },
            generate(event, index) {
                this.data[index].slug = slugify(event.target.value);
            },
            file(data) {
                this.data[0].file = data.target.files[0];
                this.data[0].file.fileShow = truncate(this.data[0].file.name, 30);
            },
        },
        components: {
            modal: Modal
        }
    }

</script>
<style>
    @import "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css";
    @import "https://use.fontawesome.com/releases/v5.7.2/css/all.css";
    input[type="text"],.input-group-text,.btn.btn-success
    {
        font-size:18px;
    }
    .badge.badge-info{
        font-size: 16px;
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
</style>
