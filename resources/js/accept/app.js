/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import OrderLogsComponent from "../components/accept-orders/OrderLogsComponent";


require('../bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import AcceptOrderComponent from './../components/accept-orders/AcceptOrderComponent';
import MyOrderComponent from './../components/accept-orders/MyOrderComponent';
import OrderComponent from './../components/accept-orders/OrderComponent';
import MyOrderComponent2 from './../components/accept-orders/MyOrderComponent2';
import MyOrdersComponent from './../components/accept-orders/MyOrdersComponent';
import OrdersComponent from './../components/accept-orders/OrdersComponent';
import WillUploadInvoicesComponent from './../components/accept-orders/WillUploadInvoicesComponent';
import OrderLogComponent from "../components/accept-orders/OrderLogComponent";

Vue.use( VueRouter );

const routes = [
    { path: '', component: AcceptOrderComponent},
    { path: '/my-order/:id', component: MyOrderComponent},
    { path: '/order/:id', component: OrderComponent},
    { path: '/my-orders', component: MyOrdersComponent},
    { path: '/orders', component: OrdersComponent},
    { path: '/invoice-upload', component: WillUploadInvoicesComponent},
    { path: '/invoice-upload/:id', component: MyOrderComponent2},
    { path: '/order/logs', component: OrderLogsComponent},
    { path: '/order/log/:id', component: OrderLogComponent},
];

const router = new VueRouter({
    routes // short for `routes: routes`
});

const app = new Vue({
    router
}).$mount('#accept-container');