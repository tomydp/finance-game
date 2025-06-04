// src/components/Login.tsx
import React, { useState } from 'react';
import { FaGoogle, FaFacebook } from 'react-icons/fa';
import { FiEye, FiEyeOff, FiArrowLeft } from 'react-icons/fi';
import { useNavigate } from 'react-router-dom';

const Login: React.FC = () => {
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [form, setForm] = useState({
    email: '',
    password: '',
    remember: false,
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value, type, checked } = e.target;
    setForm(f => ({
      ...f,
      [name]: type === 'checkbox' ? checked : value,
    }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // TODO: lógica de inicio de sesión
    navigate('/app');
  };

  return (
    <div className="relative min-h-screen bg-[var(--Blue1)] flex items-center justify-center px-4">
      {/* Flecha de regreso */}
      <button
        onClick={() => navigate('/')}
        className="absolute top-4 left-4 text-white hover:text-gray-200 transition text-2xl"
        aria-label="Volver al landing"
      >
        <FiArrowLeft />
      </button>

      <div className="w-full max-w-md bg-[#121c30] rounded-2xl shadow-xl p-8 space-y-6">
        {/* Logo */}
        <div className="flex justify-center">
          <div className="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center text-white font-bold text-lg">
            F
          </div>
        </div>

        {/* Heading */}
        <div className="text-center space-y-1">
          <h2 className="text-2xl font-extrabold text-white">Iniciar sesión</h2>
          <p className="text-gray-400">Ingresa tus credenciales para continuar</p>
        </div>

        {/* Formulario */}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm text-gray-300 mb-1" htmlFor="email">
              Correo electrónico
            </label>
            <input
              id="email"
              name="email"
              type="email"
              value={form.email}
              onChange={handleChange}
              placeholder="tu@email.com"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
              required
            />
          </div>

          <div className="relative">
            <label className="block text-sm text-gray-300 mb-1" htmlFor="password">
              Contraseña
            </label>
            <input
              id="password"
              name="password"
              type={showPassword ? 'text' : 'password'}
              value={form.password}
              onChange={handleChange}
              placeholder="Tu contraseña"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 pr-10 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
              required
            />
            <button
              type="button"
              onClick={() => setShowPassword(s => !s)}
              className="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200"
            >
              {showPassword ? <FiEyeOff /> : <FiEye />}
            </button>
          </div>

          <div className="flex items-center justify-between text-sm">
            <label className="inline-flex items-center text-gray-300">
              <input
                type="checkbox"
                name="remember"
                checked={form.remember}
                onChange={handleChange}
                className="form-checkbox h-4 w-4 text-cyan-500 bg-[var(--Blue2)] border-gray-600 rounded"
              />
              <span className="ml-2">Recordarme</span>
            </label>
            <button
              type="button"
              onClick={() => navigate('/reset-password')}
              className="text-cyan-400 hover:underline"
            >
              ¿Olvidaste tu contraseña?
            </button>
          </div>

          <button
            type="submit"
            className="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-3 rounded-md transition"
          >
            INICIAR SESIÓN
          </button>
        </form>

        {/* Divider */}
        <div className="flex items-center text-gray-500 text-sm my-4">
          <div className="flex-grow h-px bg-gray-700" />
          <span className="px-3">O CONTINÚA CON</span>
          <div className="flex-grow h-px bg-gray-700" />
        </div>

        {/* Social buttons */}
        <div className="flex gap-4">
          <button className="flex-1 flex items-center justify-center bg-white bg-opacity-10 hover:bg-opacity-20 border border-gray-700 rounded-md py-2 space-x-2 transition">
            <FaGoogle className="text-red-400" />
            <span>Google</span>
          </button>
          <button className="flex-1 flex items-center justify-center bg-white bg-opacity-10 hover:bg-opacity-20 border border-gray-700 rounded-md py-2 space-x-2 transition">
            <FaFacebook className="text-blue-600" />
            <span>Facebook</span>
          </button>
        </div>

        {/* Guest link */}
        <p className="text-center text-gray-400 text-sm mt-4">
          ¿Prefieres empezar sin cuenta?{' '}
          <button
            onClick={() => navigate('/app')}
            className="text-cyan-400 hover:underline"
          >
            Continúa como invitado
          </button>
        </p>
      </div>
    </div>
  );
};

export default Login;
