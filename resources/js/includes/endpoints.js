import axios from 'axios';

export const register   = params => axios.post (zRoute('auth.register'), params);
export const login      = params => axios.post (zRoute('auth.login'), params);
export const logout     = () =>     axios.post (zRoute('auth.logout'));
export const csrfCookie = () =>     axios.get  ('/airlock/csrf-cookie');

export const exercisesList = params =>  axios.get  (zRoute('exercises.index'), { params: params });
export const getExercises = params =>  axios.get  (zRoute('exercises.get'), { params: params });
export const storeExercise = params =>  axios.post (zRoute('exercises.store'), params);
export const updateExercise = params =>  axios.put (zRoute('exercises.update', { exercise: params.id }), params);
export const showExercise = params =>  axios.get (zRoute('exercises.show', { exercise: params.id }), { params: params });
export const destroyExercise = id =>  axios.delete (zRoute('exercises.destroy', { exercise: id }));

export const activityList = params =>  axios.get  (zRoute('activities.index'), { params: params });
export const activityAll = () =>  axios.get  (zRoute('activities.get'));
export const storeActivity = params =>  axios.post (zRoute('activities.store'), params);
export const updateActivity = params =>  axios.put (zRoute('activities.update', { activity: params.id }), params);
export const showActivity = params =>  axios.get (zRoute('activities.show', { activity: params.id }), { params: params });
export const toggleActivityStatus = id =>  axios.post (zRoute('activities.toggle-status', { activity: id }));
export const destroyActivity = id =>  axios.delete (zRoute('activities.destroy', { activity: id }));

export const statisticsList = params =>  axios.get  (zRoute('statistics.index'), { params: params });
