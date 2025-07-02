// src/components/auth/Login.tsx
import { useEffect, useState, type ChangeEvent, type FormEvent } from 'react';
import { useNavigate } from 'react-router-dom';
import { FiEye, FiEyeOff, FiArrowLeft } from 'react-icons/fi';
import { FaGoogle, FaFacebook } from 'react-icons/fa';

import { useAuth } from '../../context/AuthContext';
import type { Credentials } from '../../services/authService';

interface FieldErrors {
  email?: string[];
  password?: string[];
  general?: string[];
}

export default function Login() {
  const { login } = useAuth();
  const { user } = useAuth();
  const navigate  = useNavigate();

  useEffect(() => {
    if (user) navigate('/app/aprender', { replace: true });
  }, [user, navigate]);

  const [form, setForm]   = useState({ email: '', password: '', remember: false });
  const [showPwd, setPwd] = useState(false);
  const [sending, setSending] = useState(false);
  const [errors, setErrors]   = useState<FieldErrors>({});

  /* ───────────────────────── handlers ───────────────────────── */
  const onChange = (e: ChangeEvent<HTMLInputElement>) => {
    const { name, type, checked, value } = e.target;
    setForm(f => ({ ...f, [name]: type === 'checkbox' ? checked : value }));
    if (errors[name as keyof FieldErrors]) {
      setErrors(prev => ({ ...prev, [name]: undefined }));          // limpia error puntual
    }
  };

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setSending(true);
    setErrors({});

    try {
      await login({   // ←  **AQUÍ** está la llamada que quizá no veías
        email: form.email,
        password: form.password,
      } as Credentials);
      navigate('/app/aprender', { replace: true });                                                // dashboard privado
    } catch (err: any) {
      // Laravel Breeze devuelve 422 (validation) o 401 (unauth)
      const backend = err.response?.data;

      if (backend?.errors) {
        setErrors(backend.errors);
      } else {
        setErrors({ general: [backend?.message ?? 'Error inesperado.'] });
      }
    } finally {
      setSending(false);
    }
  };

  /* ───────────────────────── UI ───────────────────────── */
  return (
    <div className="relative min-h-screen flex items-center justify-center bg-[var(--Blue1)] px-4">
      {/* Flecha para volver al landing */}
      <button
        aria-label="Volver al inicio"
        onClick={() => navigate('/')}
        className="absolute left-4 top-4 text-white text-2xl hover:text-gray-200"
      >
        <FiArrowLeft />
      </button>

      <div className="w-full max-w-md space-y-6 rounded-2xl bg-[#121c30] p-8 shadow-xl">
        {/* Logo / marca */}
        <div className="flex justify-center">
          <div className="flex h-12 w-12 items-center justify-center rounded-full bg-cyan-500 text-lg font-bold text-white">
            F
          </div>
        </div>

        {/* Heading */}
        <div className="space-y-1 text-center">
          <h2 className="text-2xl font-extrabold text-white">¡Bienvenido de vuelta!</h2>
          <p className="text-gray-400">Ingresa tus credenciales para continuar</p>
        </div>

        {/* Formulario */}
        <form onSubmit={onSubmit} className="space-y-4">
          <div>
            <label htmlFor="email" className="mb-1 block text-sm text-gray-300">
              Correo electrónico
            </label>
            <input
              id="email"
              name="email"
              type="email"
              required
              value={form.email}
              onChange={onChange}
              placeholder="tu@email.com"
              className="w-full rounded-md border border-gray-700 bg-[var(--Blue2)] px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
            />
            {errors.email && <p className="mt-1 text-sm text-red-500">{errors.email[0]}</p>}
          </div>

          <div className="relative">
            <label htmlFor="password" className="mb-1 block text-sm text-gray-300">
              Contraseña
            </label>
            <input
              id="password"
              name="password"
              type={showPwd ? 'text' : 'password'}
              required
              value={form.password}
              onChange={onChange}
              placeholder="Tu contraseña"
              className="w-full rounded-md border border-gray-700 bg-[var(--Blue2)] px-4 py-2 pr-10 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
            />
            <button
              type="button"
              onClick={() => setPwd(v => !v)}
              className="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200"
            >
              {showPwd ? <FiEyeOff /> : <FiEye />}
            </button>
            {errors.password && (
              <p className="mt-1 text-sm text-red-500">{errors.password[0]}</p>
            )}
          </div>

          <div className="flex items-center justify-between text-sm">
            <label className="inline-flex items-center text-gray-300">
              <input
                type="checkbox"
                name="remember"
                checked={form.remember}
                onChange={onChange}
                className="form-checkbox h-4 w-4 rounded border-gray-600 bg-[var(--Blue2)] text-cyan-500"
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

          {errors.general && (
            <p className="rounded-md bg-red-100 p-2 text-center text-sm text-red-700">
              {errors.general[0]}
            </p>
          )}

          <button
            type="submit"
            disabled={sending}
            className={`w-full rounded-md bg-cyan-500 py-3 font-semibold text-white transition hover:bg-cyan-600 ${
              sending ? 'cursor-not-allowed opacity-50' : ''
            }`}
          >
            {sending ? 'Ingresando…' : 'INICIAR SESIÓN'}
          </button>
        </form>

        {/* Divider */}
        <div className="my-4 flex items-center text-sm text-gray-500">
          <div className="h-px flex-grow bg-gray-700" />
          <span className="px-3">O CONTINÚA CON</span>
          <div className="h-px flex-grow bg-gray-700" />
        </div>

        {/* Botones sociales (deshabilitados) */}
        <div className="flex gap-4">
          <button
            disabled
            className="flex-1 flex items-center justify-center space-x-2 rounded-md border border-gray-700 bg-white bg-opacity-10 py-2 opacity-50"
          >
            <FaGoogle /> <span>Google</span>
          </button>
          <button
            disabled
            className="flex-1 flex items-center justify-center space-x-2 rounded-md border border-gray-700 bg-white bg-opacity-10 py-2 opacity-50"
          >
            <FaFacebook /> <span>Facebook</span>
          </button>
        </div>

        {/* Modo invitado */}
        <p className="mt-4 text-center text-sm text-gray-400">
          ¿Prefieres empezar sin cuenta?{' '}
          <button onClick={() => navigate('/')} className="text-cyan-400 hover:underline">
            Continúa como invitado
          </button>
        </p>
      </div>
    </div>
  );
}
