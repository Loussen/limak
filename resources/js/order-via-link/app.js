
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import Order from  './components/Order.vue'
import Order2 from  './components/Order2.vue'
import Payment from  './components/Payment.vue'

const default_locale = window.default_locale;
const fallback_locale = window.fallback_locale;
const messages = window.messages;


// window.Lang = new Lang( { messages, locale: default_locale, fallback: fallback_locale } );

window.translator = (args) => {
    const arry = args.split('.');

    if(default_locale) {
        var tempObj = Object.assign({}, window.messages[default_locale]);
    }else {
        var tempObj = Object.assign({}, window.messages[fallback_locale]);
    }
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
    { path: '/', component: Order },
    { path: '/2', component: Order2 },
    { path: '/payment/:id', component: Payment },
]
const router = new VueRouter({
    routes
})


const app = new Vue({
    router
}).$mount('#app-order-via-link');
