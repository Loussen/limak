<template>



    <div role="tabpanel" class="tab-pane fade in active" id="xarici">
        <div class="block col-xs-12">

            <div class="row">
                <div class="com-md-10">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Bu tarixdən</label>
                            <input v-on:change="checkForm" type="date" class="form-control" placeholder="Bu tarixdən" v-model="filter.begin_date">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Bu tarixə</label>
                            <input v-on:change="checkForm" type="date" class="form-control" placeholder="Bu tarixə" v-model="filter.end_date">
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Ölkə </label>
                            <select v-on:change="checkForm" v-model="filter.country_id" class="form-control">
                                <option v-for="c in countries" v-bind:value="c.id" >{{c.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Status </label>
                            <select v-on:change="checkForm" v-model="filter.status_id" class="form-control">
                                <option value="0">Bütün statuslar</option>
                                <option v-for="(st,index) in arrayStatus" v-bind:value="index" >{{st}}</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-md-2 pull-right">
                    <button class="btn-effect" @click="checkForm()">Axtar</button>
                </div>
            </div>



            <div class="row">

            </div>
        </div>



        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="block table-block">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th  v-bind="count_i=(dataParams.current_page-1)*10"></th>
                        <th>Bağlama No</th>
                        <th>Qəbul edən</th>
                        <th>Mağaza</th>
                        <th>Tipi</th>
                        <th>Qiyməti</th>
                        <th>Sayı</th>
                        <th>Çəki</th>
                        <th>Çatdırılma Haqqı</th>
                        <th>Tarix</th>
                        <th>Kart</th>
                        <th>Ölkə</th>
                        <th>Anbar</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                         <tr v-for="invoice in invoices">
                             <td>{{count_i = count_i+1}}</td>
                             <td>{{invoice.purchase_no}}</td>
                            <td>{{invoice.admin_name}} {{invoice.admin_surname}}</td>
                            <td>{{invoice.shop_name}}</td>
                            <td>{{invoice.product_type_name}}</td>
                             <td>{{invoice.price}} <span v-if="invoice.country_id==1">TL</span><span v-else-if="invoice.country_id==2">USD</span></td>
                            <td>{{invoice.quantity}}</td>
                            <td>{{invoice.weight}} Kq</td>
                            <td>{{invoice.shipping_price}}$ ({{calcToAzn(invoice.shipping_price)}} AZN) <span v-if="invoice.is_paid==1">(Ödənildi)</span></td>
                             <td>{{invoice.order_date | formatDate}}</td>
                             <td>{{invoice.account_id}}</td>
                            <td v-if="invoice.country_id>0">{{countries[invoice.country_id].name}}</td>
                             <td v-else></td>
                             <td v-if="invoice.barcode_id!=null">{{invoice.barcode_id}}</td>
                             <td v-else>Yox</td>
                            <td>{{arrayStatus[invoice.status_id]}}{{"("+arrayRegions[invoice.region_id]+")"}}
                                <br /><b v-if="invoice.is_premium==1">(Premium)</b>
                            </td>
                             <td>
                                 <textarea v-on:keyup="saveToComment($event,invoice)" >{{invoice.comment}}</textarea>
                             </td>
                             <td>
                                 <a v-on:click="showInvoice(invoice)" class="btn-effect lite-green">Bəyənnaməyə bax</a><br>
                             </td>
                        </tr>
                    </tbody>
                </table>
                <nav>
                    <div style="float: left">
                        <p>Çatdırılma haqqı: {{totalShippingPrice}} ({{ calcToAzn(totalShippingPrice) }} AZN)</p>
                        <p>Çəki: {{(weight).toFixed(3)}} kq</p>
                    </div>
                    <ul class="pagination">
                        <li ><a @click.prevent="getData( dataParams.current_page - 1)" href="#" aria-label="Previous"><span
                                aria-hidden="true">ƏVVƏL</span></a></li>
                        <template  v-for="page in dataParams.last_page">
                            <li v-if="page ==1 && dataParams.current_page==1" class="active">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page ==1">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)> 3 && Math.abs(dataParams.current_page - page)<= 6 ">
                                <a href="#">.</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)<= 3 && dataParams.current_page == page" class="active">
                                <a @click.prevent="getData(  page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="Math.abs(dataParams.current_page - page)<= 3 ">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                            <li v-else-if="page == dataParams.last_page">
                                <a @click.prevent="getData( page)" href="#">{{ page }}</a>
                            </li>
                        </template>
                        <li>
                            <a v-show="dataParams.current_page < dataParams.last_page" @click.prevent="getData(  dataParams.current_page + 1)" href="#" aria-label="Next">
                                <span aria-hidden="true">NÖVBƏTİ</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <show-invoice-data-modal v-bind:productData="showProductData" v-bind:show="showShowInvoiceModal" @close-modal="closeShowInvoiceModal"></show-invoice-data-modal>


        <div class="block col-xs-12">
            <form action="" method="">
                <div class="row">
                    <div class="com-md-10">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Bu tarixdən</label>
                                <input type="date" class="form-control" placeholder="Bu tarixdən" v-model="begin_date">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Bu tarixə</label>
                                <input  type="date" class="form-control" placeholder="Bu tarixə" v-model="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <button class="btn-effect" type="submit" @click="submitForm()">Hesabat çıxart</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    import Select2 from "../../../user-panel/components/shared/select2";
    import ShowInvoiceDataModal from "../../components/showInvoiceDataModal";

    export default {
        name: "UsersInvoicesComponent",
        components: {Select2,ShowInvoiceDataModal},
        props: ['selected_type','user_id'],
        data(){
            return {
                currency: 0,
                invoices: '',
                current_type: '',
                dataParams : '',
                countries: '',
                showShowInvoiceModal: false,
                showProductData: null,
                totalShippingPrice: 0,
                weight: 0,

                filter: {
                  date: '',
                  user_id:  this.user_id,
                  begin_date: '',
                  end_date: '',
                  country_id: '',
                  status_id: '',
                },

                begin_date: '',
                end_date: '',


                arrayStatus: {1: "Sifariş verildi",2: "Xarici anbarda",3 : "Yoldadır", 11: "Gömrükdə",4 : "Anbarda ", 5: "Tamamlanıb", 6: "Kuryerdə", 7 : "Kuryer Təhvil verdi", 8: "Gömrükdə saxlanan", 9: "Poçtdadır",12: "Saklanan koliler", 13: "Saklanan koliler manual", 14: "Problemi bağlama tamamlanmışdır",15 : "Yoldadır müvəqqəti", 16: "Iade"},
                arrayRegions : {1:"Bakı",2:"Gəncə",3:"Sumqayıt",4:"Zaqatala",5: "Lənkəran"}
            };
        },

        methods:{
            saveToComment(elem,invoice){
                console.log(elem.target.value)
                console.log(invoice.p_id)
                axios.post('/cp/users/save_comment', {
                    comment: elem.target.value,
                    product_id: invoice.p_id
                })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            calcToAzn(price){
                if(price===null) return 0;
                return (parseFloat(this.currency,2)*parseFloat(price,2)).toFixed(2);
            },
            submitForm (){
                this.errors = {};
                if (this.isEmpty(this.errors)) {
                    var url ='https://limak.az/cp/users/hesabat?user_id='+this.user_id+'&begin_date='+this.begin_date+'&end_date='+this.end_date;
                    window.open(url, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes')
                }
            },

            checkForm () {
                
                this.errors = {};
                if (this.isEmpty(this.errors)) {

                    this.getData();
                }
            },
            isEmpty(obj) {
                for(var key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                }
                return true;
            },
            getData(page_id=1){
                this.totalShippingPrice= 0;
                this.weight= 0;
                axios.get('/cp/users/all_invoices'+'?page='+page_id,{params:this.filter})
                    .then((response) => {
                        this.currency = response.data.data.currency.val;
                        this.invoices = response.data.data.invoices.data;
                        for(let i in this.invoices){
                            if(this.invoices[i].shipping_price!==null)
                                this.totalShippingPrice += parseFloat(this.invoices[i].shipping_price);
                            if(this.invoices[i].weight!==null)
                                this.weight += parseFloat(this.invoices[i].weight);
                        }
                        this.dataParams = response.data.data.invoices
                        this.current_type = this.selected_type;
                        this.countries = response.data.data.countries;

                    })
            },
            closeShowInvoiceModal () {
                this.showShowInvoiceModal = false;
            },
            showInvoice(productData) {
                if(productData.package_id!==null) {
                    axios.get('/cp/getInvoiceProducts/' + productData.id)
                        .then((data) => {
                            if(data) {
                                productData = data.data.data;
                            }
                        })
                        .catch((err) => {
                            console.log(err);
                        })

                    axios.get('/cp/orders/getProductsByPackage/' + productData.package_id)
                        .then((result) => {
                            if (result.data.status === 200) {
                                /*productData = result.data.data[0]

                                this.showShowInvoiceModal = true;
                                var data = JSON.parse(JSON.stringify(productData));

                                this.showProductData = data;*/

                                productData.links = result.data.data

                                this.showShowInvoiceModal = true;
                                var data = JSON.parse(JSON.stringify(productData));
                                console.log(data)
                                this.showProductData = data;

                            } else {
                                alert(result.data.message);
                            }
                        }).catch(err => {
                        alert(err);
                    });
                }else{
                    productData.links = null

                    this.showShowInvoiceModal = true;
                    var data = JSON.parse(JSON.stringify(productData));

                    this.showProductData = data;
                }
            },

        },

        mounted(){
            this.getData();/*
            setInterval(() => {
                if(this.current_type != this.selected_type)
                    this.getData();
            }, 300);*/
        },
    }
</script>

<style scoped>
table tr td,table tr th{
    padding: 15px 10px !important;

}
table tr td a{
    width: 150px !important;
}
</style>