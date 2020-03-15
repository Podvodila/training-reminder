import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/views/Home';
import About from '@/js/components/About';
import Statistics from '@/js/components/Statistics';
import Register from '@/js/components/auth/Register';
import Login from "@/js/components/auth/Login";

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
        { path: '/register', component: Register, name: 'Register' },
        { path: '/login', component: Login, name: 'Login' },
    ],
});

export default router;
