import React, { useState } from 'react';
import { FaGoogle, FaFacebook } from 'react-icons/fa';
import { FiEye, FiEyeOff, FiArrowLeft } from 'react-icons/fi';
import { useNavigate } from 'react-router-dom';
import { register } from '../../services/authService';

const Register: React.FC = () => {
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [name, setName] = useState('');
  const [email, setEmail] = useState(''); 
  const [password, setPassword] = useState('');
  const [password_confirmation, setPasswordConfirmation] = useState('');
  const [errors, setErrors] = useState([]);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    setErrors([]);
    register({name:name, email:email, password:password, password_confirmation:password_confirmation}).then((res) => {
      if(res.data.errors) {
        setErrors(res.data.errors);
      } else {
        localStorage.setItem("user", JSON.stringify(res.data));
        localStorage.setItem("isAuthenticated", true);
        navigate('/');
      }
    });
  }

  return (
    <div className="relative min-h-screen bg-[var(--Blue1)] flex items-center justify-center px-4">
      <button
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
          {errors.length > 0 && (
            <div className="text-red-500">
              {errors.map((error: string, index: number) => (
                <p key={index}>{error}</p>
              ))}
            </div>
          )}
        </div>

        <form onSubmit={submit} className="space-y-4">
          {/* Nombre */}
          <div>
            <label className="block text-sm text-gray-300 mb-1">Nombre completo</label>
            <input
              type="text"
              name="name"
              value={name}
              onChange={(e) => setName(e.target.value)}
              placeholder="Tu nombre"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200"
            />
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
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 text-gray-200"
            />
          </div>

          {/* Contraseña */}
          <div className="relative">
            <label className="block text-sm text-gray-300 mb-1">Contraseña</label>
            <input
              type="password"
              name="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Tu contraseña"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 pr-10 text-gray-200"
            />
            <button
              type="button"
              onClick={() => setShowPassword((s) => !s)}
              className="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200"
            >
              {showPassword ? <FiEyeOff /> : <FiEye />}
            </button>
          </div>

          {/* Confirmación de contraseña */}
          <div className="relative">
            <label className="block text-sm text-gray-300 mb-1">Confirmar contraseña</label>
            <input
              type="password"
              name="confirmPassword"
              value={password_confirmation}
              onChange={(e) => setPasswordConfirmation(e.target.value)}
              placeholder="Repetí tu contraseña"
              className="w-full bg-[var(--Blue2)] border border-gray-700 rounded-md px-4 py-2 pr-10 text-gray-200"
            />
            <button
              type="button"
              onClick={() => setShowPassword((s) => !s)}
              className="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-200"
            >
              {showPassword ? <FiEyeOff /> : <FiEye />}
            </button>
          </div>

          <button
            type="submit"
            className="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-3 rounded-md transition"
          >
            CREAR CUENTA
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
