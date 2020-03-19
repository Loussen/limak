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
                                        <input type="text" class="form-control" placeholder="Axtarış" >
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
                            <router-link class="btn-effect white-button active" to="/problems/lost">İTMİŞ BAĞLAMALARIN AXTARILMASI</router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button" to="/problems/complaints">MÜŞTƏRİ ŞİKAYƏTLƏRİ</router-link>
                        </div>
                        <div class="col-md-4">
                            <router-link class="btn-effect white-button" to="/problems/formal-complaints">RƏSMİ ŞİKAYƏTLƏR</router-link>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="body block col-xs-12">
                        <div class="row">
                            <div class="col-md-10 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Axtarış" v-model="keyword">
                                    <select name="link" class="selectpicker" v-model="selected">
                                        <!--<option value="link_tag" v-bind:value="{ key: 'link'}">Link</option>-->
                                        <option value="id" v-bind:value="{ key: 'id'}">ID</option>
                                        <!--<option value="purchase_no"  v-bind:value="{ key: 'purchase_no'}">Link tag</option>-->
                                        <option value="shop_name"  v-bind:value="{ key: 'shop_name'}">Mağaza</option>
                                        <option value="purchase_no"  v-bind:value="{ key: 'purchase_no'}">Sifariş nömrəsi</option>
                                        <option value="propduct_type_name"  v-bind:value="{ key: 'product_type_name'}">Məhsul tipi</option>
                                        <!--<option value="product_name"  v-bind:value="{ key: 'product_name'}">Məhsul adı</option>-->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <button type="button" class="btn-effect" @click="searchData">Axtar</button>
                            </div>

                        </div>
                    </div>
                    <div v-if="data.length" class="block table-block col-xs-12">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Link</th>
                                <th>ID</th>
                                <th>Link tag</th>
                                <th>Mağaza</th>
                                <th>Sifarişin nömrəsi</th>
                                <th>Məhsul tipi</th>
                                <th>Məhsul adı</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in data">
                                <td>
                                    <a href="">link</a>
                                </td>
                                <td>{{item.id}}</td>
                                <td>{{item.link_tag}}</td>
                                <td>{{item.shop_name}}</td>
                                <td>{{item.purchase_no}}</td>
                                <td>{{item.product_type_name}}</td>
                                <td>{{item.product_name}}</td>
                            </tr>
                            </tbody>
                        </table>
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
        name: "LostComponent",
        mounted() {
            $('.selectpicker').selectpicker('refresh');
        },
        data: function () {
            return {
                data: [],
                selected: '',
                keyword: ''
            }
        },
        methods: {
            searchData(){
                axios.post('/admin/problems/lost-packages', {
                    key: this.selected.key,
                    keyword: this.keyword
                })
                .then(data => {
                    this.data = data.data.data;
                })
                .catch(err => {
                    console.log(err);
                });
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

    .active{
        color: #f95732 !important;
    }
    .body{
        margin-top: 1%;
    }
    li.active1{
        color: black ;
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