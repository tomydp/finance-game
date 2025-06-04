import React, { type ReactNode } from 'react';
import { Link } from 'react-router-dom';

interface LayoutProps {
  children: ReactNode;
}

const menuItems = [
  'Aprender',
  'Sonidos',
  'Ligas',
  'Desafíos',
  'Tienda',
  'Perfil',
  'Más',
];

const routeMap: Record<string, string> = {
  Aprender: '/aprendizaje',
  Sonidos: '#',
  Ligas: '#',
  Desafíos: '#',
  Tienda: '#',
  Perfil: '#',
    Más: '/mas',

};


const Layout: React.FC<LayoutProps> = ({ children }) => (
  <div className="flex min-h-screen bg-[#0d1321] text-white">
    {/* Sidebar izquierdo */}
    <aside className="w-24 bg-[#121c30] flex flex-col items-start py-6 px-2 space-y-4">
      {/* Logo */}
      <div className="w-full text-center text-cyan-400 text-xl font-bold">F</div>
      {/* Menú */}
      <nav className="flex flex-col w-full space-y-2">
        {menuItems.map((item) => (
          <Link
            key={item}
            to={routeMap[item] || '#'}
            className="w-full text-left text-sm text-gray-400 px-2 py-1 rounded hover:text-white hover:bg-[#1f2937] transition"
          >
            {item}
          </Link>
        ))}
      </nav>
    </aside>

    {/* Contenido principal */}
    <main className="flex-1 p-6 overflow-y-auto">{children}</main>

    {/* Sidebar derecho vacío */}
    <aside className="w-24 bg-[#121c30]" />
  </div>
);

export default Layout;
