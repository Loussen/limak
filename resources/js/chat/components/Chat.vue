<template>
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="animated fadeInUpShort go">
            <div class="row no-gutters">
                <sidebar :list="messages" :id="id" @submit="messageSelected($event)"></sidebar>
                <messagearea :message="message" :id="id" @input="response($event)"></messagearea>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import SideBar from "./Sidebar.vue";
    import MessageArea from "./MessageArea.vue";
    import swal from 'sweetalert2';
    import {api} from "../../apis";
    import io from 'socket.io-client';
    export default {
        mounted() {
            this.initialize();
        },
        props: {

        },
        data: function (){
            return {
                messages: [],
                message: null,
                socket: null,
                id: null,
                users: {
                    '5c3d96d148532': 'Orxan Moko',
                    '5c3baa2315d24': 'Fatima Akperova',
                    '5c3a5c50d471b': 'Samir Quliyev',
                    '5c3989e958dc3': 'Nigar Cəfərova',
                    '5c38a56d52866': 'Faiq Mahmudov',
                    '5c3894aeeed69': 'Gulşən Sadıxova',
                    '5c3889a1b89c1': 'Səbinə Məmmədova',
                    '5c36f09880c41': 'Nargiz Mammadova',
                    '5c3611ab3d5f1': 'esger abdullayev',
                    '5c34939c4abdf': 'Mekane Hemidova',
                    '5c3469cedc1a7': 'Ceyran Zulfugarova',
                    '5c345dcaf20ea': 'gulshen Hajiyeva',
                    '5c345558136a0': 'Sona Hajiyeva',
                    '5c339ffd4dcee': 'Anar Ceferov',
                    '5c2e2fd0e1d4f': 'Kamil Abushov',
                    '5c2de39eef6a4': 'Teymur Məmmədov',
                    '5c2bd2b4a21c6': 'Fidan Axundova',
                    '5c24a2670f554': 'Elşad Mirzəyev',
                    '5c24851fc1f97': 'Orkhan Jafarli',
                    '5c2231675dd83': 'Mahir Velizade',
                    '5c2137f222ff6': 'Təbriz Dilavərzadə',
                    '5c20dcc9bd3c6': 'Gunay Mammadova',
                    '5c1dcf53ad597': 'Sabina Haciyeva',
                    '5c1b4e6e22d19': 'Emin Quliyev',
                    '5c1b4b0fdfd29': 'Vusale ABISHOVA',
                    '5c1a4cdc7d21e': 'Mahir Abdullayev',
                    '5c1a4b03e7c41': 'Heydar Shukurov',
                    '5c12aaac9bbbd': 'Rashad Haci-Hasanzada',
                    '5c12aaac884ee': 'Mahir Abdullayev',
                    '5c12aaac74ccd': 'Heyder Shukurov',
                }
            }
        },
        methods: {
            initialize() {
                this.getUserData().then(
                    response => {
                        this.id = response.data.uniqid;
                        this.list().then(response => {
                            const data = response.data;
                            this.messages = data ? data : [];
                            this.messages.forEach(m => {
                                m.fullname = this.users[m.room];
                            })
                        });
                        this.socket = io(api.nodeAPI);
                        this.newMessage();
                        this.listenSubscriptions();
                        this._subscribeResponder();
                    }
                );
            },
            getUserData() {
                return this.requestUserData();
            },
            requestUserData() {
                return  axios.get(api.admin);

            },
            newMessage() {
                this.socket.on('new.message', (data) => {
                    this.messages = data ? data : [];
                });
            },
            list() {
                return axios.get(api.ncNewList+this.id);
            },
            getSubscribe(id) {
                return axios({url: api.ncSubscribe, method: 'POST', data: {id, responder: this.id}});
            },
            _subscribeResponder() {
                this.socket.emit('subscribe.responder', this.id);
                this.socket.on('notify', (data) => {
                    data = data.message;
                    this.messages = this.messages.map(message => {
                        if (message.id === data.messageId) {
                            if (message.count) {
                                ++message.count;
                            } else {
                                message.count = 1;
                            }
                        }
                        return message;
                    });
                });
            },
            messageSelected(message) {
                let fromRoom = null;
                let firstConnection = true;
                if (this.message) {
                    firstConnection = false;
                    fromRoom = this.message.room;
                }
                if(!message.isConnected) {
                    swal({
                        title: 'Diqqət',
                        text: "Bu söhbətə cavabdeh şəxs olmaqla yazılan hər sözə görə cavabdehlik daşımış olacaqsınız!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Söhbətə cavabdeh şəxs ol',
                        cancelButtonText: "Geri"
                    }).then((result) => {
                        if (result.value) {
                            this.getSubscribe(message.id).then(response => {
                                swal(
                                    'Uğurlar!',
                                    'Söhbətə cavabdeh şəxs olaraq qəbul olundunuz. Mesajlaşma zamanı müştəri məmnuniyyəti səbəbi ilə bütün yazılar qeydiyyata alınır',
                                    'success'
                                );
                                this._getChatById(message.id);
                                this.subscribe(message.room, fromRoom, firstConnection);
                            });
                        }
                    })
                } else {
                    this.getSubscribe(message.id).then(response => {
                        this._getChatById(message.id);
                        this.subscribe(message.room, fromRoom, firstConnection);
                    });
                }
            },
            _getChatById(chatId) {
                axios.get(api.chatById + chatId).then(response => {
                    this.message = response.data;
                })
            },
            subscribe(userId, fromRoom, firstConnection) {
                if (fromRoom) {
                    this.socket.emit('subscribe', {from: fromRoom, to: userId});
                } else {
                    this.socket.emit('subscribe', {to: userId});
                }
                if (firstConnection) {
                    this.socket.on('conversation-private-post', (data) => {
                        if(this.message.messages && this.message.messages.length && this.message.messages.length > 0) {
                            this.message.messages = [...this.message.messages, data.message];
                        } else {
                            this.message.messages = [data.message];
                        }
                    });
                }
            },
            listenSubscriptions() {
                this.socket.on('new.subscription', wave => {
                    this.list().then(response => {
                        const data = response.data;
                        this.messages = data ? data : [];
                        this.messages.forEach(m => {
                            m.fullname = this.users[m.room];
                        })
                    });
                })
            },
            response(response) {
                this.socket.emit('send-message', {
                    room: response.room,
                    message: response.message,
                    responder: this.id,
                    messageId: response.id
                });
                response.responder = this.id;
                if(this.message.messages && this.message.messages.length && this.message.messages.length > 0) {
                    this.message.messages = [...this.message.messages, response];
                } else {
                    this.message.messages = [response];
                }
                this.messages = this.messages.map(message => {
                    if(this.message.id === message.id) {
                        message.count = null;
                        message.last = this.id;
                    }
                    return message
                });
            }
        },
        components: {
            modal: Modal,
            sidebar: SideBar,
            messagearea: MessageArea
        }
    }

</script>
