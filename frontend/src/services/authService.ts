import API from './api';

export const register = (data: any) => API.post('/register', data);
export const login    = (data: any) => API.post('/login', data);

export const getProfile  = () => API.get('/profile'); // si cambias a GET
export const updateProfile = (data: any) => API.put('/profile', data);
export const logout       = () => API.post('/logout');

export const resendVerification = (email?: string) => {
  let token: string | undefined;
  const raw = localStorage.getItem('user');

  if (raw) {
    try {
      const parsed = JSON.parse(raw);
      token = parsed?.token;
    } catch {
      // ignore parse errors; interceptor will try as fallback
    }
  }

  const config = token
    ? {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    : undefined;

  const payload = token ? null : { email };

  return API.post('/email/verification-notification', payload, config);
};
