// src/components/VerifyEmail.tsx
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

interface VerifyEmailProps {
  /** Si ya se reenvió el mail, puedes inicializarlo en true */
  initialResent?: boolean;
  /** Callback para reenviar el email */
  onResend?: () => Promise<void>;
  /** Callback para cerrar sesión */
  onLogout?: () => Promise<void>;
}

const VerifyEmail: React.FC<VerifyEmailProps> = ({
  initialResent = false,
  onResend,
  onLogout,
}) => {
  const navigate = useNavigate();
  const [resent, setResent] = useState(initialResent);
  const [loading, setLoading] = useState(false);

  const handleResend = async () => {
    if (!onResend) return;
    setLoading(true);
    try {
      await onResend();
      setResent(true);
    } catch {
      // manejar error si se desea
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = async () => {
    if (onLogout) await onLogout();
    navigate('/login');
  };

  return (
    <div className="min-h-screen bg-[var(--Blue1)] flex items-center justify-center px-4 text-white">
      <div className="w-full max-w-md bg-[#121c30] rounded-2xl shadow-xl p-8 space-y-6">
        <h2 className="text-2xl font-bold text-center">Verifica tu correo</h2>

        <div className="mb-4 text-sm text-gray-300">
          Gracias por registrarte. Antes de continuar, verifica tu dirección de correo
          haciendo clic en el enlace que te enviamos. Si no lo recibiste, con gusto te
          enviaremos otro.
        </div>

        {resent && (
          <div className="mb-4 font-medium text-sm text-green-400">
            ¡Se ha enviado un nuevo enlace de verificación a tu correo!
          </div>
        )}

        <div className="mt-4 flex items-center justify-between">
          <button
            onClick={handleResend}
            disabled={loading}
            className="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded-md transition disabled:opacity-50"
          >
            {loading ? 'Enviando...' : 'Reenviar correo de verificación'}
          </button>
          <button
            onClick={handleLogout}
            className="underline text-sm text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
          >
            Cerrar sesión
          </button>
        </div>
      </div>
    </div>
  );
};

export default VerifyEmail;
