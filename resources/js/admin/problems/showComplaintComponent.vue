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
            <!--</div>-->
            <!--<div class="row">-->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="top-menu">
                        <h4>PROBLEMLƏRİN HƏLLİ</h4>
                        <ol class="breadcrumb">
                            <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                            <li class="active">PROBLEMLƏRİN HƏLLİ</li>
                        </ol>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row tabs">
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button" to="/problems/lost">İTMİŞ BAĞLAMALARIN AXTARILMASI</router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button active" to="/problems/complaints">MÜŞTƏRİ ŞİKAYƏTLƏRİ</router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button" to="/problems/formal-complaints">RƏSMİ ŞİKAYƏTLƏR</router-link>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-9 col-sm-3 col-xs-12">
                    <div class="block customer-list auto-height">
                        <ul class="list-user"> 
                            <li>Ad, Soyad <p>{{data.name}} {{data.surname}}</p></li>
                            <li>
                                <ul class="inline-list">
                                    <li>Müştəri kodu<p>{{data.uniqid}}</p></li>
                                    <li>E-mail <p>mahirveliyev@gmail.com</p></li>
                                    <li>Fin kodu <p>94FMDDD</p></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="balance block auto-height">
                        <p class="btn-effect">Balans <span>67.89</span></p>
                        <form action="...">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="input-group number-spinner">
                                            <span class="input-group-btn data-dwn">
                                                <button type="button" class="btn btn-default" data-dir="dwn">-</button>
                                            </span>
                                        <input type="text" class="form-control text-center" value="1" min="-10" max="40">
                                        <span class="input-group-btn data-up">
                                                <button type="button" class="btn btn-default" data-dir="up">+</button>
                                            </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <textarea class="form-control" rows="5" placeholder="Səbəb"></textarea>
                                </div>
                                <div class="block-button-list col-md-12 col-sm-12">
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect blue">E-mail İlə</button>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn-effect sms blue">SMS İlə</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import auth from '../auth.js'
    export default {
        components: {},
        name: "showComplaintComponent",
        mounted() {
            this.getComplaintDetails();
        },
        data: function () {
            return {
                data: []
            }
        },
        methods: {
            // this.$route.params.id
            getComplaintDetails() {
                axios.get(`admin/problems/get-complaint/${this.$route.params.id}`)
                    .then((data) => {
                        if(data) {
                            console.log(data.data.data)
                            this.data = data.data.data;
                        } else {
                            this.data = [];
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    })
            },
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
.tabs{
    margin-bottom: 1%;
}
</style>