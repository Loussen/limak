/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('../bootstrap');

window.Vue = require('vue');
window.select2 = require('select2');
import VueRouter from 'vue-router';
import ForeignAddress from  './components/ForeignAddress.vue'
import Orders from  './components/Orders.vue'
import Orders2 from  './components/Orders2.vue'
import Basket from  './components/Basket.vue'
import Basket2 from  './components/Basket2.vue'
import Track from  './components/Track.vue'
import Invoices from  './components/Invoices.vue'
import Settings from  './components/Settings.vue'
import Balance from  './components/Balance.vue'
import Messenger from  './components/Messenger.vue'
import Courier from  './components/Courier.vue'


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

Vue.use( VueRouter );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [
    {
        path: '/',
        name: 'layout',
        component: Vue.component( 'Layout', require( './Layout.vue' ) ),
        children: [
            {
                path: '',
                name: 'address',
                component: Vue.component( 'ForeignAddress', ForeignAddress)
            },
            {
                path: 'foreign-address',
                name: 'foreign-address',
                component: Vue.component( 'ForeignAddress', ForeignAddress)
            },
            {
                path: 'orders',
                name: 'orders',
                component: Vue.component( 'Orders', Orders)
            },
            {
                path: 'orders2',
                name: 'orders2',
                component: Vue.component( 'Orders2', Orders2)
            },
            {
                path: 'basket',
                name: 'basket',
                component: Vue.component( 'Basket', Basket)
            },
            {
                path: 'basket2',
                name: 'basket2',
                component: Vue.component( 'Basket2', Basket2)
            },
            {
                path: 'messenger',
                name: 'messenger',
                component: Vue.component( 'Messenger', Messenger)
            },
            {
                path: 'track',
                name: 'track',
                component: Vue.component( 'Track', Track)
            },
            {
                path: 'balance',
                name: 'Balance',
                component: Vue.component( 'Balance', Balance)
            },
            {
                path: 'invoices',
                name: 'invoices',
                component: Vue.component( 'Invoices', Invoices)
            },
            {
                path: 'settings',
                name: 'settings',
                component: Vue.component( 'Settings', Settings)
            },
            {
                path: 'courier',
                name: 'courier',
                component: Vue.component( 'Courier', Courier)
            }
        ]
    }
];
const router = new VueRouter({
    routes
});


const app = new Vue({
    router
}).$mount('#user-panel');

