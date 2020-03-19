<template>
    <div class="container" v-if="sections">
        <ul class="nav nav-pills pb-4 pt-3">
            <li role="presentation" class="btn btn-outline-primary">
                <router-link v-bind:to="'/' + id"><strong style="color: #03a9f4"> Ümumi</strong></router-link>
            </li>
            <li role="presentation" class="active btn btn-outline-primary">
                <router-link v-bind:to="'/' + id + '/generator'"><strong style="color: white">Generator</strong></router-link>
            </li>
        </ul>
        <div class="row">
            <div class="col-12">
                <ul class="list-group pr-0">
                    <li class="list-group-item" v-for="section in sections">
                        <template v-if="section.type === 'text'">
                            <ctext v-bind:section="section"></ctext>
                        </template>
                        <template v-if="section.type === 'media'">
                            <cmedia v-bind:section="section"></cmedia>
                        </template>
                    </li>
                    <li v-if="!sections.length || (sections.length && sections.length === 0)">
                        <div class="alert alert-info">
                            <strong>Məlumat!</strong> Səhifə generatoru üçün component seçimini ekranın sağ aşağı küncündə olan
                            <button title="Yeni komponent əlavə et" class="btn btn-sm btn-info"><i class="icon-plus"></i></button> düyməsini seçə bilərsiniz
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-12 text-right mt-3" v-if="sections.length && sections.length > 0">
                <button class="btn btn-success" @click="submit()">Göndər</button>
            </div>
        </div>
        <button class="floating-button btn" @click="add()" title="Yeni komponent əlavə et"><i class="icon-plus"></i></button>
        <modal size="modal-md" :toggle="modalToggle" :modal="'component'" @modal="modalToggle = $event">
            <template slot="header">
                Komponent seçimi
            </template>
            <template slot="body">
                <div class="alert alert-info px-2">
                    <strong>Diqqət!</strong> Göstərilmiş komponent tiplərindən birini seçərək davam edə bilərsiniz.
                    <ul class="mt-3">
                        <li><strong>Text:</strong> Bu səhifəyə mətn tipli komponentin əlavə olunmasına imkan yaradır</li>
                        <li><strong>Media:</strong> Bu səhifəyə (Şəkil / Youtube video) tipli komponentin əlavə olunmasına imkan yaradır</li>
                    </ul>
                </div>
                <ul class="list-group">
                    <li class="list-group-item ct" v-for="ct of cts" @click="component(ct)">
                        <strong>{{ct.label}}</strong>
                    </li>
                </ul>
            </template>
            <template slot="footer">
                <button class="btn btn-default" @click="modalToggle = false">Bağla</button>
            </template>
        </modal>
    </div>
</template>

