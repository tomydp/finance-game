// src/components/auth/Login.tsx
import React, { useState, useMemo, useEffect } from 'react';
import { FaGoogle, FaFacebook } from 'react-icons/fa';
import { FiEye, FiEyeOff, FiArrowLeft } from 'react-icons/fi';
import { useLocation, useNavigate } from 'react-router-dom';
import { login as loginApi, resendVerification } from '../../services/authService';

const Login: React.FC = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const navState = location.state as any;

  const getStoredUser = () => {
    const raw = localStorage.getItem('user');
    if (!raw) return null;
    try {
      return JSON.parse(raw);
    } catch {
      return null;
    }
  };

  // ?verified=1 | 0 en la URL (cuando el usuario hizo clic en el mail)
  const verifiedParam = useMemo<'ok' | 'fail' | null>(() => {
    const sp = new URLSearchParams(location.search);
    const v = sp.get('verified');
    if (v === '1') return 'ok';
    if (v === '0') return 'fail';
    return null;
  }, [location.search]);

  // flags que vienen del Register / guard
  const fromRegister = Boolean(navState?.registered);
  const registeredEmail = navState?.email as string | undefined;
  const needVerify = Boolean(navState?.needVerify);
  const [forceNeedVerify, setForceNeedVerify] = useState(false);
  const [overrideEmail, setOverrideEmail] = useState<string | undefined>(undefined);
  const effectiveNeedVerify = needVerify || forceNeedVerify;
  const emailForBanner = overrideEmail ?? registeredEmail;
  const showResendBlock = fromRegister || verifiedParam === 'fail' || effectiveNeedVerify;

  // estado para reenvío de verificación
  const [resendMsg, setResendMsg] = useState<string | null>(null);
  const [resendOk, setResendOk] = useState<boolean | null>(null);
  const [resending, setResending] = useState(false);
  const [cooldown, setCooldown] = useState(0);

  useEffect(() => {
    if (cooldown <= 0) return;
    const timer = setInterval(() => {
      setCooldown((prev) => {
        if (prev <= 1) {
          clearInterval(timer);
          return 0;
        }
        return prev - 1;
      });
    }, 1000);
    return () => clearInterval(timer);
  }, [cooldown]);

  const handleResend = async () => {
    if (cooldown > 0) {
      return;
    }

    const targetEmail = token
      ? undefined
      : (emailForBanner ?? (email.trim() || undefined));

    if (!token && !targetEmail) {
      setResendOk(false);
      setResendMsg('Ingresá tu email para reenviar el correo.');
      return;
    }

    try {
      setResending(true);
      setResendMsg(null);
      setResendOk(null);
      const { data } = await resendVerification(targetEmail);
      setResendOk(true);
      setResendMsg(data?.message || 'Te reenviamos el correo de verificación.');
      setCooldown(30);
    } catch (e: any) {
      const status = e?.response?.status;
      if (status === 429) {
        setCooldown(30);
      }
      setResendOk(false);
      setResendMsg(e?.response?.data?.message || 'Error al reenviar verificación');
    } finally {
      setResending(false);
    }
  };

  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState(''); 
  const [password, setPassword] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const [fieldErrorEmail, setFieldErrorEmail] = useState<string | null>(null);
  const [fieldErrorPassword, setFieldErrorPassword] = useState<string | null>(null);
  const [serverErrors, setServerErrors] = useState<string[]>([]);

  const validate = () => {
    let ok = true;
    setFieldErrorEmail(null);
    setFieldErrorPassword(null);

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.trim()) {
      setFieldErrorEmail('El email es obligatorio.');
      ok = false;
    } else if (!emailRegex.test(email.trim())) {
      setFieldErrorEmail('Formato de email inválido.');
      ok = false;
    }

    if (!password) {
      setFieldErrorPassword('La contraseña es obligatoria.');
      ok = false;
    }

    return ok;
  };

  const submit = async (e: React.FormEvent) => {
    e.preventDefault();
    setServerErrors([]);
    if (!validate()) return;
  
    try {
      setSubmitting(true);
      const trimmedEmail = email.trim();
      const res = await loginApi({ email: trimmedEmail, password });

      const payload = res?.data ?? {};
      const verified = payload?.verified ?? payload?.email_verified ?? false;
      const userData = {
        ...payload,
        verified,
        email_verified: payload?.email_verified ?? verified,
      };

      // guardo sesión
      localStorage.setItem('user', JSON.stringify(userData));
      localStorage.setItem('isAuthenticated', 'true');

      // 👇 JUSTO ACÁ: si no está verificado, no navego
      if (!verified) {
        setServerErrors([
          'Tu email no está verificado. Revisá tu casilla o reenviá el correo.'
        ]);
        setForceNeedVerify(true);
        setOverrideEmail(trimmedEmail || registeredEmail);
        // opcional: mostrar el bloque de “Reenviar verificación”
        // podés forzar la UI como si vinieras del registro:
        // navigate('/login', { replace: true, state: { registered: true, email } });
        return;
      }
  
      // si está verificado, seguir normalmente
      const from = (location.state as any)?.from?.pathname || '/app/aprender';
      navigate(from, { replace: true });
    } catch (err: any) {
      const status = err?.response?.status;
      if (status === 401) {
        setServerErrors(['Credenciales inválidas']);
      } else if (status === 422 && Array.isArray(err?.response?.data?.errors)) {
        setServerErrors(err.response.data.errors);
      } else if (status === 403) {
        setServerErrors(['Email no verificado. Revisá tu casilla o solicitá reenvío.']);
      } else {
        setServerErrors(['Ocurrió un error inesperado. Intentá nuevamente.']);
      }
    } finally {
      setSubmitting(false);
    }
  };

  // chequeo token
  const storedUser = getStoredUser();
  const token = storedUser?.token ?? null;

  return (
    <div className="relative min-h-screen bg-[var(--Blue1)] flex items-center justify-center px-4">
      <button
        className="absolute top-4 left-4 text-white hover:text-gray-200 transition text-2xl"
        aria-label="Volver al landing"
        onClick={() => navigate('/')}
      >
        <FiArrowLeft />
      </button>

      <div className="w-full max-w-md bg-[#121c30] rounded-2xl shadow-xl p-8 space-y-6">
        {/* Logo en vez del círculo con F */}
        <div className="flex justify-center">
          <img
            src="/Logo.png"
            alt="FinanceGame"
            className="h-12 w-12"
          />
        </div>

        <div className="text-center space-y-2">
          <h2 className="text-2xl font-extrabold text-white">¡Bienvenido de vuelta!</h2>
          <p className="text-gray-400">Ingresá tus credenciales para continuar</p>

          {/* Banner si venís del registro */}
          {fromRegister && (
            <div className="mb-2 rounded bg-cyan-900/40 border border-cyan-700 text-cyan-200 p-3 text-sm">
              Te enviamos un correo de verificación{emailForBanner ? ` a ${emailForBanner}` : ''}. 
              Abrilo y hacé clic en el enlace para activar tu cuenta.
            </div>
          )}
          {effectiveNeedVerify && !fromRegister && verifiedParam !== 'ok' && (
            <div className="mb-2 rounded bg-cyan-900/40 border border-cyan-700 text-cyan-200 p-3 text-sm">
              Necesitás verificar tu correo antes de continuar.{emailForBanner ? ` Verificá ${emailForBanner}.` : ''}
            </div>
          )}

          {/* Banner según ?verified= */}
          {verifiedParam === 'ok' && (
            <div className="mb-2 rounded bg-green-900/40 border border-green-700 text-green-200 p-3 text-sm">
              ¡Email verificado! Ya podés iniciar sesión.
            </div>
          )}
          {verifiedParam === 'fail' && (
            <div className="mb-2 rounded bg-red-900/40 border border-red-700 text-red-200 p-3 text-sm">
              El enlace de verificación no es válido o expiró. Pedí uno nuevo.
            </div>
          )}

          {/* Errores del backend */}
          {serverErrors.length > 0 && (
            <div className="text-red-500 text-sm space-y-1">
              {serverErrors.map((error, index) => (
                <p key={index}>{error}</p>
              ))}
            </div>
          )}
        </div>

        {/* Bloque Reenviar verificación */}
        {showResendBlock && (
          <div className="space-y-2">
            <button
              type="button"
              onClick={handleResend}
              disabled={resending || cooldown > 0 || (!token && !emailForBanner && !email.trim())}
              className={`w-full text-sm rounded-md px-3 py-2 transition ${
                resending || cooldown > 0 || (!token && !emailForBanner && !email.trim())
                  ? 'bg-gray-800 text-gray-400 cursor-not-allowed'
                  : 'bg-gray-700 hover:bg-gray-600 text-white'
              }`}
              title={
                resending
                  ? 'Estamos reenviando el correo...'
                  : cooldown > 0
                    ? `Podés volver a intentarlo en ${cooldown}s`
                    : !token && !emailForBanner && !email.trim()
                      ? 'Ingresá tu correo para reenviar el email'
                      : ''
              }
            >
              {resending
                ? 'Reenviando...'
                : cooldown > 0
                  ? `Volver a intentar en ${cooldown}s`
                  : 'Reenviar verificación'}
            </button>
            {resendMsg && (
              <div
                className={`text-xs p-2 rounded ${
                  resendOk
                    ? 'bg-green-900/40 border border-green-700 text-green-200'
                    : 'bg-red-900/40 border border-red-700 text-red-200'
                }`}
              >
                {resendMsg}
              </div>
            )}
          </div>
        )}

        <form onSubmit={submit} className="space-y-4" noValidate>
          {/* Email */}
          <div>
            <label className="block text-sm text-gray-300 mb-1">Correo electrónico</label>
            <input
              type="email"
              name="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="tu@email.com"
              className={`w-full bg-[var(--Blue2)] border rounded-md px-4 py-2 text-gray-200 ${
                fieldErrorEmail ? 'border-red-500' : 'border-gray-700'
              }`}
              aria-invalid={!!fieldErrorEmail}
              aria-describedby={fieldErrorEmail ? 'err-email' : undefined}
            />
            {fieldErrorEmail && (
              <p id="err-email" className="text-red-500 text-xs mt-1">{fieldErrorEmail}</p>
            )}
          </div>

          {/* Contraseña */}
          <div className="relative">
            <label className="block text-sm text-gray-300 mb-1">Contraseña</label>
            <input
              type={showPassword ? 'text' : 'password'}
              name="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Tu contraseña"
              className={`w-full bg-[var(--Blue2)] border rounded-md px-4 py-2 pr-10 text-gray-200 ${
                fieldErrorPassword ? 'border-red-500' : 'border-gray-700'
              }`}
              aria-invalid={!!fieldErrorPassword}
              aria-describedby={fieldErrorPassword ? 'err-pass' : undefined}
            />
            <button
              type="button"
              onClick={() => setShowPassword((s) => !s)}
              className="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200"
              aria-label={showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'}
            >
              {showPassword ? <FiEyeOff /> : <FiEye />}
            </button>
            {fieldErrorPassword && (
              <p id="err-pass" className="text-red-500 text-xs mt-1">{fieldErrorPassword}</p>
            )}
          </div>

          <button
            type="submit"
            disabled={submitting}
            className={`w-full text-white font-semibold py-3 rounded-md transition ${
              submitting ? 'bg-cyan-600 opacity-80 cursor-not-allowed' : 'bg-cyan-500 hover:bg-cyan-600'
            }`}
          >
            {submitting ? 'Ingresando...' : 'INICIAR SESIÓN'}
          </button>
        </form>

        {/* Divider */}
        <div className="flex items-center text-gray-500 text-sm my-4">
          <div className="flex-grow h-px bg-gray-700" />
          <span className="px-3">O CONTINUÁ CON</span>
          <div className="flex-grow h-px bg-gray-700" />
        </div>

        {/* Social buttons (mock) */}
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

        <p className="text-center text-gray-400 text-sm mt-4">
          ¿Sos nuevo en Finance Game?{' '}
          <button
            onClick={() => navigate('/register')}
            className="text-cyan-400 hover:underline"
          >
            Registrate
          </button>
        </p>
      </div>
    </div>
  );
};

export default Login;
