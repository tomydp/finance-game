import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost', // sin /api
  withCredentials: true,
  xsrfCookieName: 'XSRF-TOKEN',    // ← nombre exacto de la cookie
  xsrfHeaderName: 'X-XSRF-TOKEN',  // ← este es el default que Laravel lee
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
});

export default api;
