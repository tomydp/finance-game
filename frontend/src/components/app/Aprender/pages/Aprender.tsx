import React from "react";
import QuestionCard from "../../../ui/QuestionCard";
import LessonCompleted from "../../../ui/LessonCompleted"
import { useAprenderState } from "../hooks/useAprenderState";
import type { Module } from "../hooks/types";

const AprenderPage: React.FC = () => {
  const {
    fundamentos, inversiones,
    cursoActual, lecciones, leccionActual,
    ejercicios, indiceEjercicio,
    respuestaSeleccionada, setRespuestaSeleccionada,
    feedback, finLeccion, mostrarModalPremium,
    setCursoActual, setMostrarModalPremium, setFinLeccion,
    cargarLecciones, handleRespuesta, handleNextAfterCompletion, getTipo,
  } = useAprenderState();

  let sessionUser: any = null;
  try {
    sessionUser = JSON.parse(localStorage.getItem("user") || "null");
  } catch {
    sessionUser = null;
  }
  console.log("[Aprender] sesión de usuario", sessionUser);

  // ---- RENDER: Pantalla "Lección Completada" ----
  if (finLeccion && cursoActual) {
    const esUltima = finLeccion.lastLessonIndex + 1 >= lecciones.length;

    const handleNext = () => {
      const esPrimerCurso = cursoActual.id === 1;
      const esPrimeraLeccion = finLeccion.lastLessonIndex === 0;

      if (esPrimerCurso && esPrimeraLeccion) {
        setMostrarModalPremium(true);
        return;
      }

      setFinLeccion(null);
      const next = lecciones[finLeccion.lastLessonIndex + 1];
      if (next && !esUltima) {
        // continúa a la próxima lección
        cargarLecciones(cursoActual); // asegura recarga si cambió algo
      } else {
        // volver al listado
        setCursoActual(null);
        // limpiar resto por si acaso
      }
    };

    return (
      <>
        <LessonCompleted
          courseTitle={finLeccion.courseTitle}
          lessonTitle={finLeccion.lessonTitle}
          totalLessons={finLeccion.totalLessons}
          completedLessons={finLeccion.completedLessons}
          stats={{
            totalExercises: finLeccion.totalExercises,
            multipleChoice: finLeccion.multipleChoice,
            fillIn: finLeccion.fillIn,
          }}
          onBackToCourse={() => {
            setFinLeccion(null);
            setCursoActual(null);
          }}
          onNext={handleNext}
        />

        {/* --- MODAL PREMIUM (idéntico al original) --- */}
        {mostrarModalPremium && (
          <div className="fixed inset-0 flex items-center justify-center backdrop-blur-md bg-black/20 z-50">
            <div className="bg-[#101828]/95 text-white p-8 rounded-2xl shadow-2xl w-[90%] max-w-md text-center space-y-5 border border-cyan-600 backdrop-blur-sm">
              <h2 className="text-2xl font-bold text-cyan-400">Contenido Premium</h2>
              <p className="text-gray-300">
                Para continuar con las próximas lecciones necesitás tener una membresía{" "}
                <span className="text-cyan-400 font-semibold">Premium</span>.
              </p>
              <div className="flex justify-center gap-4 mt-6">
                <button
                  onClick={() => setMostrarModalPremium(false)}
                  className="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition"
                >
                  Volver
                </button>
                <button
                  onClick={() => (window.location.href = "http://localhost:5173/app/tienda")}
                  className="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 rounded-lg font-semibold transition"
                >
                  Ir a la tienda
                </button>
              </div>
            </div>
          </div>
        )}
      </>
    );
  }

  // ---- RENDER: Vista de ejercicios (idéntico al original) ----
  if (leccionActual && cursoActual) {
    const ejercicio = ejercicios[indiceEjercicio];
    if (!ejercicio) return null;

    const tipo = getTipo(ejercicio);

    let opciones: string[] = [];
    try {
      if (ejercicio.options) {
        opciones = Array.isArray(ejercicio.options)
          ? ejercicio.options
          : JSON.parse(ejercicio.options);
      }
    } catch (e) {
      console.error("Error parseando opciones:", ejercicio.options, e);
    }

    const disabled =
      tipo === "fill_blank"
        ? !((respuestaSeleccionada || "").trim())
        : respuestaSeleccionada === null;

    return (
      <div className="p-6 text-white space-y-6">
        <button onClick={() => setCursoActual(null)} className="text-cyan-400 hover:underline">
          ← Volver a cursos
        </button>

        <QuestionCard
          curso={cursoActual.titulo}
          leccion={leccionActual.title}
          pregunta={ejercicio.question}
          tipo={tipo}
          opciones={opciones}
          seleccionada={respuestaSeleccionada}
          setSeleccionada={setRespuestaSeleccionada}
          onComprobar={handleRespuesta}
          disabled={disabled}
          feedback={feedback || undefined}
          progress={{ current: indiceEjercicio + 1, total: ejercicios.length }}
          onBack={() => setCursoActual(null)}
        />
      </div>
    );
  }

  // ---- RENDER: Vista de cursos (con el mismo markup/clases) ----
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
          onClick={() => cargarLecciones(m)}
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
    <div className="p-6 text-white space-y-10">
      <h1 className="text-2xl font-bold">Aprender</h1>
      <section>
        <h2 className="text-xl font-semibold mb-4">Fundamentos financieros</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {fundamentos.map(renderModulo)}
        </div>
      </section>
      <section>
        <h2 className="text-xl font-semibold mb-4">Inversiones</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {inversiones.map(renderModulo)}
        </div>
      </section>
    </div>
  );
};

export default AprenderPage;
