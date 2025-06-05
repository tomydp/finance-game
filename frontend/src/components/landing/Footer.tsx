// src/components/Footer.tsx
import React from 'react';

const Footer: React.FC = () => (
  <footer className="bg-[var(--Blue1)] text-gray-300 py-10 px-6 md:px-16">
    <div className="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
      
      {/* Logo y descripción */}
      <div>
        <div className="flex items-center mb-3">
          <div className="w-8 h-8 rounded-full bg-cyan-700 flex items-center justify-center text-white font-semibold mr-2">
            F
          </div>
          <span className="text-xl font-semibold text-cyan-400">
            FinanzApp
          </span>
        </div>
        <p className="text-sm">
          La forma más divertida de aprender finanzas personales.
        </p>
      </div>

      {/* Columna Producto */}
      <div>
        <h4 className="text-white font-semibold mb-2">Producto</h4>
        <ul className="space-y-1 text-sm">
          <li><a href="#" className="hover:text-white transition">Características</a></li>
          <li><a href="#" className="hover:text-white transition">Precios</a></li>
          <li><a href="#" className="hover:text-white transition">Premium</a></li>
        </ul>
      </div>

      {/* Columna Soporte */}
      <div>
        <h4 className="text-white font-semibold mb-2">Soporte</h4>
        <ul className="space-y-1 text-sm">
          <li><a href="#" className="hover:text-white transition">Centro de ayuda</a></li>
          <li><a href="#" className="hover:text-white transition">Contacto</a></li>
          <li><a href="#" className="hover:text-white transition">Comunidad</a></li>
        </ul>
      </div>

      {/* Columna Legal */}
      <div>
        <h4 className="text-white font-semibold mb-2">Legal</h4>
        <ul className="space-y-1 text-sm">
          <li><a href="#" className="hover:text-white transition">Términos</a></li>
          <li><a href="#" className="hover:text-white transition">Privacidad</a></li>
          <li><a href="#" className="hover:text-white transition">Cookies</a></li>
        </ul>
      </div>
    </div>

    <hr className="border-gray-700 my-8" />

    <p className="text-center text-gray-500 text-sm">
      © {new Date().getFullYear()} FinanzApp. Todos los derechos reservados.
    </p>
  </footer>
);

export default Footer;
