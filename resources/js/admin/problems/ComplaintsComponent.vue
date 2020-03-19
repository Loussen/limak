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
                            <li>
                                <router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link>
                            </li>
                            <li class="active1">PROBLEMLƏRİN HƏLLİ</li>
                        </ol>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button" to="/problems/lost">İTMİŞ BAĞLAMALARIN
                                AXTARILMASI
                            </router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button active" to="/problems/complaints">MÜŞTƏRİ
                                ŞİKAYƏTLƏRİ
                            </router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button " to="/problems/formal-complaints">RƏSMİ
                                ŞİKAYƏTLƏR
                            </router-link>
                        </div>
                    </div>
                </div>

                <div v-for="item in complaints" class="col-md-12" style="margin-top: 20px">
                    <div class="block row complain">
                        <div class="col-md-4">
                            <p>Müştəri adı, soyadı, kodu</p>
                            <h5>{{item.name}} {{item.surname}}</h5>
                            <h6><b> {{item.uniqid}}</b></h6>
                        </div>
                        <div class="col-md-6">
                            <p>Sorğu başlığı</p>
                            <h5 v-if="type_reason === true" :class="item.seen ===0 ? 'notif' : ''">
                                {{customSplit(item.complaint_type_id,1)}},{{customSplit(item.complaint_reason_id,2)}} </h5>
                        </div>
                        <div class="col-md-2">
                            <button @click="showComplain(item.id)" class="btn btn-effect btn-complain">
                                Bax
                            </button>
                        </div>
                    </div>
                    <div v-if="show_complain" class="modal fade in complain-modal" style="display: block">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button @click="closeModal()" type="button" class="close">&times;</button>
                                    <h4 class="modal-title">CAVABLA</h4>
                                </div>
                                <div class="modal-body" style="max-height: 400px; overflow-y: scroll">
                                    <div v-for="item in messages">
                                        <div v-if="item.type === 0" class="customer">
                                            <div class="name">{{item.name}} {{item.surname}}</div>
                                            <div class="code">{{item.uniqid}}</div>
                                            <div class="request">{{item.message}}
                                            </div>
                                            <div class="date">{{item.created_at}}</div>
                                            <div>
                                                <img style="width: 200px" v-if="item.file !== null" v-bind:src="'/complaints/'+item.file"
                                                     alt="">
                                            </div>

                                        </div>
                                        <hr>
                                        <div v-if="item.type === 1" class="representative">
                                            <div class="name">{{item.a_name}} {{item.a_surname}}</div>
                                            <div class="job">{{item.role_name}}</div>
                                            <div class="request">{{item.message}}
                                            </div>
                                            <div class="date">{{item.created_at}}</div>
                                        </div>
                                    </div>

                                    <div class="answer">
                                        <div class="form-group">
                                            <textarea v-model="form.message" class="form-control" rows="3"
                                                      id="comment"></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button @click="sendMessage" class="btn btn-effect btn-complain">Göndər</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--<div class="right-side col-md-12 col-sm-12 col-xs-12" style="display: none">-->
                    <!--<div class="tab-content">-->
                        <!--<div role="tabpanel" class="tab-pane fade in active" id="antiinhisar">-->
                            <!--<div class="block table-block">-->
                                <!--<table class="table table-striped">-->
                                    <!--<thead>-->
                                    <!--<tr>-->
                                        <!--<th>Müştəri adı, Soyadı, Kodu</th>-->
                                        <!--<th>Şikayət etdiyi bölmə</th>-->
                                        <!--<th>Şikayətin səbəbi</th>-->
                                        <!--<th>Qeyd</th>-->
                                        <!--<th>Şəkil</th>-->
                                    <!--</tr>-->
                                    <!--</thead>-->
                                    <!--<tbody>-->
                                    <!--<tr v-for="item in complaints">-->
                                        <!--<td>{{item.name}} {{item.surname}} {{item.uniqid}}</td>-->
                                        <!--<td>{{customSplit(item.complaint_type_id,1)}}</td>-->
                                        <!--<td>{{customSplit(item.complaint_reason_id,2)}}</td>-->
                                        <!--<td>{{item.description}}</td>-->
                                        <!--<td>-->
                                            <!--<p style="color: red;cursor: pointer" v-if="item.status === 0"-->
                                               <!--&gt;Həll et</p>-->
                                            <!--<p style="color: green;" v-if="item.status === 1">Həll edilib</p>-->
                                        <!--</td>-->
                                        <!--&lt;!&ndash;<td class="web"><a href="javascript:void(0)" @click="showComplaint(item)" class="btn-effect border-btn">Bax</a>&ndash;&gt;-->
                                        <!--<td class="web"><img style="width: 100px"-->
                                                             <!--v-bind:src="'/complaints/'+item.image"></td>-->
                                    <!--</tr>-->
                                    <!--</tbody>-->
                                <!--</table>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</template>
