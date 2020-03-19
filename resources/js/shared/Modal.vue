<template>
    <!-- Modal -->
    <div class="modal fade" :ref="'modal-' + modal" tabindex="-1" role="dialog" aria-hidden="true" v-if="">
        <div class="modal-dialog" v-bind:class="size">
            <div class="modal-content">
                <header class="modal-header">
                    <h5 style="display: inline-block;" class="modal-title"><slot name="header"></slot></h5>
                    <button type="button" class="close" @click="setToggle(false)" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </header>
                <main class="modal-body">
                    <slot name="body"></slot>
                </main>
                <footer class="modal-footer">
                    <slot name="footer"></slot>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'modal',
        mounted() {
            this.initialize();
            /*$(this.$refs['modal-' + this.modal]).on('hidden.bs.modal', (e) => {
                this.$emit('modal', false);
            })*/
        },
        props: {
            toggle: {
                type: Boolean,
                default: false,
            },
            modal: {
                type: String,
                default: 'initial'
            },
            size: {
                type: String,
                default: 'modal-lg'
            }
        },
        watch: {
            toggle: function(toggle){
                this.setToggle(toggle);
            }
        },
        data: function (){
            return {
                modalToggle: false
            }
        },
        methods: {
            initialize() {

                this.$emit('modal', this.modalToggle);
                let self = this;
                /*window.addEventListener("load", function(event) {
                    if (self.modalToggle) {
                        $(self.$refs['modal-' + self.modal]).modal('show');
                    } else {
                        $(self.$refs['modal-' + self.modal]).modal('hide');
                    }
                });*/
                if (this.modalToggle) {
                    $(this.$refs['modal-' + this.modal]).modal('show');
                } else {
                    $(this.$refs['modal-' + this.modal]).modal('hide');
                }
            },
            setToggle(toggle) {
                this.modalToggle = toggle;
                this.initialize();
            }
        }
    }
</script>
<style>
    .modal-content{
        min-width: 100%;
    }

    #paytriframe {
        width: 100% !important;
    }
</style>
