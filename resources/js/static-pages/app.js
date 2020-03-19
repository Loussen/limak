
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import StaticPages from  './components/StaticPages.vue'
import StaticPagesShow from  './components/StaticPagesShow.vue'
import StaticPagesGenerator from  './components/StaticPagesGenerator.vue'
Vue.use( VueRouter );
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [
    { path: '/', component: StaticPages },
    { path: '/:id', component: StaticPagesShow },
    { path: '/:id/generator', component: StaticPagesGenerator },
]
const router = new VueRouter({
    routes // short for `routes: routes`
})


const app = new Vue({
    router
}).$mount('#app-static-pages');

