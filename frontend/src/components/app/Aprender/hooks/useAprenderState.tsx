// src/components/app/aprender/hooks/useAprenderState.ts
import { useEffect, useState } from "react";
import { FaPiggyBank, FaUniversity, FaStar } from "react-icons/fa";
import { GoDiamond } from "react-icons/go";
import type { Leccion, Module, TipoEjercicio, Feedback, FinLeccion } from "./types";
import api from "../../../../services/api";

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
      console.log("[Aprender] 🔄 Cargando cursos...");
      const { data: json } = await api.get(`/courses`);
      const cursos = json.data;

      const modulos: Module[] = await Promise.all(
        cursos.map(async (curso: any, index: number) => {
          console.log(`[Aprender] ↳ Cargando lecciones y progreso del curso ${curso.name} (#${curso.id})...`);
          const { data: j } = await api.get(`/courses/${curso.id}/lessons`);
          const lecs: Leccion[] = j.data;

          // 🔹 Pedimos progreso real desde backend
          let completadas = 0;
          let totalLecciones = lecs.length;

          try {
            const { data: prog } = await api.get(`/courses/${curso.id}/progress`);
            completadas = prog.lessons?.completed ?? 0;
            totalLecciones = prog.lessons?.total ?? lecs.length;
            console.log(`[Aprender] Progreso → ${completadas}/${totalLecciones} lecciones completadas`);
          } catch (err) {
            console.warn(`[Aprender] ⚠️ No se pudo obtener progreso del curso ${curso.id}`, err);
          }

          return {
            id: curso.id,
            titulo: curso.name,
            totalLecciones,
            completadas,
            estado: index === 0 ? "activo" : "bloqueado",
            icono: iconoPorDificultad(curso.difficulty),
            dificultad: curso.difficulty,
          } as Module;
        })
      );

      setFundamentos(modulos.filter((m) => m.dificultad.toLowerCase() === "facil"));
      setInversiones(modulos.filter((m) => m.dificultad.toLowerCase() !== "facil"));
      console.log("[Aprender] 🧩 Módulos listos:", modulos);
    };

    fetchCursos();
  }, []);

  const cargarLecciones = async (curso: Module) => {
    console.log(`[Aprender] 🧠 Cargando lecciones del curso: ${curso.titulo} (#${curso.id})`);
    const { data: json } = await api.get(`/courses/${curso.id}/lessons`);
    const lessons: Leccion[] = json.data;
    console.log("[Aprender] ✅ Lecciones recibidas:", lessons);

    setCursoActual(curso);
    setLecciones(lessons);

    const pendiente = lessons.find((l) => !l.completed) || lessons[0];
    if (pendiente) {
      console.log("[Aprender] Próxima lección a iniciar:", pendiente.title);
      await cargarEjercicios(pendiente);
    }
  };

  const cargarEjercicios = async (leccion: Leccion) => {
    console.log(`[Aprender] 🧩 Cargando ejercicios de lección ${leccion.id}: ${leccion.title}`);
    const { data: json } = await api.get(`/lessons/${leccion.id}/exercises`);
    console.log("[Aprender] ✅ Ejercicios recibidos:", json.data);

    setLeccionActual(leccion);
    setEjercicios(json.data || []);
    setIndiceEjercicio(0);
    setRespuestaSeleccionada(null);
    setFeedback(null);
    setFinLeccion(null);
  };

  const marcarLeccionComoCompletada = async (leccionId: number) => {
    console.log("[Aprender] → Marcando lección como completada:", leccionId);
  
    try {
      const res = await api.post(`/lessons/${leccionId}/complete`, { force: true });
      console.log("[Aprender] ✅ Respuesta de /complete:", res.data);
  
      // 🔹 Identificar curso actual
      if (cursoActual) {
        // pedir progreso actualizado del curso
        const { data: prog } = await api.get(`/courses/${cursoActual.id}/progress`);
        const completadas = prog.lessons?.completed ?? 0;
        const totalLecciones = prog.lessons?.total ?? lecciones.length;
  
        console.log(`[Aprender] 🔁 Actualizando progreso del curso ${cursoActual.titulo}: ${completadas}/${totalLecciones}`);
  
        // actualizar el estado del curso en fundamentos/inversiones
        const updateProgress = (modulos: Module[]) =>
          modulos.map((m) =>
            m.id === cursoActual.id ? { ...m, completadas, totalLecciones } : m
          );
  
        setFundamentos((prev) => updateProgress(prev));
        setInversiones((prev) => updateProgress(prev));
      }
  
      // 🔹 marcar la lección localmente
      setLecciones((prev) =>
        prev.map((l) => (l.id === leccionId ? { ...l, completed: true } : l))
      );
    } catch (err: any) {
      console.error("[Aprender] ❌ Error marcando como completada:", err.response?.data || err);
    }
  };

  // ---------- Lógica de respuesta ----------
  const handleRespuesta = () => {
    const ej = ejercicios[indiceEjercicio];
    if (!ej || !leccionActual || !cursoActual) return;

    console.log("[Aprender] 🧠 Comprobando respuesta...");
    const correct = ej.correct_answer ?? ej.correct ?? "";
    const esCorrecto = toComparable(respuestaSeleccionada || "") === toComparable(String(correct));

    if (esCorrecto) {
      console.log("[Aprender] ✅ Respuesta correcta para ejercicio", ej.id);

      setFeedback({
        tipo: "correcto",
        mensaje: "",
        onContinue: () => {
          setFeedback(null);
          setRespuestaSeleccionada(null);

          if (indiceEjercicio + 1 < ejercicios.length) {
            console.log("[Aprender] → Pasando al siguiente ejercicio");
            setIndiceEjercicio((i) => i + 1);
          } else {
            console.log("[Aprender] 🏁 Lección completada:", leccionActual.title);
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

            console.log("[Aprender] 📊 Fin de lección:", {
              completedNow,
              totalLessons: lecciones.length,
              course: cursoActual.titulo,
            });

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
      console.warn("[Aprender] ❌ Respuesta incorrecta para ejercicio", ej.id);
      const mensaje =
        ej.explanation_md && ej.explanation_md.trim() !== "" ? ej.explanation_md : "";

      setFeedback({
        tipo: "incorrecto",
        mensaje,
        onContinue: () => {
          console.log("[Aprender] ↩ Intentando nuevamente el ejercicio...");
          setFeedback(null);
          setRespuestaSeleccionada(null);
        },
      });
    }
  };

  const handleNextAfterCompletion = () => {
    if (!finLeccion || !cursoActual) return;

    const esUltima = finLeccion.lastLessonIndex + 1 >= lecciones.length;
    console.log("[Aprender] ⏭ handleNextAfterCompletion → esÚltima:", esUltima);

    const esPrimerCurso = cursoActual.id === 1;
    const esPrimeraLeccion = finLeccion.lastLessonIndex === 0;

    if (esPrimerCurso && esPrimeraLeccion) {
      console.log("[Aprender] ⚠️ Bloqueo premium: primera lección del primer curso.");
      setMostrarModalPremium(true);
      return;
    }

    setFinLeccion(null);
    const next = lecciones[finLeccion.lastLessonIndex + 1];
    if (next && !esUltima) {
      console.log("[Aprender] → Cargando próxima lección:", next.title);
      cargarEjercicios(next);
    } else {
      console.log("[Aprender] ✅ Curso finalizado o sin más lecciones.");
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
