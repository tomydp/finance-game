// src/components/Desafios.tsx
import React, { useState } from 'react';
import {
  FaBolt,
  FaStar,
  FaRegStar,
  FaCircle,
  FaCheckCircle,
} from 'react-icons/fa';
import type { IconType } from 'react-icons'; 

interface Challenge {
  id: number;
  title: string;
  description: string;
  icon: IconType;
  progress: number;        // Progreso actual
  goal: number;            // Meta para completar
  reward: number;          // Recompensa (XP, diamantes, etc.)
}

const dailyChallenges: Challenge[] = [
  {
    id: 1,
    title: 'Gana 10 XP',
    description: 'Completa lecciones para ganar XP',
    icon: FaBolt,
    progress: 0,
    goal: 10,
    reward: 5,
  },
  {
    id: 2,
    title: 'Completa 3 lecciones',
    description: 'Termina 3 lecciones hoy',
    icon: FaStar,
    progress: 0,
    goal: 3,
    reward: 10,
  },
  {
    id: 3,
    title: 'Mantén tu racha',
    description: 'Completa al menos una lección',
    icon: FaRegStar,
    progress: 0,
    goal: 1,
    reward: 5,
  },
  {
    id: 4,
    title: 'Responde perfectamente',
    description: 'Completa una lección sin errores',
    icon: FaCheckCircle,
    progress: 0,
    goal: 1,
    reward: 15,
  },
];

const Desafios: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'Diarios' | 'Semanales' | 'Logros'>(
    'Diarios'
  );

  // Calcula porcentaje de progreso para la barra
  const getProgressWidth = (challenge: Challenge) => {
    const pct = Math.min(
      Math.round((challenge.progress / challenge.goal) * 100),
      100
    );
    return `${pct}%`;
  };

  return (
    <div className="space-y-8">
      {/* ----- HEADER PRINCIPAL ----- */}
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold flex items-center space-x-2">
          <FaCircle className="text-cyan-400 w-5 h-5" />
          <span>Desafíos</span>
        </h1>
        {/* Indicadores de usuario (Corazón, Moneda, Diamante) */}
        <div className="flex items-center space-x-4">
          {/* Corazón */}
          <div className="flex items-center space-x-1">
            <FaBolt className="text-red-500 w-5 h-5" />
            <span className="text-sm font-medium">5</span>
          </div>
          {/* Comentarios */}
          <div className="flex items-center space-x-1">
            <FaRegStar className="text-yellow-400 w-5 h-5" />
            <span className="text-sm font-medium">0</span>
          </div>
          {/* Diamantes */}
          <div className="flex items-center space-x-1">
            <FaCheckCircle className="text-cyan-400 w-5 h-5" />
            <span className="text-sm font-medium">500</span>
          </div>
        </div>
      </div>

      {/* ----- BANNER RACHAS ----- */}
      <div className="bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-lg p-4 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
        {/* Ícono y texto */}
        <div className="flex items-center space-x-4">
          <div className="w-12 h-12 rounded-full bg-yellow-700 flex items-center justify-center text-white text-xl">
            <FaBolt />
          </div>
          <div>
            <h2 className="text-lg font-semibold text-white">¡Racha de 3 días!</h2>
            <p className="text-yellow-100">
              Completa una lección hoy para mantener tu racha
            </p>
          </div>
        </div>
        {/* Botón de acción */}
        <button className="px-6 py-2 bg-yellow-400 hover:bg-yellow-500 text-sm font-semibold rounded transition">
          CONTINUAR RACHA
        </button>
      </div>

      {/* ----- TABS: Diarios | Semanales | Logros ----- */}
      <div className="flex space-x-4 bg-[#1f2937] rounded-lg overflow-hidden">
        {(['Diarios', 'Semanales', 'Logros'] as const).map((tab) => (
          <button
            key={tab}
            onClick={() => setActiveTab(tab)}
            className={`flex-1 text-center py-2 text-sm font-medium transition ${
              activeTab === tab
                ? 'bg-[#0f172a] text-white'
                : 'text-gray-400 hover:bg-[#111827] hover:text-white'
            }`}
          >
            {tab}
          </button>
        ))}
      </div>

      {/* ----- CONTENIDO DE LA PESTAÑA ACTIVA ----- */}
      {activeTab === 'Diarios' && (
        <div className="space-y-6">
          <div className="flex justify-between items-center">
            <h3 className="text-lg font-semibold text-gray-200">Desafíos del día</h3>
            <span className="text-sm text-gray-400">
              Se reinician en: <strong>14h 35m</strong>
            </span>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {dailyChallenges.map((challenge) => (
              <div
                key={challenge.id}
                className="bg-[#121c30] border border-gray-700 rounded-lg p-4 space-y-3"
              >
                {/* Título e ícono */}
                <div className="flex items-center justify-between">
                  <div className="flex items-center space-x-2">
                    <div className="w-10 h-10 rounded-full bg-[#1f2937] flex items-center justify-center text-cyan-400 text-xl">
                      <challenge.icon />
                    </div>
                    <h4 className="text-base font-semibold">{challenge.title}</h4>
                  </div>
                  <span className="text-sm text-gray-400">
                    Progreso {challenge.progress}/{challenge.goal}
                  </span>
                </div>

                {/* Descripción */}
                <p className="text-sm text-gray-300">{challenge.description}</p>

                {/* Barra de progreso */}
                <div className="w-full bg-[#0f172a] rounded-full h-1 overflow-hidden">
                  <div
                    className="bg-cyan-500 h-1"
                    style={{ width: getProgressWidth(challenge) }}
                  />
                </div>

                {/* Recompensa y botón */}
                <div className="flex items-center justify-between">
                  <div className="flex items-center space-x-1 text-gray-200 text-sm">
                    <FaCircle className="text-cyan-400 w-4 h-4" />
                    <span>+{challenge.reward}</span>
                  </div>
                  <button className="px-4 py-1 bg-cyan-500 hover:bg-cyan-600 text-sm font-medium rounded transition">
                    Completar
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {activeTab === 'Semanales' && (
        <div className="text-center text-gray-400 py-8">
          <p className="italic">No hay desafíos semanales disponibles aún.</p>
        </div>
      )}

      {activeTab === 'Logros' && (
        <div className="text-center text-gray-400 py-8">
          <p className="italic">No hay logros desbloqueados aún.</p>
        </div>
      )}
    </div>
  );
};

export default Desafios;
