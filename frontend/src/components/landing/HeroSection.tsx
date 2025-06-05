// src/components/landing/HeroSection.tsx
import React from 'react';
import { motion } from 'framer-motion';
import { useNavigate } from 'react-router-dom';

const HeroSection: React.FC = () => {
  const navigate = useNavigate();

  // Lleva al form de registro
  const goToRegister = () =>
    navigate('/login', { state: { mode: 'register' } });

  // Lleva al form de login
  const goToLogin = () =>
    navigate('/login', { state: { mode: 'login' } });

  return (
    <section className="bg-[var(--Blue1)] text-white min-h-screen flex items-center justify-center px-4 md:px-16">
      <div className="w-full max-w-6xl flex flex-col md:flex-row items-center justify-between gap-12">
        {/* Bloque visual animado */}
        <div className="w-full md:w-1/2 flex justify-center">
          <div className="relative w-[500px] h-[320px] bg-[#121c30] rounded-3xl shadow-lg p-8">
            {/* ✨ Nivel completado */}
            <motion.div
              className="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-cyan-500 text-white text-sm px-5 py-2 rounded-full shadow-md"
              initial={{ opacity: 0, y: -10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.4 }}
            >
              ✨ ¡Nivel completado!
            </motion.div>

            {/* Icono central */}
            <motion.div
              className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full bg-cyan-500 flex items-center justify-center text-white text-4xl shadow-inner"
              animate={{ x: [0, -12, 0], y: [0, -12, 0] }}
              transition={{
                x: { repeat: Infinity, duration: 3, repeatType: 'reverse', ease: 'easeInOut' },
                y: { repeat: Infinity, duration: 3, repeatType: 'reverse', ease: 'easeInOut' },
              }}
            >
              🐷
            </motion.div>

            {/* Íconos secundarios */}
            <motion.div
              className="absolute top-8 left-8 w-16 h-16 rounded-xl bg-yellow-400 flex items-center justify-center text-white text-2xl shadow-md"
              animate={{ x: [0, 20, 0] }}
              transition={{ x: { repeat: Infinity, duration: 2.5, repeatType: 'reverse', ease: 'easeInOut' } }}
            >
              📊
            </motion.div>
            <motion.div
              className="absolute bottom-8 left-12 w-16 h-16 rounded-xl bg-blue-500 flex items-center justify-center text-white text-2xl shadow-md"
              animate={{ y: [0, 16, 0] }}
              transition={{ y: { repeat: Infinity, duration: 2.2, repeatType: 'reverse', ease: 'easeInOut' } }}
            >
              🎯
            </motion.div>
            <motion.div
              className="absolute top-12 right-12 w-16 h-16 rounded-xl bg-green-400 flex items-center justify-center text-white text-2xl shadow-md"
              animate={{ x: [0, -16, 0], y: [0, 12, 0] }}
              transition={{
                x: { repeat: Infinity, duration: 3.2, repeatType: 'reverse', ease: 'easeInOut' },
                y: { repeat: Infinity, duration: 2.8, repeatType: 'reverse', ease: 'easeInOut' },
              }}
            >
              📈
            </motion.div>
            <motion.div
              className="absolute bottom-12 right-8 w-16 h-16 rounded-xl bg-purple-500 flex items-center justify-center text-white text-2xl shadow-md"
              animate={{ x: [0, 16, 0], y: [0, -12, 0] }}
              transition={{
                x: { repeat: Infinity, duration: 2.7, repeatType: 'reverse', ease: 'easeInOut' },
                y: { repeat: Infinity, duration: 3.1, repeatType: 'reverse', ease: 'easeInOut' },
              }}
            >
              🎓
            </motion.div>

            {/* ✨ +50 XP */}
            <motion.div
              className="absolute -bottom-6 right-1/2 transform translate-x-1/2 bg-gray-700 text-white text-sm px-5 py-2 rounded-full shadow-md"
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.3, duration: 0.4 }}
            >
              +50 XP
            </motion.div>
          </div>
        </div>

        {/* Texto descriptivo + dos botones */}
        <div className="w-full md:w-1/2 space-y-6">
          <h1 className="text-4xl md:text-5xl font-extrabold leading-tight">
            ¡La forma{' '}
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
              divertida
            </span>
            ,{' '}
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-500">
              efectiva
            </span>{' '}
            y{' '}
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-cyan-400">
              gratis
            </span>{' '}
            de aprender{' '}
            <span className="text-cyan-400">finanzas</span>!
          </h1>
          <p className="text-gray-300 text-lg">
            Domina tus finanzas personales con lecciones interactivas, desafíos
            divertidos y un enfoque gamificado que hace el aprendizaje adictivo.
          </p>

          <div className="flex flex-col sm:flex-row gap-4">
            <button
              onClick={goToRegister}
              className="flex-1 px-8 py-4 bg-cyan-500 hover:bg-cyan-600 transition text-white font-semibold rounded"
            >
              EMPIEZA AHORA
            </button>
            <button
              onClick={goToLogin}
              className="flex-1 px-8 py-4 border-2 border-cyan-500 text-cyan-500 hover:bg-cyan-500 hover:text-white transition font-semibold rounded"
            >
              YA TENGO UNA CUENTA
            </button>
          </div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
