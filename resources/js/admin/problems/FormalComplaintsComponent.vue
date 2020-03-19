<template>
    <div class="content complaints" v-if="this.roles.length >0">
        <div class="container-fluid">
            <div class="row">
                <div class="top-search col-md-12 col-sm-12 col-xs-12">
                    <div class="block col-xs-12">
                        <form action="...">
                            <div class="row">
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Axtarış">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <div class="input-group">
                                        <select class="selectpicker">
                                            <option>Tarixə görə</option>
                                            <option>Müştəri koduna görə</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <button type="button" class="btn-effect">Axtar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="top-menu">
                        <h4>PROBLEMLƏRİN HƏLLİ</h4>
                        <ol class="breadcrumb">
                            <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                            <li class="active1">PROBLEMLƏRİN HƏLLİ</li>
                        </ol>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button" to="/problems/lost">İTMİŞ BAĞLAMALARIN AXTARILMASI</router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button " to="/problems/complaints">MÜŞTƏRİ ŞİKAYƏTLƏRİ</router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button active" to="/problems/formal-complaints">RƏSMİ ŞİKAYƏTLƏR</router-link>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="block list-block col-xs-12">
                        <div class="col-xs-2">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Müştəri adı" v-model="name">
                                <p style="color:red;" v-if="errors[0]">{{errors[0].name}}</p>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Soyadı" v-model="surname">
                                <p style="color:red;" v-if="errors[1]">{{errors[1].surname}}</p>

                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Kodu" v-model="uniqid">
                                <p style="color:red;" v-if="errors[2]">{{errors[2].uniqid}}</p>

                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="select-outer">
                                <div class="select-header form-control">
                                    <p data-title="Şikayət etdiyi bölmə" @click="openSelect($event)">Şikayət etdiyi bölmə</p>
                                </div>
                                <ul class="select-inner">
                                    <li v-for="item in complaint_types" class="select-item"  @click="getId(item.id, $event, 1)">{{item.key}}</li>
                                </ul>
                                <p style="color:red;" v-if="errors[3]">{{errors[3].type_ids}}</p>

                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="select-outer">
                                <div class="select-header form-control" >
                                    <p data-title="Şikayətin səbəbi" @click="openSelect($event)">Şikayətin səbəbi</p>
                                </div>
                                <ul class="select-inner">
                                    <li v-for="item in complaint_reasons" class="select-item"  @click="getId(item.id, $event, 2)">{{item.key}}</li>
                                </ul>
                                <p style="color:red;" v-if="errors[4]">{{errors[4].reason_ids}}</p>

                            </div>
                        </div>
                        <div class="col-xs-2 button-last text-left">
                            <div class="form-group">
                                <span><img src="admin/new/img/upload1.png" alt="upload">Şəkli yüklə</span>
                                <input  type="file" name="upload" id="file" ref="file" v-on:change="handleFileUpload()">
                            </div>
                            <button type="button" class="btn-effect" v-on:click="submit()">Əlavə et</button>
                        </div>
                    </div>
                    <div class="block table-block col-xs-12">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Müştəri adı, Soyadı, Kodu</th>
                                <th>Şikayət etdiyi bölmə</th>
                                <th>Şikayətin səbəbi</th>
                                <th>Şəkil</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in complaints">
                                <td>{{item.name}} {{item.surname}} {{item.uniqid}}</td>
                                <td>{{customSplit(item.type_ids,1)}}</td>
                                <td>{{customSplit(item.reason_ids,2)}}</td>
                                <td>
                                    <a href="javascript:void(0)" @click="showComplaint(item)" class="btn-effect">Bax</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <show-complaint-modal v-bind:data="showComplaintData" v-bind:show="showComplaintModal" @close-modal="closeModal" ></show-complaint-modal>

    </div>
