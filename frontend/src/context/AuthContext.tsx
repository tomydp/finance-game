// src/context/AuthContext.tsx
import React, {
    createContext,
    useContext,
    useEffect,
    useState,
    useCallback,
    type ReactNode,
    type FC,
  } from 'react';
  import * as authService from '../services/authService';
  import { isAxiosError, type AxiosError } from 'axios';
  
  interface User { id: number; name: string; email: string }
  interface AuthContextType {
    user: User | null;
    loading: boolean;
    errors: Record<string,string[]> | null;
    handleRegister: (p: authService.AuthPayload) => Promise<void>;
    handleLogin:    (p: authService.AuthPayload) => Promise<void>;
    handleLogout:   () => Promise<void>;
    setErrors:      React.Dispatch<React.SetStateAction<Record<string,string[]>|null>>;
  }
  
  const AuthContext = createContext<AuthContextType|undefined>(undefined);
  
  export const AuthProvider: FC<{children:ReactNode}> = ({ children }) => {
    // ——————— Safe-parse localStorage.user ———————
    const [user, setUser] = useState<User|null>(() => {
      const raw = localStorage.getItem('user');
      if (!raw) return null;
      try {
        return JSON.parse(raw) as User;
      } catch {
        console.warn('AuthContext: user inválido en localStorage, eliminando…');
        localStorage.removeItem('user');
        return null;
      }
    });
  
    const [loading, setLoading] = useState<boolean>(user !== null);
    const [errors, setErrors] = useState<Record<string,string[]>|null>(null);
  
    // ——————— Logout memoizado ———————
    const handleLogout = useCallback(async () => {
      await authService.logout().catch(() => {/* ignore */});
      localStorage.clear();
      setUser(null);
    }, []);
  
    // ——————— Init: comprueba /user sólo si hay token ———————
    useEffect(() => {
      const init = async () => {
        const token = localStorage.getItem('token');
        if (!token) {
          setLoading(false);
          return;
        }
        try {
          const { data } = await authService.me();
          setUser(data.user);
        } catch {
          await handleLogout();
        } finally {
          setLoading(false);
        }
      };
      init();
    }, [handleLogout]);
  
    // ——————— Persistir login/register ———————
    const persist = (data: authService.AuthResponse) => {
      localStorage.setItem('user', JSON.stringify(data.user));
      localStorage.setItem('token', data.token);
      setUser(data.user);
    };
  
    // ——————— Register ———————
    const handleRegister = useCallback(async (payload: authService.AuthPayload) => {
      try {
        const { data } = await authService.register(payload);
        persist(data);
      } catch (error: unknown) {
        if (isAxiosError(error)) {
          const axiosErr = error as AxiosError<{ errors: Record<string,string[]> }>;
          setErrors(axiosErr.response?.data.errors ?? { general: [axiosErr.message] });
        } else if (error instanceof Error) {
          setErrors({ general: [error.message] });
        } else {
          setErrors({ general: [String(error)] });
        }
        throw error;
      }
    }, []);
  
    // ——————— Login ———————
    const handleLogin = useCallback(async (payload: authService.AuthPayload) => {
      try {
        const { data } = await authService.login(payload);
        persist(data);
      } catch (error: unknown) {
        if (isAxiosError(error)) {
          const axiosErr = error as AxiosError<{ errors: Record<string,string[]> }>;
          setErrors(axiosErr.response?.data.errors ?? { general: [axiosErr.message] });
        } else if (error instanceof Error) {
          setErrors({ general: [error.message] });
        } else {
          setErrors({ general: [String(error)] });
        }
        throw error;
      }
    }, []);
  
    return (
      <AuthContext.Provider
        value={{ user, loading, errors, handleRegister, handleLogin, handleLogout, setErrors }}
      >
        {children}
      </AuthContext.Provider>
    );
  };
  
  export const useAuth = (): AuthContextType => {
    const ctx = useContext(AuthContext);
    if (!ctx) throw new Error('useAuth debe usarse dentro de <AuthProvider>');
    return ctx;
  };
  