<script>
    import auth from '../auth.js'

    export default {
        components: {},
        name: "ComplaintsComponent",
        mounted() {
            // this.getFormalComplaints(1)
            this.getComplaints();
            this.getComplaintTypes();
            setInterval(function () {
                if (this.selectedComplaintId !== '') {
                    this.getComplaints()
                    this.showComplain(this.selectedComplaintId);
                }else{
                    this.getComplaints()
                }
            }.bind(this), 15000);
        },
        data: function () {
            return {
                form:{
                    message: ''
                },
                data: [],
                complaints: '',
                // complaint_types: [],
                // complaint_reasons: [],
                complaint_types_arr: [],
                complaint_reasons_arr: [],
                show_complain: false,
                messages: '',
                type_reason: false,
                selectedComplaintId:''

            }
        },
        methods: {
            sendMessage(){
                let formData = new FormData();
                formData.append('message', this.form.message);
                formData.append('user_complaint_id', this.selectedComplaintId);
                axios.post('/admin/problems/send-message', formData
                ).then(data => {
                    this.form.message = '';
                    this.showComplain(this.selectedComplaintId);
                });
            },
            showComplain(id) {
                this.selectedComplaintId = id;
                this.show_complain = true;
                axios.get(`/admin/problems/get-complaint/${id}`)
                    .then(data => {
                        this.messages = data.data.data;
                        this.getComplaints();
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getComplaints() {
                axios.get('/admin/problems/user-complaints')
                    .then(data => {
                        this.complaints = data.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });

            },
            getFormalComplaints(type, event) {
                if (event != null) {
                    $('li.active').removeClass('active');
                    let elem = event.target;
                    $(elem).addClass('active');
                }
                axios.get('/admin/problems/formal-complaints/' + type)
                    .then(data => {
                        this.data = data.data.data;
                    })
            },
            customSplit(key, type) {
                return (type == 1 ?
                        this.complaint_types_arr[key]
                        :
                        this.complaint_reasons_arr[key]
                )
            },
            getComplaintTypes() {
                axios.get('/admin/problems/complaint-types')
                    .then(data => {
                        let complaint_types = data.data.data.complaint_types;
                        for (let i in complaint_types) {
                            if (this.complaint_types_arr.indexOf(complaint_types[i].id) < 0)
                                this.complaint_types_arr[complaint_types[i].id] = complaint_types[i].key;
                        }

                        let complaint_reasons = data.data.data.complaint_reasons;
                        for (let i in complaint_reasons) {
                            if (this.complaint_reasons_arr.indexOf(complaint_reasons[i].id) < 0)
                                this.complaint_reasons_arr[complaint_reasons[i].id] = complaint_reasons[i].key;
                        }
                        this.type_reason = true;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            closeModal(){
                this.show_complain =false;
                this.selectedComplaintId= ''

            }
        },
        updated() {
            if (this.roles.length > 0) {
                this.ifNoAccessRedirect(Array('super_admin', 'problematic_department'))
            }
        },
        mixins: [auth]
    }
</script>


<style scoped>
    h5{
        position: absolute;
    }
    .notif:after{
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: red;
        position: absolute;
        top: 50%;
        right: -15px;
        transform: translateY(-50%);
    }
    .block {
        margin-top: 1%;
        padding: 20px;
    }

    .active {
        color: #f95732 !important;
    }

    li.active1 {
        color: black;
    }

    .nav li {
        color: #231f20;
        font-size: 16px;
        padding: 30px 15px;
        display: inline-block;
        border: none;
        font-weight: 500;
        cursor: pointer;
    }

    .white-button {
        background: #ffffff;
        border: none;
        border-radius: 10px;
        color: black;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .block.row.complain {
        text-align: left;
        margin: 0 0 15px;
    }

    .block.row.complain p {
        font-size: 12px;
        color: #a09d9d;
    }

    .btn-complain {
        width: 86px;
        height: 27px;
        margin-top: 39px;
        font-size: 12px;
        border-radius: 15px;
    }

    .complain .col-md-2 {
        text-align: right
    }

    .representative .name, .customer .name {
        font-weight: bold;
    }

    .modal .code, .modal .job, .modal .date {
        color: #a09d9d;
    }

    .modal .code, .modal .job {
        font-size: 12px;
    }

    .modal .request, .modal .answer {
        font-size: 12px;
    }

    .representative div, .customer div {
        margin: 5px 0;
    }

    .modal .date {
        font-size: 11px;
    }

    .complain-modal {
        padding: 7px 30px;
    }

    .complain-modal .representative {
        text-align: right;
    }

    .complain-modal .btn-complain {
        margin-top: 0;
    }


</style>