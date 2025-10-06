// src/components/app/aprender/components/QuestionCard.tsx
import React from "react";
import type { TipoEjercicio } from "../hooks/types"

interface Props {
  curso: string;
  leccion: string;
  pregunta: string;
  tipo: TipoEjercicio;
  opciones: string[];
  seleccionada: string | null;
  setSeleccionada: (op: string) => void;
  onComprobar: () => void;
  disabled: boolean;
  feedback?: {
    tipo: "correcto" | "incorrecto";
    mensaje: string;
    onContinue: () => void;
  };
  progress?: { current: number; total: number };
  onBack?: () => void;
}

const QuestionCard: React.FC<Props> = ({
  curso,
  leccion,
  pregunta,
  tipo,
  opciones,
  seleccionada,
  setSeleccionada,
  onComprobar,
  disabled,
  feedback,
  progress,
  onBack,
}) => {
  return (
    <div className="bg-[#121c30] rounded-xl p-6 space-y-4">
      <div className="flex items-center justify-between">
        <button onClick={onBack} className="text-cyan-400 hover:underline">
          ← Volver
        </button>
        {progress && (
          <span className="text-sm text-gray-400">
            {progress.current}/{progress.total}
          </span>
        )}
      </div>

      <h3 className="text-lg font-semibold">{curso} — {leccion}</h3>
      <p className="text-2xl font-bold">{pregunta}</p>

      {tipo === "multiple_choice" ? (
        <div className="space-y-2">
          {opciones.map((op) => (
            <button
              key={op}
              onClick={() => setSeleccionada(op)}
              className={`w-full text-left p-3 rounded-lg border transition ${
                seleccionada === op ? "border-cyan-500 bg-cyan-500/10" : "border-gray-700 hover:border-gray-500"
              }`}
            >
              {op}
            </button>
          ))}
        </div>
      ) : (
        <input
          className="w-full p-3 rounded-lg bg-[#0e1626] border border-gray-700 outline-none"
          placeholder="Escribe tu respuesta..."
          value={seleccionada || ""}
          onChange={(e) => setSeleccionada(e.target.value)}
        />
      )}

      {feedback ? (
        <div className={`rounded-lg p-4 ${feedback.tipo === "correcto" ? "bg-emerald-900/30 border border-emerald-600" : "bg-rose-900/30 border border-rose-600"}`}>
          {feedback.mensaje && <div className="prose prose-invert">{feedback.mensaje}</div>}
          <button onClick={feedback.onContinue} className="mt-3 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 rounded-lg">
            Continuar
          </button>
        </div>
      ) : (
        <button
          disabled={disabled}
          onClick={onComprobar}
          className={`w-full py-3 rounded-lg font-semibold ${disabled ? "bg-slate-700 cursor-not-allowed" : "bg-cyan-500 hover:bg-cyan-600"}`}
        >
          Comprobar
        </button>
      )}
    </div>
  );
};

export default QuestionCard;
