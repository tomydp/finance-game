// src/components/Layout.tsx
import React from 'react';
import { Outlet } from 'react-router-dom';
import Sidebar from './Sidebar';

const Layout: React.FC = () => {
  return (
    <div className="min-h-screen flex bg-[#0d1321] text-white">
      {/* Sidebar izquierdo */}
      <Sidebar />

      {/* Aquí React Router inyecta la ruta hija (Dashboard u otra) */}
      <main className="flex-1 p-6 overflow-y-auto">
        <Outlet />
      </main>

      {/* Sidebar derecho vacío (puedes quitarlo o cambiar su ancho) */}
      <aside className="w-24 bg-[#121c30]" />
    </div>
  );
};

export default Layout;

