<template>
    <div>
        <h4>{{ExWindow ? ExWindow.translator('panel-errors.daily_currency') : '' }}</h4>
        <ul>
            <li><img src="/front/img/flg-tr.png" alt="flg-tr"><span>1</span><span>TL</span></li>
            <li><img src="/front/img/flg-az.png" alt="flg-az"><span v-if="currencies_object && currencies_object['try-azn']">{{ currencies_object['try-azn'].val }}</span><span>AZN</span></li>
            <li><img src="/front/img/flg-usa.png" alt="flg-usa"><span v-if="currencies_object && currencies_object['try-usd']">{{ currencies_object['try-usd'].val }}</span><span>USD</span></li>
            <!--<li><img src="/front/img/flg-ru.png" alt="flg-ru"><span>11.31</span><span>RUB</span></li>-->
        </ul>
    </div>
</template>

<script>
    export default {
        name: "dailyCurrency",
        mounted(){
            this.ExWindow = window;
            axios.get('/calculate-currency')
                .then(({data}) =>{
                    this.currencies = data.currencies
                })
        },
        data: function(){
            return {
                ExWindow: null,
                currencies: []
            }
        },
        computed: {
            currencies_object: function(){
                let obj = {};
                this.currencies.forEach((cur) =>{
                    obj[cur.name] = cur;
                })
                return obj;
            }
        }
    }
</script>

<style scoped>

</style>