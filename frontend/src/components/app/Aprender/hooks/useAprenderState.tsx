// src/components/app/aprender/hooks/useAprenderState.ts
import { useEffect, useState } from "react";
import { FaPiggyBank, FaUniversity, FaStar } from "react-icons/fa";
import { GoDiamond } from "react-icons/go";
import type { Leccion, Module, TipoEjercicio, Feedback, FinLeccion } from "./types";

const API = "http://localhost/api";

const normalizeBase = (s: string) =>
  String(s)
    .toLowerCase()
    .normalize("NFD")
    .replace(/\p{Diacritic}/gu, "")
    .replace(/\s+/g, " ")
    .trim();

const toComparable = (s: string) => {
  const n = normalizeBase(String(s));
  if (n === "verdadero") return "true";
  if (n === "falso") return "false";
  return n;
};

const getTipo = (ej: any): TipoEjercicio => {
  const t = (ej.type || ej.tipo || "").toString().toLowerCase();
  if (t === "fill_blank" || t === "fill-blank") return "fill_blank";
  return ej.options ? "multiple_choice" : "fill_blank";
};

export function useAprenderState() {
  const [fundamentos, setFundamentos] = useState<Module[]>([]);
  const [inversiones, setInversiones] = useState<Module[]>([]);
  const [cursoActual, setCursoActual] = useState<Module | null>(null);

  const [lecciones, setLecciones] = useState<Leccion[]>([]);
  const [leccionActual, setLeccionActual] = useState<Leccion | null>(null);

  const [ejercicios, setEjercicios] = useState<any[]>([]);
  const [indiceEjercicio, setIndiceEjercicio] = useState(0);

  const [respuestaSeleccionada, setRespuestaSeleccionada] = useState<string | null>(null);
  const [feedback, setFeedback] = useState<Feedback>(null);

  const [mostrarModalPremium, setMostrarModalPremium] = useState(false);
  const [finLeccion, setFinLeccion] = useState<FinLeccion | null>(null);

  // ---------- Cursos ----------
  useEffect(() => {
    const iconoPorDificultad = (d: string) => {
      switch (d.toLowerCase()) {
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

    const fetchCursos = async () => {
      const res = await fetch(`${API}/courses`);
      const json = await res.json();
      let currentUser = null;
      try {
        currentUser = JSON.parse(localStorage.getItem("user") || "null");
      } catch {
        currentUser = null;
      }
      console.log("[Aprender] GET /courses →", json?.data);
      console.log("[Aprender] current user →", currentUser);
      const cursos = json.data;

      const modulos: Module[] = await Promise.all(
        cursos.map(async (curso: any, index: number) => {
          const r = await fetch(`${API}/courses/${curso.id}/lessons`);
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
          } as Module;
        })
      );

      setFundamentos(modulos.filter((m) => m.dificultad.toLowerCase() === "facil"));
      setInversiones(modulos.filter((m) => m.dificultad.toLowerCase() !== "facil"));
    };

    fetchCursos();
  }, []);

  const cargarLecciones = async (curso: Module) => {
    const res = await fetch(`${API}/courses/${curso.id}/lessons`);
    const json = await res.json();
    const lessons: Leccion[] = json.data;

    setCursoActual(curso);
    setLecciones(lessons);

    const pendiente = lessons.find((l) => !l.completed) || lessons[0];
    if (pendiente) await cargarEjercicios(pendiente);
  };

  const cargarEjercicios = async (leccion: Leccion) => {
    const res = await fetch(`${API}/lessons/${leccion.id}/exercises`);
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
      await fetch(`${API}/lessons/${leccionId}/complete`, { method: "POST" });
    } catch {}
    setLecciones((prev) => prev.map((l) => (l.id === leccionId ? { ...l, completed: true } : l)));
  };

  // ---------- Lógica de respuesta ----------
  const handleRespuesta = () => {
    const ej = ejercicios[indiceEjercicio];
    if (!ej || !leccionActual || !cursoActual) return;

    const correct = ej.correct_answer ?? ej.correct ?? "";
    const esCorrecto = toComparable(respuestaSeleccionada || "") === toComparable(String(correct));

    if (esCorrecto) {
      setFeedback({
        tipo: "correcto",
        mensaje: "",
        onContinue: () => {
          setFeedback(null);
          setRespuestaSeleccionada(null);

          if (indiceEjercicio + 1 < ejercicios.length) {
            setIndiceEjercicio((i) => i + 1);
          } else {
            // terminó la lección
            marcarLeccionComoCompletada(leccionActual.id);

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
      const mensaje =
        ej.explanation_md && ej.explanation_md.trim() !== "" ? ej.explanation_md : "";

      setFeedback({
        tipo: "incorrecto",
        mensaje,
        onContinue: () => {
          setFeedback(null);
          setRespuestaSeleccionada(null);
        },
      });
    }
  };

  const handleNextAfterCompletion = () => {
    if (!finLeccion || !cursoActual) return;

    const esUltima = finLeccion.lastLessonIndex + 1 >= lecciones.length;

    const esPrimerCurso = cursoActual.id === 1;
    const esPrimeraLeccion = finLeccion.lastLessonIndex === 0;

    if (esPrimerCurso && esPrimeraLeccion) {
      setMostrarModalPremium(true);
      return;
    }

    setFinLeccion(null);
    const next = lecciones[finLeccion.lastLessonIndex + 1];
    if (next && !esUltima) {
      cargarEjercicios(next);
    } else {
      // volver al listado
      setCursoActual(null);
      setLeccionActual(null);
      setEjercicios([]);
    }
  };

  return {
    // estado
    fundamentos,
    inversiones,
    cursoActual,
    lecciones,
    leccionActual,
    ejercicios,
    indiceEjercicio,
    respuestaSeleccionada,
    feedback,
    mostrarModalPremium,
    finLeccion,

    // setters
    setCursoActual,
    setRespuestaSeleccionada,
    setMostrarModalPremium,
    setFinLeccion,

    // helpers
    cargarLecciones,
    cargarEjercicios,
    handleRespuesta,
    handleNextAfterCompletion,
    getTipo,
  };
}
