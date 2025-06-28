import api from '../api/axios';

export interface AuthPayload {
  name?: string;
  email: string;
  password: string;
  password_confirmation?: string;
}

export interface AuthResponse {
  user: { id: number; name: string; email: string };
  token: string;
}

export const register = (data: AuthPayload) =>
  api.post<AuthResponse>('/register', data);

export const login = (data: AuthPayload) =>
  api.post<AuthResponse>('/login', data);

export const logout = () =>
  api.post('/logout');

export const me = () =>
  api.get<{ user: AuthResponse['user'] }>('/user');
