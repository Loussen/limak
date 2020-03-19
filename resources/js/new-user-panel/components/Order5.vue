<template>
    <section class="order spin-load content num-spin fixed-class">
        <div class="container">
            <div class="row">
                <div class="order-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-6">
                        <h4>5{{ExWindow ? ExWindow.translator('panel-errors.order2') : '' }} <b>{{ExWindow ? ExWindow.translator('panel-errors.do') : '' }}</b></h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-6 ">
                        <ol class="breadcrumb web">
                            <li><a href="/">{{ExWindow ? ExWindow.translator('panel.home_page') : '' }} </a></li>
                            <li><a href="/site/user-panel"> {{ExWindow ? ExWindow.translator('panel.user_panel') : '' }} </a></li>
                            <li class="active">{{ExWindow ? ExWindow.translator('panel-errors.do_order') : '' }}</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="/">... </a></li>
                            <li class="active">{{ExWindow ? ExWindow.translator('panel-errors.do_order') : '' }}</li>
                        </ol>
                    </div>
                </div>
                <div class="order-body col-xs-12">
                    <div class="row relative">
                        <div class="changeable col-md-9 col-sm-8 col-xs-12">
                            <div class="order-left-side block">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#1" aria-controls="home" role="tab" data-toggle="tab" class="order-img1">
                                            {{ExWindow ? ExWindow.translator('panel-errors.from_turkey') : '' }}
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="1">
                                        <div class="left-content " v-for="(order, index) in orders">
                                            <div class="row">
                                                <div class="col-md-9 col-sm-9 col-xs-6">
                                                    <div class="input-group border-radius">
                                                        <label>
                                                            <input type="text" v-model="order.link" @blur="parse(index)" class="form-control inputText" placeholder=" ">
                                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.product_link') : '' }} *</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-6">
                                                    <div class="input-group border-radius">
                                                        <label>
                                                            <input type="text" v-model="order.price" @input="singleTotal(index)" @change="singleTotal(index)" class="form-control inputText" placeholder=" ">
                                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.amount_cash') : '' }}(TL) *</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="input-group">
                                                        <label data-v-170866b4="" class="select-class">{{ExWindow ? ExWindow.translator('link-order.cargoTR') : '' }} *</label>
                                                        <v-select placeholder="getPlaceHolder" v-model="order.cargo_type" :options="cargo_types" label="name" v-on:change="change(order,index)"></v-select>

                                                        <!--<select class="form-control selectpicker" @change="change($event, index)" name="olke" title="Türkiyə daxili karqo *">
                                                            <option selected value="-1">Kargo pulu</option>
                                                            <option value="ode">Var</option>
                                                            <option value="pulsuz">Yoxdur</option>
                                                        </select>-->
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-xs-6">
                                                    <label class="select-class" v-if="order.hasCargo === 'ode'">{{ExWindow ? ExWindow.translator('panel-errors.cargo_price') : '' }} *</label>
                                                    <input v-if="order.hasCargo === 'ode'" v-model="order.cargo" type="number" class="form-control" name="input" @input="singleTotal(index)">
                                                    <!--
                                                                                                        <div class="simple-btn">+5%</div>
                                                    -->
                                                </div>
                                                <div class="col-sm-3  col-xs-6">
                                                    <div class="input-group border-radius">
                                                        <label>
                                                            <label data-v-170866b4="" class="select-class">{{ExWindow ? ExWindow.translator('panel-errors.sum') : '' }}(+5%)</label>
                                                            <input type="text" v-model="order.total" class="form-control"
                                                                   disabled
                                                                   :placeholder="ExWindow ? ExWindow.translator('panel-errors.sum') : ''+ '(TL)'">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="input-group number-spinner">
                                                        <span class="input-group-prepend">
                                                            <button class="btn btn-default" type="button" data-dir="dwn" @click="dec(order,index)"><strong >-</strong></button>
                                                        </span>
                                                        <input class="form-control text-center" v-model="order.quantity"  @input="singleTotal(index)" placeholder="Bağlamadakı məhsulun sayı *" type="number" value="" min="1"/>
                                                        <span class="input-group-append">
                                                            <button class="btn btn-default" type="button" data-dir="up" @click="inc(order,index)"><strong>+</strong></button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="input-group border-radius">
                                                        <label>
                                                            <input type="text" v-model="order.desc" name="length" class="form-control inputText" placeholder=" ">
                                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.order_desc') : '' }} *</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="btn-part text-right">
                                                        <button class="btn-linkk btn-effect" v-if="index > 0" @click="deleteRow(index)">
                                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.delete-link') : '' }}</span>
                                                        </button>
                                                        <button class="btn-add btn-effect" v-if="orders.length - 1 === index" @click="addRow()">
                                                            <span>{{ExWindow ? ExWindow.translator('panel-errors.add-link') : '' }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="changeable fixed-right col-md-3 col-sm-4 col-xs-12">
                            <div class="right-top block">
                                <last30days2></last30days2>
                            </div>
                            <div class="right-bottom ">
                                <h4 class="text-center">{{ExWindow ? ExWindow.translator('panel-errors.do_order') : '' }}</h4>

                                <label class="radio-button">
                                    <span class="exp">{{ExWindow ? ExWindow.translator('panel-errors.credit_card_payment') : '' }} </span> <br>
                                    <span class="description">( {{ExWindow ? ExWindow.translator('panel-errors.credit_card_payment_desc') : '' }} )</span>
                                    <input type="radio" v-model="payment_type" value="0" checked="checked" name="radio"  @click="paymentTypeStatus('paytr')">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="radio-button">
                                    <span class="exp">{{ExWindow ? ExWindow.translator('panel-errors.balance_payment') : '' }} </span> <br>
                                    <span class="description">( {{ExWindow ? ExWindow.translator('panel-errors.balance_payment_desc') : '' }} <br /><b>TL balansınız: {{balance_try}}TL</b> )</span>
                                    <input type="radio" v-model="payment_type" value="1" name="radio" @click="paymentTypeStatus('balance')">
                                    <span class="checkmark"></span>
                                </label>

                                <div class="sum">
                                    <span>{{ExWindow ? ExWindow.translator('panel-errors.sum') : '' }}: </span>
                                    <span class="cash">{{exchange ? exchange : totalPrice}} AZN</span>

                                    <label class="check-button">
                                        <span class="check-btn-text" data-toggle="modal" data-target=".privacy-modal" @click="privacyPolicy">{{lang ? lang('link-order.policy') : ''}}</span>
                                        <input type="checkbox" v-model="policy">
                                        <span class="checkmark"></span>
                                    </label>

                                </div>
                                <div class="text-center">
                                    <button :class="this.orders[0].price > 0 ? null : 'btn-send'" class="btn-effect"  @click="submit()">{{ExWindow ? ExWindow.translator('panel-errors.pay') : '' }}</button>
                                    <p class="almark">{{ExWindow ? ExWindow.translator('panel-errors.almark_service') : '' }}</p>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="privacy-modal modal fade" :class="{ in: modalShown }" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <header class="modal-header">
                        <h5 style="display: inline-block;" class="modal-title"><h3>{{lang ? lang('link-order.distantDeliverytitle') : ''}}</h3></h5>
                        <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </header>
                    <main class="modal-body">
                        <div v-if="lang && lang('link-order.distantDeliveryBody') === 'az'" >
                            <p>
                                Sifarişçi bu müqaviləni təsdiqləməklə şirkətin məsafəli satış şərtlərini qəbul
                                etdiyini bildirir.
                            </p>
                            <h4>I. Sifarişçinin öhdəlikləri</h4>
                            <ul>
                                <li>
                                    &mdash; Sifarişçi sistemdə qeydiyyatdan keçməklə, şəxsi məlumatlarını tələb olunan
                                    qaydada qeyd etməlidir.
                                </li>
                                <li>
                                    &mdash; Sifarişçi qeydiyyatdan keçmək üçün daxil etdiyi məlumatların doğruluğuna
                                    məsuliyyət daşıyır.
                                </li>
                                <li>
                                    &mdash; Sonradan qeyd olunmuş məlumatlarda dəyişikliklər olarsa, bununla bağlı
                                    sistemə məlumatların yenilənməsi üçün bildiriş göndərmək sifarişçinin
                                    öhdəliyidir.
                                </li>
                                <li>
                                    &mdash; Qadağan olunmuş məhsullar xəbərdarlıqdan sonra da sifariş verilərsə,
                                    xidmət həmin sifarişçi üçün bloklanır.
                                </li>
                                <li>
                                    &mdash; Sistemin tələb etdiyi qaydada ödəmə etmək sifarişçinin öhdəliyidir.
                                </li>
                                <li>
                                    &mdash; Öz şifrə və məlumatları digər şəxslərə verməməlidir. Belə hallar baş verərsə,
                                    borclanmaya və sifarişə görə şəxs özü cavabdehdir.
                                </li>
                                <li>
                                    &mdash; Sifarişdən sonra istehsalçı tələb olunan əşyaya adekvat olaraq sifarişi icra
                                    etmədiyi halda icraçı öhdəlik daşımır.
                                </li>
                                <li>
                                    &mdash; Sifarişlər edilərkən endirim barədə əlavə məlumat verməlidir, əks təqdirdə
                                    endirimlər nəzərə alınmır.
                                </li>
                                <li>
                                    &mdash; Sifarişçi “Təcili sifariş et” xidmətindən istifadə edirsə, buna görə müəyyən
                                    olunmuş taarifə uyğun əlavə ödəniş edilməlidir.
                                </li>
                                <li>
                                    &mdash; Sifarişçi gömrük rüsumu ödəmədən şəxsi istifadəsi üçün ölkəyə mal idxal
                                    etməklə bağlı Nazirlər Kabineti tərəfindən müəyyənləşdirilmiş aylıq həddi
                                    keçdiyi zaman bağlamanın rəsmiləşdirilməsi üçün ondan əlavə gömrük
                                    rüsumu tələb olunur.
                                </li>
                                <li>
                                    &mdash; Sifarişçi Nazirlər Kabineti tərəfindən müəyyənləşdirilmiş aylıq həddən artıq
                                    məbləğdə gömrük bəyannaməsi olan məhsul idxal etdikdə məhsulun gömrük
                                    rəsmiləşdirməsini özü və ya vəkalət verdiyi şəxs həyata keçirir.
                                </li>
                                <li>
                                    &mdash; Sifarişçi sistemin xəbərdarlığına baxmayaraq, riskli hesab olunan online
                                    mağazalardan alış-veriş edilməsində israr edirsə, bu zaman baş verəcək
                                    risklərə görə icraçı məsuliyyət daşımır.
                                </li>
                                <li>
                                    &mdash; İstehsalçının və sifariş olunmuş əşyanın keyfiyyət, ölçü, marka qüsurları və
                                    s. çatışmazlıqlara görə icraçı məsuliyyət daşımır.
                                </li>
                            </ul>
                            <h4>II. İcraçının öhdəlikləri:</h4>
                            <ul>
                                <li>&mdash; İcraçı yalnız sifarişçi tərəfindən seçilən məhsulun həyata keçirir.</li>
                                <li>&mdash; İcraçı sifarişçinin bütün məlumatlarının gizliliyinə və təhlükəsizliyinə təminat
                                    verir.</li>
                                <li>&mdash; İcraçıya sifarişin alınması və bu xidmətin həyata keçirilməsi üçün müəyyən
                                    olunmuş vəsait tam ödədikdən sonra sifariş icra olunur.</li>
                                <li>&mdash; İcraçının məhsulun tam və vaxtında, sifariş şərtlərinə görə sifariş edilməsi
                                    öhdəliyi var</li>
                                <li>&mdash; İcraçı hər hansı səbəbdən sifarişi yerinə yetirə bilmədikdə sifarişçinin ödədiyi
                                    məbləği və xidmət haqqını ona geri qaytarır.</li>
                                <li>&mdash; İcraçı sifarişin izlənməsini təmin edir, sifarişin verilməsi ilə bağlı sifarişçini
                                    mütəmadi məlumatlandırır.</li>
                            </ul>
                        </div>
                        <div v-if="lang && lang('link-order.distantDeliveryBody') === 'ru'" >
                            <p>
                                Заказчик, подтверждая данный договор, соглашается с правилами дистанционной продажи компании.

                            </p>
                            <h4>I. Обязательства заказчика
                            </h4>
                            <ul>
                                <li>
                                    &mdash; Заказчик, пройдя регистрацию в системе, должен указать в требуемом порядке свои личные данные.
                                </li>
                                <li>
                                    &mdash; Заказчик носит ответственность за достоверность вводимых для регистрации данных
                                </li>
                                <li>
                                    &mdash; В случае внесения заказчиком каких-либо изменений в данные, он обязан направить оповещение об обновлении личной информации.
                                </li>
                                <li>
                                    &mdash; Если заказчик после предупреждения продолжит заказывать запрещенные товары, услуга будет заблокирована для этого заказчика.
                                </li>
                                <li>
                                    &mdash; Осуществление платежа в надлежащем порядке входит в обязательство заказчика.

                                </li>
                                <li>
                                    &mdash; Не следует делиться с другими лицами шифром и данными. В таких случаях пользователь несет персональную ответственность за задолженность и заказ.
                                </li>
                                <li>
                                    &mdash;  Если после оформления заказа производитель не выполняет его адекватно, то исполнитель не несет ответственность за это.

                                </li>
                                <li>
                                    &mdash;  При оформлении заказа следует указывать дополнительную информацию о скидках, иначе скидки не будут учтены.
                                </li>
                                <li>
                                    &mdash; Если заказчик пользуется услугой "Срочный заказ", за это следует внести дополнительную плату в соответствии с определенным тарифом.
                                </li>
                                <li>
                                    &mdash; Если заказчик превысит установленный Кабинетом Министров месячный лимит для беспошлинного ввоза товаров личного пользования, ему потребуется заплатить дополнительную таможенную пошлину для оформления посылки.
                                </li>
                                <li>
                                    &mdash; Если заказчик ввозит в страну продукцию на сумму, значительно превышающую установленный Кабинетом Министров месячный лимит, то таможенное оформление груза проводит он сам, либо его полномочный представитель.
                                </li>
                                <li>
                                    &mdash; Если заказчик после предупреждения системы продолжит заказывать товары с подозрительных интернет-магазинов, то исполнитель не будет нести ответственность за возможные риски.

                                </li>
                                <li>
                                    &mdash; Исполнитель не несет ответственность за изъяны в качестве, размере, марке заказанного товара и др. недостатки производителя.
                                </li>
                            </ul>
                            <h4>II. Обязательства исполнителя:</h4>
                            <ul>
                                <li>&mdash; Исполнитель осуществляет доставку лишь выбранного заказчиком  товара.</li>
                                <li>&mdash; Исполнитель гарантирует анонимность и безопасность всех данных заказчика.
                                </li>
                                <li>&mdash; Заказ выполняется после полной оплаты товара и услуг исполнителя.
                                </li>
                                <li>&mdash; В случае невыполнения исполнителем заказа по какой-либо причине, ему следует возвратить заказчику уплаченную сумму и плату за услуги.
                                </li>
                                <li>&mdash; Исполнитель обязан своевременно и в полной мере выполнить заказ в соответствии с условиями заказа.
                                </li>
                                <li>&mdash; Исполнитель обеспечивает отслеживание заказа, регулярно информирует заказчика об этапах выполнения заказа.  Отказ    Подтверждение </li>
                            </ul>
                        </div>
                    </main>
                    <footer class="modal-footer">
                        <button class="btn btn-effect" @click="deni()"  data-dismiss="modal">İmtina</button>
                        <button class="btn btn-effect" @click="accept()"  data-dismiss="modal">Təsdiq</button>
                    </footer>
                </div>
            </div>
        </div>
        <modal :size="'modal-lg'" :toggle="modalTogglePayment" :modal="'payment'">
            <template slot="header">
                <h3>{{ExWindow ? ExWindow.translator('panel-errors.payment_panel') : '' }}</h3>
            </template>
            <template slot="body">
                <div id="iframe_payment" ></div>
            </template>
        </modal>
    </section>

</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import {api} from "../../apis";
    import {urlchextractor} from "../../pipes/slug";
    import vSelect from 'vue-select'
    import swal from "sweetalert2";
    import last30days2 from './shared/last30days2'
    import toastr from 'toastr';

    export default {
        name: "Order",
        mounted() {
            this.initialize();
            this.ExWindow = window;
            this.cargo_types = [
                {id:"ode",name: this.ExWindow ? this.ExWindow.translator('panel-errors.yes') : ''},
                {id:"pulsuz",name: this.ExWindow ? this.ExWindow.translator('panel-errors.no') : ''}
            ];
            this.getBalance();
        },
        props: {
        },
        data: function (){
            return {
                timeout: 0,
                ExWindow: null,
                orders: [
                    {
                        link:  null,
                        price: null,
                        cargo: null,
                        hasCargo: null,
                        total: null,
                        shop:  null,
                        brand: null,
                        color: null,
                        type:  null,
                        quantity: 1,
                        size:  null,
                        desc:  null,
                    }
                ],
                promo_code: null,
                promo_result: null,
                promo_accepted: false,
                totalPrice: 0,
                lang: null,
                deliveryType: 0,
                exchange: 0,
                country: 1,
                exchangeValue: 0,
                policy: false,
                modalToggle: false,
                modalTogglePayment: false,
                currency: null,
                attempts: 0,
                cargo_type: '',
                modalShown: false,
                modalPayment: false,
                cargo_types: [],
                payment_method: 'paytr',
                payment_type: 0,
                balance_try: 0,
            }
        },
        computed: {
            promo: function(){
                return this.promo_accepted ? this.promo_code : null;
            },
            // cargo_types: function(){
            //     return  [{id:"ode",name: "Var"},{id:"pulsuz",name: 'Yoxdur'}];
            // },

            getPlaceHolder: function () {
                return 'Türkiyə daxili karqo *';
            }
        },
        methods: {
            hasPrice(){
                this.orders.some((order) => order.price > 0)
            },
            checkPromo(){
                if(this.promo_code){
                    axios.get('/checkPromo?promo_code='+this.promo_code).then(res => {
                        this.promo_result = res.data == true ? 'Qəbul olundu' : 'Promo kod səhvdir';
                        this.promo_accepted = res.data == true ? true : false;
                    })
                }
            },
            getBalance(){
                axios.get('/user-panel/get-try-balance').then(res => {
                    this.balance_try = res.data;
                })
            },
            log() {
                console.log(this.orders)
            },
            initialize() {
                this.lang = window.translator;
                this.currencyData();
            },
            addRow() {
                const row = {
                    link:  null,
                    price: null,
                    cargo: null,
                    hasCargo: null,
                    total: null,
                    shop:  null,
                    brand: null,
                    color: null,
                    type:  null,
                    quantity: 1,
                    size:  null,
                    desc:  null,
                    uri: null
                };
                this.orders.push(row);
            },
            deleteRow(key) {
                this.orders.splice(key, 1);
                this.summarize();
            },
            deliveryTypeStatus(type) {
                let exchange = 0;
                if (type === 'standart') {
                    exchange = 0;
                } else {
                    const p = this.currency ? parseFloat(this.currency.usd) : 1.7;
                    exchange = 2 * parseFloat(p);
                    this.payment_method = 'balance';
                }
                //this.exchangeValue = exchange;
                this.totalAdder();
            },
            paymentTypeStatus(type) {
                if (type === 'balance') {
                    this.payment_method = 'balance';
                } else {
                    this.payment_method = 'paytr';
                }
            },
            totalAdder() {
                const c = this.currency ? this.currency.ytl : 0.4;
                const totalPrice = parseFloat(parseFloat(this.totalPrice) * parseFloat(c));
                setTimeout(() => {
                    this.exchange = parseFloat(totalPrice) + parseFloat(this.exchangeValue);
                    this.exchange = this.exchange.toFixed(2);
                })
                this.$forceUpdate();
            },
            currencyData() {
                axios.get(api.currency).then(res => {
                    const ytl = res.data.tl;
                    const usd = res.data.usd;
                    this.currency = {ytl, usd};
                })
            },
            parse(index) {
                let  link = this.orders[index].link;
                if(link.indexOf("trendyol.com")>0 || link.indexOf("ty.gl")>0){
                    this.orders[index].link = '';
                    swal({
                        title: `Hörmətli müştəri, hazırda Trendyol saytından alış zamanı yaranan problemlərə görə müvəqqəti olaraq bu saytdan sifariş qəbulu dayandırılmışdır.Problem tezliklə aradan qaldırılacaq`,
                        type: "error"
                    });
                }

                this.parseApi(this.orders[index].link, urlchextractor(this.orders[index].link)).then(
                    res => {
                        if (res.data && typeof res.data !== 'string') {
                            const order = Object.assign({}, res.data);
                            this.orders[index] = order;
                            this.$forceUpdate();
                            this.orders[index].hasCargo = null;
                            this.orders[index].cargo = null;
                            this.orders[index].price = null;
                            //auto parse price
                            //this.orders[index].price = this.orders[index].price.replace(",",".");
                            this.singleTotal(index);
                        }
                    });
            },
            checkUris(currentUri) {
                let uris = [];
                this.orders = this.orders.map(order => {
                    if(uris.indexOf(order.uri) === -1) {
                        uris = [...uris, order.uri];
                    } else {
                        order.hideCargo = true;
                        order.hasCargo = 'pulsuz';
                    }
                    return order;
                });
            },
            parseApi(url, domain) {
                return axios({url: api.parseUrl, method: 'POST', data: {url, domain}});
            },
            dec(order,index){
                order.quantity--;
                this.singleTotal(index);
            },
            inc(order,index){
                order.quantity++;
                this.singleTotal(index);
            },
            submit() {
                if(!this.policy){
                    return  swal({
                        title: this.ExWindow ? this.ExWindow.translator('panel-errors.payment_terms') : '',
                        type: "error"
                    });

                }
                let valid = true;
                this.orders.forEach((order, index) => {
                    const count = index + 1;
                    if (!order.hasCargo || !order.link || (!order.price || order.price === '') || (!order.quantity || order.quantity === 0)) {
                        valid = false;
                        swal({
                            title: `${count} ${this.ExWindow ? this.ExWindow.translator('panel-errors.input_is_required') : ''}`,
                            type: "error"
                        });

                    }
                    if (order.hasCargo === 'ode' && (!order.cargo || order.cargo == 0)) {
                        valid = false;
                        swal({
                            title: `${count} ${this.ExWindow ? this.ExWindow.translator('panel-errors.cargo_price_required') : ''}`,
                            type: "error"
                        });

                    }
                });
                if (valid) {
                    var ytl = parseFloat(parseFloat(this.exchange) / parseFloat(this.currency.ytl));
                    const ytlPayment = ytl.toFixed(2);

                    swal({
                        title: this.ExWindow ? this.ExWindow.translator('panel-errors.sending') : '',
                        onOpen: () => {
                            swal.showLoading();
                            if(this.payment_method=='paytr'){
                                axios({url: api.orderLinkInsert, method: 'POST', data: {data: this.orders,promo_code: this.promo, ytl: ytlPayment, total: this.exchange, deliveryType: this.deliveryType, country: this.country}}).then(response => {
                                    swal.close();
                                    $('#iframe_payment').html(response.data);
                                    this.modalTogglePayment = true;
                                    // window.location.href = '/az/user-panel#/orders'
                                });
                            }else if(this.payment_method == 'balance'){
                                swal.fire({
                                    title: 'Ödəniş?',
                                    text: "TL balansından ödəniş etməyə əminsinizmi?",
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Bəli',
                                    cancelButtonText: 'Xeyr'
                                }).then((result) => {
                                    if (result.value) {
                                        axios({url: api.orderBalanceLinkInsert, method: 'POST', data: {data: this.orders,promo_code: this.promo, ytl: ytlPayment, total: this.exchange, deliveryType: this.deliveryType, country: this.country}}).then(response => {

                                            if(response.data.status==200){
                                                swal.fire(
                                                    'Hazirdi!',
                                                    response.data.data,
                                                    'success'
                                                )

                                                location.href = '/payment-success';

                                            }else{
                                                swal.fire(
                                                    'Diqqət!',
                                                    response.data.data,
                                                    'error'
                                                )
                                            }

                                        });
                                    }



                                })
                            }


                        }
                    })
                } else {
                    this.attempts++;
                    if (this.attempts > 3) {
                        swal({
                            title: this.ExWindow ? this.ExWindow.translator('panel-errors.order_pr_3_title') : '',
                            type: "info",
                            text: this.ExWindow ? this.ExWindow.translator('panel-errors.order_pr_3_text') : ''
                        });
                    }
                    if (this.attempts > 5) {
                        swal({
                            title: this.ExWindow ? this.ExWindow.translator('panel-errors.order_pr_5_title') : '',
                            type: "info",
                            text: this.ExWindow ? this.ExWindow.translator('panel-errors.order_pr_5_text') : ''
                        })
                    }
                }
            },
            selectCountry(country) {
                this.country = country;
                const row = {
                    link:  null,
                    price: null,
                    cargo: null,
                    hasCargo: null,
                    total: null,
                    shop:  null,
                    brand: null,
                    color: null,
                    type:  null,
                    quantity: null,
                    size:  null,
                    desc:  null,
                    uri: null
                }
                this.orders = [row];
                this.exchange = 0;
                this.totalPrice = 0;
                this.$forceUpdate();
            },
            change(order,index) {
                console.log(order.cargo_type.id);
                this.orders[index].hasCargo = order.cargo_type.id;
                this.$forceUpdate();
            },
            singleTotal(index) {
                const order = this.orders[index];
                this.orders[index].price = this.orders[index].price.replace(",",".");
                this.orders[index].price = this.orders[index].price.replace(/[^0-9.]/g, "");
                let price = order.price ? order.price : 0;
                if (order.quantity > 0) {
                    price = parseFloat(price) * order.quantity;
                }else{
                    this.orders[index].quantity = 1;
                    price = parseFloat(price);
                }
                if(order.hasCargo && order.hasCargo === 'ode' && order.cargo) {
                    price = price + parseFloat(order.cargo);// * parseFloat(order.quantity)
                }
                price = parseFloat(price) + (parseFloat(price) * 0.05);
                order.total = price.toFixed(2);
                this.summarize();
                this.$forceUpdate();
            },
            summarize() {
                let total = 0;
                this.orders.forEach(order => {
                    if(!isNaN(order.total)){
                        total = parseFloat(total) + parseFloat(order.total);
                    }
                })
                this.totalPrice = parseFloat(total).toFixed(2);
                this.totalAdder();
            },
            privacyPolicy() {
                this.modalToggle = true;
                this.modalShown = !this.modalShown;

            },
            accept() {
                this.modalToggle = false; this.policy = true;
                this.modalShown = false;
            },
            deni() {
                this.modalToggle = false; this.policy = false;
                this.modalShown = false;
            },


        },
        components: {
            last30days2,
            vSelect,
            modal: Modal,
        }
    }

</script>

<style scoped>

</style>