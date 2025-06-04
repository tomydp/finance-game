import React from 'react';
import { FaPiggyBank, FaChartLine, FaDollarSign, FaStar, FaBolt, FaMedal } from 'react-icons/fa';
import { FiTrendingUp } from 'react-icons/fi';
import { GoDiamond } from 'react-icons/go';

const Aprender: React.FC = () => {
  const modulos = [
    {
      titulo: 'Ahorro básico',
      lecciones: '4/5 lecciones',
      estado: 'activo',
      icono: <FaPiggyBank className="text-cyan-400" />,
    },
    {
      titulo: 'Presupuesto personal',
      lecciones: '1/5 lecciones',
      estado: 'activo',
      icono: <FaDollarSign className="text-cyan-400" />,
    },
    {
      titulo: 'Control de deudas',
      lecciones: 'Bloqueado',
      estado: 'bloqueado',
      icono: <FaChartLine className="text-gray-500" />,
    },
  ];

  const inversiones = [
    {
      titulo: 'Conceptos básicos',
      estado: 'bloqueado',
      icono: <FaChartLine className="text-gray-500" />,
    },
    {
      titulo: 'Mercado de valores',
      estado: 'bloqueado',
      icono: <FiTrendingUp className="text-gray-500" />,
    },
    {
      titulo: 'Diversificación',
      estado: 'bloqueado',
      icono: <FaMedal className="text-gray-500" />,
    },
  ];

  const logros = [
    { nombre: 'Primera lección', icono: <FaStar className="text-cyan-400" /> },
    { nombre: 'Racha de 3 días', icono: <FaBolt className="text-cyan-400" /> },
    { nombre: 'Ahorrador', icono: <FaMedal className="text-gray-500" /> },
    { nombre: 'Inversor', icono: <FiTrendingUp className="text-gray-500" /> },
    { nombre: 'Experto', icono: <GoDiamond className="text-gray-500" /> },
  ];

  return (
    <div className="p-6 space-y-10 text-white">
      <div className="flex justify-between items-center">
        <h1 className="text-2xl font-bold">Aprender</h1>
        <div className="flex gap-4 text-sm">
          <span>❤️ 5</span>
          <span>🧠 0</span>
          <span>💎 500</span>
        </div>
      </div>

      {/* Fundamentos financieros */}
      <section>
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Fundamentos financieros</h2>
          <button className="text-cyan-400 text-sm hover:underline">Ver todo</button>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {modulos.map((m) => (
            <div key={m.titulo} className={`rounded-lg p-4 ${m.estado === 'bloqueado' ? 'bg-[#1a1f2e] opacity-60' : 'bg-[#121c30]'}`}>
              <div className="flex items-center gap-3 mb-2">
                <div className="text-xl">{m.icono}</div>
                <div>
                  <h3 className="font-bold">{m.titulo}</h3>
                  <p className="text-sm text-gray-400">{m.lecciones}</p>
                </div>
              </div>
              <div className="w-full h-2 bg-gray-700 rounded overflow-hidden mb-3">
                <div className="h-full bg-cyan-500 w-2/3"></div>
              </div>
              <button className={`w-full py-2 rounded font-semibold text-sm ${m.estado === 'bloqueado' ? 'bg-slate-700 cursor-not-allowed' : 'bg-cyan-500 hover:bg-cyan-600'}`}>
                {m.estado === 'bloqueado' ? 'BLOQUEADO' : 'CONTINUAR'}
              </button>
            </div>
          ))}
        </div>
      </section>

      {/* Inversiones */}
      <section>
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Inversiones</h2>
          <button className="text-cyan-400 text-sm hover:underline">Ver todo</button>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {inversiones.map((m) => (
            <div key={m.titulo} className="rounded-lg p-4 bg-[#1a1f2e] opacity-60">
              <div className="flex items-center gap-3 mb-2">
                <div className="text-xl">{m.icono}</div>
                <div>
                  <h3 className="font-bold">{m.titulo}</h3>
                  <p className="text-sm text-gray-400">Bloqueado</p>
                </div>
              </div>
              <div className="w-full h-2 bg-gray-700 rounded overflow-hidden mb-3">
                <div className="h-full bg-cyan-500 w-1/5"></div>
              </div>
              <button className="w-full py-2 rounded font-semibold text-sm bg-slate-700 cursor-not-allowed">
                BLOQUEADO
              </button>
            </div>
          ))}
        </div>
      </section>

      {/* Logros recientes */}
      <section>
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Logros recientes</h2>
          <button className="text-cyan-400 text-sm hover:underline">Ver todos</button>
        </div>
        <div className="flex flex-wrap gap-4">
          {logros.map((l) => (
            <div key={l.nombre} className="w-20 flex flex-col items-center">
              <div className="w-12 h-12 rounded-full bg-[#121c30] flex items-center justify-center text-xl">
                {l.icono}
              </div>
              <p className="text-center text-sm mt-2">{l.nombre}</p>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

export default Aprender;
