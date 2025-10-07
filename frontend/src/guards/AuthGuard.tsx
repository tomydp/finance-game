import React from 'react';
import { Navigate, useLocation } from 'react-router-dom';

export default function AuthGuard({ children }: { children: React.ReactNode }) {
  const location = useLocation();

  const raw  = localStorage.getItem('user');
  const user = raw ? JSON.parse(raw) : null;
  const authFlag = localStorage.getItem('isAuthenticated');

  const isAuth = authFlag === 'true' && !!user?.token;
  const emailVerified = Boolean(
    user?.verified ?? user?.email_verified ?? false
  ); // 👈 clave

  if (!isAuth) {
    // no logueado: mandá a login guardando destino
    return <Navigate to="/login" replace state={{ from: location }} />;
  }

  if (!emailVerified) {
    // logueado pero NO verificado: bloquear acceso a /app/*
    // y mandar a login con una bandera para mostrar aviso/botón de reenvío.
    return (
      <Navigate
        to="/login"
        replace
        state={{ needVerify: true, from: location, email: user?.email }}
      />
    );
  }

  return <>{children}</>;
}
