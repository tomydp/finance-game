import api from '../lib/axios';

export interface Credentials { email: string; password: string }
export interface User        { id: number; name: string; email: string }

export const csrf = () => api.get('/sanctum/csrf-cookie');

export const login = async (data: Credentials) => {
  // 1) Trae la cookie XSRF-TOKEN
  await csrf();

  // 2) Extrae su valor y lo coloca en el header
  const xsrf = decodeURIComponent(
    document.cookie
      .split('XSRF-TOKEN=')[1]       // parte después de la clave
      .split(';')[0]                // hasta el próximo ;
  );

  api.defaults.headers.common['X-XSRF-TOKEN'] = xsrf;  // 👈 header manual

  // 3) Ahora sí, login
  return api.post('/api/login', data);
};

export const logout       = () => api.post('/api/logout');
export const currentUser  = () => api.get<User>('/api/user');

export const syncXsrfHeader = () => {
  const token = decodeURIComponent(
    document.cookie.split('XSRF-TOKEN=')[1]?.split(';')[0] ?? ''
  );
  if (token) {
    api.defaults.headers.common['X-XSRF-TOKEN'] = token;
  }
};
