<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>SUALLAR</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li><router-link to="/asistant-management"><i class="fa fa-user"></i>Limak Asistant</router-link></li>
                    <li class="active">Suallar</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="top-menu">
                <router-link class="btn btn-primary btn-lg active" to="/questions/add"><i class="fa fa-plus"></i> Əlavə et</router-link>
            </div>
        </div>
        <br />
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th v-bind="count_i=0">ID</th>
                        <th>Sual</th>
                        <th>Cavab</th>
                        <th>Ana sual</th>
                        <th>Başlıq</th>
                        <th>Step</th>
                        <th>Sıra</th>
                        <th>Redaktə</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="questions in list">

                        <td>{{count_i = count_i+1}}</td>
                        <td> {{ questions.value }}</td>
                        <td v-html="questions.answer"></td>
                        <td v-html="questions.p_value"></td>
                        <td v-html="questions.name_az"></td>
                        <td> {{ questions.q_step }}</td>
                        <td> {{ questions.q_ordering }}</td>
                        <td>
<!--                            <a href="#" class="btn btn-primary a-btn-slide-text">-->
<!--                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>-->
<!--                            </a>-->
                            <router-link class="btn btn-primary a-btn-slide-text" v-bind:to="'/questions/edit/'+questions.questions_id">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </router-link>
                            <a @click.prevent="deleteQuestion(questions.questions_id)" href="#" class="btn btn-danger">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li ><a @click.prevent="getData( paginate.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in paginate.last_page">
                            <li v-if="page ==1 && paginate.current_page==1" class="active">
                                <a @click.prevent="getData(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getData(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(paginate.current_page - page)> 3 && Math.abs(paginate.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(paginate.current_page - page)<= 3 && paginate.current_page == page" class="active">
                                <a @click.prevent="getData(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(paginate.current_page - page)<= 3 ">
                                <a @click.prevent="getData(page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == paginate.last_page">
                                <a @click.prevent="getData(page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="paginate.current_page < paginate.last_page" @click.prevent="getData( paginate.current_page + 1)" href="#" aria-label="Next">
                                <span aria-hidden="true">NÖVBƏTİ</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js'
    import swal from 'sweetalert2'
    window.axios = require('axios');
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    export default {
        name: "QuestionsList",
        data () {
            return {
                isHidden: true,
                list: '',
                paginate: []
            }
        },
        methods:{
            getData(page_id){
                axios.get('/cp/questionsList?page='+page_id)
                    .then((response) => {
                        this.list = response.data.data.data;
                        this.paginate = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            deleteQuestion: function(question_id){
                swal.fire({
                    title: 'Silmək istədiyinizə əminsiniz?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sil!',
                    cancelButtonText: 'Xeyr'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/cp/questionsDelete/'+question_id).then((response) => {
                            if(response.data.data === true)
                            {
                                swal.fire(
                                    'Silindi!',
                                    '',
                                    'success'
                                )

                                this.getData(this.paginate.current_page);
                            }
                        });

                    }
                })
            }

        },
        mounted(){
            this.getData(1);
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .top-search .check-label {
        margin-top: 35px;
        float: left;
        display: inline-block;
    }

    .top-search .btn-effect {
        width: 150px;
        float: right;
        margin-top: 15px;
    }

    button.btn-effect{
        width: 105px !important;
    }

</style>