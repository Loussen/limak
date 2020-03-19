<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="block customer-list auto-height">
                <ul class="list-user">
                    <li>Ad, Soyad <p>Mahir Veliyev</p></li>
                    <li>
                        <ul class="inline-list">
                            <li>Müştəri kodu<p>5c1b4b0ffdf29</p></li>
                            <li>Qeydiyyat tarixi<p>06.02.2019</p></li>
                            <li>Mobil telefonu<p>+994 50 123 45 67 </p></li>
                            <li>E-mail <p>mahirveliyev@gmail.com</p></li>
                            <li>Fin kodu <p>94FMDDD</p></li>
                        </ul>
                    </li>
                    <li>Ünvan
                        <p>Halkalı Merkez Mahellesi. Üzümlü SK. 5/7 kod: 5m1b4m0mvfd29 Mah</p></li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    import auth from '../auth.js'
    export default {
        components: {},
        name: "ProblemComponent",
        mounted() {
        },
        data: function () {
            return {

            }
        },
        methods: {

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

</style>