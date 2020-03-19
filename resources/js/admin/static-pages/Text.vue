<template>
    <div>
        <ul class="nav nav-tabs mx-3">
            <li class="nav-item">
                <a class="nav-link" :class="lang === 'az' ? 'active' : ''" @click="lang = 'az'">AZ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="lang === 'ru' ? 'active' : ''" @click="lang = 'ru'">RU</a>
            </li>
        </ul>
        <template v-if="lang === 'az'">
            <div class="form-group">
                <label><strong>Başlıq:</strong></label>
                <input type="text" v-model="sec.value.az.name" class="form-control" placeholder="...">
            </div>
            <div class="form-group">
                <label><strong>Mətn:</strong></label>
                <vue-ckeditor
                        v-model="sec.value.az.description"
                        :config="config"/>
            </div>
        </template>
        <template v-if="lang === 'ru'">
            <div class="form-group">
                <label><strong>Başlıq:</strong></label>
                <input type="text" v-model="sec.value.ru.name" class="form-control" placeholder="...">
            </div>
            <div class="form-group">
                <label><strong>Mətn:</strong></label>
                <vue-ckeditor
                        v-model="sec.value.ru.description"
                        :config="config"/>
            </div>
        </template>
        <div class="form-group text-right" v-if="sec.componentId">
            <button class="btn btn-success" @click="update()">Yadda saxla <i class="icon-send"></i></button>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert2';
    import {api} from "../../apis";
    import VueCkeditor from 'vue-ckeditor2';

    export default {
        mounted() {

        },
        props: {
            sec: {
                type: Object
            }
        },
        data: function (){
            return {
                config: {
                    height: 300
                },
                lang: 'az'
            }
        },
        methods: {
            update() {
                swal({
                    title: 'Yadda saxlanılır...',
                    onOpen: () => {
                        swal.showLoading();
                        axios({url: api.gUpdateText, method: 'PATCH', data: this.section}).then(response => {
                            swal.close();
                        });
                    }
                })
            },
        },
        components: {
            'vue-ckeditor': VueCkeditor
        }
    }

</script>
<style>

</style>
