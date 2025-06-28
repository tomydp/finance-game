// src/components/auth/Login.tsx
import React, { useState, useEffect, type ChangeEvent, type FormEvent } from 'react';
import { FaGoogle, FaFacebook } from 'react-icons/fa';
import { FiEye, FiEyeOff, FiArrowLeft } from 'react-icons/fi';
import { useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import type { AuthPayload } from '../../services/authService';

interface LocationState {
  mode?: 'login' | 'register';
}

const Login: React.FC = () => {
  const navigate = useNavigate();
  const { handleLogin, handleRegister, errors, setErrors } = useAuth();
  const location = useLocation();
  const state = location.state as LocationState;

  // Definimos el modo inicial según state.mode
  const initialMode = state?.mode === 'register' ? 'register' : 'login';
  const [mode, setMode] = useState<'login' | 'register'>(initialMode);

  const [showPassword, setShowPassword] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    confirm: '',
    remember: false,
  });

  // Si cambian las props de la ruta, actualizamos el modo
  useEffect(() => {
    setMode(state?.mode === 'register' ? 'register' : 'login');
    setErrors(null);
    setForm({ name: '', email: '', password: '', confirm: '', remember: false });
  }, [state?.mode, setErrors]);

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const { name, value, type, checked } = e.target;
    setForm(f => ({
      ...f,
      [name]: type === 'checkbox' ? checked : value,
    }));
    // Limpiar error de ese campo
    if (errors?.[name]) {
      setErrors(prev => ({ ...(prev ?? {}), [name]: [] }));
    }
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      if (mode === 'login') {
        // Sólo email y password
        await handleLogin({ email: form.email, password: form.password } as AuthPayload);
      } else {
        // Registro: name, email, password, password_confirmation
        await handleRegister({
          name: form.name,
          email: form.email,
          password: form.password,
          password_confirmation: form.confirm,
        } as AuthPayload);
      }
      navigate('/app');
    } catch {
      // errores ya están en `errors`
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="relative min-h-screen bg-[var(--Blue1)] flex items-center justify-center px-4">
      {/* Flecha de regreso al landing */}
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
          <h2 className="text-2xl font-extrabold text-white">
            {mode === 'login' ? '¡Bienvenido de vuelta!' : 'Crea tu cuenta'}
          </h2>
          <p className="text-gray-400">
            {mode === 'login'
              ? 'Ingresa tus credenciales para continuar'
              : 'Únete a FinanzApp y comienza a aprender finanzas gratis'}
          </p>
        </div>

        {/* Formulario */}
        <form onSubmit={handleSubmit} className="space-y-4">
          {mode === 'register' && (
            <div>
              <label htmlFor="name" className="block text-sm text-gray-300 mb-1">
                Nombre completo
              </label>
              <input
                id="name"
                name="name"
                value={form.name}
                onChange={handleChange}
                placeholder="Tu nombre completo"
                className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
              {errors?.name && <p className="text-red-500 text-sm mt-1">{errors.name[0]}</p>}
            </div>
          )}

          <div>
            <label htmlFor="email" className="block text-sm text-gray-300 mb-1">
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
            {errors?.email && <p className="text-red-500 text-sm mt-1">{errors.email[0]}</p>}
          </div>

          <div className="relative">
            <label htmlFor="password" className="block text-sm text-gray-300 mb-1">
              Contraseña
            </label>
            <input
              id="password"
              name="password"
              type={showPassword ? 'text' : 'password'}
              value={form.password}
              onChange={handleChange}
              placeholder={mode === 'register' ? 'Crea una contraseña' : 'Tu contraseña'}
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
            {errors?.password && (
              <p className="text-red-500 text-sm mt-1">{errors.password[0]}</p>
            )}
          </div>

          {mode === 'register' && (
            <div>
              <label htmlFor="confirm" className="block text-sm text-gray-300 mb-1">
                Confirmar contraseña
              </label>
              <input
                id="confirm"
                name="confirm"
                type="password"
                value={form.confirm}
                onChange={handleChange}
                placeholder="Confirma tu contraseña"
                className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
              {errors?.password_confirmation && (
                <p className="text-red-500 text-sm mt-1">
                  {errors.password_confirmation[0]}
                </p>
              )}
            </div>
          )}

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
            {mode === 'login' && (
              <button
                type="button"
                onClick={() => navigate('/reset-password')}
                className="text-cyan-400 hover:underline"
              >
                ¿Olvidaste tu contraseña?
              </button>
            )}
          </div>

          <button
            type="submit"
            disabled={submitting}
            className={`w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-3 rounded-md transition ${
              submitting ? 'opacity-50 cursor-not-allowed' : ''
            }`}
          >
            {mode === 'login' ? 'INICIAR SESIÓN' : 'CREAR CUENTA'}
          </button>

          {errors?.general && (
            <p className="text-red-500 text-center mt-2">{errors.general[0]}</p>
          )}
        </form>

        {/* Divider */}
        <div className="flex items-center text-gray-500 text-sm my-4">
          <div className="flex-grow h-px bg-gray-700" />
          <span className="px-3">O CONTINÚA CON</span>
          <div className="flex-grow h-px bg-gray-700" />
        </div>

        {/* Social buttons */}
        <div className="flex gap-4">
          <button disabled className="flex-1 flex items-center justify-center bg-white bg-opacity-10 border border-gray-700 rounded-md py-2 space-x-2 opacity-50">
            <FaGoogle /> <span>Google</span>
          </button>
          <button disabled className="flex-1 flex items-center justify-center bg-white bg-opacity-10 border border-gray-700 rounded-md py-2 space-x-2 opacity-50">
            <FaFacebook /> <span>Facebook</span>
          </button>
        </div>

        {/* Invitado */}
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
