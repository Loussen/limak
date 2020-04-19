/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('../bootstrap');

window.Vue = require('vue');
require('@voerro/vue-tagsinput');
window.select2 = require('select2');
import VueRouter from 'vue-router';
import Panel from './components/Panel'
import PremiumPanel from './components/PremiumPanel'
import Panel2 from './components/Panel2'
import Premium from './components/Premium'
import ForeignAddress from  './components/ForeignAddress.vue'
import Basket from  './components/Basket.vue'
import Basket2 from  './components/Basket2.vue'
import Invoices from  './components/Invoices.vue'
import InvoicesNew from  './components/InvoicesNew.vue'
import InvoicesNergiz from  './components/InvoicesNergiz.vue'
import Invoices2 from  './components/Invoices2.vue'
import Track from  './components/Track.vue'
import Balance from  './components/Balance.vue'
import Balance_new from  './components/Balance_new.vue'
import BalanceTl from  './components/BalanceTl.vue'
import BalanceTl_new from  './components/BalanceTl_new.vue'
import Courier from  './components/Courier.vue'
import CourierNew from  './components/CourierNew.vue'
import CourierPause from  './components/CourierPause.vue'
import Courier1 from  './components/Courier1.vue'
import Kuryer from  './components/Kuryer.vue'
import Questions from  './components/Questions.vue'
import Settings from  './components/Settings.vue'

import Invoice from  './components/Invoice.vue'
import InvoiceNew from  './components/InvoiceNew.vue'
import Invoice2 from  './components/Invoice2.vue'
import Tags from  './components/Tags.vue'
import Order from  './components/Order.vue'
import OrderNew from  './components/OrderNew.vue'
import OrderY from  './components/OrderY.vue'
import OrderTl from  './components/OrderTl.vue'
import OrderPause from  './components/OrderPause.vue'
import OrderOld from  './components/OrderOld.vue'
import Order2 from  './components/Order2.vue'
import Order5 from  './components/Order5.vue'
import Payment from  './components/Payment.vue'
import Close from  './components/Close.vue'
import testsorgu from  './components/testsorgu.vue'
import testsorgu2 from  './components/testsorgu2.vue'
import testsorgu3 from  './components/testsorgu3.vue'
import testsorgu4 from  './components/testsorgu4.vue'
import testsorgu5 from  './components/testsorgu5.vue'
import Clients from './components/Clients.vue'
import Chat from './components/Chat.vue'
import Sifarisler from  './components/Sifarisler.vue'

// import couriertest from  './components/courier_test.vue'


// import Orders from  './components/Orders.vue'
// import Orders2 from  './components/Orders2.vue'
// import Basket from  './components/Basket.vue'
// import Basket2 from  './components/Basket2.vue'
// import Settings from  './components/Settings.vue'
// import Messenger from  './components/Messenger.vue'


// var Lang = require('lang.js');

const default_locale = window.default_locale;
const fallback_locale = window.fallback_locale;
const messages = window.messages;


// window.Lang = new Lang( { messages, locale: default_locale, fallback: fallback_locale } );

window.translator = (args) => {
    const arry = args.split('.');
    // console.log(arry, window.default_locale, window.fallback_locale, window.messages);
    if(default_locale) {
        var tempObj = Object.assign({}, window.messages[default_locale]);
    }else {
        var tempObj = Object.assign({}, window.messages[fallback_locale]);
    }
    // console.log(tempObj);
    arry.forEach((dat) => {
        tempObj = tempObj[dat];
    });
    return tempObj;
};

Vue.component('tags-input', require('@voerro/vue-tagsinput').default);


Vue.use( VueRouter );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [
    {
        path: '/',
        component: Vue.component( 'Layout', require( './Layout.vue' ) ),
        children: [
            { path: '/', component: Panel },
            { path: '/premium-panel', component: PremiumPanel },
            { path: '/panel', component: Panel2 },
            { path: '/foreign-address', component: ForeignAddress },
            { path: '/basket', component: Basket },
            { path: '/basket2', component: Basket2 },
            { path: '/invoices', component: Invoices },
            { path: '/invoicesNew', component: InvoicesNew },
            { path: '/invoicesNergiz', component: InvoicesNergiz },
            { path: '/invoices2', component: Invoices2 },
            { path: '/track', component: Track },
            { path: '/balance', component: Balance },
            { path: '/balanceNew', component: Balance_new},
            { path: '/balanceTl', component: BalanceTl },
            { path: '/balanceTl_new', component: BalanceTl_new },
            { path: '/courier', component: Courier },
            { path: '/courier_new', component: CourierNew },
            { path: '/courier1', component: Courier1 },
            { path: '/kuryer', component: Kuryer },
            { path: '/clients', component: Clients },
            //{ path: '/questions', component: Questions }, Sorgu dayandirilmisdir
            { path: '/settings', component: Settings },
            { path: '/questionsNew', component: Questions },
            { path: '/chat', component: Chat },
            // { path: '/couriertest', component: couriertest },
            { path: '/testsorgu', component: testsorgu },
            { path: '/testsorgu2', component: testsorgu2 },
            { path: '/testsorgu3', component: testsorgu3 },
            { path: '/testsorgu4', component: testsorgu4 },
            { path: '/testsorgu5', component: testsorgu5 },
            { path: '/orders', component: Sifarisler },

        ],

    },
    {
        path: '/invoice',
        component: Invoice
    },

    {
        path: '/invoiceNew',
        component: InvoiceNew
    },
    {
        path: '/orderY',
        component: Order
    },
    /*{
        path: '/courier',
        component: CourierPause
    },*/

    {
        path: '/tags',
        component: Tags
    },
    {
        path: '/order',
        component: Order
    },
    {
        path: '/orderPause',
        component: OrderPause
    },
    /*{
        path: '/orderNew',
        component: OrderNew
    },
    {
        path: '/orderTl',
        component: OrderTl
    },
    {
        path: '/orderpause',
        component: OrderPause
    },
    {
        path: '/orderold', // lap kohne
        component: OrderOld
    },*/
    {
        path: '/close',
        component: Close
    },
    {
        path: '/order2',
        component: Order2
    },
    {
        path: '/order5',
        component: Order5
    },{
        path: '/premium',
        component: Premium
    },
    {
        path: '/payment/:id/',
        component: Payment
    },

];
const router = new VueRouter({
    routes
});


const app = new Vue({
    router
}).$mount('#user-panel');

