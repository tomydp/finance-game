import React from "react";
import { CheckCircle, XCircle } from "lucide-react";

interface QuestionCardProps {
  curso: string;
  leccion: string;
  pregunta: string;
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
}

const QuestionCard: React.FC<QuestionCardProps> = ({
  curso,
  leccion,
  pregunta,
  opciones,
  seleccionada,
  setSeleccionada,
  onComprobar,
  disabled,
  feedback,
}) => {
  return (
    <div className="space-y-6 max-w-2xl mx-auto">
      {/* Encabezado */}
      <div>
        <span className="bg-cyan-600 text-white text-xs px-3 py-1 rounded-full">
          {curso}
        </span>
        <h2 className="text-2xl font-bold mt-3">{leccion}</h2>
      </div>

      {/* Caja de pregunta */}
      <div className="bg-[#1c2436] p-6 rounded-xl shadow-md space-y-2">
        <p className="text-lg font-semibold">{pregunta}</p>
        <p className="text-sm text-gray-400">Selecciona la opción correcta:</p>
      </div>

      {/* Opciones */}
      <div className="space-y-4">
        {opciones.map((op) => (
          <label
            key={op}
            className={`flex items-center gap-3 p-4 border rounded-lg cursor-pointer transition-all ${
              seleccionada === op
                ? "border-cyan-500 bg-cyan-800/30"
                : "border-gray-600 hover:border-cyan-400"
            }`}
          >
            <input
              type="radio"
              name="respuesta"
              value={op}
              checked={seleccionada === op}
              onChange={() => setSeleccionada(op)}
              className="hidden"
            />
            <span
              className={`w-5 h-5 rounded-full border-2 flex items-center justify-center ${
                seleccionada === op
                  ? "border-cyan-400 bg-cyan-500"
                  : "border-gray-500"
              }`}
            >
              {seleccionada === op && (
                <span className="w-2 h-2 bg-white rounded-full" />
              )}
            </span>
            <span className="text-lg">{op}</span>
          </label>
        ))}
      </div>

      {/* Botón comprobar */}
      <button
        disabled={disabled}
        onClick={onComprobar}
        className={`px-10 py-3 font-bold rounded-lg transition text-white mx-auto block ${
            disabled
            ? "bg-gray-600 cursor-not-allowed"
            : "bg-cyan-500 hover:bg-cyan-600"
        }`}
        >
        COMPROBAR
        </button>

      {/* Feedback */}
      {feedback && (
        <div
          className={`p-6 rounded-xl mt-6 text-center ${
            feedback.tipo === "correcto"
              ? "bg-green-600 text-white"
              : "bg-red-600 text-white"
          }`}
        >
          <div className="flex flex-col items-center gap-3">
            {feedback.tipo === "correcto" ? (
              <CheckCircle size={48} />
            ) : (
              <XCircle size={48} />
            )}
            <p className="text-lg font-semibold">{feedback.mensaje}</p>
            <button
              onClick={feedback.onContinue}
              className="mt-2 px-5 py-2 rounded-lg bg-white text-black font-bold hover:bg-gray-200"
            >
              CONTINUAR
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default QuestionCard;
