<template>
    <div class="col-md-9 col-sm-12 white">
        <div class="card h-htn100">
            <div class="card-body  chat-widget p-3 slimScroll" v-bind:data-height="messageContainerHeight">
                <div class="w-body w-scroll ">
                    <ul class="list-unstyled" v-if="">
                        <template v-if="message">
                            <!-- Chat by us. Use the class "by-me". -->
                            <li class="by-other">
                                <!-- Use the class "float-left" in avatar -->
                                <div class="avatar float-right">
                                    <!-- Online or offline -->
                                    <b class="c-idle"></b>
                                    <img src="/assets/img/dummy/u1.png" alt="" class="img-responsive">
                                    <!-- Name -->
                                    <span></span>
                                </div>

                                <div class="chat-content">
                                    <div class="chat-meta">
                                        <div class="row">
                                            <div class="col-10"><strong class="font-16">{{message.subject}}</strong></div>
                                            <div class="col-2 text-right">{{message.created_at}}</div>
                                        </div>
                                    </div>
                                    <p class="mb-2 font-15">{{message.message}}</p>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                            <template v-if="message.messages && message.messages.length && message.messages.length > 0">
                                <template v-for="child in message.messages">
                                    <span style="display: none">.</span>
                                    <template v-if="child.responder">
                                        <li v-bind:class="child.responder === id ? 'by-me' : 'by-other'">
                                            <!-- Use the class "float-right" in avatar -->
                                            <div class="avatar" v-bind:class="child.responder === id ? 'float-left' : 'float-right'">
                                                <!-- Online or offline -->
                                                <b class="c-off"></b>
                                                <img v-bind:src="child.responder === id ? '/assets/img/dummy/u4.png' : '/assets/img/dummy/u1.png'" alt="" class="img-responsive">
                                                <!-- Name -->
                                                <span></span>
                                            </div>

                                            <div class="chat-content">
                                                <!-- In the chat meta, first include "time" then "name" -->
                                                <div class="chat-meta">
                                                    <div class="row">
                                                        <div class="col-10"><p class="font-15 mb-2">{{child.message}}</p></div>
                                                        <div class="col-2 text-right"><span class="">{{child.createdAt}}</span></div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </li>
                                    </template>
                                </template>
                            </template>
                        </template>
                        <template v-if="!message">
                            <div class="alert alert-info text-center font-16">
                                <strong>Diqqət!</strong> Söhbətə başlamaq üçün solda yerləşən söhbətlərdən birini seçin
                            </div>
                        </template>
                    </ul>
                </div>
            </div>
            <div class="card-footer bg-light">
                <!-- Chat button -->
                    <div class="input-group">
                        <input v-bind:disabled="!message" class="form-control" placeholder="Cavabı bura yazın..." type="text" v-model="response" @keyup.enter="submit()">

                        <span class="input-group-btn ml-2">
                            <button type="submit" class="btn btn-primary" v-bind:disabled="!(response && message)" @click="submit()"><i class="icon-send"></i> Göndər</button>
                        </span>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue"
    export default {
        mounted() {
            this.initialize();
        },
        watch: {
            message: function(val) {
                console.log('updated');
                console.log(val);
                this.message = val;
            }
        },
        props: {
            message: {
                type: Object
            },
            id: {
                type: String | Number
            }
        },
        data: function (){
            return {
                messageContainerHeight: 600,
                response: '',
            }
        },
        methods: {
            initialize() {
                const navHeight = $('.navbar.navbar-expand.d-flex.justify-content-between.bd-navbar.white.shadow').outerHeight();
                const windowHeight = window.innerHeight;
                this.messageContainerHeight = windowHeight - ( navHeight + 80 );
            },
            submit() {
                this.$emit('input', {message: this.response, id: this.message.id, room: this.message.room});
                this.response = null;
            },
        },
        components: {
            modal: Modal
        }
    }

</script>
<style>
    .font-16{
        font-family: 'Segoe UI', Helvetica;
        font-size: 16px;
        color: #000;
    }
    .font-15{
        font-family: 'Segoe UI', Helvetica;
        font-size: 15px;
        color: #000;
    }
</style>
