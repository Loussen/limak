<template>
    <div class="row">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="send-notify col-xs-12 col-md-4 col-md-offset-8">
            <div class="box">
                <div class="form-group">
                    <label for="comment">Bildiriş göndər:</label>
                    <textarea v-model="message" class="form-control" rows="5" id="comment"></textarea>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <input type="button" class="blue btn-effect" value="Email ilə">
                    </div>
                    <div class="col-md-6">
                        <input @click="sendSms" type="button" class="blue btn-effect" value="SMS ilə">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="tab-content courier">
                <div role="tabpanel" class="tab-pane active" id="paytr">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Geri qaytaran</th>
                                <th>Sifariş №</th>
                                <th>Sifariş tarixi</th>
                                <th>Sifarişin təsdiq kodu</th>
                                <th>Referans nömrəsi</th>
                                <th>Qaytarılacaq məbləğ</th>
                                <th>Qaytarılma tarixi</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr  >
                                <td v-if="repayment.executor">{{ repayment.executor.name }} {{ repayment.executor.surname }}</td>
                                <td v-else></td>
                                <td>{{ order_nums }}</td>
                                <td>{{ order_dates }}</td>
                                <td v-if="repayment.executor">{{ repayment.confirmation_code}}</td>
                                <td v-else><input v-model="repayment.confirmation_code" type="text"></td>
                                <td v-if="repayment.executor">{{ repayment.referance }}</td>
                                <td v-else><input v-model="repayment.referance" type="text"></td>
                                <td>{{ repayment.repayment_amount }} TL</td>
                                <td v-if="repayment.executor">{{repayment.repayment_date | formatDate}}</td>
                                <td v-else><input v-model="repayment.repayment_date" type="date"></td>
                                <td v-if="repayment.executor"><a href="#" @click.prevent class="btn green">Icra edildi</a></td>
                                <td v-else >
                                    <a @click.prevent="execute()" href="#" class="btn btn-primary">Icra et</a>
                                    <a @click.prevent="deleteRepayment()" href="#" class="btn btn-danger">Sil</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Menu from  './AccountantMenuComponent';
    import swal from 'sweetalert2';
    export default {
        name: "BackToCardDetailsComponent",
        data: function(){
            return {
                message: '',
                user: {},
                orders: [],
                repayment: {}
            }
        },
        computed: {
            order_nums: function(){
                let orders =  this.orders.map((order) =>{
                    return order.id + 1000000
                })
                return orders.join(', ')
            },
            order_dates: function(){
                let orders =  this.orders.map((order) =>{
                    return order.created_at
                })
                return orders.join(', ')
            }
        },
        methods: {
            getData: function(){
                axios.get(`/cp/accounts/repaymentOrders/${this.$route.params.id}`).then( ( { data } ) => {
                   this.orders = data.orders;
                   this.repayment = data.repayment;
                   this.user = data.user;
                });
            },
            execute: function(){
                if( this.repayment.confirmation_code && this.repayment.referance ){
                    axios.post('/cp/accounts/repaymentExecute/'+ this.repayment.id, {
                        confirmation_code: this.repayment.confirmation_code,
                        referance: this.repayment.referance,
                        repayment_date: this.repayment.repayment_date
                    }).then((data) => {
                        this.getData();
                    });
                }

            },
            deleteRepayment: function(){
                axios.delete('/cp/accounts/repayment/'+this.repayment.id)
                    .then( () => {
                        return this.$router.push({ path: '/accountant/backToCard' })
                    });
            },
            sendSms(){
                axios.post('/cp/orders/sendSMS', {
                    text: this.message,
                    phone: this.user.name,
                }).then((data) => {
                    if (data.data.status === 200) {
                        this.show = false;
                        this.message = ''
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə göndərildi',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            },
            sendEmail(){
                axios.post('/cp/orders/sendEmail', {
                    text: this.message,
                    email: this.user.email,
                }).then((data) => {
                    if (data.data.status === 200) {
                        this.show = false;
                        this.message = ''
                        swal({
                            type: 'success',
                            title: 'Müvəffəqiyyətlə göndərildi',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            },
        },
        mounted(){
            this.getData()
        },
        components: {
            Menu
        }
    }
</script>

<style scoped>
    .send-notify{
        margin-bottom: 50px;
    }
    .btn.green{
        color: #fff;
    }
</style>