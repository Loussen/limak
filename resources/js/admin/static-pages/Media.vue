<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="row" v-if="mediaType === 'image'">
                    <div class="col-md-2" v-for="(file, index) in files" @click="show(index)" title="Sil">
                        <img class="img-responsive pb-2" v-bind:src="file.b64" v-bind:alt="file.name">
                    </div>
                    <div class="col-12 text-center mt-3">
                        <label class="btn btn-primary icon-file_upload" title="Fayl yüklə">
                            <input type="file" style="display: none" @change="bindFile($event)" multiple>
                        </label>
                        <div class="alert alert-info">
                            <strong>Diqqət!</strong> Şəkil seçərkən düzgün görünüş səbəbiylə ölçüsündən fərqli olmayaraq eyni ölçülü şəkillərin seçilməsi məsləhət görülür
                        </div>
                    </div>
                </div>
                <div class="row" v-if="mediaType === 'video'">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-control-label">Başlıq:</label>
                            <input type="text" v-model="section.value.name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Link:</label>
                            <input type="text" class="form-control" placeholder="https://www.youtube.com/watch?v=E225LBTSUDWDWU0A" @input="inputted($event)" v-bind:value="section && section.value && section.value.link ? section.value.link : ''">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2 text-right">
                <button class="btn icon-image icon-custom"
                        @click="mediaType = 'image'"
                        v-bind:class="mediaType === 'image' ? 'btn-primary' : 'btn-outline-primary'"
                        title="Şəkil yüklə"></button>
                <button class="btn icon-youtube icon-custom"
                        @click="mediaType = 'video'"
                        v-bind:class="mediaType === 'video' ? 'btn-primary' : 'btn-outline-primary'"
                        title="YouTube video yüklə"></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group text-right" v-if="section.componentId">
                    <button class="btn btn-success" @click="update()">Yadda saxla <i class="icon-send"></i></button>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    import {b64, sizeOfFile} from "../../pipes/slug";
    import swal from 'sweetalert2';
    import {api} from "../../apis";
    export default {
        mounted() {
            this.initialize();
        },
        props: {
            section: {
                type: Object
            }
        },
        data: function (){
            return {
                mediaType: 'image',
                files: [],
                file: null,
                modalTogglePhoto: false
            }
        },
        methods: {
            initialize() {
                if (this.section){
                    this.mediaType = this.section.subtype;
                    if(this.mediaType === 'image') {
                        this.files = this.section.value;
                    } else if (this.mediaType === 'video') {

                    }
                }
            },
            bindFile(event) {
                const files = event.target.files;
                const fileCount = files.length;
                this.files = [];
                for(let i = 0; i < fileCount; i++) {
                    const file = files[i];
                    const newElement = {
                        file: file,
                        b64: null,
                        index: i,
                        size: sizeOfFile(file),
                        name: file.name
                    };

                    b64(file, (base64) => {
                        this.files[i].b64 = base64;
                    });
                    this.files = [...this.files, newElement]
                }
                this.section.value = {type: 'media', subtype: 'photo', data: this.files};
            },
            removePhoto(index) {
                this.files.splice(index, 1);
            },
            update() {
                if (this.mediaType === 'video') {
                    swal({
                        title: 'Yadda saxlanılır...',
                        onOpen: () => {
                            swal.showLoading();
                            axios({url: api.gUpdateVideo, method: 'PATCH', data: this.section}).then(response => {
                                swal.close();
                            });
                        }
                    })
                } else if(this.mediaType === 'image') {
                    swal({
                        title: 'Yadda saxlanılır...',
                        onOpen: () => {
                            swal.showLoading();
                            const form = new FormData();
                            form.append('media_id', this.section.media_id);
                            form.append('subtype', this.mediaType);
                            form.append('type', this.section.type);
                            if(this.section.value.data.length && this.section.value.data.length > 0) {
                                this.files.forEach(
                                    (file, index) => {
                                        form.append(`file[${index}][file]`, file.file);
                                        if(file.nameChange) {
                                            form.append(`file[${index}][name]`, file.name);
                                        }
                                    }
                                );
                            }
                            axios({url: api.gUpdateImage, method: 'POST', data: form}).then(response => {
                                swal.close();
                            });
                        }
                    })
                }
            },
            show(index) {
                this.file = this.files[index];
                swal({
                    input: 'text',
                    inputPlaceholder: 'Başlıq',
                    html: `
                    <img class="img-responsive pb-2" src="${this.file.b64}" alt="${this.file.name}">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>Ad:</td>
                            <th>${this.file.name}</th>
                        </tr>
                        <tr>
                            <td>Ölçü:</td>
                            <th>${this.file.size}</th>
                        </tr>
                        </tbody>
                    </table>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Yadda saxla',
                    showCloseButton: true,
                    showCancelButton: true,
                    cancelButtonText:
                        '<i class="icon-close"></i> Sil!',
                }).then((result) => {
                    if(result.value) {
                        this.file.name = result.value;
                        this.file.nameChange = true;
                    }
                    if (result.dismiss === 'cancel') {
                        this.removePhoto(index);
                    }
                })
            },
            inputted(event) {
                this.section.value = {type: 'media', subtype: 'video', data: event.target.value, name: this.section.value.name, link: event.target.value};
            }
        },
        components: {

        }
    }

</script>
<style scoped>
    .icon-custom{
        font-size: x-large;
        display: initial;
    }
    .image-border{
        border: 1px solid gray;
    }
</style>
