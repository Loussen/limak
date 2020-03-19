<template>
    <div class="settings order-body col-md-9 col-sm-11 col-xs-11">
        <div class="row">
            <div class="col-xs-12 courier-log">
                <div class="block col-xs-12">
                    <ul class="nav nav-tabs web-xs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab1" aria-controls="home" role="tab" data-toggle="tab">{{ this.ExWindow && this.ExWindow.translator('panel-errors.profile_details') }}</a>
                        </li>
                        <li role="presentation">
                            <a href="#tab2" aria-controls="profile" role="tab" data-toggle="tab">{{ this.ExWindow && this.ExWindow.translator('panel-errors.password') }}</a>
                        </li>
                        <li role="presentation">
                            <a href="#tab3" aria-controls="messages" role="tab" data-toggle="tab">{{ this.ExWindow && this.ExWindow.translator('panel-errors.passport_details') }}</a>
                        </li>
                        <!--<li role="presentation">
                            <a href="#tab4" aria-controls="settings" role="tab" data-toggle="tab">Ümumi tənzimləmələr</a>
                        </li>-->
                    </ul>
                    <ul class="mobile-xs nav nav-tabs" role="tablist">
                        <li role="presentation" class="active replace">
                            <a href="#tab1" aria-controls="home" role="tab" data-toggle="tab">
                                <span class="in_visible">{{ this.ExWindow && this.ExWindow.translator('panel-errors.profile_details') }}</span>
                                <img src="/front/new/img/user.png" alt="user">
                            </a>
                        </li>
                        <li role="presentation" class="replace">
                            <a href="#tab2" aria-controls="profile" role="tab" data-toggle="tab">
                                <span class="in_visible">{{ this.ExWindow && this.ExWindow.translator('panel-errors.password') }}</span>
                                <img src="/front/new/img/padlock.png" alt="padlock">
                            </a>
                        </li>
                        <li role="presentation" class="replace">
                            <a href="#tab3" aria-controls="messages" role="tab" data-toggle="tab">
                                <span class="in_visible">{{ this.ExWindow && this.ExWindow.translator('panel-errors.passport_details') }}</span>
                                <img src="/front/new/img/biometric.png" alt="biometric">
                            </a>
                        </li>
                        <!--<li role="presentation" class="replace">-->
                            <!--<a href="#tab4" aria-controls="settings" role="tab" data-toggle="tab">-->
                                <!--<span class="in_visible">Ümumi tənzimləmələr</span>-->
                                <!--<img src="/front/new/img/setting.png" alt="setting">-->
                            <!--</a>-->
                        <!--</li>-->
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab1">
                            <div class="right-content ">
                                <div class="profil-data form-list">
                                    <form @submit="saveProfile" id="profile">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div :class="errors.name ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input v-model="name" type="text" name="name" class="form-control inputText" placeholder=" " >
                                                        <span>{{ExWindow && ExWindow.translator('register.name')}} * </span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.name" >{{errors.name? errors.name: ''}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div :class="errors.surname ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input type="text" v-model="surname" name="surname" class="form-control inputText" id="surname" placeholder=" ">
                                                        <span>{{ExWindow && ExWindow.translator('register.surname')}} *</span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.surname" >{{errors.surname}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-xs-12">
                                                <h5>{{ExWindow && ExWindow.translator('panel-errors.delivery_office')}} *</h5>
                                                <div class="invoice-address input-group border-radius">
                                                    <input class="form-control inputText" v-model="region.name" v-if="!editable" readonly>
                                                    <v-select v-model="region"  :options="regions" label="name" v-if="editable" name="region_id"></v-select>
                                                    <span v-if="!editable" @click="showSelect" class="invoice-pen"><i class="fa fa-pencil"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-xs-12">
                                                <h5>{{ExWindow && ExWindow.translator('register.birthdate')}}</h5>
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
                                            <div class="col-xs-6">
                                                <div :class="errors.email ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input type="text" class="form-control inputText" v-model="email" name="email" id="mail" placeholder=" ">
                                                        <span>{{ExWindow && ExWindow.translator('register.email')}} *</span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.email" >{{errors.email}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div :class="errors.phone ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input type="text" name="phone" class="form-control inputText" v-model="phone" id="phone" placeholder=" " value="+994 ">
                                                        <span>{{ExWindow && ExWindow.translator('register.phone')}} *</span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.phone" >{{errors.phone? errors.phone: ''}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 button-part">
                                                <button  type="submit" class="btn-effect">{{ExWindow && ExWindow.translator('panel-errors.save2')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab2">
                            <div class="right-content ">
                                <div class="password-part form-list ">
                                    <form @submit="changePassword" id="password_data">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h4>{{ExWindow && ExWindow.translator('register.current')}} {{ExWindow && ExWindow.translator('register.password_small')}}</h4>
                                                <div class="row">
                                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                                        <div :class="errors.currentPassword ? 'error-message' : ''" class="input-group border-radius">
                                                            <label>
                                                                <input v-model="currentPassword" type="password" class="form-control" name="currentPassword" id="nowpass" placeholder=" ">
                                                            </label>
                                                            <span style="color: red" v-if="errors.currentPassword" >{{errors.currentPassword? errors.currentPassword: ''}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="new-password">
                                                    <h4>{{ExWindow && ExWindow.translator('panel-errors.new_password')}}</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div :class="errors.password ? 'error-message' : ''" class="input-group border-radius">
                                                                    <label>
                                                                        <input type="password" name="password" class="form-control inputText" v-model="password"  id="passii" placeholder=" ">
                                                                        <span>{{ExWindow && ExWindow.translator('register.password')}}</span>
                                                                    </label>
                                                                    <span style="color: red" type="password" v-if="errors.password" >{{errors.password? errors.password: ''}}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12">
                                                                <div :class="errors.cpassword ? 'error-message' : ''" class="input-group border-radius">
                                                                    <label>
                                                                        <input v-model="cpassword" type="password" name="cpassword" class="form-control inputText"  id="repeat-pass" placeholder=" ">
                                                                        <span>{{ExWindow && ExWindow.translator('register.password_confirmation')}}</span>
                                                                    </label>
                                                                    <span style="color: red" v-if="errors.cpassword" >{{errors.cpassword? errors.cpassword: ''}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ps-right col-md-7 col-sm-7 col-xs-12">
                                                        <div v-html="ExWindow && ExWindow.translator('panel-errors.password_rules')" class="password-right">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 button-part">
                                                <button type="submit" class="btn-effect">{{ExWindow && ExWindow.translator('panel-errors.save2')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab3">
                            <div class="right-content ">
                                <div class="id-data form-list">
                                    <form id="passport_data" @submit="savePassport">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div :class="errors.serialNumber ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input v-model="serialNumber" type="text" name="serialNumber" class="form-control inputText" placeholder=" ">
                                                        <span>{{ExWindow && ExWindow.translator('panel-errors.passport_serial')}} * </span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.serialNumber" >{{errors.serialNumber}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div :class="errors.pin ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input v-model="pin" type="text" name="pin" class="form-control inputText" placeholder=" ">
                                                        <span>{{ExWindow && ExWindow.translator('register.fin')}} </span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.pin" >{{errors.pin}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-8">
                                                <div :class="errors.nationality ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input v-model="nationality" type="text" name="nationality" class="form-control inputText" placeholder=" ">
                                                        <span>{{ExWindow && ExWindow.translator('register.nationality')}} *</span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.nationality" >{{errors.nationality}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div :class="errors.gender ? 'error-message' : ''" class="gender input-group border-radius">
                                                    <v-select v-model="gender" :options="genders"  label="name"></v-select>
                                                    <!--<select v-model="gender" class="form-control selectpicker" name="gender" :title="ExWindow && ExWindow.translator('register.gender')">
                                                        <option value="2">{{ExWindow && ExWindow.translator('register.female')}}</option>
                                                        <option value="1">{{ExWindow && ExWindow.translator('register.male')}}</option>
                                                    </select>-->
                                                    <span style="color: red" v-if="errors.gender" >{{errors.gender}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div :class="errors.address ? 'error-message' : ''" class="input-group border-radius">
                                                    <label>
                                                        <input v-model="address" type="text" name="address" class="form-control inputText" placeholder=" ">
                                                        <span>{{ExWindow && ExWindow.translator('register.address')}} *</span>
                                                    </label>
                                                    <span style="color: red" v-if="errors.address" >{{errors.address}}</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 button-part">
                                                <button type="submit" class="btn-effect">{{ExWindow && ExWindow.translator('panel-errors.save2')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--<div role="tabpanel" class="tab-pane fade" id="tab4">
                            <div class="right-content ">
                                <div class="regulation form-list">
                                    <form action="...">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h4>Bildirişlərin göndərilməsi</h4>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label class="radio-button">
                                                            <span class="reg-text">E-mail vasitəsi ilə</span>
                                                            <input type="radio"  name="radio">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="radio-button">
                                                            <span class="reg-text">SMS vasitəsi ilə</span>
                                                            <input type="radio"  name="radio">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <hr>
                                                    </div>
                                                </div>
                                            </div>
                                            &lt;!&ndash;<div class="col-xs-12">
                                                <div class="new-password">
                                                    <h4>Abunəlik</h4>
                                                </div>
                                                <div class="switch-part ">
                                                    <label class="switch">
                                                        <input type="checkbox">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span class="reg-text">Abunəliyimi deaktiv et</span>
                                                </div>
                                            </div>&ndash;&gt;
                                            <div class="col-xs-12 button-part">
                                                <button type="button" class="btn-effect">Yadda saxla</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import swal from 'sweetalert2';
    import vSelect from 'vue-select'

    export default {
        mounted() {
            this.getSettingsData().then(() => {
                setTimeout(() => {
                    $('.customSelect').val(this.gender).selectpicker();
                }, 1400);
            });

            this.password = '';
            this.cpassword = '';
            this.currentPassword = '';
            //this.gender = this.gender_arr[this.Settings.gender];
            this.ExWindow = window;

            let month_names = this.ExWindow && this.ExWindow.translator('register.months');
            let months_array = month_names.split(',');
            let months = months_array.map((el,index) =>{
                return {
                    id: index+1,
                    name: el
                }
            })
            this.months = months;
            this.genders = [
                {id:2,name: this.ExWindow && this.ExWindow.translator('register.female')},
                {id:1,name: this.ExWindow && this.ExWindow.translator('register.male')}
            ];

            this.regions = [
                {id:0, name: ''},
                {id: 1, name: this.ExWindow ? this.ExWindow.translator('panel-errors.baku') : ''},
                {id: 2, name: this.ExWindow ? this.ExWindow.translator('panel-errors.ganja') : ''},
                {id: 3, name: this.ExWindow ? this.ExWindow.translator('panel-errors.sumgait') : ''},
                {id: 4, name: this.ExWindow ? this.ExWindow.translator('panel-errors.zagatala') : ''}
                //{id: 5, name: this.ExWindow ? this.ExWindow.translator('panel-errors.lankaran') : ''}
            ];

            this.region = this.regions[this.Settings.region_id];


        },
        beforeDestroy() {
            $('.customSelect').selectpicker('destroy', true);
            $('.customSelect').remove();
        },
        computed:{
            days: function(){
                return this.range(1,31)
            },
            years: function(){
                let date = new Date()
                return (this.range(date.getFullYear()-80,date.getFullYear())).reverse();
            },
        },
        data: function (){
            return {
                months: [],
                genders: [],
                Settings: Object,
                errors: {},
                name: String,
                surname: String,
                email: String,
                phone: String,
                password: String,
                serialNumber: String,
                pin: String,
                cpassword: String,
                currentPassword: String,
                birthdate: String,
                address: String,
                gender: {},
                nationality: String,
                ExWindow: null,
                day:'',
                month:'',
                year:'',
                editable: false,
                regions: [],
                region: {}
            }
        },
        methods: {
            saveProfile(){
                event.preventDefault();

                this.errors = {};
                if (!this.name) {
                    this.errors.name = window.translator('panel-errors.empty');
                }
                if (!this.surname) {
                    this.errors.surname = window.translator('panel-errors.empty');
                }
                if (!this.email) {
                    this.errors.email = window.translator('panel-errors.empty');
                } else {
                    if(!this.validEmail(this.email)) {
                        this.errors.email = window.translator('panel-errors.email-not-valid');
                    }
                }
                if (!this.phone) {
                    this.errors.phone = window.translator('panel-errors.empty');
                }

                if (this.isEmpty(this.errors)) {

                    this.month = (typeof(this.month) === "object" ? this.months[this.month.id - 1].id : this.months[parseInt(this.month) - 1].id);
                    var form = document.getElementById('profile');
                    var formData = new FormData(form);
                    formData.append('birthdate', this.day + '.' + this.month + '.' + this.year);
                    formData.append('region_id', this.region.id);
                    axios.post('/user-panel/post-settings-data-profile', formData)
                        .then((response) => {
                            if (response.data.code === 200) {
                                swal(
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed2'),
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed_successfully'),
                                    'success'
                                )
                            }
                        })
                        .catch(function (error) {

                        });
                }
            },
            savePassport(){
                event.preventDefault();
                this.errors = {};
                if (!this.address) {
                    this.errors.address = window.translator('panel-errors.empty');
                }
                if (!this.serialNumber) {
                    this.errors.serialNumber = window.translator('panel-errors.empty');
                }
                if (!this.pin) {
                    this.errors.pin = window.translator('panel-errors.empty');
                }
                if (!this.gender) {
                    this.errors.gender = window.translator('panel-errors.empty');
                }
                if (!this.nationality) {
                    this.errors.nationality = window.translator('panel-errors.empty');
                }

                if (this.isEmpty(this.errors)) {
                    var form = document.getElementById('passport_data');
                    var formData = new FormData(form);
                    formData.append('gender',this.gender.id)
                    axios.post('/user-panel/post-settings-data-passport', formData)
                        .then((response) => {
                            if (response.data.code === 200) {
                                swal(
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed2'),
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed_successfully'),
                                    'success'
                                )
                            }
                        })
                        .catch(function (error) {

                        });
                }
            },
            changePassword(){
                event.preventDefault();
                this.errors = {};
                if(this.password.length < 8) {
                    this.errors.password = this.ExWindow && this.ExWindow.translator('panel-errors.password_min');
                }
                if(!this.cpassword) {
                    this.errors.cpassword = this.ExWindow && this.ExWindow.translator('panel-errors.password_repeat')
                } else{
                    if(this.cpassword !== this.password) {
                        this.errors.cpassword =  window.translator('panel-errors.password-noteq');
                    }

                }
                if(!this.currentPassword) {
                    this.errors.currentPassword = this.ExWindow && this.ExWindow.translator('panel-errors.password_current')
                }


                if (this.isEmpty(this.errors)) {
                    var form = document.getElementById('password_data');
                    var formData = new FormData(form);
                    axios.post('/user-panel/post-settings-data-password', formData)
                        .then((response) => {
                            if (response.data.code === 200) {
                                swal(
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed2'),
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed_successfully'),
                                    'success'
                                )
                            }
                        })
                        .catch(function (error) {

                        });
                }

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
            async getSettingsData() {
               const test  = await this.getSettings();
               test.data.user_contacts = test.data.user_contacts.length? test.data.user_contacts[0].name: '';
               // console.log(test.data.user_contacts);
                this.Settings = test.data;
                //this.gender = this.gender_arr[this.Settings.gender];
                this.name = this.Settings.name;
                this.surname = this.Settings.surname;
                this.email = this.Settings.email;
                this.phone = this.Settings.user_contacts;
                this.birthdate = this.Settings.birthdate;
                this.address = this.Settings.address;
                this.pin = this.Settings.pin;
                this.serialNumber = this.Settings.serial_number;
                this.nationality = this.Settings.nationality;

                this.region = this.regions[this.Settings.region_id];

                let bday = this.birthdate.split('.');
                this.day = bday[0];
                this.month = bday[1];
                this.year = bday[2];

            },
            getSettings() {
                var path = '/user-panel/get-settings-data';
                return  axios.get(path);

            },
            showSelect() {
                this.editable =true;
            },
            checkForm (e) {
                e.preventDefault();
                this.errors = {};
                if (!this.name) {
                    this.errors.name = window.translator('panel-errors.empty');
                }
                if (!this.surname) {
                    this.errors.surname = window.translator('panel-errors.empty');
                }
                if (!this.email) {
                    this.errors.email = window.translator('panel-errors.empty');
                } else {
                    if(!this.validEmail(this.email)) {
                        this.errors.email = window.translator('panel-errors.email-not-valid');
                    }
                }
                if (!this.phone) {
                    this.errors.phone = window.translator('panel-errors.empty');
                }
                if (!this.birthdate) {
                    this.errors.birthdate = window.translator('panel-errors.empty');
                }
                if (!this.address) {
                    this.errors.address = window.translator('panel-errors.empty');
                }
                if (!this.serialNumber) {
                    this.errors.serialNumber = window.translator('panel-errors.empty');
                }
                if (!this.pin) {
                    this.errors.pin = window.translator('panel-errors.empty');
                }
                if (!this.gender) {
                    this.errors.gender = window.translator('panel-errors.empty');
                }
                if(this.password) {
                    if(this.password.length < 8) {
                        this.errors.password = window.translator('panel-errors.password_min');
                    }
                    if(!this.cpassword) {
                        this.errors.cpassword = 'tekrar sifre daxil edilmeyib'
                    } else{
                        if(this.cpassword !== this.password) {
                            this.errors.cpassword =  window.translator('panel-errors.password-noteq');
                        }

                    }
                    if(!this.currentPassword) {
                        this.errors.currentPassword = 'cari sifre daxil edilmeyib'
                    }
                }

                if (this.isEmpty(this.errors)) {
                    var form = document.getElementById('settings_form');
                    var formData = new FormData(form);
                    axios.post('/user-panel/post-settings-data', formData)
                        .then((response) => {
                            if(response.data.code === 200) {
                                swal(
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed2'),
                                    this.ExWindow && this.ExWindow.translator('panel-errors.completed_successfully'),
                                    'success'
                                )
                            }
                        })
                        .catch(function (error) {
                        });
                }
            },
            validEmail: function (email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            }
        },
        components: {
            vSelect
        }
    }

</script>
<style scoped>
    .kabinet .nav-tabs li {
        width: 83px;
    }
    .kuryer-kabinet .input-group-addon {
        position: absolute;
        background: transparent;
        right: 8px;
        top: 50%;
        z-index: 999;
        border: none;
        color: #707c88;
        font-size: 12px;
    }
    .kuryer-kabinet .input-group .form-control:not(:first-child):not(:last-child) {
        border-radius: 17px;
    }
    .count-end button {
        width: 140px;
        padding: 6px 0;
    }
</style>