<script>
    import {api} from "../../apis";
    import Modal from "../../shared/Modal.vue"
    import Text from "./Text.vue"
    import Media from "./Media.vue"
    import swal from 'sweetalert2'
    export default {
        mounted() {
            this.initialize();
        },
        props: {

        },
        data: function (){
            return {
                "id": null,
                "modalToggle": false,
                "cts": [],
                "sections": []
            }
        },
        methods: {
            initialize() {
                this.id = this.$route.params.id;
                this.fetch();
            },
            load() {
                return axios.get(api.gList + this.id);
            },
            fetch() {
                this.load().then(response => {
                    const data = new GeneratorData(response.data).section;
                    this.sections = data;
                });
            },
            add() {
                axios.get(api.ctList).then(
                    response => {
                        if(response && response.data) {
                            this.cts = response.data
                        }
                    }
                );
                this.modalToggle = true;
            },
            component(ct) {
                const section = {
                    id: ct.id,
                    type: ct.label,
                    value:{
                        az: {name: null, description: null},
                        ru: {name: null, description: null},
                    }
                };
                this.sections = [...this.sections, section]
                console.log(this.sections);
            },
            submit() {
                const  formData = new FormData();

                const requestData = new PageGeneratorRequest(this.sections, formData, this.id);
                // this.debugFormData(requestData.form);
                if (requestData.valid && requestData.form) {
                    swal({
                        title: 'Göndərir...',
                        onOpen: () => {
                            swal.showLoading();
                            axios({url: api.gInsert, method: 'POST', data: requestData.form}).then(response => {
                                swal.close();
                            });
                        }
                    })
                } else {
                    swal('Sahələrin seçildiyinə əmin olduqdan sonra davam edə bilərsiniz', '', 'error')
                }
            },
            debugFormData(requestData) {
                const formD = requestData.form.entries();
                for (var pair of formD) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
            }
        },
        components: {
            modal: Modal,
            ctext: Text,
            cmedia: Media
        }
    }
    class PageGeneratorRequest {
        constructor(sections, form, id){
            this.valid = false;
            this.form = null;
            form.append(`id`, id);
            this._modify(sections, form);
        }
        _modify(sections, form){
            if (sections, sections.length, sections.length > 0) {
                sections.forEach((section, index) => {
                    if(!section.componentId) {
                        form.append(`section[${index}][contentTypeId]`, section.id);
                        form.append(`section[${index}][contentTypeLabel]`, section.type);
                        this.valid = true;
                        switch(section.type) {
                            case 'text':
                                form.append(`section[${index}][value][az][name]`, section.value.az.name);
                                form.append(`section[${index}][value][ru][name]`, section.value.ru.name);
                                form.append(`section[${index}][value][az][description]`, section.value.az.description);
                                form.append(`section[${index}][value][ru][description]`, section.value.ru.description);
                                break;
                            case 'media':
                                this._media(form, section.value, index)
                                break;
                        }
                        form.append(`section[${index}][contentTypeId]`, section.id);
                    }
                });
                this.form = form;
            } else {
                this.valid = false;
            }
        }
        _media(form, media, index) {
            switch(media.subtype) {
                case 'video':
                    form.append(`section[${index}][value]`, media.data)
                    form.append(`section[${index}][name]`, media.name)
                    form.append(`section[${index}][subtype]`, media.subtype);
                    break;
                case 'photo':
                    form.append(`section[${index}][subtype]`, media.subtype);
                    this._photo(form, media.data, index);
                    break;
            }
        }
        _photo(form, files, index) {
            if (files && files.length && files.length > 0) {
                files.forEach((file, i) => {
                    form.append(`section[${index}][value][${i}][name]`, file.nameChange ? file.name : '');
                    form.append(`section[${index}][value][${i}][file]`, file.file);
                })
            } else {
                this.valid = false;
            }
        }
    }
    class GeneratorData {
        constructor(data) {
            this.section = this._modifyData(data);
        }
        _modifyData(sections) {
            if(sections.length && sections.length > 0) {
                return sections.map( section => {
                    if(section.type === 'text') {
                        section = {
                            type: section.type,
                            componentId:   'has',
                            value: this._refactorText(section.data)
                        };
                    } else if (section.type === 'media') {
                        const d = this._refactorMedia(section);
                        if(d.subtype === 'image') {
                            section = {
                                type: section.type,
                                value: d.value,
                                subtype: d.subtype,
                                componentId: d.id,
                                media_id: d.media_id,
                                media_file_id: d.media_file_id
                            };
                        } else if(d.subtype === 'video') {
                            section = {
                                type: section.type,
                                value: {name: d.name, link: d.link, },
                                subtype: d.subtype,
                                componentId: d.id,
                                media_id: d.media_id,
                                media_file_id: d.media_file_id
                            };
                        }
                    }
                    return section;
                })
            }
            return [];
        }
        _refactorText(section) {
            let az = {name: null, description: null};
            let ru = {name: null, description: null};
            section.forEach(item => {
                if (item.locale === 'az') {
                    az = {
                        name: item.name,
                        description: item.description,
                        locale: item.locale,
                        id: item.id
                    };
                } else if(item.locale === 'ru') {
                    ru = {
                        name: item.name,
                        description: item.description,
                        locale: item.locale,
                        id: item.id
                    }
                }
            });
            const data = Object.assign({}, {az, ru});
            return data;
        }
        _refactorMedia(section) {
            let data = [];
            if (section.files[0].type === 'photo') {
                data = {
                    subtype: 'image',
                    id: 'in-array',
                    media_id: section.id,
                    value: section.files.map(file => {
                        if (file.type === 'photo') {
                            return {
                                file: null,
                                b64: file.file,
                                name: file.name,
                                id: file.id,
                                media_id: file.media_id,
                                media_file_id: file.media_file_id,
                            };
                        }
                    })
                }
            } else {
                data = {
                    subtype: 'video',
                    name: section.files[0].name,
                    link: section.files[0].file,
                    id: section.files[0].id,
                    media_id: section.files[0].media_id,
                    media_file_id: section.files[0].media_file_id,
                }
            }
            return data;
        }
    }
</script>
<style>
    .ct{
        transition: 200ms;
        cursor: pointer;
    }
    .ct:hover {
        background: #d9ecfa;
        border: #00b0ff;
    }
</style>
