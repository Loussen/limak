<template>
    <div class="chat-box-container" v-if="!this.lock">
        <div class="top" @click="chatToggle = !chatToggle">
            <h5 class="my-0 py-3">{{ExWindow && ExWindow.translator('common.chat')}}</h5>
        </div>
        <template v-if="chatToggle">
            <div class="body">
                <ul v-if="list" class="list-group my-0">
                    <li v-for="message in list" class="list-group-item" :class="message.messages ? 'text-left' : message.responder === userId ? 'text-left' : 'text-right'">
                        <span>{{message.message}}</span>
                    </li>
                </ul>
            </div>
            <div class="type-area">
                <div class="input-group">
                    <input type="text" class="form-control" v-model="message" @keyup.enter="send()" placeholder="Mesajınızı yazın...">
                    <div class="input-group-append">
                        <div class="input-group-text" @click="send()" style="cursor: pointer">↵</div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue"
    import io from 'socket.io-client';
    import {api} from "../../apis";
    import swal from 'sweetalert2';
    export default {
        mounted() {
            this.initialize();
        },
        data: function (){
            return {
                message: '',
                files: [],
                socket: null,
                connected: false,
                list: [],
                userId: null,
                User: null,
                chatToggle: false,
                lock: true,
                ExWindow: null
            }
        },
        methods: {
            initialize() {
                this.socket = io(api.nodeAPI);
                this.requestUserData().then(res => {
                    this.User = res.data;
                    this.userId = this.User.uniqid;
                    this.lock = false;
                    this.initiateSocket();
                    this.getData();
                }).catch(e => {
                    this.lock = true;
                })
                this.ExWindow = window;
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';

                return  axios.get(path);
            },
            send() {
                if (this.message && this.message !== '') {
                    const data = {responder: this.userId, message: this.message};
                    if(this.list && this.list.length && this.list.length > 0) {
                        this.list = [...this.list, data];
                    } else {
                        this.list = [data];
                    }
                    if(!this.connected) {
                        const requestData = {id: this.userId, message: this.message};
                        axios({url: api.ncNew, method: 'POST', data: requestData})
                            .then(response => {
                                if (response && !response.data.reply) {
                                    this.connected = response.data ? response.data.isConnected : false;
                                } else if(response && response.data.reply) {
                                    this.socket.emit('send-message', {
                                        room: response.data.room,
                                        message: response.data.message,
                                        responder: response.data.responder,
                                        messageId: response.data.messageId
                                    });
                                }
                            })
                            .catch(e => {
                                swal(
                                    'Səhf!',
                                    'Mesaj göndərmək hal hazırda mümkün deyil xahiş olunur bir müddət sonra yenidən cəhd edin',
                                    'error'
                                );
                                console.error(e);
                            });
                    } else {
                        this.socket.emit('send-message', {
                            room: this.userId,
                            message: this.message,
                            responder: this.userId,
                            messageId: this.list[0].id,
                            operator: this.list[0].responder
                        });
                    }
                    this.message = '';
                }
            },
            initiateSocket() {
                this.socket.emit('subscribe', {to: this.userId});
                this.socket.on('conversation-private-post', (data) => {
                    if(this.list && this.list.length && this.list.length > 0) {
                        this.list = [...this.list, data.message];
                    } else {
                        this.list = [data.message];
                    }
                });
            },
            getData() {
                axios.get(api.ncUsrMsg+this.userId).then(
                    res => {
                        const data = res.data;
                        if (data) {
                            let list = [data];
                            this.connected = data ? data.isConnected : false;
                            if (data.messages) {
                                list = [...list, ...data.messages];
                            }
                            this.list = list;
                        }
                    }
                );
            }
        },
        components: {
            modal: Modal
        }
    }

</script>
<style scoped>
    .list-group{
        max-height: 400px;
        overflow-y: auto;
    }
    /* width */
    .list-group::-webkit-scrollbar {
        width: 2px;
    }

    /* Track */
    .list-group::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    .list-group::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    .list-group::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    .chat-box-container {
        position: fixed;
        bottom: 0;
        z-index: 100;
        right: 0;
        min-width: 350px;
        max-width: 351px;
    }
    .chat-box-container .top {
        background: #f95732;
        color: white;
    }
    .chat-box-container .body {
        background: white;
        color: black;
        min-height: 50px;
    }
    .my-0 {
        margin-top: 0px!important;
        margin-bottom: 0px!important;
    }
    .py-3{
        padding-top: 1em;
        padding-bottom: 1em;
        padding-left: 1em;
    }
    .input-group{
        margin: 0;
        display: flex;
    }
    .input-group input {
        border-radius: 2px!important;
    }
    .input-group-append {
        vertical-align: middle;
        margin-left: -20px;
        z-index: 102;
        padding-top: 8px;
        width: 20px;
    }
    .list-group-item:not(:last-child){
        margin-bottom: 5px;
    }
    .list-group-item.text-left{
        padding-left: 5px;
    }
    .list-group-item.text-right{
        padding-right: 5px;
    }
</style>
