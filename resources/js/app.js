import 'es6-promise/auto'
import axios from 'axios'
import './bootstrap'
import Vue from 'vue'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import Index from '@/Index';
import auth from 'configs/auth'
import router from 'configs/router'
import store from 'configs/store';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import Loading from 'vue-loading-overlay';
import Toast from "vue-toastification";
import VModal from 'vue-js-modal';
import VueEvents from 'vue-events';
import Fragment from 'vue-fragment';
import linkify from 'vue-linkify';

import 'vue-loading-overlay/dist/vue-loading.css';

// Set Vue globally
window.Vue = Vue
// Set Vue router
Vue.router = router
Vue.use(VueRouter)
// Set Vue authentication
Vue.use(VueAxios, axios)
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`
Vue.use(VueAuth, auth)

// Load Index
Vue.component('index', Index)
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.use(Loading, {
	color: '#FCE2E3',
	width: 40,
	height: 40,
});

Vue.use(Toast, {
    // You can set your default options here
});

//Modal
Vue.use(VModal, { dynamic: true, injectModalsContainer: true, adaptive: true, height: "auto" })

//Events
Vue.use(VueEvents);

//Fragment
Vue.use(Fragment.Plugin)

//linkify
Vue.directive('linkified', linkify)

const app = new Vue({
    el: '#app',
    router,
    store
});