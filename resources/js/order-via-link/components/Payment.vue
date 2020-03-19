<template>
    <section class="page-block">
        <div class="container">
            <div class="page-head">
                <img src="/front/img/beyanname-yukle.png" alt="beyanname-yukle">
                <h1>BƏYANNAMƏ YÜKLƏ</h1>
            </div>
            <div class="block invoice">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h3>Ödəniləcək cəmi məbləğ</h3>
                        <p class="pay-count">2000 TL</p>
                        <p class="pay-count">2000 AZN</p>
                        <div class="text">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <form action="...">
                            <div class="input-group">
                                <label for="card">Kart üzərindəki Ad, Soyad:</label>
                                <input type="text" name="owner" class="form-control" id="card" placeholder="">
                            </div>
                            <div class="input-group">
                                <label for="num">Kart nömrəsi:</label>
                                <input type="text" name="cardNumber" class="form-control" id="num" placeholder="">
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <p><b>Kartınızın son istifadə tarixi:</b></p>
                                </div>
                                <div class="col-xs-6 use-time">
                                    <div class="input-group">
                                        <label for="use-time"></label>
                                        <input type="text" name="expiryMonth" class="form-control" id="use-time" placeholder="Ay">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="input-group">
                                        <label for="year"></label>
                                        <input type="text" name="expiryYear" class="form-control" id="year" placeholder="İl">
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <label for="security">CVV təhlükəsizlik kodu:</label>
                                <input type="text" name="cvv" class="form-control" id="security" placeholder="">
                            </div>
                            <button type="button" class="btn-effect">Göndər</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "Payment",
        data: function (){
            return {
                "id": null,
            }
        },
        mounted() {
            this.initialize();
        },
        methods: {
            initialize() {
                this.id = this.$route.params.id;
                this.fetch();
            },

            fetch() {
                axios.get('/cp/getPaymentData'+this.id).then(
                    response => {
                        if(response && response.length>0) {
                            this.cts = response.data
                        }
                    }
                );
            }

        }

    }


</script>

<style scoped>

</style>