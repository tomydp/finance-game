// src/components/Aprender.tsx
import React, { useState, useEffect } from 'react';
import {
  FaPiggyBank,
  FaDollarSign,
  FaChartLine,
  FaUniversity,
  FaBuilding,
  FaCoins,
  FaStar,
  FaBolt,
  FaMedal,
} from 'react-icons/fa';
import { FiTrendingUp } from 'react-icons/fi';
import { GoDiamond } from 'react-icons/go';

interface Module {
  id: number;
  titulo: string;
  totalLecciones: number;
  completadas: number;
  estado: 'activo' | 'bloqueado' | 'completo';
  icono: React.ReactNode;
}

const Aprender: React.FC = () => {
  // 1. Estados para Fundamentos e Inversiones
  const [fundamentos, setFundamentos] = useState<Module[]>([]);
  const [inversiones, setInversiones] = useState<Module[]>([]);
  const [showAllFundamentos, setShowAllFundamentos] = useState(false);

  // 2. Carga inicial: Solo el primer Fundamento (id = 1) arranca 'activo'; el resto 'bloqueado'
  //    Todas las Inversiones arrancan 'bloqueado'
  useEffect(() => {
    const inicialFundamentos: Module[] = [
      {
        id: 1,
        titulo: 'Ahorro básico',
        totalLecciones: 5,
        completadas: 1,
        estado: 'activo',
        icono: <FaPiggyBank className="text-cyan-400" />,
      },
      {
        id: 2,
        titulo: 'Presupuesto personal',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FaDollarSign className="text-gray-500" />,
      },
      {
        id: 3,
        titulo: 'Control de deudas',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FaChartLine className="text-gray-500" />,
      },
      {
        id: 4,
        titulo: 'Inversiones básicas',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FaUniversity className="text-gray-500" />,
      },
      {
        id: 5,
        titulo: 'Mercado de valores',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FiTrendingUp className="text-gray-500" />,
      },
      {
        id: 6,
        titulo: 'Diversificación',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FaMedal className="text-gray-500" />,
      },
    ];

    const inicialInversiones: Module[] = [
      {
        id: 101,
        titulo: 'Conceptos avanzados',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FaBuilding className="text-gray-500" />,
      },
      {
        id: 102,
        titulo: 'Análisis de acciones',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FaCoins className="text-gray-500" />,
      },
      {
        id: 103,
        titulo: 'Renta fija y variable',
        totalLecciones: 5,
        completadas: 1,
        estado: 'bloqueado',
        icono: <FaPiggyBank className="text-gray-500" />,
      },
    ];

    setFundamentos(inicialFundamentos);
    setInversiones(inicialInversiones);
  }, []);

  // 3. Cuando el usuario pulsa "CONTINUAR" en un Fundamento:
  //    - Avanza la lección (completadas + 1)
  //    - Si llega a totalLecciones, marca ese módulo como 'completo'
  //    - Desbloquea el siguiente Fundamento (índice + 1)
  //    - Si era el último Fundamento, desbloquea todas las Inversiones
  const handleContinueFundamento = (moduloId: number) => {
    setFundamentos(prev =>
      prev.map(m => {
        if (m.id !== moduloId) return m;
        if (m.estado === 'activo' && m.completadas < m.totalLecciones) {
          const nuevasCompletadas = m.completadas + 1;
          const nuevoEstado =
            nuevasCompletadas >= m.totalLecciones ? 'completo' : 'activo';
          return {
            ...m,
            completadas: nuevasCompletadas,
            estado: nuevoEstado,
          };
        }
        return m;
      })
    );

    // Después de actualizar el módulo clicado, revisamos si acaba de completarse
    setTimeout(() => {
      setFundamentos(prevFund => {
        // 1) Encontrar índice del módulo completado
        const idxActual = prevFund.findIndex(m => m.id === moduloId);

        if (
          idxActual !== -1 &&
          prevFund[idxActual].completadas >= prevFund[idxActual].totalLecciones
        ) {
          // 2) Si existe siguiente Fundamento (índice + 1), desbloquearlo
          const idxSiguiente = idxActual + 1;
          if (idxSiguiente < prevFund.length) {
            if (prevFund[idxSiguiente].estado === 'bloqueado') {
              const actualizado = [...prevFund];
              // Cambiamos estado a 'activo' y actualizamos el icono a cian
              actualizado[idxSiguiente] = {
                ...actualizado[idxSiguiente],
                estado: 'activo',
                icono: React.cloneElement(
                  actualizado[idxSiguiente]
                    .icono as React.ReactElement<any, any>,
                  { className: 'text-cyan-400' }
                ),
              };
              return actualizado;
            }
          } else {
            // 3) Si completamos el último Fundamento, desbloquear todas las Inversiones
            setInversiones(prevInv =>
              prevInv.map(inv => ({
                ...inv,
                estado: 'activo',
                icono: React.cloneElement(
                  inv.icono as React.ReactElement<any, any>,
                  { className: 'text-cyan-400' }
                ),
              }))
            );
          }
        }
        return prevFund;
      });
    }, 0);
  };

  // 4. Avanza lecciones en Inversiones: si está 'activo' incrementa, si llega a 5/5 marca 'completo'
  const handleContinueInversion = (moduloId: number) => {
    setInversiones(prev =>
      prev.map(inv => {
        if (inv.id !== moduloId) return inv;
        if (inv.estado === 'activo' && inv.completadas < inv.totalLecciones) {
          const nuevasCompletadas = inv.completadas + 1;
          const nuevoEstado =
            nuevasCompletadas >= inv.totalLecciones ? 'completo' : 'activo';
          return {
            ...inv,
            completadas: nuevasCompletadas,
            estado: nuevoEstado,
          };
        }
        return inv;
      })
    );
  };

  // 5. Parchar la lista de Fundamentos a mostrar (solo primeros 3 si showAllFundamentos es false)
  const visiblesFundamentos = showAllFundamentos
    ? fundamentos
    : fundamentos.slice(0, 3);

  return (
    <div className="p-6 space-y-10 text-white">
      {/* —— Encabezado —— */}
      <div className="flex justify-between items-center">
        <h1 className="text-2xl font-bold">Aprender</h1>
        <div className="flex gap-4 text-sm">
          <span>❤️ 5</span>
          <span>🧠 0</span>
          <span>💎 500</span>
        </div>
      </div>

      {/* —— Sección: Fundamentos financieros —— */}
      <section>
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Fundamentos financieros</h2>
          <button
            onClick={() => setShowAllFundamentos(prev => !prev)}
            className="text-cyan-400 text-sm hover:underline"
          >
            {showAllFundamentos ? 'Mostrar menos' : 'Ver todo'}
          </button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {visiblesFundamentos.map(m => {
            const porcentaje = Math.round(
              (m.completadas / m.totalLecciones) * 100
            );
            return (
              <div
                key={m.id}
                className={`rounded-lg p-4 ${
                  m.estado === 'bloqueado'
                    ? 'bg-[#1a1f2e] opacity-60'
                    : 'bg-[#121c30]'
                }`}
              >
                <div className="flex items-center gap-3 mb-2">
                  <div className="text-xl">{m.icono}</div>
                  <div>
                    <h3 className="font-bold">{m.titulo}</h3>
                    <p className="text-sm text-gray-400">
                      {m.estado === 'bloqueado'
                        ? 'Bloqueado'
                        : `${m.completadas}/${m.totalLecciones} lecciones`}
                    </p>
                  </div>
                </div>

                {(m.estado === 'activo' || m.estado === 'completo') && (
                  <div className="w-full h-2 bg-gray-700 rounded overflow-hidden mb-3">
                    <div
                      className="h-full bg-cyan-500"
                      style={{ width: `${porcentaje}%` }}
                    />
                  </div>
                )}

                {m.estado === 'activo' && (
                  <button
                    onClick={() => handleContinueFundamento(m.id)}
                    className="w-full py-2 rounded font-semibold text-sm bg-cyan-500 hover:bg-cyan-600 transition"
                  >
                    CONTINUAR
                  </button>
                )}
                {m.estado === 'bloqueado' && (
                  <button className="w-full py-2 rounded font-semibold text-sm bg-slate-700 cursor-not-allowed">
                    BLOQUEADO
                  </button>
                )}
                {m.estado === 'completo' && (
                  <button className="w-full py-2 rounded font-semibold text-sm bg-green-500 cursor-default">
                    COMPLETO
                  </button>
                )}
              </div>
            );
          })}
        </div>
      </section>

      {/* —— Sección: Inversiones —— */}
      <section>
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Inversiones</h2>
          <button className="text-cyan-400 text-sm hover:underline">Ver todo</button>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {inversiones.map(inv => {
            const porcentaje = Math.round(
              (inv.completadas / inv.totalLecciones) * 100
            );
            return (
              <div
                key={inv.id}
                className={`rounded-lg p-4 ${
                  inv.estado === 'bloqueado'
                    ? 'bg-[#1a1f2e] opacity-60'
                    : 'bg-[#121c30]'
                }`}
              >
                <div className="flex items-center gap-3 mb-2">
                  <div className="text-xl">{inv.icono}</div>
                  <div>
                    <h3 className="font-bold">{inv.titulo}</h3>
                    <p className="text-sm text-gray-400">
                      {inv.estado === 'bloqueado'
                        ? 'Bloqueado'
                        : `${inv.completadas}/${inv.totalLecciones} lecciones`}
                    </p>
                  </div>
                </div>

                {(inv.estado === 'activo' || inv.estado === 'completo') && (
                  <div className="w-full h-2 bg-gray-700 rounded overflow-hidden mb-3">
                    <div
                      className="h-full bg-cyan-500"
                      style={{ width: `${porcentaje}%` }}
                    />
                  </div>
                )}

                {inv.estado === 'activo' && (
                  <button
                    onClick={() => handleContinueInversion(inv.id)}
                    className="w-full py-2 rounded font-semibold text-sm bg-cyan-500 hover:bg-cyan-600 transition"
                  >
                    CONTINUAR
                  </button>
                )}
                {inv.estado === 'bloqueado' && (
                  <button className="w-full py-2 rounded font-semibold text-sm bg-slate-700 cursor-not-allowed">
                    BLOQUEADO
                  </button>
                )}
                {inv.estado === 'completo' && (
                  <button className="w-full py-2 rounded font-semibold text-sm bg-green-500 cursor-default">
                    COMPLETO
                  </button>
                )}
              </div>
            );
          })}
        </div>
      </section>

      {/* —— Sección: Logros recientes —— */}
      <section>
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-semibold">Logros recientes</h2>
          <button className="text-cyan-400 text-sm hover:underline">
            Ver todos
          </button>
        </div>
        <div className="flex flex-wrap gap-4">
          {[
            { nombre: 'Primera lección', icono: <FaStar className="text-cyan-400" /> },
            { nombre: 'Racha de 3 días', icono: <FaBolt className="text-cyan-400" /> },
            { nombre: 'Ahorrador', icono: <FaMedal className="text-gray-500" /> },
            { nombre: 'Inversor', icono: <FiTrendingUp className="text-gray-500" /> },
            { nombre: 'Experto', icono: <GoDiamond className="text-gray-500" /> },
          ].map((l, i) => (
            <div key={i} className="w-20 flex flex-col items-center">
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
