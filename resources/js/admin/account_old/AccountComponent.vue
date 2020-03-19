<template>
    <div class="row">
        <div class="top-search col-md-12 col-sm-12 col-xs-12">
            <div class="block col-xs-12">
                <form action="...">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Axtarış">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <div class="input-group">
                                <select class="selectpicker">
                                    <option>Tarixə görə</option>
                                    <option>Müştəri koduna görə</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <button type="button" class="btn-effect">Axtar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="top-menu">
                <h4>Hesablar</h4>
                <ol class="breadcrumb">
                    <li><router-link to="/"><i class="fa fa-home"></i>Ana səhifə</router-link></li>
                    <li class="active">Hesablar</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="block invoys">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#cards" aria-controls="cards" role="tab" data-toggle="tab">Kartlar</a></li>
                    <li role="presentation"><a href="#account" aria-controls="account" role="tab" data-toggle="tab">Hesablar</a></li>
                    <li role="presentation"><a href="#e-wallet" aria-controls="e-wallet" role="tab" data-toggle="tab">E-Pulqabı</a></li>
                    <li role="presentation"><a href="#cashbox" aria-controls="cashbox" role="tab" data-toggle="tab">Kassa</a></li>
                    <!--<li role="presentation"><a href="#cardback" aria-controls="cardback" role="tab" data-toggle="tab">Karta qaytarılanlar</a></li>-->
                    <!--<li role="presentation"><a href="#five-percent" aria-controls="five-percent" role="tab" data-toggle="tab">5%</a></li>-->
                    <!--<li role="presentation"><a href="#customer-balance" aria-controls="customer-balance" role="tab" data-toggle="tab">Müştəri balansları</a></li>-->
                    <!--<li role="presentation"><a href="#eft" aria-controls="eft" role="tab" data-toggle="tab">EFT/həvalə</a></li>-->
                </ul>
            </div>
        </div>
        <div class="right-side col-md-12 col-sm-12 col-xs-12">
            <div class="tab-content courier">
                <div role="tabpanel" class="tab-pane fade in active" id="cards">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Hesab tipi</th>
                                <th>Balans</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="account in accounts[3]">
                                <td>{{ account.name }}</td>
                                <td>{{ account.balance }}</td>
                                <td>
                                    <router-link class="btn-effect" v-bind:to="'/account/logs/' + account.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!--<nav>-->
                            <!--<ul class="pagination">-->
                                <!--<li class="disabled"><a href="#" aria-label="Previous"><span-->
                                        <!--aria-hidden="true">ƏVVƏL</span></a></li>-->
                                <!--<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>-->
                                <!--<li><a href="#">2</a></li>-->
                                <!--<li><a href="#">3</a></li>-->
                                <!--<li><a href="#">4</a></li>-->
                                <!--<li><a href="#">5</a></li>-->
                                <!--<li>-->
                                    <!--<a href="#" aria-label="Next">-->
                                        <!--<span aria-hidden="true">NÖVBƏTİ</span>-->
                                    <!--</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</nav>-->
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="account">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Hesab tipi</th>
                                <th>Balans</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="account in accounts[2]">
                                <td>{{ account.name }}</td>
                                <td>{{ account.balance }}</td>
                                <td>
                                    <router-link class="btn-effect" v-bind:to="'/account/logs/' + account.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!--<nav>-->
                            <!--<ul class="pagination">-->
                                <!--<li class="disabled"><a href="#" aria-label="Previous"><span-->
                                        <!--aria-hidden="true">ƏVVƏL</span></a></li>-->
                                <!--<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>-->
                                <!--<li><a href="#">2</a></li>-->
                                <!--<li><a href="#">3</a></li>-->
                                <!--<li><a href="#">4</a></li>-->
                                <!--<li><a href="#">5</a></li>-->
                                <!--<li>-->
                                    <!--<a href="#" aria-label="Next">-->
                                        <!--<span aria-hidden="true">NÖVBƏTİ</span>-->
                                    <!--</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</nav>-->
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="e-wallet">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Hesab tipi</th>
                                <th>Balans</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="account in accounts[4]">
                                <td>{{ account.name }}</td>
                                <td>{{ account.balance }}</td>
                                <td>
                                    <router-link class="btn-effect" v-bind:to="'/account/logs/' + account.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="cashbox">
                    <div class="block table-block">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Hesab</th>
                                <th>Balans</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="account in accounts[1]">
                                <td>{{ account.name }}</td>
                                <td>{{ account.balance }}</td>
                                <td>
                                    <router-link class="btn-effect" v-bind:to="'/account/logs/' + account.id">Bax</router-link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--<div role="tabpanel" class="tab-pane fade" id="cardback">-->
                    <!--<div class="block table-block">-->
                        <!--<table class="table table-striped">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th>Müştəri Adı, Soyadı</th>-->
                                <!--<th>Balans</th>-->
                                <!--<th></th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<tr>-->
                                <!--<td>Fatimə Nağıyeva</td>-->
                                <!--<td>8 AZN</td>-->
                                <!--<td>-->
                                    <!--<a href="#" class="btn-effect">Bax</a>-->
                                <!--</td>-->
                            <!--</tr>-->
                            <!--</tbody>-->
                        <!--</table>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div role="tabpanel" class="tab-pane fade" id="five-percent">-->
                    <!--<div class="block table-block">-->
                        <!--<table class="table table-striped">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th>Müştəri Adı, Soyadı</th>-->
                                <!--<th>Balans-5%</th>-->
                                <!--<th></th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<tr>-->
                                <!--<td>Fatimə Nağıyeva</td>-->
                                <!--<td>8 AZN</td>-->
                                <!--<td>-->
                                    <!--<a href="#" class="btn-effect">Bax</a>-->
                                <!--</td>-->
                            <!--</tr>-->
                            <!--</tbody>-->
                        <!--</table>-->
                        <!--<nav>-->
                            <!--<ul class="pagination">-->
                                <!--<li class="disabled"><a href="#" aria-label="Previous"><span-->
                                        <!--aria-hidden="true">ƏVVƏL</span></a></li>-->
                                <!--<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>-->
                                <!--<li><a href="#">2</a></li>-->
                                <!--<li><a href="#">3</a></li>-->
                                <!--<li><a href="#">4</a></li>-->
                                <!--<li><a href="#">5</a></li>-->
                                <!--<li>-->
                                    <!--<a href="#" aria-label="Next">-->
                                        <!--<span aria-hidden="true">NÖVBƏTİ</span>-->
                                    <!--</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</nav>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div role="tabpanel" class="tab-pane fade" id="customer-balance">-->
                    <!--<div class="block table-block">-->
                        <!--<table class="table table-striped">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th>Müştəri Adı, Soyadı</th>-->
                                <!--<th>Balans customer</th>-->
                                <!--<th></th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<tr>-->
                                <!--<td>Fatimə Nağıyeva</td>-->
                                <!--<td>8 AZN</td>-->
                                <!--<td>-->
                                    <!--<a href="#" class="btn-effect">Bax</a>-->
                                <!--</td>-->
                            <!--</tr>-->
                            <!--</tbody>-->
                        <!--</table>-->
                        <!--<nav>-->
                            <!--<ul class="pagination">-->
                                <!--<li class="disabled"><a href="#" aria-label="Previous"><span-->
                                        <!--aria-hidden="true">ƏVVƏL</span></a></li>-->
                                <!--<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>-->
                                <!--<li><a href="#">2</a></li>-->
                                <!--<li><a href="#">3</a></li>-->
                                <!--<li><a href="#">4</a></li>-->
                                <!--<li><a href="#">5</a></li>-->
                                <!--<li>-->
                                    <!--<a href="#" aria-label="Next">-->
                                        <!--<span aria-hidden="true">NÖVBƏTİ</span>-->
                                    <!--</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</nav>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div role="tabpanel" class="tab-pane fade" id="eft">-->
                    <!--<div class="block table-block">-->
                        <!--<table class="table table-striped">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th>Müştəri Adı, Soyadı</th>-->
                                <!--<th>Balans customer</th>-->
                                <!--<th></th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<tr>-->
                                <!--<td>Fatimə Nağıyeva</td>-->
                                <!--<td>8 AZN</td>-->
                                <!--<td>-->
                                    <!--<a href="#" class="btn-effect">Bax</a>-->
                                <!--</td>-->
                            <!--</tr>-->
                            <!--</tbody>-->
                        <!--</table>-->
                        <!--<nav>-->
                            <!--<ul class="pagination">-->
                                <!--<li class="disabled"><a href="#" aria-label="Previous"><span-->
                                        <!--aria-hidden="true">ƏVVƏL</span></a></li>-->
                                <!--<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>-->
                                <!--<li><a href="#">2</a></li>-->
                                <!--<li><a href="#">3</a></li>-->
                                <!--<li><a href="#">4</a></li>-->
                                <!--<li><a href="#">5</a></li>-->
                                <!--<li>-->
                                    <!--<a href="#" aria-label="Next">-->
                                        <!--<span aria-hidden="true">NÖVBƏTİ</span>-->
                                    <!--</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</nav>-->
                    <!--</div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AccountComponent",
        data: function(){
            return {
                accounts: {}
            }
        },
        mounted(){
            axios.get('/cp/accounts')
                .then((response) => {
                    console.log(response);
                    this.accounts = response.data.accounts
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    }
</script>

<style scoped>

</style>