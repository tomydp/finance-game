import React, { useState, useEffect } from "react";
import { FaPiggyBank, FaUniversity, FaStar } from "react-icons/fa";
import { GoDiamond } from "react-icons/go";
import QuestionCard from "../ui/QuestionCard";
import LessonCompleted from "../ui/LessonCompleted";

interface Leccion {
  id: number;
  title: string;
  completed: boolean;
}

interface Module {
  id: number;
  titulo: string;
  totalLecciones: number;
  completadas: number;
  estado: "activo" | "bloqueado" | "completo";
  icono: React.ReactNode;
  dificultad: string;
}

type TipoEjercicio = "multiple_choice" | "fill_blank";

const Aprender: React.FC = () => {
  const [fundamentos, setFundamentos] = useState<Module[]>([]);
  const [inversiones, setInversiones] = useState<Module[]>([]);
  const [cursoActual, setCursoActual] = useState<Module | null>(null);
  const [lecciones, setLecciones] = useState<Leccion[]>([]);
  const [leccionActual, setLeccionActual] = useState<Leccion | null>(null);
  const [ejercicios, setEjercicios] = useState<any[]>([]);
  const [indiceEjercicio, setIndiceEjercicio] = useState(0);

  const [respuestaSeleccionada, setRespuestaSeleccionada] = useState<string | null>(null);
  const [feedback, setFeedback] = useState<{
    tipo: "correcto" | "incorrecto";
    mensaje: string;
    onContinue: () => void;
  } | null>(null);

  // Estado para la pantalla "¡Lección Completada!"
  const [finLeccion, setFinLeccion] = useState<null | {
    totalExercises: number;
    multipleChoice: number;
    fillIn: number;
    completedLessons: number;
    totalLessons: number;
    lessonTitle: string;
    courseTitle: string;
    lastLessonIndex: number;
  }>(null);

  useEffect(() => {
    const fetchCursos = async () => {
      const res = await fetch("http://localhost/api/courses");
      const json = await res.json();
      const cursos = json.data;

      const iconoPorDificultad = (dificultad: string) => {
        switch (dificultad.toLowerCase()) {
          case "facil":
            return <FaPiggyBank className="text-cyan-400" />;
          case "medio":
            return <FaUniversity className="text-gray-500" />;
          case "dificil":
            return <GoDiamond className="text-gray-500" />;
          default:
            return <FaStar className="text-gray-500" />;
        }
      };

      const modulos: Module[] = await Promise.all(
        cursos.map(async (curso: any, index: number) => {
          const r = await fetch(`http://localhost/api/courses/${curso.id}/lessons`);
          const j = await r.json();
          const lecs: Leccion[] = j.data;
          const completadas = lecs.filter((l) => l.completed).length;

          return {
            id: curso.id,
            titulo: curso.name,
            totalLecciones: lecs.length,
            completadas,
            estado: index === 0 ? "activo" : "bloqueado",
            icono: iconoPorDificultad(curso.difficulty),
            dificultad: curso.difficulty,
          };
        })
      );

      setFundamentos(modulos.filter((m) => m.dificultad.toLowerCase() === "facil"));
      setInversiones(modulos.filter((m) => m.dificultad.toLowerCase() !== "facil"));
    };

    fetchCursos();
  }, []);

  const cargarLecciones = async (curso: Module) => {
    const res = await fetch(`http://localhost/api/courses/${curso.id}/lessons`);
    const json = await res.json();
    const lessons: Leccion[] = json.data;

    setCursoActual(curso);
    setLecciones(lessons);

    const pendiente = lessons.find((l) => !l.completed) || lessons[0];
    if (pendiente) {
      await cargarEjercicios(pendiente);
    }
  };

  const cargarEjercicios = async (leccion: Leccion) => {
    const res = await fetch(`http://localhost/api/lessons/${leccion.id}/exercises`);
    const json = await res.json();

    setLeccionActual(leccion);
    setEjercicios(json.data || []);
    setIndiceEjercicio(0);
    setRespuestaSeleccionada(null);
    setFeedback(null);
    setFinLeccion(null);
  };

  const marcarLeccionComoCompletada = async (leccionId: number) => {
    try {
      await fetch(`http://localhost/api/lessons/${leccionId}/complete`, { method: "POST" });
    } catch {}
    setLecciones((prev) =>
      prev.map((l) => (l.id === leccionId ? { ...l, completed: true } : l))
    );
  };

  const normalize = (s: string) =>
    s
      .toLowerCase()
      .normalize("NFD")
      .replace(/\p{Diacritic}/gu, "")
      .replace(/\s+/g, " ")
      .trim();

  const getTipo = (ej: any): TipoEjercicio => {
    const t = (ej.type || ej.tipo || "").toString().toLowerCase();
    if (t === "fill_blank" || t === "fill-blank") return "fill_blank";
    return ej.options ? "multiple_choice" : "fill_blank";
  };

  const handleRespuesta = () => {
    const ej = ejercicios[indiceEjercicio];
    if (!ej || !leccionActual || !cursoActual) return;

    const correct = ej.correct_answer ?? ej.correct ?? "";
    const esCorrecto =
      normalize(respuestaSeleccionada || "") === normalize(String(correct));

    if (esCorrecto) {
      setFeedback({
        tipo: "correcto",
        mensaje: "¡Respuesta correcta! 🎉",
        onContinue: () => {
          setFeedback(null);
          setRespuestaSeleccionada(null);

          if (indiceEjercicio + 1 < ejercicios.length) {
            setIndiceEjercicio((i) => i + 1);
          } else {
            // Fin de la lección: marcar como completada y mostrar pantalla de feedback
            marcarLeccionComoCompletada(leccionActual.id);

            // Contabilizar tipos de ejercicios
            const counts = ejercicios.reduce(
              (acc: any, e: any) => {
                const t = getTipo(e);
                acc.totalExercises++;
                if (t === "multiple_choice") acc.multipleChoice++;
                else acc.fillIn++;
                return acc;
              },
              { totalExercises: 0, multipleChoice: 0, fillIn: 0 }
            );

            const idx = lecciones.findIndex((l) => l.id === leccionActual.id);
            const completedNow = lecciones.filter((l) => l.completed).length + 1;

            setFinLeccion({
              ...counts,
              completedLessons: completedNow,
              totalLessons: lecciones.length,
              lessonTitle: leccionActual.title,
              courseTitle: cursoActual.titulo,
              lastLessonIndex: idx,
            });
          }
        },
      });
    } else {
      setFeedback({
        tipo: "incorrecto",
        mensaje: `La respuesta correcta es: ${correct}`,
        onContinue: () => {
          setFeedback(null);
          setRespuestaSeleccionada(null);
        },
      });
    }
  };

  // ---- RENDER: Pantalla "Lección Completada" ----
  if (finLeccion && cursoActual) {
    const esUltima = finLeccion.lastLessonIndex + 1 >= lecciones.length;

    return (
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
          setCursoActual(null); // volver al listado de cursos
          setLeccionActual(null);
          setEjercicios([]);
        }}
        onNext={() => {
          setFinLeccion(null);
          const next = lecciones[finLeccion.lastLessonIndex + 1];
          if (next && !esUltima) {
            cargarEjercicios(next);
          } else {
            // terminó el curso
            setCursoActual(null);
            setLeccionActual(null);
            setEjercicios([]);
          }
        }}
      />
    );
  }

  // ---- RENDER: Vista de ejercicios ----
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

  // ---- RENDER: Vista de cursos ----
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

export default Aprender;
