// src/components/Login.tsx
import React, { useState } from 'react';
import { FaGoogle, FaFacebookF, FaEye, FaEyeSlash } from 'react-icons/fa';

const Login: React.FC = () => {

  
  const [isRegister, setIsRegister] = useState(false);
  const [showPassword, setShowPassword] = useState(false);


  return (
    <div className="min-h-screen bg-[#0d1321] text-white flex items-center justify-center px-4">
      <div className="w-full max-w-md bg-[#121c30] rounded-xl p-8 shadow-xl">
        {/* Logo + Título */}
        <div className="flex items-center justify-center mb-6 space-x-2">
          <div className="w-10 h-10 rounded-full bg-cyan-700 flex items-center justify-center font-bold text-white text-lg">
            F
          </div>
          <h1 className="text-2xl font-bold text-cyan-400">FinanzApp</h1>
        </div>

        <h2 className="text-xl font-semibold text-center mb-1">¡Bienvenido de vuelta!</h2>
        <p className="text-sm text-center mb-6 text-gray-400">
          Continúa tu viaje de aprendizaje financiero
        </p>


        <div className="flex mb-6">
  <button
    className={`flex-1 py-2 rounded-l-lg font-semibold ${!isRegister ? 'bg-cyan-700 text-white' : 'bg-[#1f2937] text-white'}`}
    onClick={() => setIsRegister(false)}
    type="button"
  >
    Iniciar sesión
  </button>
  <button
    className={`flex-1 py-2 rounded-r-lg font-semibold ${isRegister ? 'bg-cyan-700 text-white' : 'bg-[#1f2937] text-white'}`}
    onClick={() => setIsRegister(true)}
    type="button"
  >
    Registrarse
  </button>
</div>


        {/* Tabs */}
        <div className="flex mb-6">
          <button className="flex-1 bg-cyan-700 text-white font-semibold py-2 rounded-l-lg">Iniciar sesión</button>
          <button className="flex-1 bg-[#1f2937] text-white py-2 rounded-r-lg">Registrarse</button>
        </div>

        {/* Formulario */}
        <form>
          <label className="block text-sm mb-1">Correo electrónico</label>
          <input
            type="email"
            placeholder="tu@email.com"
            className="w-full mb-4 px-4 py-2 bg-[#1a2332] border border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
            required
          />

          <label className="block text-sm mb-1">Contraseña</label>
          <div className="relative mb-4">
            <input
              type={showPassword ? 'text' : 'password'}
              placeholder="Tu contraseña"
              className="w-full px-4 py-2 bg-[#1a2332] border border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
              required
            />
            <button
              type="button"
              onClick={() => setShowPassword(!showPassword)}
              className="absolute right-3 top-2.5 text-gray-400 hover:text-white"
            >
              {showPassword ? <FaEyeSlash /> : <FaEye />}
            </button>
          </div>

          {/* Recordarme + link */}
          <div className="flex justify-between items-center text-sm mb-4">
            <label className="flex items-center space-x-2">
              <input type="checkbox" className="accent-cyan-500" />
              <span>Recordarme</span>
            </label>
            <a href="#" className="text-cyan-400 hover:underline">
              ¿Olvidaste tu contraseña?
            </a>
          </div>

          <button className="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-2 rounded font-semibold mb-4">
            INICIAR SESIÓN
          </button>

          <div className="text-center text-sm text-gray-400 mb-4">
            — O CONTINÚA CON —
          </div>

          {/* Botones sociales */}
          <div className="flex space-x-4 mb-4">
            <button className="flex-1 bg-white text-black py-2 rounded flex items-center justify-center space-x-2">
              <FaGoogle />
              <span>Google</span>
            </button>
            <button className="flex-1 bg-blue-600 text-white py-2 rounded flex items-center justify-center space-x-2">
              <FaFacebookF />
              <span>Facebook</span>
            </button>
          </div>

          <p className="text-sm text-center text-gray-400">
            ¿Prefieres empezar sin cuenta?{' '}
            <a href="#" className="text-cyan-400 hover:underline">
              Continúa como invitado
            </a>
          </p>
        </form> {/* ✅ Acá se cerró el form correctamente */}
      </div>
    </div>
  );
};

export default Login;
