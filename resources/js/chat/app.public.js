
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');
import Chat from  './public/Chat.vue'
const default_locale = window.default_locale;
const fallback_locale = window.fallback_locale;
const messages = window.messages;
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

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('chat-component', Chat);
const app = new Vue({
    el: '#app-chat'
});
