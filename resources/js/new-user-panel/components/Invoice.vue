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

                            <div class="row col-xs-12 order-body">
                                <div class="block">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#turkey" aria-controls="home" role="tab" data-toggle="tab" class="order-img1" @click="changeCountry(1)">
                                                {{ExWindow ? ExWindow.translator('common.turkey') : '' }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#usa" aria-controls="home" role="tab" data-toggle="tab" class="order-img2" @click="changeCountry(2)">
                                                {{ExWindow ? ExWindow.translator('common.usa') : '' }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="invoice-left-side block">
                                <div class="left-content" id="tabContent" v-bind:class="activeClass">
                                    <div class="row" v-if="User.corporate==1">
                                        <div class="col-md-12 col-xs-12">
                                            <h5>{{ExWindow ? ExWindow.translator('panel-errors.corporate_client_name') : '' }} *</h5>
                                            <v-select v-model="client_id"  :options="clients"></v-select>
                                        </div>
                                    </div>
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
                                        <div class="col-md-5 col-sm-5 col-xs-6">
                                            <div :class="this.errors.product_type ? 'error-message' : ''" class="input-group border-radius">
                                                <label>
                                                    <input v-model="product_type" type="text" name="length" class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('panel.product_type') : '' }}  * </span>
                                                </label>
                                                <span v-if="this.errors.product_type" class="error-text">{{ this.errors.product_type }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div :class="this.errors.count ? 'error-message' : ''" class="ch-input-1 input-group number-spinner">
                                                <span class="input-group-prepend">
                                                    <button class="btn btn-default" type="button" data-dir="dwn" @click="dec()"><strong >-</strong></button>
                                                </span>
                                                <input class="form-control text-center" v-model="count" @input="check()" @change="check()" :placeholder="ExWindow ? ExWindow.translator('panel.product_count') : '' +'*'" type="number" value="" min="1"/>
                                                <span class="input-group-append">
                                                    <button class="btn btn-default" type="button" data-dir="up" @click="inc()"><strong>+</strong></button>
                                                </span>
                                                <span v-if="this.errors.count" class="error-text">{{ this.errors.count }}</span>
                                            </div>

                                            <div :class="this.errors.price ? 'error-message' : ''" class="ch-input-2 input-group border-radius">
                                                <label>
                                                    <input v-model="price" type="text" @input="check()" @change="check()" name="length" class="form-control inputText" placeholder=" ">
                                                    <span>{{ExWindow ? ExWindow.translator('panel.price')+' ('+currency+')' : '' }}  * </span>
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
                                        <div class="col-md-4 col-xs-12">
                                            <h5>{{ExWindow ? ExWindow.translator('panel.delivery_office') : '' }}*</h5>
                                            <div class="invoice-address input-group border-radius">
                                                <input class="form-control inputText" v-model="region.name"
                                                       v-if="!editable" readonly>
                                                <v-select v-model="region" :options="regions" label="name"
                                                          v-if="editable"></v-select>
                                                <span v-if="!editable" @click="showSelect"
                                                      class="invoice-pen"><i class="fa fa-pencil"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <h5>{{ExWindow ? ExWindow.translator('panel.order_date') : '' }}  *</h5>
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
                                        <div class="col-xs-12">
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
            });
            this.months = months;

            let cur_month = (new Date()).getMonth()+ 1;
            let month = this.months.find((el) =>{
                return el.id == cur_month
            });
            this.month = month;
            this.regions = [
                {id: 0, name: ''},
                {id: 1, name: this.ExWindow ? this.ExWindow.translator('panel.Baku') : ''},
                {id: 2, name: this.ExWindow ? this.ExWindow.translator('panel.Ganja') : ''},
                {id: 3, name: this.ExWindow ? this.ExWindow.translator('panel-errors.sumgait') : ''},
                {id: 4, name: this.ExWindow ? this.ExWindow.translator('panel-errors.zagatala') : ''}
                //{id: 5, name: this.ExWindow ? this.ExWindow.translator('panel-errors.lankaran') : ''},

            ];

            this.getUserData();

        },
        data: function(){
            return {
                ExWindow: null,
                product_type:'',
                months: [],
                shop: '',
                count: '',
                price: '',
                order_track: '',
                day: 1,
                month: {id:1,name: 'Yanvar'},
                year: '2020',
                description: '',
                file: '',
                editable: false,
                User: {},
                regions: [],
                region: {},
                country_id: 1,
                currency: 'tl',
                errors : {
                    shop: null,
                    count: null,
                    price: null,
                    order_track: null,
                    product_type: null

                },
                activeClass: 'trBg',
                client_id: '',

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
            },
            clients: function(){
                let i =1;
                if(this.User.corporate==1){
                    var c =  [{"id": 0,"name":"Yoxdur","label": "Yoxdur"}];
                    $.each(this.User.clients, function(key, value) {
                        c[i] = {"id": value.id,"name": value.name,"label": value.name+' '+value.surname+' - '+value.last30+'$'};
                        i++;
                    });
                }
                console.log(c);
                return c;
            }
        },
        methods: {
            changeCountry(country){

                if(this.country_id!=country){
                    $("#tabContent").fadeOut("5000");
                    $("#tabContent").fadeIn("5000");
                }
                this.country_id = country;
                if(this.country_id==1){
                    this.currency = 'tl';
                }else if(this.country_id==2){
                    this.currency = 'usd';
                }

            },
            getUserData() {
                axios.get('/user-panel/get-user-data').then( data => {
                    this.User = data.data;
                    this.region = this.regions[this.User.region_id];
                    this.client_id = this.clients[0];
                });

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
            dec() {
                this.count--;
                if(parseInt(this.count)<=0){
                    this.count = 1;
                }
            },
            inc() {
                this.count++;
            },
            check(){
                if(parseInt(this.count)<=0){
                    this.count = 1;
                }


                if(parseInt(this.price)<=0){
                    this.price = 1;
                }
            },
            send(){
                this.resetErrors();
                this.checkErrors();
                if(Object.values(this.errors).every((el) => el == null)){
                    swal.showLoading();
                    const data= new FormData();
                    data.append('shop_name', this.shop);
                    data.append('country_id', this.country_id);
                    data.append('client_id', this.client_id.id);
                    data.append('product_type', this.product_type);
                    data.append('quantity', this.count);
                    data.append('region', this.region.id);
                    data.append('country_id', this.country_id);
                    data.append('price', this.price);
                    data.append('order_number', this.order_track);
                    data.append('order_date', this.full_date);
                    data.append('description', this.description);

                    data.append('file', this.file);
                    axios({url: '/site/invoice', method: 'POST', data: data}).then(response => {
                        swal({
                            type: "info",
                            text: this.ExWindow.translator('panel.invoice_uploaded')
                        }).then(() =>{
                            this.product_type='';
                            this.shop= '';
                            this.count= '';
                            this.price= '';
                            this.order_track= '';
                            this.description= '';
                            this.file= '';
                        })
                    });
                }
            },
            showSelect() {
                this.editable = true;
            },
            checkErrors(){
                if(!this.shop.trim()){
                    this.errors.shop = this.ExWindow ? this.ExWindow.translator('panel-errors.show_shop') : '';
                }
                if(!this.product_type.trim()){
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
            vSelect
        }
    }
</script>

<style scoped>
    .order-body .block{
        padding: 0px;
    }
</style>
