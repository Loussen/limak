<template>
    <div class="container" v-if="this.roles.length >0">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top-menu">
                    <h4>İstifadəçi tənzimlənmələri</h4>
                    <ol class="breadcrumb">
                        <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                        <li class="active">tənzimləmələr</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row user-management">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">APİ - lər</div>
                    <div class="panel-body">
                        <a href="/cp/apis" class="btn btn-default">Siyahı</a>
                        <a href="/cp/apis/create" class="btn btn-default">Əlavə et</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Admin tipləri</div>
                    <div class="panel-body">
                        <a href="/cp/roles" class="btn btn-default">Siyahı</a>
                        <a href="/cp/roles/create" class="btn btn-default">Əlavə et</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Adminlər</div>
                    <div class="panel-body">
                        <a href="/cp/admins" class="btn btn-default">Siyahı</a>
                        <a href="/cp/admins/create" class="btn btn-default">Əlavə et</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth.js'
    export default {
        name: "UserManagementComponent",
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .user-management{
        margin-top: 50px;
    }
    .btn-default{
        width: 49%;
    }
</style>