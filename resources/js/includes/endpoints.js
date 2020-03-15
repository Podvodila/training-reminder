import axios from 'axios';

export const register = params => axios.post (zRoute('auth.register'), params);
export const login = params => axios.post (zRoute('auth.login'), params);
export const logout = () => axios.post (zRoute('auth.logout'));
export const csrfCookie = () => axios.get ('/airlock/csrf-cookie');
