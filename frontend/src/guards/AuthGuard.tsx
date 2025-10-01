import React from 'react';
import { Navigate, useLocation } from 'react-router-dom';

export default function AuthGuard({ children }: { children: React.ReactNode }) {
  const location = useLocation();
  const userRaw = localStorage.getItem('user');
  const user = userRaw ? JSON.parse(userRaw) : null;
  const isAuth = localStorage.getItem('isAuthenticated') === 'true' && !!user?.token;

  if (!isAuth) {
    // Te lleva a /login y guarda a dónde querías ir
    return <Navigate to="/login" replace state={{ from: location }} />;
  }
  return <>{children}</>;
}
