// src/components/auth/Register.tsx
import React, { useState, type ChangeEvent, type FormEvent } from 'react';
import { FaGoogle, FaFacebook } from 'react-icons/fa';
import { FiEye, FiEyeOff, FiArrowLeft } from 'react-icons/fi';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

interface FormState {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

const Register: React.FC = () => {
  const navigate = useNavigate();
  const { handleRegister, errors, setErrors } = useAuth();
  const [showPassword, setShowPassword] = useState(false);
  const [form, setForm] = useState<FormState>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });
  const [submitting, setSubmitting] = useState(false);

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setForm(f => ({ ...f, [name]: value }));
    // limpiar error de ese campo al tipear
    if (errors?.[name]) {
      setErrors(prev => ({ ...(prev ?? {}), [name]: [] }));
    }
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      await handleRegister(form);
      navigate('/verify-email');
    } catch {
      // los errores ya están en `errors`
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="relative min-h-screen bg-[var(--Blue1)] flex items-center justify-center px-4">
      <button
        onClick={() => navigate('/')}
        className="absolute top-4 left-4 text-white hover:text-gray-200 transition text-2xl"
        aria-label="Volver al landing"
      >
        <FiArrowLeft />
      </button>

      <div className="w-full max-w-md bg-[#121c30] rounded-2xl shadow-xl p-8 space-y-6">
        <div className="flex justify-center">
          <div className="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center text-white font-bold text-lg">
            F
          </div>
        </div>

        <div className="text-center space-y-1">
          <h2 className="text-2xl font-extrabold text-white">Crear cuenta</h2>
          <p className="text-gray-400">Empieza a dominar tus finanzas</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Nombre */}
          <div>
            <label htmlFor="name" className="block text-sm text-gray-300 mb-1">
              Nombre completo
            </label>
            <input
              id="name"
              name="name"
              type="text"
              value={form.name}
              onChange={handleChange}
              placeholder="Tu nombre"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
              required
            />
            {errors?.name && (
              <p className="text-red-500 text-sm mt-1">{errors.name[0]}</p>
            )}
          </div>

          {/* Email */}
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
            {errors?.email && (
              <p className="text-red-500 text-sm mt-1">{errors.email[0]}</p>
            )}
          </div>

          {/* Contraseña */}
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
              placeholder="Tu contraseña"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 pr-10 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
              required
            />
            <button
              type="button"
              onClick={() => setShowPassword(v => !v)}
              className="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200"
            >
              {showPassword ? <FiEyeOff /> : <FiEye />}
            </button>
            {errors?.password && (
              <p className="text-red-500 text-sm mt-1">{errors.password[0]}</p>
            )}
          </div>

          {/* Confirmación de contraseña */}
          <div>
            <label htmlFor="password_confirmation" className="block text-sm text-gray-300 mb-1">
              Confirmar contraseña
            </label>
            <input
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              value={form.password_confirmation}
              onChange={handleChange}
              placeholder="Repite tu contraseña"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
              required
            />
            {errors?.password_confirmation && (
              <p className="text-red-500 text-sm mt-1">
                {errors.password_confirmation[0]}
              </p>
            )}
          </div>

          <button
            type="submit"
            disabled={submitting}
            className={`w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-3 rounded-md transition ${
              submitting ? 'opacity-50 cursor-not-allowed' : ''
            }`}
          >
            {submitting ? 'Creando cuenta...' : 'CREAR CUENTA'}
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

        {/* Social buttons (puedes dejarlos deshabilitados hasta integrar OAuth) */}
        <div className="flex gap-4">
          <button disabled className="flex-1 flex items-center justify-center bg-white bg-opacity-10 border border-gray-700 rounded-md py-2 space-x-2 opacity-50">
            <FaGoogle /> <span>Google</span>
          </button>
          <button disabled className="flex-1 flex items-center justify-center bg-white bg-opacity-10 border border-gray-700 rounded-md py-2 space-x-2 opacity-50">
            <FaFacebook /> <span>Facebook</span>
          </button>
        </div>

        <p className="text-center text-gray-400 text-sm mt-4">
          ¿Ya tenés cuenta?{' '}
          <button
            onClick={() => navigate('/login')}
            className="text-cyan-400 hover:underline"
          >
            Iniciar sesión
          </button>
        </p>
      </div>
    </div>
  );
};

export default Register;
