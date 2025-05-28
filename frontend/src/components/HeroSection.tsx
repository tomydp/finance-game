// src/components/HeroSection.tsx
import React from 'react';

const HeroSection: React.FC = () => {
  return (
    <section className="bg-[#0d1321] text-white min-h-screen px-6 md:px-16 flex items-center justify-center">
      {/* Wrapper centrado con ancho máximo */}
      <div className="w-full max-w-7xl flex flex-col md:flex-row items-center justify-between gap-12">
        
        {/* Bloque visual */}
        <div className="w-full md:w-1/2 flex justify-center">
          <div className="relative w-[300px] h-[220px] bg-[#121c30] rounded-3xl shadow-lg p-4">
            {/* Icono central */}
            <div className="absolute top-[40%] left-[40%] transform -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-full bg-cyan-500 flex items-center justify-center text-white text-3xl">
              🐷
            </div>

            {/* Íconos secundarios */}
            <div className="absolute top-4 left-4 w-10 h-10 rounded-xl bg-yellow-400 flex items-center justify-center text-white text-lg shadow-md">
              📊
            </div>
            <div className="absolute bottom-4 left-6 w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center text-white text-lg shadow-md">
              🎯
            </div>
            <div className="absolute top-2 right-6 w-10 h-10 rounded-xl bg-green-400 flex items-center justify-center text-white text-lg shadow-md">
              📈
            </div>
            <div className="absolute bottom-6 right-4 w-10 h-10 rounded-xl bg-purple-500 flex items-center justify-center text-white text-lg shadow-md">
              🎓
            </div>

            {/* Chip de nivel completado */}
            <div className="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-cyan-500 text-white text-xs px-3 py-1 rounded-full shadow-md">
              ✨ ¡Nivel completado!
            </div>

            {/* Chip de XP */}
            <div className="absolute bottom-[-12px] right-1/2 transform translate-x-1/2 bg-gray-700 text-white text-xs px-3 py-1 rounded-full shadow-md">
              +50 XP
            </div>
          </div>
        </div>

        {/* Texto descriptivo y CTA */}
        <div className="w-full md:w-1/2">
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

          <p className="mt-6 text-gray-300 text-lg">
            Domina tus finanzas personales con lecciones interactivas, desafíos
            divertidos y un enfoque gamificado que hace el aprendizaje adictivo.
          </p>

          <button className="mt-6 px-6 py-3 bg-cyan-500 text-white font-semibold rounded hover:bg-cyan-600 transition">
            EMPIEZA AHORA YA TENGO UNA CUENTA
          </button>

          {/* Métricas de confianza */}
          <div className="mt-8 flex gap-10 text-center text-cyan-300 text-sm md:text-base">
            <div>
              <p className="text-2xl font-bold text-white">10K+</p>
              <p>Estudiantes</p>
            </div>
            <div>
              <p className="text-2xl font-bold text-white">50+</p>
              <p>Lecciones</p>
            </div>
            <div>
              <p className="text-2xl font-bold text-white">4.9★</p>
              <p>Calificación</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
