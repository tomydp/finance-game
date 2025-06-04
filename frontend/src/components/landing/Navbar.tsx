// src/components/Navbar.tsx
import React from 'react';

const Navbar: React.FC = () => {
  return (
    <header className="
      fixed top-0 left-0 w-full z-50 
      bg-[#0d1321]/80 backdrop-blur-sm 
      text-white px-6 py-4 
      flex items-center justify-between
    ">
      {/* Logo + nombre */}
      <div className=" flex items-center space-x-2">
        <div className="w-8 h-8 rounded-full py-7 bg-[var(--Blue2)] flex items-center justify-center text-white font-semibold">
          F
        </div>
        <span className="text-xl font-semibold text-cyan-400">
          FinanzApp
        </span>
      </div>

      {/* Enlaces */}
      <nav>
        <ul className="flex space-x-8 text-sm font-medium">
          <li className="hover:text-cyan-300 cursor-pointer">Características</li>
          <li className="hover:text-cyan-300 cursor-pointer">Cómo funciona</li>
          <li className="hover:text-cyan-300 cursor-pointer">Testimonios</li>
        </ul>
      </nav>
    </header>
  );
};

export default Navbar;
