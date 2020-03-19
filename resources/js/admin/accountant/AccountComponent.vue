<template>
    <div class="row" v-if="this.roles.length >0">
        <div class="col-md-12"><Menu></Menu></div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a @click="selected_account_id=3" aria-controls="cards" role="tab" data-toggle="tab">Kartlar</a></li>
                    <li role="presentation"><a @click="selected_account_id=2"  aria-controls="account" role="tab" data-toggle="tab">Hesablar</a></li>
                    <li role="presentation"><a @click="selected_account_id=4" aria-controls="e-wallet" role="tab" data-toggle="tab">E-Pulqabı</a></li>
                    <li role="presentation"><a @click="selected_account_id=1" aria-controls="cashbox" role="tab" data-toggle="tab">Kassa</a></li>
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="tab-content courier">
                <div role="tabpanel" class="tab-pane active">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Hesab tipi</th>
                                <th>Balans</th>
                                <th>Minus</th>
                                <th>Plus</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="account in all_accounts[selected_account_id].accounts">
                                <td>{{ account.name }}</td>
                                <td>{{ account.balance }}</td>
                                <td>{{ account.minus }}</td>
                                <td>{{ account.plus }}</td>
                                <td>
                                    <button @click="addEntry(account.id)" class="btn btn-success">Mədaxil et</button>
                                    <router-link class="btn-effect" v-bind:to="'/account/logs/'+account.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="add_modal" id="add-entry" style="display: block" class="modal fade in" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="add_modal=false" type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Mədaxil (Məxaric) et</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tip</label>
                            <select v-model="type" name="type" id="type" class="form-control">
                                <option value="plus">Plus</option>
                                <option value="minus">Minus</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Məbləğ:</label>
                            <input v-model="amount" type="text" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label >Şərh:</label>
                            <textarea v-model="comment" class="form-control" rows="3"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button @click="addToAccount" class="btn btn-success">Əlavə et</button>
                        <button @click="add_modal=false" type="button" class="btn btn-danger">Bağla</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import Menu from  './AccountantMenuComponent'
    import auth from '../auth.js'
    export default {
        name: "AccountComponent",
        data: function(){
            return {
                all_accounts: {
                    3: {
                        accounts: []
                    }
                },
                selected_account_id: 3,
                add_modal: false,
                amount: '',
                comment: '',
                type: 'plus',
                id: ''
            }
        },
        methods: {
           addToAccount: function(){
               axios.post('/cp/accounts/add',{
                   id: this.id,
                   amount: this.amount,
                   comment: this.comment,
                   type: this.type,
               }).then((data) => {
                    this.add_modal = false;
                   this.getData()
               });
           },
            addEntry: function(id){
               this.id = id;
               this.add_modal = true
            },
            getData: function(){
                axios.get('/cp/accounts')
                    .then((response) => {
                        this.all_accounts = response.data.accounts;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        mounted(){
            this.getData()
        },
        components: {
            Menu
        },
        updated(){
            if(this.roles.length >0){
                this.ifNoAccessRedirect(Array('super_admin','accountant'))
            }
        },
        mixins: [auth]
    }
</script>

<style scoped>
    .btn-success, .btn-danger{
        padding: 1px 3px;
        border-radius: 12px;
        height: 24px;
        width: 75px;
    }
    #add-entry{
        top: 200px;
    }
</style>