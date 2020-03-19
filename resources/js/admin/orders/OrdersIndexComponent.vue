<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>SİFARİŞLƏR</h4>
                <ol class="breadcrumb">
                    <li>
                        <router-link to="/">
                            <i class="fa fa-home"></i>Ana səhifə
                        </router-link>
                    </li>
                    <li class="active">Sifarişlər</li>
                </ol>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div v-if="hasAccess(Array('super_admin','buyer'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/incoming">
                        <div class="block">
                            <img src="/admin/new/img/gelen-sifarish.png" alt="mushteri">
                            <p>GƏLƏN SİFARİŞLƏR</p>
                            <span><img src="/admin/new/img/endir.png" alt="endir"> {{ orders.incoming_order}}</span>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess(Array('super_admin','buyer'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/executing">
                        <div class="block">
                            <img src="/admin/new/img/ica-olunan.png" alt="sifarish">
                            <p>İCRA OLUNAN SİFARİŞLƏR</p>
                            <span><img src="/admin/new/img/restart.png" alt="restart"> {{ orders.executing }}</span>
                        </div>
                    </router-link>
                </div>

                <div v-if="hasAccess(Array('super_admin','buyer'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/waiting">
                        <div class="block">
                            <img src="/admin/new/img/ica-olunan.png" alt="sifarish">
                            <p>GÖZLƏMƏDƏ OLAN SİFARİŞLƏR</p>
                            <span><img src="/admin/new/img/restart.png" alt="restart"> {{ orders.waiting }}</span>
                        </div>
                    </router-link>

                </div>
            </div>
            <div class="row">
                <div v-if="hasAccess(Array('super_admin','buyer','problematic_department'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/noinvoice">
                        <div class="block">
                            <img src="/admin/new/img/invoys.png" alt="baglama">
                            <p>İnvoys olmayanlar</p>
                            <span><img src="/admin/new/img/error.png" alt="error"> {{ orders.invoice }}</span>
                        </div>
                    </router-link>

                </div>
                
                <div v-if="hasAccess(Array('super_admin','buyer','problematic_department'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/completed">
                        <div class="block">
                            <img src="/admin/new/img/butun.png" alt="anbar">
                            <p>Tamamlanmış SİFARİŞLƏR</p>
                            <span><img src="/admin/new/img/list.png" alt="list">{{ orders.all_order}}</span>
                        </div>
                    </router-link>

                </div>
                <div v-if="hasAccess(Array('super_admin','buyer','problematic_department','dispatcher','operator'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/all">
                        <div class="block">
                            <img src="/admin/new/img/butun.png" alt="mushteri">
                            <p>BÜTÜN BAĞLAMALAR</p>
                            <span><img src="/admin/new/img/list.png" alt="endir"> {{ orders.all_invoices }}</span>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="row">
                <div v-if="hasAccess(Array('super_admin','admin'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/all2">
                        <div class="block">
                            <img src="/admin/new/img/butun.png" alt="mushteri">
                            <p>BAĞLAMA ƏMƏLİYYATLARI</p>
                            <span><img src="/admin/new/img/list.png" alt="endir"> {{ orders.all_invoices }}</span>
                        </div>
                    </router-link>
                </div>
                <div v-if="hasAccess(Array('super_admin','buyer','problematic_department','dispatcher','operator'))" class="block-list col-md-4 col-sm-4 col-xs-12">
                    <router-link to="/orders/late">
                        <div class="block">
                            <img src="/admin/new/img/butun.png" alt="mushteri">
                            <p>GECİKƏN SİFARİŞLƏR</p>
                            <span><img src="/admin/new/img/list.png" alt="endir"> {{ orders.late_invoices }}</span>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import auth from '../auth.js'
export default {
  name: 'orders',
    data () {
        return {
            orders: ''
        }
    },
    mixins: [auth],
    mounted(){
      console.log(this.props);
        this.getAllData();

    },
    methods:{
        getAllData(){
            axios.get('/cp/orders')
                .then((response) => {
                    console.log(response);
                    this.orders = response.data.data;

                })
                .catch(function (error) {
                    console.log(error);
                });

        },
    },


    updated(){
        if(this.roles.length >0){
            this.ifNoAccessRedirect(Array('super_admin','buyer','problematic_department','dispatcher','operator'))
        }
    },
}
</script>

<style>

</style>
