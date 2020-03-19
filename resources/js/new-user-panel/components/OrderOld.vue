<template>
    <section class="page-block sifarish-et sifarisle-gorey">
        <div class="container">
            <div class="page-head">
                <img src="/front/img/sifaris-et-page.png" alt="sifaris-et-page">
                <h1>{{lang ? lang('link-order.order') : ''}}</h1>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" :class="country === 1 ? 'active' : ''">
                        <a class="btn-effect"  aria-controls="turkey" role="tab" data-toggle="tab" @click="selectCountry(1)">Türkiyə</a>
                    </li>
                    <!--<li role="presentation" :class="country === 2 ? 'active' : ''">-->
                    <!--<a class="btn-effect" aria-controls="amerika" role="tab" data-toggle="tab" @click="selectCountry(2)">Amerika</a>-->
                    <!--</li>-->
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="turkey">
                    <template v-for="(order, index) in orders">
                        <div class="block turkey-tab col-md-12 col-sm-12 col-xs-12 ">
                            <span class="number">{{index < 9 ? '0' + (index + 1) : ++index}}</span>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label>{{!order.link ? '*' : ''}}{{lang ? lang('link-order.link') : ''}}</label>
                                                <input type="text" name="link" class="form-control" v-model="order.link" @input="parse(index)"
                                                       :placeholder="lang ? lang('link-order.url') : ''">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label>{{!order.price ? '*' : ''}}{{lang ? lang('link-order.price') : ''}} (TL)</label>
                                                <input type="number" name="count" class="form-control" v-model="order.price" @input="singleTotal(index)" @change="singleTotal(index)"
                                                       placeholder="0.00 TL">
                                            </div>
                                        </div>
                                        <div class="mobile-span col-md-2 col-sm-12 col-xs-12" v-bind:class="order.hasCargo === 'ode' ? 'odenisli' : ''">
                                            <input v-if="order.hasCargo === 'ode'" v-model="order.cargo" type="number" class="form-control coolio" @input="singleTotal(index)" placeholder="0.00 TL">
                                            <span class="btn-effect">+5%</span>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group" v-if="!order.hideCargo">
                                                <label v-if="country === 1" >{{!order.hasCargo || order.hasCargo === '-1' ? '*' : ''}}{{lang ? lang('link-order.cargoTR') : ''}}</label>
                                                <label v-if="country === 2" >{{!order.hasCargo || order.hasCargo === '-1' ? '*' : ''}}{{lang ? lang('link-order.cargoUS') : ''}}</label>
                                                <select name="kargo" class="selectpicker-data form-control"
                                                        title="Əlavə et" @change="change($event, index)">
                                                    <option selected value="-1">{{lang ? lang('link-order.deliveryCargo') : ''}}</option>
                                                    <option value="ode">{{lang ? lang('link-order.deliveryCargoHas') : ''}}</option>
                                                    <option value="pulsuz">{{lang ? lang('link-order.deliveryCargoHasnt') : ''}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="web-span col-md-2 col-sm-6 col-xs-6">
                                            <input v-if="order.hasCargo === 'ode'" v-model="order.cargo" type="number" class="form-control" name="input" @input="singleTotal(index)">
                                            <span class="btn-effect" :class="order.hasCargo === 'ode' ? 'w-36-p' : ''">+5%</span>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group">
                                                <label >{{lang ? lang('link-order.total') : ''}} (TL)</label>
                                                <input type="number" v-model="order.total" class="form-control custom-disabled-total"
                                                       id="all-count" disabled
                                                       placeholder="0.00 TL">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group">
                                                <input v-model="order.shop" type="text" name="shop" class="form-control"
                                                       :placeholder="lang ? lang('link-order.shop') : ''">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group">
                                                <input  v-model="order.brand" type="text" name="brend" class="form-control"
                                                        :placeholder="lang ? lang('link-order.brand') : ''">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group">
                                                <input v-model="order.color" type="text" name="rengi" class="color form-control"
                                                       :placeholder="lang ? lang('link-order.color') : ''">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group">
                                                <!--<select v-model="order.type" class="selectpicker form-control" name="tip" title="Tipi">-->
                                                <!--<option value="kazak">Kazak</option>-->
                                                <!--<option value="t-shirt">T-shirt</option>-->
                                                <!--</select>-->
                                                <input v-model="order.type" type="text" class="form-control"
                                                       :placeholder="lang ? lang('link-order.type') : ''">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group" style="display: flex">
                                                <!--<select v-model="order.color" class="selectpicker form-control" name="amount"-->
                                                <!--title="Miqdar">-->
                                                <!--<option value="1">01</option>-->
                                                <!--<option value="2">02</option>-->
                                                <!--<option value="3">03</option>-->
                                                <!--<option value="4">04</option>-->
                                                <!--</select>-->
                                                {{!order.quantity || order.quantity === 0 ? '*' : ''}}<input v-model="order.quantity" type="number" class="form-control"
                                                                                                             :placeholder="lang ? lang('link-order.quantity') : ''" @input="singleTotal(index)">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="input-group">
                                                <!--<select v-model="order.color" class="selectpicker form-control" name="size"-->
                                                <!--title="Ölçüsü">-->
                                                <!--<option value="01">01</option>-->
                                                <!--<option value="02">02</option>-->
                                                <!--<option value="02">03</option>-->
                                                <!--<option value="02">04</option>-->
                                                <!--</select>-->
                                                <input v-model="order.size" type="text" class="form-control"
                                                       :placeholder="lang ? lang('link-order.size') : ''">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <label for="comment"></label>
                                                <textarea v-model="order.desc" class="form-control" rows="5" id="comment"
                                                          placeholder="..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                    <button v-if="index > 0" type="button" class="btn-effect remove" @click="deleteRow(index)">Linki sil <img src="/front/img/minus.png" alt="minus"></button>
                                    <button v-if="orders.length - 1 === index" type="button" class="btn-effect plus" @click="addRow()">{{lang ? lang('link-order.newRow') : ''}}<img
                                            src="/front/img/plus-sifarish.png"
                                            alt="plus"></button>
                                </div>
                            </div>

                        </div>
                    </template>
                    <div class="block turkey-tab count-end col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 5px !important">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="input-group">
                                            <select class=" form-control" name="amount" v-model="deliveryType" @change="deliveryTypeStatus($event)">
                                                <option value="standart">{{lang ? lang('link-order.normalDelivery') : ''}}</option>
                                                <option value="express">{{lang ? lang('link-order.expressDelivery') : ''}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <p>{{lang ? lang('link-order.total') : ''}}: <strong style="color: #000;"> {{exchange ? exchange : totalPrice}} AZN</strong></p>
                                    </div>
                                    <!--<div class="col-md-4 col-sm-12 col-xs-12">-->
                                    <!--<div class="input-group">-->
                                    <!--<label></label>-->
                                    <!--<input type="text" name="promo_code" class="form-control" v-model="promo_code" @blur="checkPromo()"-->
                                    <!--placeholder="Promo kod">-->
                                    <!--<span style="text-align: left;display: block;" class="text-danger">{{ promo_result }}</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="checkbox">
                                    <label>
                                        <input id="checkbox1" type="checkbox" name="checkbox-reg" v-model="policy"><span></span>
                                        <a @click="privacyPolicy">{{lang ? lang('link-order.policy') : ''}}</a>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-effect" data-toggle="modal" :disabled="!policy"
                                        data-target=".tesdiq" @click="submit()">{{lang ? lang('link-order.send') : ''}}
                                </button>

                            </div>
                            <span class="pull-right col-5" style="margin: 15px 50px 0px 0px; ">Bu bir Almark xidmətidir.</span>
                        </div>
                    </div>
                    <modal :size="'modal-lg'" :toggle="modalToggle" :modal="'Hello'" @modal="modalToggle = $event">
                        <template slot="header">
                            <h3>{{lang ? lang('link-order.distantDeliverytitle') : ''}}</h3>
                        </template>
                        <template slot="body" v-if="lang && lang('link-order.distantDeliveryBody') === 'az'" >
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
                        </template>
                        <template slot="body" v-if="lang && lang('link-order.distantDeliveryBody') === 'ru'" >
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
                        </template>
                        <template slot="footer">
                            <button class="btn btn-effect" @click="deni()">İmtina</button>
                            <button class="btn btn-effect" @click="accept()">Təsdiq</button>
                        </template>
                    </modal>
                    <modal :size="'modal-lg'" :toggle="modalTogglePayment" :modal="'payment'">
                        <template slot="header">
                            <h3>Ödəniş paneli</h3>
                        </template>
                        <template slot="body">
                            <div id="iframe_payment" ></div>
                        </template>
                    </modal>
                </div>
            </div>
        </div>
    </section>

    <!--<section class="page-block sifarish-et sifarisle-gorey">
        <div class="container">
            <div class="page-head">
                <h3>
                    "Sifariş et" xidmətimiz bayram günlərində deaktiv olacaq. Xidmətimizdan 22 mart saat 10:00 -dan etibarən istifadə edə bilərsiniz. Bizi seçdiyiniz üçün təşəkkür edirik.
                </h3>
            </div>
        </div>
    </section>-->
</template>

<script>
    import Modal from "../../shared/Modal.vue";
    import {api} from "../../apis";
    import {urlchextractor} from "../../pipes/slug";
    import swal from "sweetalert2";
    import toastr from 'toastr';
    export default {
        mounted() {
            this.initialize();
        },
        props: {
        },
        data: function (){
            return {
                timeout: 0,
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
                deliveryType: 'standart',
                exchange: 0,
                country: 1,
                exchangeValue: 0,
                policy: false,
                modalToggle: false,
                modalTogglePayment: false,
                currency: null,
                attempts: 0
            }
        },
        computed: {
            promo: function(){
                return this.promo_accepted ? this.promo_code : null;
            }
        },
        methods: {
            checkPromo(){
                if(this.promo_code){
                    axios.get('/checkPromo?promo_code='+this.promo_code).then(res => {
                        this.promo_result = res.data == true ? 'Qəbul olundu' : 'Promo kod səhvdir';
                        this.promo_accepted = res.data == true ? true : false;
                    })
                }
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
            },
            deliveryTypeStatus(event) {
                let exchange = 0;
                if (event.target.value === 'standart') {
                    exchange = 0;
                } else {
                    const p = this.currency ? parseFloat(this.currency.usd) : 1.7;
                    exchange = 2 * parseFloat(p);
                }
                this.exchangeValue = exchange;
                this.totalAdder();
            },
            totalAdder() {
                const c = this.currency ? this.currency.ytl : 0.4;
                const totalPrice = parseFloat(this.totalPrice) * parseFloat(c);
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
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    const url = this.orders[index].link;
                    this.orders[index] = {
                        link:  url,
                        price: null,
                        hasCargo: this.orders[index].hasCargo,
                        cargo: null,
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
                    this.$forceUpdate();
                    const chextractor = urlchextractor(url);
                    if (chextractor) {
                        this.parseApi(url, chextractor).then(
                            res => {
                                console.log(res);
                                if (res.data && typeof res.data !== 'string') {
                                    const order = Object.assign({}, res.data);
                                    this.orders[index] = order;
                                    this.$forceUpdate();
                                    this.orders[index].hasCargo = null;
                                    this.orders[index].cargo = null;
                                    this.orders[index].uri = chextractor;
                                    this.checkUris(this.orders[index].uri);
                                    this.singleTotal(index);
                                } else {
                                    this.orders[index] = {
                                        link:  url,
                                        price: null,
                                        hasCargo: this.orders[index].hasCargo,
                                        cargo: null,
                                        total: null,
                                        shop:  null,
                                        brand: null,
                                        color: null,
                                        type:  null,
                                        quantity: 1,
                                        size:  null,
                                        desc:  null,
                                        uri: chextractor
                                    };
                                }
                            }).catch(e => {
                            this.orders[index] = {
                                link:  url,
                                price: null,
                                hasCargo: this.orders[index].hasCargo,
                                cargo: null,
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
                        });
                    } else {
                        console.log('web page is not right')
                    }
                }, 200);
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
            submit() {
                let valid = true;
                this.orders.forEach((order, index) => {
                    const count = index + 1;
                    if (!order.hasCargo || !order.link || (!order.price || order.price === '') || (!order.quantity || order.quantity === 0)) {
                        valid = false;
                        toastr.error(`${count} nömrəli sifarişdə "*" (ulduz)- ilə işarələnmiş sahələrin doldurulması zəruridir`);
                    }
                    if (order.hasCargo === 'ode' && (!order.cargo || order.cargo == 0)) {
                        valid = false;
                        toastr.error(`${count} nömrəli sifarişdə Ölkədaxili cargo ödənişli seçilməyinə baxmayaraq cargo qiyməti qeyd edilməmişdir!`);
                    }
                    // if (!order.size) {
                    //     valid = false;
                    //     toastr.error(`${count} nömrəli sifarişdə ölçü qeyd edilməmişdir`);
                    // }
                });
                if (valid) {
                    this.modalTogglePayment = false;
                    var ytl = parseFloat(this.exchange) / parseFloat(this.currency.ytl);
                    const ytlPayment = ytl.toFixed(2);

                    swal({
                        title: 'Göndərilir....',
                        onOpen: () => {
                            swal.showLoading();
                            axios({url: api.orderLinkInsert, method: 'POST', data: {data: this.orders,promo_code: this.promo, ytl: ytlPayment, total: this.exchange, deliveryType: this.deliveryType, country: this.country}}).then(response => {
                                swal.close();
                                $('#iframe_payment').html(response.data);
                                this.modalTogglePayment = true;
                                // window.location.href = '/az/user-panel#/orders'
                            });
                        }
                    })
                } else {
                    this.attempts++;
                    if (this.attempts > 3) {
                        toastr.info('Əgər sifariş edərkən çətinlik çəkirsinizsə Online chat vasitəsi ilə kömək ala bilərsiniz!');
                        toastr.info('Online chat-a başlamaq üçün sag aşağı küncdəki "Dialoqa başla" düyməsini sıxın');
                    }
                    if (this.attempts > 5) {
                        swal({
                            title: 'Online chat xidmətimizdən istifadə edərək kömək alın',
                            type: "info",
                            text: "Online chat-a başlamaq üçün sag aşağı küncdəki \"Dialoqa başla\" düyməsini sıxın"
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
            change(e, index) {
                this.orders[index].hasCargo = e.target.value;
                this.$forceUpdate();
            },
            singleTotal(index) {
                const order = this.orders[index];
                let price = order.price;
                if (order.quantity > 0) {
                    price = parseFloat(price) * order.quantity;
                }
                price = parseFloat(price) + (parseFloat(price) * 0.05);
                if(order.hasCargo && order.hasCargo === 'ode' && order.cargo) {
                    price = price + parseFloat(order.cargo) * parseFloat(order.quantity);
                }
                order.total = price.toFixed(2);
                this.summarize();
                this.$forceUpdate();
            },
            summarize() {
                let total = 0;
                this.orders.forEach(order => {
                    total = parseFloat(total) + parseFloat(order.total);
                })
                this.totalPrice = total.toFixed(2);
                this.totalAdder();
            },
            privacyPolicy() {
                this.modalToggle = true;
            },
            accept() {
                this.modalToggle = false; this.policy = true
            },
            deni() {
                this.modalToggle = false; this.policy = false
            }
        },
        components: {
            modal: Modal,
        }
    }

</script>
<style scoped>
    .custom-disabled-total {
        background: white;
    }
    .w-36-p {
        width: 36%!important;
    }
    .coolio {
        border-radius: 17px;
        border-color: #717c88;
    }
</style>
