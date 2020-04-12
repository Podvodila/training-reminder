import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/views/Home';
import Statistics from '@/js/components/Statistics';
import Exercises from "@/js/components/Exercises";
import Activities from "@/js/components/Activities";
import Register from '@/js/components/auth/Register';
import Login from "@/js/components/auth/Login";

import methods from "@/js/includes/utils";
import NotFound from "@/js/views/NotFound";

Vue.use(VueRouter);

const redirectIfNotAuth = (to, from, next) => {
    methods.waitWhile('!window.Vue', () => {
        if (to.matched.some(record => record.meta.authRequired)) {
            if (!window.Vue.$auth.check()) {
                next({
                    path: '/login',
                    // query: { redirect: to.fullPath } //TODO: handle redirect after login
                })
            } else {
                next()
            }
        } else {
            next()
        }
    });
};

const redirectIfAuth = (to, from, next) => {
    methods.waitWhile('!window.Vue', () => {
        if (window.Vue.$auth.check()) {
            next({
                name: 'Statistics'
            })
        } else {
            next()
        }
    });
};

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '',
            component: Home,
            menu: true,
            children: [
                { path: '/',            component: Statistics,  name: 'Statistics', meta: { authRequired: true }, beforeEnter: redirectIfNotAuth },
                { path: '/activities',  component: Activities,  name: 'Activities', meta: { authRequired: true }, beforeEnter: redirectIfNotAuth },
                { path: '/exercises',   component: Exercises,   name: 'Exercises',  meta: { authRequired: true }, beforeEnter: redirectIfNotAuth },
                { path: '/register',    component: Register,    name: 'Register',                                 beforeEnter: redirectIfAuth },
                { path: '/login',       component: Login,       name: 'Login',                                    beforeEnter: redirectIfAuth },
            ],
        },
        { path: '*',       component: NotFound },
    ],
});

export default router;
