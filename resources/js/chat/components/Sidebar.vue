<template>
    <div class="col-md-3 d-none d-md-block text-truncate white b-r">
        <div class="card-header white">
            <div class="form-group has-right-icon m-0">
                <input class="form-control light r-30" placeholder="Search" type="text">
                <i class="icon-search"></i>
            </div>
        </div>
        <div class="slimScroll"  data-height="600">
            <ul class="list-unstyled" v-if="list">
                <template v-for="message in list">
                    <li class="media p-4 b-b list-group-item-action" v-if="!message.responder || message.responder === id" v-bind:class="message.last && message.last !== id ? 'light border-left border-danger' : ''">  <!-- light border-left border-primary -->
                        <div class="media-body text-truncate">
                            <small class="float-right">
                                <span>{{message.created_at}}</span>
                            </small>
                            <h6>{{message.fullname}} <template v-if="message.count">({{message.count}})</template></h6>
                            <div class="row">
                                <div class="col-10">
                                    <div class="messagearea">
                                        <p v-bind:title="message.message">
                                            {{message.message}}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-2 px-0">
                                    <button @click="respond(message)" class="btn btn-success btn-sm" title="Cavabla!"><i class="icon-send"></i> </button>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
    import Modal from "../../shared/Modal.vue"
    import {statusclass} from "../../pipes/slug";
    export default {
        mounted() {
            this.initialize();
        },
        props: {
            list: {
                type: Array
            },
            id: {
                type: String | Number
            }
        },
        data: function (){
            return {

            }
        },
        methods: {
            initialize() {

            },
            respond(message) {
                this.$emit('submit', message);
            }
        },
        components: {
            modal: Modal
        }
    }

</script>
<style>
    .messagearea{
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
</style>
