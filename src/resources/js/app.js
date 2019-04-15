import './bootstrap'

import Vue from 'vue';
import API from './classes/Api'
import Auth from './classes/Auth'
import NotificationHelpers from './classes/NotificationHelpers'
import Notifications from 'vue-notification'
import VeeValidate from 'vee-validate';
import VeeValidateLaravel from 'vee-validate-laravel';
import {router} from './router'
import {store} from './store'
import Input from './components/Input.vue';
import TextArea from './components/TextArea.vue';
import Select from './components/Select.vue';

window.Vue = Vue;

Vue.prototype.$api = API;
Vue.prototype.$http = axios;

Vue.use(Auth);
Vue.use(Notifications);
Vue.use(NotificationHelpers);
Vue.use(VeeValidate);
Vue.use(VeeValidateLaravel);

Vue.component('l-input', Input);
Vue.component('l-textarea', TextArea);
Vue.component('l-select', Select);

new Vue({
    el: '#app',

    store,
    router,
});

