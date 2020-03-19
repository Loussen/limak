<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="col-md-12 tab-content">
            <div class="block table-block">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Konşimento no</th>
                        <th>Toplam fiyat</th>
                        <th>Toplam ağırlık</th>
                        <th>Toplam hacim</th>
                        <th>Gönderim tarihi</th>
                        <th>Fayl</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="dispatch in dispatchs">
                        <td>{{ dispatch.consignment}}</td>
                        <td>{{ dispatch.total_price}}</td>
                        <td>{{ dispatch.total_weight}}</td>
                        <td>{{ dispatch.total_volume}}</td>
                        <td>{{ dispatch.dispatch_date}}</td>
                        <td v-if="dispatch.file!=null"><a @click="openLink(dispatch.file)"> Fayl</a></td>
                        <td v-else>Yoxdur</td>
                        <td><p @click='openLink("https://tr.limak.az/invoices/exportFile?id="+dispatch.id)'>Bax</p></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Menu from './AccountantMenuComponent'
    import auth from '../auth.js'
    export default {
        name: "DispatchComponent",
        data: function(){
            return {
                dispatchs: [],}
        },
        methods: {
           getDispatchs: function(){
               axios.get('/cp/accountant/dispatchs')
                   .then(({data}) => {
                       this.dispatchs = data.dispatchs;
                   })
                   .catch(function (error) {
                       console.log(error);
                   });
           },
            openLink: function (link) {
                window.open(link, "_blank");
            }
        },
        mounted(){
            this.getDispatchs();
        },
        components: {
            Menu
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','accountant'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .table > tbody > tr > td:last-child, .table > thead > tr > th:last-child {
        text-align: left;
    }
    .table > tbody > tr > td:first-child, .table > thead > tr > th:first-child {
        width: 30%;
    }
    .tab-content{
        margin-top: 20px;
    }
    .btn-default.active{
        color: #f95732;
    }
</style>