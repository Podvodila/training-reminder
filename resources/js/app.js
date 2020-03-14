require('./bootstrap');
import Vue from 'vue';

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

// Route information for Vue Router
import Routes from '@/js/routes.js'

import App from '@/js/views/App';

Vue.use(ElementUI);

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
});

export default app;
