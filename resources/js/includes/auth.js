import {Ziggy} from './ziggy'

window.Ziggy  = Ziggy;
window.zRoute = require('../../../vendor/tightenco/ziggy/dist/js/route');

export default {
    auth: {
        request: () => {},
        response: res => res.status === 200,
    },
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
    loginData:          { url: zRoute('auth.login').url() },
    logoutData:         { url: zRoute('auth.logout').url() },
    fetchData:          { url: zRoute('api.user').url() },
    registerData:       { url: zRoute('auth.register').url() },
    refreshData: { enabled: false },
    tokenExpired: false,
};
