import React, { useEffect, useMemo, useRef } from "react";
import { CheckCircle, XCircle, ChevronLeft } from "lucide-react";

type Tipo = "multiple_choice" | "fill_blank";

interface QuestionCardProps {
  curso: string;
  leccion: string;
  pregunta: string;
  tipo: Tipo;
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

  // opcionales
  progress?: { current: number; total: number };
  subtitle?: string;
  onBack?: () => void;
  disabledReason?: string;
}

const QuestionCard: React.FC<QuestionCardProps> = ({
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
  subtitle,
  onBack,
  disabledReason,
}) => {
  const optionIds = useMemo(
    () => opciones.map((_, i) => `opt-${i}-${leccion.replace(/\s+/g, "-")}`),
    [opciones, leccion]
  );

  const inputRef = useRef<HTMLInputElement | null>(null);

  // 👉 Etiqueta legible en español para booleanos
  const prettyLabel = (v: string) => {
    const t = String(v).trim().toLowerCase();
    if (t === "true") return "Verdadero";
    if (t === "false") return "Falso";
    return String(v);
  };

  // Atajos de teclado
  useEffect(() => {
    const handler = (e: KeyboardEvent) => {
      if (feedback) return;

      if (tipo === "multiple_choice") {
        const idx = Number(e.key) - 1;
        if (idx >= 0 && idx < opciones.length) {
          setSeleccionada(opciones[idx]);
          return;
        }
      }

      if (e.key === "Enter" && !disabled) onComprobar();
    };
    window.addEventListener("keydown", handler);
    return () => window.removeEventListener("keydown", handler);
  }, [opciones, setSeleccionada, onComprobar, disabled, feedback, tipo]);

  // Autofocus en fill_blank
  useEffect(() => {
    if (tipo === "fill_blank" && inputRef.current) {
      inputRef.current.focus();
    }
  }, [tipo]);

  return (
    <div className="max-w-4xl mx-auto px-4 text-white">
      {/* Header */}
      <div className="flex items-center justify-between gap-3 mb-6">
        <div className="flex items-center gap-3">
          {onBack && (
            <button
              onClick={onBack}
              className="inline-flex items-center gap-2 text-cyan-300 hover:text-cyan-200"
            >
              <ChevronLeft size={18} />
              <span className="text-sm">Volver</span>
            </button>
          )}
          <span className="bg-cyan-600/20 text-cyan-300 text-xs px-3 py-1 rounded-full">
            {curso}
          </span>
        </div>

        {progress && (
          <div className="min-w-[220px] text-right">
            <span className="text-xs text-gray-400">
              Ejercicio {progress.current} de {progress.total}
            </span>
            <div className="w-full h-1.5 bg-slate-700 rounded mt-1 overflow-hidden">
              <div
                className="h-full bg-cyan-400"
                style={{
                  width: `${Math.min(
                    100,
                    (progress.current / Math.max(1, progress.total)) * 100
                  )}%`,
                }}
              />
            </div>
          </div>
        )}
      </div>

      {/* Título + subtítulo */}
      <h1 className="text-4xl md:text-5xl font-extrabold tracking-tight">{leccion}</h1>
      {subtitle && <p className="mt-2 text-sm md:text-base text-slate-300">{subtitle}</p>}

      {/* Card */}
      <div className="mt-8 rounded-2xl border border-slate-700/60 bg-slate-900/50 backdrop-blur-sm shadow-[0_10px_40px_rgba(0,0,0,0.35)] p-6 md:p-8">
        {/* Pregunta */}
        <div className="mb-6">
          <p className="text-2xl font-bold leading-snug">{pregunta}</p>
          <p className="text-sm text-gray-400 mt-1">
            {tipo === "fill_blank"
              ? "Escribe tu respuesta y presiona Enter o Comprobar"
              : "Selecciona la respuesta que consideres correcta"}
          </p>
        </div>

        {/* Contenido dinámico */}
        {tipo === "fill_blank" ? (
          <div className={`${feedback ? "pointer-events-none opacity-70" : ""}`}>
            <input
              ref={inputRef}
              value={seleccionada || ""}
              onChange={(e) => setSeleccionada(e.target.value)}
              placeholder="Escribe tu respuesta…"
              className="w-full rounded-xl bg-[#0c1220] border border-white/10 px-4 py-3 outline-none focus:border-cyan-500"
            />
          </div>
        ) : (
          <div className={`space-y-4 ${feedback ? "pointer-events-none opacity-70" : ""}`}>
            {opciones.map((op, i) => {
              const checked = seleccionada === op;
              return (
                <label
                  key={op}
                  htmlFor={optionIds[i]}
                  className={`group flex items-center gap-3 p-4 rounded-xl border cursor-pointer
                    transition-[transform,box-shadow,background,border-color] duration-200
                    ${
                      checked
                        ? "border-cyan-500 bg-cyan-400/10 shadow-[0_0_0_2px_rgba(34,211,238,0.25)]"
                        : "border-slate-700 bg-gradient-to-r from-slate-800/60 to-slate-800/30 hover:from-slate-800/80 hover:to-slate-700/40 hover:border-cyan-400/50 hover:shadow-[0_8px_24px_rgba(2,132,199,0.15)] hover:-translate-y-0.5"
                    }
                    focus-within:ring-2 focus-within:ring-cyan-400/70`}
                >
                  <input
                    id={optionIds[i]}
                    type="radio"
                    name="respuesta"
                    value={op}
                    checked={checked}
                    onChange={() => setSeleccionada(op)}
                    className="sr-only"
                  />
                  <span
                    className={`flex items-center justify-center w-5 h-5 rounded-full border-2 transition
                      ${
                        checked
                          ? "border-cyan-400 bg-cyan-500"
                          : "border-slate-500 group-hover:border-cyan-300"
                      }
                      shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)]`}
                    aria-hidden="true"
                  >
                    {checked && <span className="w-2 h-2 bg-white rounded-full" />}
                  </span>
                  {/* 👇 mostrar etiqueta traducida */}
                  <span className="text-base md:text-lg">{prettyLabel(op)}</span>
                </label>
              );
            })}
          </div>
        )}

        {/* Footer */}
        <div className="mt-6 space-y-4">
          {!feedback ? (
            <button
              disabled={disabled}
              onClick={onComprobar}
              title={disabled ? disabledReason : undefined}
              className={`w-full md:w-auto px-8 py-3 font-bold rounded-xl transition
                ${
                  disabled
                    ? "bg-slate-700 text-slate-300 cursor-not-allowed"
                    : "bg-cyan-500 hover:bg-cyan-600 text-white"
                }`}
            >
              Comprobar respuesta
            </button>
          ) : (
            <>
              {/* Alerta con ícono a la izquierda del título */}
              <div
                className={`rounded-2xl border px-6 py-8 text-center shadow-[0_10px_40px_rgba(0,0,0,0.25)]
                  ${
                    feedback.tipo === "correcto"
                      ? "border-emerald-500/60 bg-transparent"
                      : "border-rose-500/60 bg-transparent"
                  }`}
              >
                <div className="flex items-center justify-center gap-3">
                  <div
                    className={`grid place-items-center h-12 w-12 rounded-full
                      ${feedback.tipo === "correcto" ? "bg-emerald-500/15" : "bg-rose-500/15"}`}
                  >
                    {feedback.tipo === "correcto" ? (
                      <CheckCircle className="h-7 w-7 text-emerald-400" />
                    ) : (
                      <XCircle className="h-7 w-7 text-rose-400" />
                    )}
                  </div>

                  <h3
                    className={`text-2xl md:text-3xl font-extrabold tracking-tight
                      ${feedback.tipo === "correcto" ? "text-emerald-400" : "text-rose-400"}`}
                  >
                    {feedback.tipo === "correcto" ? "¡Correcto!" : "Incorrecto"}
                  </h3>
                </div>

                <p className="mt-2 text-base md:text-lg text-white/90">
                  {feedback.tipo === "correcto"
                    ? "Has seleccionado la respuesta correcta"
                    : "Vuelve a intentarlo"}
                </p>

                {feedback.mensaje && (
                  <p className="mt-1 text-sm md:text-base text-white/80">
                    {feedback.mensaje}
                  </p>
                )}
              </div>

              {/* CTA */}
              <button
                onClick={feedback.onContinue}
                className={`w-full flex items-center justify-center gap-2 rounded-xl py-3 font-semibold shadow-md transition
                  ${
                    feedback.tipo === "correcto"
                      ? "bg-emerald-500 hover:bg-emerald-400 text-white"
                      : "bg-rose-500 hover:bg-rose-400 text-white"
                  }`}
              >
                <span>
                  {feedback.tipo === "correcto"
                    ? "Continuar"
                    : "Intentar de nuevo"}
                </span>
                <svg
                  className="h-4 w-4"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                >
                  <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
              </button>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default QuestionCard;
