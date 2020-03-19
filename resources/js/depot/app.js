
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'; 
import Cashier from  './components/Cashier.vue'
import Main from  './components/Main.vue'
import PayedProducts from  './components/PayedProducts.vue'
import Depot from  './components/Depot.vue'
import DepotEditor from  './components/DepotEditor.vue'
Vue.use( VueRouter );
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [
    { path: '/', component: Main },
    { path: '/cashier', component: Cashier },
    { path: '/payed-products', component: PayedProducts },
    { path: '/depot', component: Depot },
    { path: '/depot/editor', component: DepotEditor },
];
const router = new VueRouter({
    routes
});

Vue.mixin({
    created: function () {

    }
})


const app = new Vue({
    router,
}).$mount('#app-depot');

