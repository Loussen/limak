<template>
    <section class="invoice content num-spin">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-6">
                        <h4>{{ExWindow ? ExWindow.translator('panel.invoice') : '' }} <b>{{ExWindow ? ExWindow.translator('panel.upload') : '' }}</b></h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-6">
                        <ol class="breadcrumb web">
                            <li><a href="/">{{ExWindow ? ExWindow.translator('panel.home_page') : '' }} </a></li>
                            <li><a href="/site/user-panel">{{ExWindow ? ExWindow.translator('panel.user_panel') : '' }}  </a></li>
                            <li class="active">{{ExWindow ? ExWindow.translator('panel.invoice_upload_bread') : '' }}</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="/">... </a></li>
                            <li class="active">{{ExWindow ? ExWindow.translator('panel.invoice_upload_bread') : '' }}</li>
                        </ol>
                    </div>
                </div>
                <div class="invoice-body col-xs-12">
                    <div class="row relative">
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <div class="invoice-left-side block">
                                <div class="left-content">
                                    <div class="row">
                                        <div class="col-md-7 col-sm-7 col-xs-6 ">

                                            <div :class="this.errors.shop ? 'error-message' : ''" class="input-group border-radius">
                                                <label>
                                                    <input v-model="shop" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('panel.shop_name') : '' }}  * </span>
                                                </label>
                                                <span v-if="this.errors.shop" class="error-text">{{ this.errors.shop }}</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-xs-12">
                                            <div :class="this.errors.count ? 'error-message' : ''" class="ch-input-1 input-group number-spinner">
                                                <span class="input-group-prepend">
                                                    <button class="btn btn-default" type="button" data-dir="dwn" @click="count--"><strong >-</strong></button>
                                                </span>
                                                <input class="form-control text-center" v-model="count" :placeholder="ExWindow ? ExWindow.translator('panel.product_count') : '' +'*'" type="number" value="" min="1"/>
                                                <span class="input-group-append">
                                                    <button class="btn btn-default" type="button" data-dir="up" @click="count++"><strong>+</strong></button>
                                                </span>
                                                <span v-if="this.errors.count" class="error-text">{{ this.errors.count }}</span>
                                            </div>

                                            <div :class="this.errors.price ? 'error-message' : ''" class="ch-input-2 input-group border-radius">
                                                <label>
                                                    <input v-model="price" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('panel.price') : '' }}  * </span>
                                                </label>
                                                <span v-if="this.errors.price" class="error-text">{{ this.errors.price }}</span>
                                            </div>
                                            <div :class="this.errors.order_track ? 'error-message' : ''" class="ch-input-2 input-group border-radius">
                                                <label>
                                                    <input v-model="order_track" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('panel.track_code') : '' }}  * </span>
                                                </label>
                                                <span v-if="this.errors.order_track" class="error-text">{{ this.errors.order_track }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="invoice-date">
                                                <h5>{{ExWindow ? ExWindow.translator('panel.order_date') : '' }}  *</h5>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="invoice-day input-group border-radius">
                                                <v-select v-model="day"  :options="days"></v-select>
                                            </div>
                                            <div class="invoice-month input-group border-radius">
                                                <v-select v-model="month" label="name" :options="months"></v-select>
                                            </div>
                                            <div class="invoice-year input-group border-radius">
                                                <v-select v-model="year"  :options="years"></v-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div :class="this.errors.product_type ? 'error-message' : ''" class="input-group border-radius">
                                                <div class="field">
                                                    <tags-input v-model="product_types"
                                                                element-id="tags"
                                                                :existing-tags="tags"
                                                                :typeahead="true"
                                                                typeahead-style="dropdown"
                                                                :limit="count"
                                                                :only-existing-tags="true"
                                                                :typeahead-max-results="0"
                                                                :typeahead-activation-threshold="0"
                                                                :placeholder="ExWindow ? ExWindow.translator('panel.product_type') : ''"
                                                    ></tags-input>
                                                </div>
                                                <!--
                                                                                                    <span>{{ExWindow ? ExWindow.translator('panel.product_type') : '' }}  * </span>
                                                -->
                                                <!--
                                                                                                    <input v-model="product_type" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                -->

                                                <span v-if="this.errors.product_type" class="error-text">{{ this.errors.product_type }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="textarea-form form-group">
                                                <textarea v-model="description" class="form-control" rows="4" id="comment" :placeholder="ExWindow ? ExWindow.translator('panel-errors.package_desc') : ''"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-part text-left">
                                                <label for="file" class="invoice-upload">
                                                    <span>{{ExWindow ? ExWindow.translator('panel.invoice_upload') : '' }}</span>
                                                    <input @change="handleFileUpload" refs="file" type="file" class="form-control-file" id="file" name="invoice">
                                                </label>
                                                <button @click="send" class="btn-effect">
                                                    <span>{{ExWindow ? ExWindow.translator('common.send') : '' }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="invoice-right block">
                                <h3 class="text-center">{{ExWindow ? ExWindow.translator('panel.attention') : '' }}</h3>
                                <p>{{ExWindow ? ExWindow.translator('panel.attention_text') : '' }}</p>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import VoerroTagsInput from '@voerro/vue-tagsinput';

    import vSelect from 'vue-select'
    import swal from "sweetalert2";
    export default {
        name: "Invoice",
        mounted(){
            this.ExWindow = window;
            this.day = (new Date()).getDate();

            let month_names = this.ExWindow && this.ExWindow.translator('register.months');
            let months_array = month_names.split(',');
            let months = months_array.map((el,index) =>{
                return {
                    id: index+1,
                    name: el
                }
            })
            this.months = months;

            let cur_month = (new Date()).getMonth()+ 1;
            let month = this.months.find((el) =>{
                return el.id == cur_month
            })
            this.month = month
            this.getTags();

        },
        data: function(){
            return {
                ExWindow: null,
                product_type:'',
                months: [],
                shop: '',
                count: 1,
                price: '',
                order_track: '',
                day: 1,
                month: {id:1,name: 'Yanvar'},
                year: '2019',
                description: '',
                file: '',
                errors : {
                    shop: null,
                    count: null,
                    price: null,
                    order_track: null,
                    product_type: null

                },
                selectedTags: '',
                product_types: '',
                tags: {},
                limitTag: 2,

            }
        },
        computed: {
            days: function(){
                return this.range(1,31)
            },
            years: function(){
                let date = new Date()
                return (this.range(date.getFullYear()-2,date.getFullYear())).reverse();
            },
            full_date: function(){
                let date = new Date(this.year,this.month.id,this.day);
                return date.getFullYear() +':'+ date.getMonth()+ ':'+ date.getDate();
            }
        },
        methods: {
            getTags() {
                axios.get('/site/productTypes')

                    .then(data => {
                        this.product_types = data.data.data;
                        this.tags = data.data.data;
                        /*data.data.data.forEach(type => {
                            this.product_types.push({
                                name: type.name
                            })
                        });*/
                    })
                    .catch(err => {
                        console.log(err);
                    });

                //console.log(this.product_types);
            },
            range(a, b, step){
                var A= [];
                if(typeof a== 'number'){
                    A[0]= a;
                    step= step || 1;
                    while(a+step<= b){
                        A[A.length]= a+= step;
                    }
                }
                else{
                    var s= 'abcdefghijklmnopqrstuvwxyz';
                    if(a=== a.toUpperCase()){
                        b=b.toUpperCase();
                        s= s.toUpperCase();
                    }
                    s= s.substring(s.indexOf(a), s.indexOf(b)+ 1);
                    A= s.split('');
                }
                return A;
            },
            handleFileUpload(e){
                console.log(e.target.files)
                this.file = e.target.files[0];
            },
            resetErrors(){
                this.errors = {
                    shop: null,
                    count: null,
                    price: null,
                    order_track: null,
                    product_type: null

                }
            },
            send(){
                //console.log(this.product_types)

                this.resetErrors();
                this.checkErrors();
                if(Object.values(this.errors).every((el) => el == null)){
                    swal.showLoading();
                    const data= new FormData();
                    data.append('shop_name', this.shop);
                    data.append('product_type', this.product_type);
                    data.append('product_types', this.product_types);
                    data.append('quantity', this.count);
                    data.append('price', this.price);
                    data.append('order_number', this.order_track);
                    data.append('order_date', this.full_date);
                    data.append('description', this.description);

                    data.append('file', this.file);
                    console.log(this.product_types)
                    axios({url: '/site/invoice2', method: 'POST', data: data}).then(response => {
                        console.log(response);
                        swal({
                            type: "info",
                            text: this.ExWindow.translator('panel.invoice_uploaded')
                        }).then(() =>{
                            this.product_type='';
                            this.product_types='';
                            this.shop= '';
                            this.count= '';
                            this.price= '';
                            this.order_track= '';
                            this.day= 1,
                                this.month= {id:1,name: 'Yanvar'};
                            this.year= '2019';
                            this.description= '';
                            this.file= '';
                        })
                    });
                }
            },
            checkErrors(){
                if(!this.shop.trim()){
                    this.errors.shop = this.ExWindow ? this.ExWindow.translator('panel-errors.show_shop') : '';
                }/*
                if(!this.product_type.trim()){
                    this.errors.product_type = this.ExWindow ? this.ExWindow.translator('panel-errors.show_product_type') : '';
                }*/

                if(this.product_types.length!=this.count){
                    this.errors.product_type = this.ExWindow ? this.ExWindow.translator('panel-errors.show_product_type') : '';
                }
                if(!this.count){
                    this.errors.count =  this.ExWindow ? this.ExWindow.translator('panel-errors.show_count') : '';
                }
                else if(isNaN(parseFloat(this.count))){
                    this.errors.count = this.ExWindow ? this.ExWindow.translator('panel-errors.show_count_digit') : '';
                }
                if(!this.price.trim()){
                    this.errors.price = this.ExWindow ? this.ExWindow.translator('panel-errors.show_price') : '';
                }
                else if(isNaN(parseFloat(this.price))){
                    this.errors.price = this.ExWindow ? this.ExWindow.translator('panel-errors.show_price_digit') : '';
                }
                if(!this.order_track.trim()){
                    this.errors.order_track = this.ExWindow ? this.ExWindow.translator('panel-errors.show_order_truck') : '';
                }
            }

        },
        components: {
            vSelect,
            'tags-input' : VoerroTagsInput
        }
    }
</script>

<style scoped>


</style>