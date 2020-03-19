<template>
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="animated fadeInUpShort go">
            <div class="row no-gutters">
                <div class="right-side col-md-9 col-sm-12 col-xs-12">
                    <div class="block col-md-12 col-sm-12 col-xs-12">
                        <div class="table-block">
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        <button class="btn-effect px-3" @click="open()">
                                            <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'az'">
                                                Mesaj yaz
                                            </template>
                                            <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'ru'">
                                                Написать сообщение
                                            </template>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>№</th>
                                    <th>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'az'">
                                            Mesajlar
                                        </template>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'ru'">
                                            Cообщений
                                        </template>
                                    </th>
                                    <th>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'az'">
                                            Kateqoriya
                                        </template>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'ru'">
                                            Kатегория
                                        </template>
                                    </th>
                                    <th>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'az'">
                                            Tarix
                                        </template>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'ru'">
                                            История
                                        </template>
                                    </th>
                                    <th>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'az'">
                                            Cavabla
                                        </template>
                                        <template v-if="ExWindow && ExWindow.translator('panel-errors.tr') === 'ru'">
                                            Oтветить
                                        </template>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(message, index) in list">
                                    <tr v-if="!message.message_id && message.category">
                                        <td>{{index + 1}}</td>
                                        <td>{{message.message}}</td>
                                        <td>{{message.category.name}}</td>
                                        <td>{{message.created_at}}</td>
                                        <td>
                                            <a v-if="message.from_user_id !== User.uniqid">
                                                <img src="/front/img/reply.png" alt="reply" @click="reply(message)">
                                            </a>
                                            <a @click="close(message.id)" style="cursor: pointer">
                                              <strong> &times;</strong>
                                            </a>
                                            <!--<a class="trash" v-if="message.from_user_id === User.uniqid">-->
                                            <!--<img src="/front/img/Delete.png" alt="Delete">-->
                                            <!--</a>-->
                                        </td>
                                    </tr>
                                    <template v-if="message.messages">
                                        <tr v-for="(child, childindex) in message.messages">
                                            <td>{{index + 1}}.{{childindex + 1}}</td>
                                            <td>{{child.message}}</td>
                                            <td></td>
                                            <td>{{child.created_at}}</td>
                                            <td>
                                                <a v-if="child.from_user_id !== User.uniqid" @click="reply(message)">
                                                    <img src="/front/img/reply.png" alt="reply">
                                                </a>
                                                <!--<a class="trash" v-if="message.from_user_id === User.uniqid">-->
                                                <!--<img src="/front/img/Delete.png" alt="Delete">-->
                                                <!--</a>-->
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <modal :toggle="modalToggle" :modal="'message'" @modal="modalToggle = $event">
                <template slot="header">
                    Mesaj yaz
                </template>
                <template slot="body">
                    <label for="name">Başlıq</label>
                    <input class="form-control" id="name" type="text" v-model="formData.name" title="Başlıq" name="subject" placeholder="..." /><br>
                    <label for="message">Mesaj</label>
                    <textarea class="form-control" id="message" type="text" title="Mesaj" v-model="formData.message" name="message" placeholder="..."></textarea><br>
                    <label for="category_id">
                        Kateqoriya
                    </label>
                    <select class="form-control" name="category_id" id="category_id" v-model="formData.category">
                        <option v-bind:value="category.id" v-for="category in categories">
                            {{category.name}}
                        </option>
                    </select><br>
                    <label for="files">Qoşma</label>
                    <input class="form-control" id="files" type="file" name="files[]" title="Qoşma" multiple @change="files($event)"/><br>
                </template>
                <template slot="footer">
                    <button class="btn btn-effect" @click="modalToggle = false">Bağla</button>
                    <button class="btn btn-effect btn-effect-success" @click="submit()" v-bind:disabled="!(formData.name)">Göndər</button>
                </template>
            </modal>
            <modal :toggle="replyToggle" :modal="'messageReply'" @modal="replyToggle = $event">
                <template slot="header">
                    Cavab yaz
                </template>
                <template slot="body">
                    <label for="messageReply">Mesaj</label>
                    <textarea class="form-control" id="messageReply" type="text" title="Mesaj" v-model="formData.message" name="message" placeholder="..."></textarea><br>
                    <input type="hidden" v-model="formData.category"><br>
                    <label for="filesReply">Qoşma</label>
                    <input class="form-control" id="filesReply" type="file" name="files[]" title="Qoşma" multiple @change="files($event)"/><br>
                </template>
                <template slot="footer">
                    <button class="btn btn-effect" @click="modalToggle = false">Bağla</button>
                    <button class="btn btn-effect btn-effect-success" @click="submitReply()" v-bind:disabled="!(formData.message)">Göndər</button>
                </template>
            </modal>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert2';
    import Modal from "../../shared/Modal.vue";
    import {api} from "../../apis";
    export default {
        mounted() {
            this.initialize();
            this.ExWindow = window;
        },
        props: {

        },
        data: function (){
            return {
                modalToggle: false,
                replyToggle: false,
                formData: {
                    name: null,
                    message: null,
                    category: null,
                    files: null,
                    id: null
                },
                categories: [],
                list: [],
                User: null,
                ExWindow: null
            }
        },
        methods: {
            initialize() {
                this.getUserData();
            },
            async getUserData() {
                const user  = await this.requestUserData();
                this.User = user.data;
                this.listData().then(response => {
                    const data = response.data;
                    this.list = data;
                });
                this.dataGet().then(response => {
                    const data = response.data;
                    this.categories = data;
                });
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';
                return  axios.get(path);

            },
            listData() {
                return axios.get(api.msgerList);
            },
            submit() {
                const formData = new FormData();
                formData.append('subject', this.formData.name);
                formData.append('message', this.formData.message);
                formData.append('category_id', this.formData.category);
                formData.append('files', this.formData.files);
                swal({
                    title: 'Göndərir...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.msgerPost, method: 'POST', data: formData}).then(response => {
                            swal.close();
                            this.listData().then(response => {
                                const data = response.data;
                                this.modalToggle = false;
                                this.list = data;
                            });
                        });
                    }
                })
            },
            submitReply() {
                const formData = new FormData();
                formData.append('id', this.formData.id);
                formData.append('subject', this.formData.name);
                formData.append('message', this.formData.message);
                formData.append('category_id', this.formData.category);
                formData.append('files', this.formData.files);
                swal({
                    title: 'Göndərir...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.msgerReply, method: 'POST', data: formData}).then(response => {
                            swal.close();
                            this.listData().then(response => {
                                const data = response.data;
                                this.replyToggle = false;
                                this.list = data;
                                this.formData.id = null
                            });
                        });
                    }
                })
            },
            reply(data) {
                this.replyToggle = true;
                this.formData.id = data.id;
                this.formData.category = data.category_id;
            },
            dataGet() {
                return axios.get(api.msgerCategories);
            },
            files(event) {
                this.formData.files = event.target.files;
            },
            open() {
                this.modalToggle = true,
                this.formData.id = null;
            },
            close(id) {
                axios.get(api.msgerClose + id).then(
                    res => {
                        this.listData().then(response => {
                            const data = response.data;
                            this.replyToggle = false;
                            this.list = data;
                            this.formData.id = null
                        });
                    }
                );
            }
        },
        components: {
            modal: Modal
        }
    }

</script>
