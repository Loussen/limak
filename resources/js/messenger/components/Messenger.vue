<template>
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="animated fadeInUpShort go">
            <div class="row no-gutters">
                <sidebar :list="messages" @submit="messageSelected($event)"></sidebar>
                <messagearea :message="message" @input="response($event)"></messagearea>
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
    export default {
        mounted() {
            this.initialize();
        },
        props: {

        },
        data: function (){
            return {
                messages: [],
                message: null
            }
        },
        methods: {
            initialize() {
                this.list().then(response => {
                    const data = response.data;
                    this.messages = data ? data.data : [];
                })
            },
            list() {
                return axios.get(api.msgList);
            },
            getChatById(id) {
                return axios.get(api.msgShow + id);
            },
            messageSelected(message) {
                if(message.status === 1) {
                    swal({
                        title: 'Diqqət',
                        text: "Bu mesaja cavabdeh şəxs olmaqla mesajlaşmada yazılan hər sözə görə cavabdehlik daşımış olacaqsınız!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Mesaja cavabdeh şəxs ol',
                        cancelButtonText: "Geri"
                    }).then((result) => {
                        if (result.value) {
                            this.getChatById(message.id).then(response => {
                                swal(
                                    'Uğurlar!',
                                    'Mesaja cavabdeh şəxs olaraq qəbul olundunuz. Mesajlaşma zamanı müştəri məmnuniyyəti səbəbi ilə bütün yazılar qeydiyyata alınır',
                                    'success'
                                );
                                this.message = response.data;
                            });
                        }
                    })
                } else {
                    this.getChatById(message.id).then(response => {
                        this.message = response.data;
                    });
                }
            },
            response(response) {
                const requestData = new FormData();
                requestData.append('message', response.response);
                requestData.append('parent', this.message.id);
                requestData.append('files[]', response.files);
                axios({url: api.msgPost, method: 'POST', data: requestData}).then(response => {
                    this.getChatById(this.message.id);
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
