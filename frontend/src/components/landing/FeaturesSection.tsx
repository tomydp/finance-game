// src/components/FeaturesSection.tsx
import React from 'react';
import {
  FaGamepad,
  FaBrain,
  FaBook,
  FaLock,
  FaUsers,
  FaCertificate,
} from 'react-icons/fa';

const features = [
  {
    title: 'Gamificación',
    description:
      'Gana XP, desbloquea logros y compite en ligas mientras aprendes conceptos financieros esenciales.',
    icon: FaGamepad,
  },
  {
    title: 'Aprendizaje Adaptativo',
    description:
      'Nuestro algoritmo se adapta a tu ritmo y estilo de aprendizaje para maximizar tu progreso.',
    icon: FaBrain,
  },
  {
    title: 'Contenido Práctico',
    description:
      'Aprende con casos reales y simulaciones que puedes aplicar inmediatamente en tu vida financiera.',
    icon: FaBook,
  },
  {
    title: '100% Seguro',
    description:
      'Tus datos están protegidos con encriptación de nivel bancario. Nunca compartimos tu información.',
    icon: FaLock,
  },
  {
    title: 'Comunidad Activa',
    description:
      'Únete a miles de estudiantes que comparten tips, experiencias y se motivan mutuamente.',
    icon: FaUsers,
  },
  {
    title: 'Certificaciones',
    description:
      'Obtén certificados reconocidos que validen tus conocimientos financieros ante empleadores.',
    icon: FaCertificate,
  },
];

const FeaturesSection: React.FC = () => (
  <section className=" bg-[var(--Blue2)]  text-white py-16 px-6 md:px-16">
    <div className="max-w-6xl mx-auto">
      {/* Título */}
      <h2 className="text-center text-3xl md:text-4xl font-extrabold">
        ¿Por qué elegir{' '}
        <span className="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
          FinanzApp
        </span>
        ?
      </h2>
      <p className="mt-2 text-center text-gray-400">
        Nuestra metodología gamificada hace que aprender finanzas sea tan adictivo
        como tu juego favorito
      </p>

      {/* Grid de tarjetas */}
      <div className="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        {features.map(({ title, description, icon: Icon }) => (
          <div
            key={title}
            className="group bg-[#121c30] border border-gray-700 rounded-2xl p-6 hover:border-cyan-500 transition"
          >
            <div className="w-12 h-12 mb-4 flex items-center justify-center bg-gray-800 rounded-full group-hover:bg-cyan-500 transition">
              <Icon className="text-white text-xl" />
            </div>
            <h3 className="text-xl font-semibold mb-2">{title}</h3>
            <p className="text-gray-300 text-sm">{description}</p>
          </div>
        ))}
      </div>
    </div>
  </section>
);

export default FeaturesSection;
