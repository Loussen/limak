<template>
    <div>
        <h4>{{ExWindow ? ExWindow.translator('currency.currency_calculator') : '' }}</h4>
        <!--<button type="button" class="return"><img src="/front/new/img/return.png" alt="return"></button>-->
        <form action="...">
            <div class="input-group border-radius">
                <input @input="calculate" v-model="currency1.val" name="" type="number" class="form-control" placeholder="0.0">
                <v-select @input="calculate" v-model="currency1.type" name=""   label="name" :options="['AZN','USD','TRY']"></v-select>
            </div>
            <div class="input-group border-radius">
                <input @input="calculate" v-model="currency2.val" type="number" name="currency2" class="form-control" placeholder="0.0">
                <v-select @input="calculate" v-model="currency2.type" name="" label="name" :options="['AZN','USD','TRY']"></v-select>
            </div>
        </form>
        <span>{{ExWindow ? ExWindow.translator('currency.calculating') : '' }}</span>
    </div>
</template>

<script>
    import vSelect from 'vue-select'
    export default {
        name: "currency",
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
                currency1: {
                    val: 0,
                    type: 'AZN'
                },
                currency2: {
                    val: 0,
                    type: 'USD'
                },
                currencies: [],
            }
        },
        computed: {
            currencies_object: function(){
                let obj = {};
                this.currencies.forEach((cur) =>{
                    obj[cur.name] = cur;
                })
                return obj;
            },
            cur_pair: function(){
                return (this.currency1.type + '-'+ this.currency2.type).toLowerCase();
            },
            cur: function(){
                return this.currencies_object[this.cur_pair]
            }
        },
        methods: {
          calculate(){
              if(this.currency1.type != this.currency2.type){
                  this.currency2.val = (this.currency1.val * this.cur.val).toFixed(2);
              } else if(this.currency1.type == this.currency2.type){
                  this.currency2.val = 0
              }
          }
        },
        components: {
            vSelect
        }
    }
</script>

<style scoped>

</style>