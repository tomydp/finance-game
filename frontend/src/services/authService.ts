import API from './api';

export const register = (data: any) => API.post('/register', data);
export const login    = (data: any) => API.post('/login', data);

export const getProfile  = () => API.get('/profile'); // si cambias a GET
export const updateProfile = (data: any) => API.put('/profile', data);
export const logout       = () => API.post('/logout');
