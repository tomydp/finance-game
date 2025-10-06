// src/components/app/aprender/components/ModulesGrid.tsx
import React from "react";
import type { Module } from "../hooks/types"

interface Props {
  titulo: string;
  modulos: Module[];
  onSelect: (m: Module) => void;
}

const ModulesGrid: React.FC<Props> = ({ titulo, modulos, onSelect }) => {
  const renderModulo = (m: Module) => {
    const porcentaje = Math.round((m.completadas / Math.max(1, m.totalLecciones)) * 100);
    return (
      <div
        key={m.id}
        className={`rounded-lg p-4 ${
          m.estado === "bloqueado" ? "bg-[#1a1f2e] opacity-60" : "bg-[#121c30]"
        }`}
      >
        <div className="flex items-center gap-3 mb-2">
          <div className="text-xl">{m.icono}</div>
          <div>
            <h3 className="font-bold">{m.titulo}</h3>
            <p className="text-sm text-gray-400">
              {m.estado === "bloqueado"
                ? "Bloqueado"
                : `${m.completadas}/${m.totalLecciones} lecciones`}
            </p>
          </div>
        </div>

        {(m.estado === "activo" || m.estado === "completo") && (
          <div className="w-full h-2 bg-gray-700 rounded overflow-hidden mb-3">
            <div className="h-full bg-cyan-500" style={{ width: `${porcentaje}%` }} />
          </div>
        )}

        <button
          onClick={() => onSelect(m)}
          className={`w-full py-2 rounded font-semibold text-sm ${
            m.estado === "bloqueado"
              ? "bg-slate-700 cursor-not-allowed"
              : m.estado === "completo"
              ? "bg-green-500 cursor-default"
              : "bg-cyan-500 hover:bg-cyan-600"
          }`}
        >
          {m.estado === "completo"
            ? "COMPLETO"
            : m.estado === "bloqueado"
            ? "BLOQUEADO"
            : "CONTINUAR"}
        </button>
      </div>
    );
  };

  return (
    <section>
      <h2 className="text-xl font-semibold mb-4">{titulo}</h2>
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {modulos.map(renderModulo)}
      </div>
    </section>
  );
};

export default ModulesGrid;
