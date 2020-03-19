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
                    <li class="media p-4 b-b list-group-item-action">  <!-- light border-left border-primary -->
                        <div class="avatar avatar-md mr-3">
                            <!--<img src="assets/img/dummy/u6.png" alt="">-->
                            <i style="color: black" class="icon" v-bind:title="statusclass(message).title"  v-bind:class="statusclass(message).icon"></i>
                        </div>
                        <div class="media-body text-truncate">
                            <h6 v-if="message.fromUser">{{message.fromUser.name}} {{message.fromUser.surname}}</h6>
                            <small class="float-right">
                                <span>{{message.created_at}}</span>
                            </small>
                            <div class="row">
                                <div class="col-9">
                                    <div class="messagearea">
                                        <p v-bind:title="message.message">
                                            {{message.subject ? message.subject : message.message}}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-3">
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
            }
        },
        data: function (){
            return {

            }
        },
        methods: {
            initialize() {

            },
            statusclass(message) {
                const icon = statusclass(message.status);
                return icon;
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
