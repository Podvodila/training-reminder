require('./bootstrap');
import Vue from 'vue';

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import { default as elementLocale } from 'element-ui/lib/locale/lang/en';

import VueAxios from 'vue-axios';
import VueAuth from '@websanova/vue-auth';

import moment from 'moment'

import Routes from '@/js/routes.js'
import authParams from './includes/auth'
import App from '@/js/views/App'

import {isSmallScreen} from "@/js/mixins/isSmallScreen"

Vue.router = Routes;

Vue.use(ElementUI, { locale: elementLocale });
Vue.use(VueAxios, window.axios);
Vue.use(VueAuth, authParams);

Vue.axios.defaults.baseURL = window.Laravel.appUrl;
Vue.axios.defaults.withCredentials = true;

window.moment = moment;

window.Vue = new Vue({
    mixins: [isSmallScreen],
    el: '#app',
    router: Routes,
    render: h => h(App),
});
