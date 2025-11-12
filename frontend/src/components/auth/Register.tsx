// src/components/auth/Register.tsx
import React, { useState } from 'react';
import { FaGoogle, FaFacebook } from 'react-icons/fa';
import { FiEye, FiEyeOff, FiArrowLeft } from 'react-icons/fi';
import { useNavigate } from 'react-router-dom';
import { register as registerApi } from '../../services/authService';

type FieldErrors = {
  name?: string;
  email?: string;
  password?: string;
  password_confirmation?: string;
};

const Register: React.FC = () => {
  const navigate = useNavigate();

  // form
  const [name, setName] = useState('');
  const [email, setEmail] = useState(''); 
  const [password, setPassword] = useState('');
  const [password_confirmation, setPasswordConfirmation] = useState('');

  // ui
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  // errores
  const [fieldErrors, setFieldErrors] = useState<FieldErrors>({});
  const [serverErrors, setServerErrors] = useState<string[]>([]);

  // ---- helpers de validación ----
  const isValidEmail = (val: string) =>
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim());

  const isStrongPassword = (val: string) => {
    const lengthOK = val.length >= 8;
    const upperOK  = /[A-ZÁÉÍÓÚÑ]/.test(val);
    const lowerOK  = /[a-záéíóúñ]/.test(val);
    const numOK    = /\d/.test(val);
    return lengthOK && upperOK && lowerOK && numOK;
  };

  const validate = (): FieldErrors => {
    const errors: FieldErrors = {};
    const _name = name.trim();

    if (!_name) {
      errors.name = 'El nombre es obligatorio.';
    } else if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(_name)) {
      errors.name = 'Usa solo letras y espacios.';
    } else if (_name.length > 255) {
      errors.name = 'Máximo 255 caracteres.';
    }

    if (!email.trim()) {
      errors.email = 'El email es obligatorio.';
    } else if (!isValidEmail(email)) {
      errors.email = 'Formato de email inválido.';
    }

    if (!password) {
      errors.password = 'La contraseña es obligatoria.';
    } else if (!isStrongPassword(password)) {
      errors.password =
        'Debe tener al menos 8 caracteres, mayúscula, minúscula y número.';
    }

    if (!password_confirmation) {
      errors.password_confirmation = 'Repetí tu contraseña.';
    } else if (password !== password_confirmation) {
      errors.password_confirmation = 'Las contraseñas no coinciden.';
    }

    return errors;
  };

  // ---- submit ----
  const submit = async (e: React.FormEvent) => {
    e.preventDefault();
    setServerErrors([]);
    const errs = validate();
    setFieldErrors(errs);

    if (Object.keys(errs).length > 0) return;

    try {
      setSubmitting(true);
      await registerApi({
        name: name.trim(),
        email: email.trim(),
        password,
        password_confirmation,
      });

      navigate('/login', { 
        replace: true,
        state: {registered: true, email}
      });
    } catch (err: any) {
      // back puede devolver 422 con { errors: string[] }
      const status = err?.response?.status;
      if (status === 422 && Array.isArray(err?.response?.data?.errors)) {
        setServerErrors(err.response.data.errors);
      } else {
        setServerErrors(['Ocurrió un error inesperado. Intentá nuevamente.']);
      }
    } finally {
      setSubmitting(false);
    }
  };

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
            className="h-45 w-45"
          />
        </div>

        <div className="text-center space-y-1">
          <h2 className="text-2xl font-extrabold text-white">Crear cuenta</h2>
          <p className="text-gray-400">Empieza a dominar tus finanzas</p>

          {/* Errores del backend (422) */}
          {serverErrors.length > 0 && (
            <div className="text-red-500 text-sm space-y-1 mt-2">
              {serverErrors.map((error, i) => (
                <p key={i}>{error}</p>
              ))}
            </div>
          )}
        </div>

        <form onSubmit={submit} className="space-y-4" noValidate>
          {/* Nombre */}
          <div>
            <label className="block text-sm text-gray-300 mb-1">Nombre completo</label>
            <input
              type="text"
              name="name"
              value={name}
              onChange={(e) => setName(e.target.value)}
              placeholder="Tu nombre"
              className={`w-full bg-[var(--Blue2)] border rounded-md px-4 py-2 text-gray-200 ${
                fieldErrors.name ? 'border-red-500' : 'border-gray-700'
              }`}
              aria-invalid={!!fieldErrors.name}
              aria-describedby={fieldErrors.name ? 'err-name' : undefined}
            />
            {fieldErrors.name && (
              <p id="err-name" className="text-red-500 text-xs mt-1">{fieldErrors.name}</p>
            )}
          </div>

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
                fieldErrors.email ? 'border-red-500' : 'border-gray-700'
              }`}
              aria-invalid={!!fieldErrors.email}
              aria-describedby={fieldErrors.email ? 'err-email' : undefined}
            />
            {fieldErrors.email && (
              <p id="err-email" className="text-red-500 text-xs mt-1">{fieldErrors.email}</p>
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
    className={`w-full bg-[var(--Blue2)] border rounded-md px-4 py-2 pr-10 text-gray-200 transition-all duration-150 ${
      fieldErrors.password ? 'border-red-500' : 'border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50'
    }`}
    aria-invalid={!!fieldErrors.password}
    aria-describedby={fieldErrors.password ? 'err-pass' : undefined}
  />

  <button
    type="button"
    onClick={() => setShowPassword(!showPassword)}
    className="absolute right-3 top-[62%] -translate-y-1/2 flex h-5 w-5 items-center justify-center leading-none text-gray-500 hover:text-gray-300 transition"
    aria-label={showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'}
  >
    {showPassword ? <FiEyeOff size={50} /> : <FiEye size={50} />}
  </button>

  {/* Espaciado fijo para evitar que salte al aparecer la pista o error */}
  <div className="h-2">
    {fieldErrors.password ? (
      <p id="err-pass" className="text-red-500 text-xs">{fieldErrors.password}</p>
    ) : password.length > 0 && !isStrongPassword(password) ? (
      <p className="text-xs text-gray-400">Requiere: 8+ caracteres, mayúscula, minúscula y número.</p>
    ) : null}
  </div>
</div>


{/* Confirmar contraseña */}
<div className="relative">
  <label className="block text-sm text-gray-300 mb-1">Confirmar contraseña</label>
  <input
    type={showConfirm ? 'text' : 'password'}
    name="password_confirmation"
    value={password_confirmation}
    onChange={(e) => setPasswordConfirmation(e.target.value)}
    placeholder="Repetí tu contraseña"
    className={`w-full bg-[var(--Blue2)] border rounded-md px-4 py-2 pr-10 text-gray-200 ${
      fieldErrors.password_confirmation ? 'border-red-500' : 'border-gray-700'
    } focus:outline-none focus:ring-2 focus:ring-cyan-500`}
    aria-invalid={!!fieldErrors.password_confirmation}
    aria-describedby={fieldErrors.password_confirmation ? 'err-passc' : undefined}
  />

 <button
  type="button"
  onClick={() => setShowConfirm(!showConfirm)}
  className="absolute right-3 top-[68%] -translate-y-1/2 flex h-5 w-5 items-center justify-center leading-none text-gray-500 hover:text-gray-300 transition"
  aria-label={showConfirm ? 'Ocultar confirmación' : 'Mostrar confirmación'}
>
  {showConfirm ? <FiEyeOff size={50} /> : <FiEye size={50} />}
</button>



  {fieldErrors.password_confirmation && (
    <p id="err-passc" className="text-red-500 text-xs mt-1">{fieldErrors.password_confirmation}</p>
  )}
</div>


          <button
            type="submit"
            disabled={submitting}
            className={`w-full text-white font-semibold py-3 rounded-md transition ${
              submitting ? 'bg-cyan-600 opacity-80 cursor-not-allowed' : 'bg-cyan-500 hover:bg-cyan-600'
            }`}
          >
            {submitting ? 'Creando...' : 'CREAR CUENTA'}
          </button>
        </form>

        {/* Divider */}
        <div className="flex items-center text-gray-500 text-sm my-4">
          <div className="flex-grow h-px bg-gray-700" />
          <span className="px-3">O CONTINÚA CON</span>
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
