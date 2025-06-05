// src/components/Sidebar.tsx
import React from 'react';
import { NavLink } from 'react-router-dom';
import {
  FaHome,
  FaMusic,
  FaTrophy,
  FaBolt,
  FaShoppingCart,
  FaUser,
  FaEllipsisH,
} from 'react-icons/fa';

// Lista de ítems del menú (cada uno con su ruta, texto y componente de ícono)
const menuItems = [
  { to: '/app/aprender',   label: 'Aprender',  icon: FaHome },
  { to: '/app/sonidos',    label: 'Sonidos',   icon: FaMusic },
  { to: '/app/ligas',      label: 'Ligas',     icon: FaTrophy },
  { to: '/app/desafios',   label: 'Desafíos',  icon: FaBolt },
  { to: '/app/tienda',     label: 'Tienda',    icon: FaShoppingCart },
  { to: '/app/perfil',     label: 'Perfil',    icon: FaUser },
  { to: '/app/mas',        label: 'Más',       icon: FaEllipsisH },
];

const Sidebar: React.FC = () => {
  return (
    <aside className="w-48 bg-[#121c30] text-white flex flex-col items-start px-4 py-6">
      {/* Logo + Nombre */}
      <div className="flex items-center space-x-2 mb-6">
        <div className="w-8 h-8 rounded-full bg-cyan-500 flex items-center justify-center text-white font-bold">
          F
        </div>
        <span className="text-lg font-semibold text-cyan-400">
          FinanzApp
        </span>
      </div>

      {/* Menú de navegación */}
      <nav className="flex-1 w-full space-y-2">
        {menuItems.map(({ to, label, icon: Icon }) => (
          <NavLink
            key={to}
            to={to}
            className={({ isActive }) =>
              [
                'flex items-center w-full px-3 py-2 rounded-lg text-sm font-medium',
                isActive
                  ? 'bg-cyan-500 text-white'
                  : 'text-gray-400 hover:bg-gray-800 hover:text-white',
              ].join(' ')
            }
          >
            <Icon className="w-5 h-5 mr-3" />
            <span>{label}</span>
          </NavLink>
        ))}
      </nav>
    </aside>
  );
};

export default Sidebar;
