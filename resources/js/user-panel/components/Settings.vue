<template>
    <div>
        <div class="kuryer-kabinet right-side col-md-9 col-sm-12 col-xs-12">
            <div class="block">
                <form id="settings_form" @submit="checkForm" method="post" action="/user-panel/post-settings-data">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="name">{{ExWindow && ExWindow.translator('register.name')}} *</label>
                                        <input type="text" v-model="name" name="name" class="form-control" id="name" :placeholder="ExWindow && ExWindow.translator('register.nameP')">
                                        <span style="color: red" v-if="errors.name" >{{errors.name? errors.name: ''}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="surname">{{ExWindow && ExWindow.translator('register.surname')}} *</label>
                                        <input type="text" v-model="surname" name="surname" class="form-control" id="surname"
                                               :placeholder="ExWindow && ExWindow.translator('register.surnameP')">
                                        <span style="color: red" v-if="errors.surname" >{{errors.surname}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="mail">{{ExWindow && ExWindow.translator('register.email')}} *</label>
                                        <input type="email" v-model="email" class="form-control" name="email" id="mail"
                                               :placeholder="ExWindow && ExWindow.translator('register.emailP')">
                                        <span style="color: red" v-if="errors.email" >{{errors.email}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="phone">{{ExWindow && ExWindow.translator('register.phone')}} *</label>
                                        <input type="text" v-model="phone" class="form-control" id="phone" name="phone" placeholder="+994" value="+994 ">
                                        <span style="color: red" v-if="errors.phone" >{{errors.phone? errors.phone: ''}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="passii">{{ExWindow && ExWindow.translator('register.password')}} *</label>
                                        <input type="text" v-model="password" class="form-control" name="password" id="passii"
                                               :placeholder="ExWindow && ExWindow.translator('register.passwordP')">
                                        <span style="color: red" type="password" v-if="errors.password" >{{errors.password? errors.password: ''}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="repeat-pass">{{ExWindow && ExWindow.translator('register.password_confirmation')}} *</label>
                                        <input type="password" v-model="cpassword" class="form-control" name="cpassword" id="repeat-pass"
                                               :placeholder="ExWindow && ExWindow.translator('register.passwordP')">
                                        <span style="color: red" v-if="errors.cpassword" >{{errors.cpassword? errors.cpassword: ''}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="nowpass">{{ExWindow && ExWindow.translator('register.password')}} {{ExWindow && ExWindow.translator('register.current')}} *</label>
                                        <input type="password" v-model="currentPassword" class="form-control" name="currentPassword" id="nowpass"
                                               :placeholder="ExWindow && ExWindow.translator('register.passwordP')">
                                        <span style="color: red" v-if="errors.currentPassword" >{{errors.currentPassword? errors.currentPassword: ''}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group date" data-date-format="dd.mm.yyyy">
                                        <label for="birthday">{{ExWindow && ExWindow.translator('register.birthdate')}}</label>
                                        <input type="text" v-model="birthdate" class="form-control" name="birthdate" id="birthday"
                                               placeholder="-- / -- / ----">
                                        <span class="input-group-addon"> <span class="fa fa-caret-down"></span> </span>
                                        <span style="color: red" v-if="errors.birthdate" >{{errors.birthdate? errors.birthdate: ''}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="address">{{ExWindow && ExWindow.translator('register.address')}} *</label>
                                        <input type="text" v-model="address" class="form-control" name="address" id="address"
                                               :placeholder="ExWindow && ExWindow.translator('register.addressP')">
                                        <span style="color: red" v-if="errors.address" >{{errors.address? errors.address: ''}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="seriya">{{ExWindow && ExWindow.translator('register.serial_number')}} *</label>
                                        <input type="text" v-model="serialNumber" class="form-control" name="serialNumber" id="seriya"
                                               :placeholder="ExWindow && ExWindow.translator('register.serial_numberP')">
                                        <span style="color: red" v-if="errors.serialNumber" >{{errors.address? errors.address: ''}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="pin">{{ExWindow && ExWindow.translator('register.fin')}}</label>
                                        <input type="text" v-model="pin" class="form-control" name="pin" id="pin"
                                               :placeholder="ExWindow && ExWindow.translator('register.finP')">
                                        <span style="color: red" v-if="errors.pin" >{{errors.pin? errors.pin: ''}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="nationality">{{ExWindow && ExWindow.translator('register.nationality')}}</label>
                                        <input type="text" v-model="nationality" class="form-control" name="nationality" id="nationality"
                                               :placeholder="ExWindow && ExWindow.translator('register.nationalityP')">
                                        <span style="color: red" v-if="errors.nationality" >{{errors.nationality? errors.nationality: ''}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="cins">{{ExWindow && ExWindow.translator('register.gender')}}</label>
                                        <select v-model="gender" class="customSelect form-control" name="gender" id="cins" title="@lang('register.gender')">
                                            <option value="2">{{ExWindow && ExWindow.translator('register.female')}}</option>
                                            <option value="1">{{ExWindow && ExWindow.translator('register.male')}}</option>
                                        </select>
                                        <span style="color: red" v-if="errors.gender" >{{errors.gender? errors.gender: ''}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                            <button class="btn-effect">{{ExWindow && ExWindow.translator('register.confirm')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert2';
    export default {
        mounted() {
            this.getSettingsData().then(() => {
                setTimeout(() => {
                    console.log(this.gender);
                    $('.customSelect').val(this.gender).selectpicker();
                }, 1400);
            });
            this.name = this.Settings.name;
            this.surname = this.Settings.surname;
            this.email = this.Settings.email;
            this.phone = this.Settings.user_contacts;
            this.birthdate = this.Settings.birthdate;
            this.address = this.Settings.address;
            this.serialNumber = this.Settings.serial_number;
            this.pin = this.Settings.pin;
            this.password = '';
            this.cpassword = '';
            this.currentPassword = '';
            this.gender = this.Settings.gender;
            this.nationality = this.Settings.nationality;
            this.ExWindow = window;
        },
        beforeDestroy() {
            $('.customSelect').selectpicker('destroy', true);
            $('.customSelect').remove();
        },
        props: {

        },
        data: function (){
            return {
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
                gender: String,
                nationality: String,
                ExWindow: null
            }
        },
        methods: {
            async getSettingsData() {
               const test  = await this.getSettings();
               test.data.user_contacts = test.data.user_contacts.length? test.data.user_contacts[0].name: '';
               console.log(test.data.user_contacts);
                this.Settings = test.data;
                this.gender = this.Settings.gender;
                this.nationality = this.Settings.nationality;
                this.name = this.Settings.name;
                this.surname = this.Settings.surname;
                this.email = this.Settings.email;
                this.phone = this.Settings.user_contacts;
                this.birthdate = this.Settings.birthdate;
                this.address = this.Settings.address;
                this.pin = this.Settings.pin;
                this.serialNumber = this.Settings.serial_number;
            },
            getSettings() {
                var path = '/user-panel/get-settings-data';
                return  axios.get(path);

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
                if (!this.nationality) {
                    this.errors.nationality = window.translator('panel-errors.empty');
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
                        .then(function (response) {
                            if(response.data.code === 200) {
                                swal(
                                    'Tamamlandı',
                                    'Tənzimləmələrdə edilmiş dəyişikliklər təsdiqləndi',
                                    'success'
                                )
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
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
