import { createContext, useContext, useEffect, useState, type ReactNode } from 'react';
import * as auth from '../services/authService';

interface AuthState {
  user: auth.User | null;
  loading: boolean;
  login: (c: auth.Credentials) => Promise<void>;
  logout: () => Promise<void>;
}

const AuthContext = createContext<AuthState | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [user, setUser]     = useState<auth.User | null>(null);
  const [loading, setLoading] = useState(true);

  const hydrate = async () => {
    try {
      await auth.csrf();           // 1) obtiene cookie
      auth.syncXsrfHeader();       // 2) la coloca en la cabecera
      const { data } = await auth.currentUser();   // 3) ya viaja header correcto
      setUser(data);
    } catch {
      setUser(null);
    } finally {
      setLoading(false);
    }
  };
  
  useEffect(() => { hydrate(); }, []);

  const login = async (cred: auth.Credentials) => {
    await auth.login(cred);   // dentro ya haces csrf + sync
    await hydrate();        // opcional, para refrescar estado
  };

  const logout = async () => {
    await auth.logout();
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

/** Hook de consumo */
export const useAuth = () => {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth debe usarse dentro de AuthProvider');
  return ctx;
};