</template>
<script>
    import ShowComplaintModal from "./showComplaintModal";
    import auth from '../auth.js'
    export default {
        components: {ShowComplaintModal},
        name: "ComplaintsComponent",
        mounted() {
            this.getComplaintTypes();
            this.getComplaints();
        },
        data: function () {
            return {
                complaint_types: [],
                complaint_reasons: [],
                complaints: '',
                type_ids: [],
                reason_ids: [],
                name:null,
                surname: null,
                uniqid: null,
                file: '',
                complaint_types_arr: [],
                complaint_reasons_arr: [],

                showComplaintData: '',
                showComplaintModal: false,
                errors:[]

            }
        },
        methods: {
            closeModal(){
                this.showComplaintModal = false;
            },
            showComplaint(item){
                this.showComplaintModal = true;
                let data = JSON.parse(JSON.stringify(item));
                this.showComplaintData = data;
            },
            openSelect(event){
                let elem=event.target;
                let outer = $(elem).parents('.select-outer');
                $(outer).find('.select-inner').toggle();
            },
            handleFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            submit(){
                if(this.checkForm()==='true') {
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('name', this.name);
                    formData.append('surname', this.surname);
                    formData.append('uniqid', this.uniqid);
                    formData.append('type_ids', this.type_ids);
                    formData.append('reason_ids', this.reason_ids);

                    axios.post('/admin/problems/add-complaint',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(data => {
                        console.log(data)
                        this.getComplaints();
                    })
                }
            },
            checkForm(){
                this.errors=[];
                if(!this.name) this.errors[0]={name:"Ad boş ola bilməz."}
                if(!this.surname) this.errors[1]={surname:"Soyad boş ola bilməz."}
                if(!this.uniqid) this.errors[2]={uniqid:"Kod boş ola bilməz."}
                if(this.type_ids.length===0) this.errors[3]={type_ids:"Şikayətin böıməsini seçin."}
                if(this.reason_ids.length===0) this.errors[4]={reason_ids:"Şikayət səbəbi seçin."}
                return (this.errors.length===0 ? 'true' : 'false');
            },
            getId(id, event, type){

                let arr_name = (type==1 ? this.type_ids : this.reason_ids);
                let elem=event.target;
                if($(elem).hasClass('selected')) {
                    $(elem).removeClass('selected');
                    let index = arr_name.indexOf(id);
                    arr_name.splice(index, 1);
                }
                else {
                    $(elem).addClass('selected');
                    arr_name.push(id);
                }
            },
            getComplaintTypes(){
                axios.get('/admin/problems/complaint-types')
                    .then(data => {
                        this.complaint_types = data.data.data.complaint_types;
                        for(let i in this.complaint_types){
                            if(this.complaint_types_arr.indexOf(this.complaint_types[i].id) < 0)
                                this.complaint_types_arr[this.complaint_types[i].id] = this.complaint_types[i].key;
                        }

                        this.complaint_reasons = data.data.data.complaint_reasons;
                        for(let i in this.complaint_types){
                            if(this.complaint_reasons_arr.indexOf(this.complaint_reasons[i].id) < 0)
                                this.complaint_reasons_arr[this.complaint_reasons[i].id] = this.complaint_reasons[i].key;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getComplaints(){
                axios.get('/admin/problems/complaints')
                    .then(data => {
                        this.complaints = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });

            },

            customSplit(str,type){
                let arr = (type==1 ? this.complaint_types_arr : this.complaint_reasons_arr)
                let ids = str.split(",");
                let result = [];
                for(let i in ids){
                    result.push(arr[ids[i]]);
                }
                return result.join();
            }
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','problematic_department'))
            }
        },
        mixins: [auth]
    }
</script>


<style scoped>
    .select-inner{
        display: none;
        position: absolute;
        list-style: none;
        background: rgb(255, 255, 255);
        width: 100%;
        padding: 0px;
        margin: 0px;
        border: 1px solid #d2d4d5;
        z-index: 9999;
        margin-top: 1%;
    }
    .selected{
        background: #eef3f6;
    }
    .select-outer{
        position: relative;
    }
    .select-item{
        cursor: pointer;
        padding: 3% 0;
        border-bottom: 1px solid #d2d4d5;
    }
    .select-header p{
        cursor: pointer;
    }
    .block.list-block{
        margin-top: 1%;
    }
    .active{
        color: #f95732 !important;
    }
    li.active1{
        color: black ;
    }
    .complaints .multi-select .btn-group>.btn:first-child {
        max-width: 100% !important;
    }
    .white-button{
        background: #ffffff;
        border:none;
        border-radius:10px;
        color: black ;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>