import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/views/Home';
import About from '@/js/components/About';
import Statistics from '@/js/components/Statistics';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '',
            component: Home,
            children: [
                { path: '/', component: Statistics, name: 'Statistics' },
                { path: '/about', component: About, name: 'about' },
            ],
        },
    ],
});

export default router;
