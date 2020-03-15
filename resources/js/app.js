require('./bootstrap');
import Vue from 'vue';

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

import VueAxios from 'vue-axios';
import VueAuth from '@websanova/vue-auth';


import Routes from '@/js/routes.js'
import authParams from './includes/auth'
import App from '@/js/views/App'

Vue.router = Routes;

Vue.use(ElementUI);
Vue.use(VueAxios, window.axios);
Vue.use(VueAuth, authParams);

Vue.axios.defaults.baseURL = window.Laravel.appUrl;
Vue.axios.defaults.withCredentials = true;

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
});

export default app;
