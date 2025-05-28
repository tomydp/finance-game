import React from 'react';

const Navbar: React.FC = () => {
  return (
    <header className="bg-[#0d1321] text-white px-6 py-4 flex items-center justify-between">
      {/* Logo + nombre */}
      <div className="flex items-center space-x-2">
        <div className="w-8 h-8 rounded-full bg-cyan-700 flex items-center justify-center text-white font-semibold">
          F
        </div>
        <span className="text-xl font-semibold text-cyan-500">FinanzApp</span>
      </div>

      {/* Navegación */}
      <nav>
        <ul className="flex space-x-8 text-sm font-medium">
          <li className="hover:text-cyan-400 cursor-pointer">Características</li>
          <li className="hover:text-cyan-400 cursor-pointer">Cómo funciona</li>
          <li className="hover:text-cyan-400 cursor-pointer">Testimonios</li>
          <li className="hover:text-cyan-400 cursor-pointer">Iniciar sesión</li>
        </ul>
      </nav>
    </header>
  );
};

export default Navbar;
