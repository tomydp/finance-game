// src/components/ResetPassword.tsx
import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, useLocation } from 'react-router-dom';
import { FiArrowLeft } from 'react-icons/fi';

const ResetPassword: React.FC = () => {
  const navigate = useNavigate();
  const { token } = useParams<{ token: string }>();
  const location = useLocation();
  const query = new URLSearchParams(location.search);
  const emailFromQuery = query.get('email') || '';

  const [email, setEmail] = useState(emailFromQuery);
  const [password, setPassword] = useState('');
  const [confirm, setConfirm] = useState('');

  useEffect(() => {
    // si el backend envía el email en la URL, puedes leerlo aquí
    setEmail(emailFromQuery);
  }, [emailFromQuery]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // TODO: llamar a tu servicio resetPassword({ token, email, password, password_confirmation: confirm })
    console.log({ token, email, password, confirm });
  };

  return (
    <div className="min-h-screen bg-[var(--Blue1)] flex items-center justify-center px-4 text-white relative">
      <button
        onClick={() => navigate(-1)}
        className="absolute top-4 left-4 text-white text-2xl"
        aria-label="Volver"
      >
        <FiArrowLeft />
      </button>

      <div className="w-full max-w-md bg-[#121c30] rounded-2xl shadow-xl p-8 space-y-6">
        <h2 className="text-2xl font-bold text-center">Restablecer contraseña</h2>

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Token oculto */}
          <input type="hidden" name="token" value={token} />

          {/* Email */}
          <div>
            <label htmlFor="email" className="block text-sm text-gray-300 mb-1">
              Correo electrónico
            </label>
            <input
              id="email"
              type="email"
              value={email}
              onChange={e => setEmail(e.target.value)}
              required
              autoFocus
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
            />
          </div>

          {/* Nueva contraseña */}
          <div>
            <label htmlFor="password" className="block text-sm text-gray-300 mb-1">
              Nueva contraseña
            </label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={e => setPassword(e.target.value)}
              required
              autoComplete="new-password"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
            />
          </div>

          {/* Confirmar contraseña */}
          <div>
            <label htmlFor="confirm" className="block text-sm text-gray-300 mb-1">
              Confirmar contraseña
            </label>
            <input
              id="confirm"
              type="password"
              value={confirm}
              onChange={e => setConfirm(e.target.value)}
              required
              autoComplete="new-password"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
            />
          </div>

          <button
            type="submit"
            className="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-3 rounded-md transition"
          >
            Restablecer contraseña
          </button>
        </form>
      </div>
    </div>
  );
};

export default ResetPassword;
