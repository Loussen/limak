<template>
    <section class="order spin-load content num-spin">
        <div class="container">
            <div class="row">
                <div class="order-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-6">
                        <h4>ÖDƏNİŞ  <b>ET</b></h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-6 ">
                        <ol class="breadcrumb web">
                            <li><a href="/">Ana səhifə </a></li>
                            <li><a href="/site/user-panel"> İstifadəçi paneli </a></li>
                            <li class="active">Ödəniş et</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="/">... </a></li>
                            <li class="active">Ödəniş et</li>
                        </ol>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="block invoice-pay col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h3>Ödəniləcək cəmi məbləğ</h3>
                                <p class="pay-count">{{prices.try}} TL</p>
                                <p class="pay-count">{{prices.azn}} AZN</p>
                                <div class="text">
                                    <p><img src="/front/new/img/cards.png" alt="payment" width="350"></p>

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="alert alert-danger" v-if="error_message.length>0">
                                    <p v-for="m in error_message">{{m}}</p>
                                </div>
                                <form action="...">
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="owner" v-model="formData.owner" class="form-control inputText" id="card" placeholder=" ">
                                            <span>Kart üzərindəki Ad, Soyad:</span>
                                        </label>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="cardNumber" v-model="formData.cardNumber" class="form-control inputText" id="num" placeholder=" ">
                                            <span>Kart nömrəsi:</span>
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p><b>Kartınızın son istifadə tarixi:</b></p>
                                        </div>
                                        <div class="col-xs-6 use-time">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input type="text" name="expiryMonth" v-model="formData.expiryMonth" class="form-control inputText" id="use-time" placeholder=" ">
                                                    <span>Ay</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input type="text" name="expiryYear" v-model="formData.expiryYear" class="form-control inputText" id="year" placeholder=" ">
                                                    <span>İl</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="cvv" v-model="formData.cvv" class="form-control inputText" id="security" placeholder=" ">
                                            <span>CVV təhlükəsizlik kodu:</span>
                                        </label>
                                    </div>
                                    <button type="button" @click="submitForm()" class="btn-effect">ÖDƏ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import {api} from "../../apis";
    export default {
        name: "Payment",
        data: function (){
            return {
                "id": null,
                "prices" : {'azn':0,'try':'0'},
                "formData": {
                    "owner" : '',
                    "cardNumber" : '',
                    "expiryMonth" : '',
                    "expiryYear" : '',
                    "cvv" : '',
                    "operationId": this.id
                },
                "cavab": '',
                "error_message" : []
             }
        },
        methods: {
            initialize() {
                this.id = this.$route.params.id;
                this.formData.operationId = this.id;
                this.fetch();
            },
            fetch() {
                axios.get(api.orderLinkData + '/' + this.id).then(res => {
                    if(res.data.code==200){
                        this.prices.azn = res.data.azn.toFixed(2);
                        this.prices.try = res.data.try.toFixed(2);
                    }else{
                        this.$router.push('/');
                    }

                })
            },
            submitForm () {
                this.errors = {};
                if (this.isEmpty(this.errors)) {
                    this.error_message = [];
                    if(this.formData.owner.length<3){
                        this.error_message.push('Kart sahibinin adı və soyadı doğru daxil edilməyib');
                    }

                    if(this.formData.cardNumber.length!=16){
                        this.error_message.push('Kartın nömrəsi doğru daxil edilməyib');
                    }

                    if(this.formData.cvv.length!=3){
                        this.error_message.push('CVV kodu doğru daxil edilməyib');
                    }

                    if('20'+this.formData.expiryYear< (new Date().getFullYear()) ){
                        this.error_message.push('Kartın son istifadə müddəti doğru daxil edilməyib');
                    }else if('20'+this.formData.expiryYear==(new Date().getFullYear()) && this.formData.expiryMonth<(new Date().getMonth())){
                        this.error_message.push('Kartın son istifadə müddəti doğru daxil edilməyib');
                    }


                    if(this.error_message.length==0){
                        axios.post('/paym/pay', this.formData)
                            .then((response) => {
                                console.log(this.formData);
                                this.cavab = response.data;
                                if(this.cavab.code==200)
                                {
                                    location.replace(this.cavab.url);
                                    console.log("Success payment")
                                }else{
                                    this.error_message.push(this.cavab.message);
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },

        },
        mounted() {
            this.initialize();
        }
    }


</script>

<style scoped>

</style>