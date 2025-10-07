// src/components/Sidebar.tsx
import React from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import {
  FaHome,
  FaMusic,
  FaTrophy,
  FaBolt,
  FaShoppingCart,
  FaUser,
  FaEllipsisH,
  FaSignOutAlt,
} from 'react-icons/fa';
import api from '../../services/api'; // tu instancia de axios (opcional para llamar /logout)

interface MenuItem {
  to: string;
  label: string;
  icon: React.ComponentType<{ className?: string }>;
  enabled: boolean;
}

const menuItems: MenuItem[] = [
  { to: '/app/aprender', label: 'Aprender',  icon: FaHome,           enabled: true  },
  { to: '/app/sonidos',  label: 'Sonidos',   icon: FaMusic,          enabled: true  },
  // ✅ Renombrado + habilitado + nueva ruta
  { to: '/app/ranking',  label: 'Ranking',   icon: FaTrophy,         enabled: true  },
  { to: '/app/desafios', label: 'Desafíos',  icon: FaBolt,           enabled: true  },
  { to: '/app/tienda',   label: 'Tienda',    icon: FaShoppingCart,   enabled: true  },
  { to: '/app/perfil',   label: 'Perfil',    icon: FaUser,           enabled: true  },
  { to: '/app/mas',      label: 'Más',       icon: FaEllipsisH,      enabled: true  },
];

const Sidebar: React.FC = () => {
  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
      // Si tu backend tiene /api/logout protegido, lo llamás:
      await api.post('/logout'); // si no lo tenés, podés comentar esta línea
    } catch (err) {
      console.error('handleLogout →', err);
    } finally {
      localStorage.removeItem('user');
      localStorage.removeItem('isAuthenticated');
      navigate('/login', { replace: true });
    }
  };

  // (Opcional) Mostrar nombre del usuario si está guardado
  const userRaw = localStorage.getItem('user');
  const user = userRaw ? JSON.parse(userRaw) : null;

  return (
    <aside className="w-48 bg-[#121c30] text-white flex flex-col items-start px-4 py-6">
      <div className="flex items-center space-x-2 mb-6">
        <div className="w-8 h-8 rounded-full bg-cyan-500 flex items-center justify-center text-white font-bold">
          {user?.name ? user.name[0]?.toUpperCase() : 'F'}
        </div>
        <div className="flex flex-col">
          <span className="text-lg font-semibold text-cyan-400">Finance Game</span>
          {user?.name && <span className="text-xs text-gray-400 truncate max-w-[9rem]">{user.name}</span>}
        </div>
      </div>

      <nav className="flex-1 w-full space-y-2">
        {menuItems.map(({ to, label, icon: Icon, enabled }) =>
          enabled ? (
            <NavLink
              key={label}
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
          ) : (
            <div
              key={label}
              className="flex items-center w-full px-3 py-2 rounded-lg text-sm font-medium text-gray-600 cursor-not-allowed"
              title="Próximamente"
            >
              <Icon className="w-5 h-5 mr-3 opacity-50" />
              <span className="opacity-50">{label}</span>
            </div>
          )
        )}
      </nav>

      {/* Separador */}
      <div className="w-full h-px bg-gray-800 my-3" />

      {/* Botón de Cerrar sesión */}
      <button
        onClick={handleLogout}
        className="flex items-center w-full px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition"
      >
        <FaSignOutAlt className="w-5 h-5 mr-3" />
        Cerrar sesión
      </button>
    </aside>
  );
};

export default Sidebar;
