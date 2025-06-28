import React from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import App from './App';
import { AuthProvider } from './context/AuthContext';  // <-- importa tu provider

createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <AuthProvider>    {/* <-- envolvemos aquí */}
      <App />
    </AuthProvider>
  </React.StrictMode>
);
