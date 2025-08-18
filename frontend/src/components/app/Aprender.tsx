// src/components/app/Aprender.tsx
import React, { useState, useEffect } from 'react';
import {
  FaPiggyBank,
  FaUniversity,
  FaStar,
  FaBolt,
  FaCheckCircle,
} from 'react-icons/fa';
import { GoDiamond } from 'react-icons/go';

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
  estado: 'activo' | 'bloqueado' | 'completo';
  icono: React.ReactNode;
  dificultad: string;
}

const Aprender: React.FC = () => {
  const [fundamentos, setFundamentos] = useState<Module[]>([]);
  const [inversiones, setInversiones] = useState<Module[]>([]);
  const [cursoActual, setCursoActual] = useState<Module | null>(null);
  const [lecciones, setLecciones] = useState<Leccion[]>([]);
  const [leccionActual, setLeccionActual] = useState<Leccion | null>(null);
  const [ejercicios, setEjercicios] = useState<any[]>([]);

  useEffect(() => {
    const fetchCursos = async () => {
      const res = await fetch("http://localhost/api/courses");
      const json = await res.json();
      const cursos = json.data;

      const iconoPorDificultad = (dificultad: string) => {
        switch (dificultad.toLowerCase()) {
          case 'facil':
            return <FaPiggyBank className="text-cyan-400" />;
          case 'medio':
            return <FaUniversity className="text-gray-500" />;
          case 'dificil':
            return <GoDiamond className="text-gray-500" />;
          default:
            return <FaStar className="text-gray-500" />;
        }
      };

      const modulos: Module[] = await Promise.all(
        cursos.map(async (curso: any, index: number) => {
          const res = await fetch(`http://localhost/api/courses/${curso.id}/lessons`);
          const json = await res.json();
          const lecciones: Leccion[] = json.data;
          const completadas = lecciones.filter(l => l.completed).length;

          return {
            id: curso.id,
            titulo: curso.name,
            totalLecciones: lecciones.length,
            completadas,
            estado: index === 0 ? 'activo' : 'bloqueado',
            icono: iconoPorDificultad(curso.difficulty),
            dificultad: curso.difficulty,
          };
        })
      );

      setFundamentos(modulos.filter(m => m.dificultad.toLowerCase() === 'facil'));
      setInversiones(modulos.filter(m => m.dificultad.toLowerCase() !== 'facil'));
    };

    fetchCursos();
  }, []);

  const cargarLecciones = async (curso: Module) => {
    const res = await fetch(`http://localhost/api/courses/${curso.id}/lessons`);
    const json = await res.json();
    setCursoActual(curso);
    setLecciones(json.data);
  };

  const cargarEjercicios = async (leccion: Leccion) => {
    const res = await fetch(`http://localhost/api/lessons/${leccion.id}/exercises`);
    const json = await res.json();
    setLeccionActual(leccion);
    setEjercicios(json.data);
  };

  const marcarLeccionComoCompletada = async (leccionId: number) => {
    await fetch(`http://localhost/api/lessons/${leccionId}/complete`, {
      method: 'POST',
    });

    setLecciones(prev =>
      prev.map(l => (l.id === leccionId ? { ...l, completed: true } : l))
    );

    setLeccionActual(null);
    setCursoActual(null);
  };

  // Vista de ejercicios
  if (leccionActual) {
    const handleRespuesta = (op: string, ej: any) => {
      console.log("Seleccionaste:", op);
      console.log("Correcta:", ej.correct_answer); // CORREGIDO

      if (op === ej.correct_answer) {
        alert("¡Correcto! 🎉");
        marcarLeccionComoCompletada(leccionActual.id);
      } else {
        alert("Incorrecto ❌");
      }
    };

    return (
      <div className="p-6 text-white space-y-6">
        <button
          onClick={() => setLeccionActual(null)}
          className="text-cyan-400 hover:underline"
        >
          ← Volver a lecciones
        </button>
        <h2 className="text-2xl font-bold mb-4">{leccionActual.title}</h2>

        {ejercicios.map((ej, i) => (
          <div key={i} className="bg-[#121c30] p-4 rounded space-y-3">
            <h3 className="font-semibold">{ej.question}</h3>
            <ul className="space-y-2">
              {JSON.parse(ej.options).map((op: string, idx: number) => (
                <li
                  key={idx}
                  onClick={() => handleRespuesta(op, ej)}
                  className="bg-gray-800 px-3 py-2 rounded hover:bg-cyan-600 transition cursor-pointer"
                >
                  {op}
                </li>
              ))}
            </ul>
          </div>
        ))}
      </div>
    );
  }

  // Vista de lecciones
  if (cursoActual) {
    return (
      <div className="p-6 text-white space-y-6">
        <button onClick={() => setCursoActual(null)} className="text-cyan-400 hover:underline">← Volver a cursos</button>
        <h2 className="text-2xl font-bold mb-4">{cursoActual.titulo}</h2>
        <ul className="space-y-3">
          {lecciones.map(leccion => (
            <li
              key={leccion.id}
              className={`bg-[#121c30] p-4 rounded transition cursor-pointer ${leccion.completed ? 'border-l-4 border-green-400' : ''}`}
              onClick={() => cargarEjercicios(leccion)}
            >
              <h3 className="font-semibold">{leccion.title}</h3>
              {leccion.completed && <p className="text-sm text-green-400">Completada</p>}
            </li>
          ))}
        </ul>
      </div>
    );
  }

  // Vista de cursos
  const renderModulo = (m: Module) => {
    const porcentaje = Math.round((m.completadas / m.totalLecciones) * 100);
    return (
      <div key={m.id} className={`rounded-lg p-4 ${m.estado === 'bloqueado' ? 'bg-[#1a1f2e] opacity-60' : 'bg-[#121c30]'}`}>
        <div className="flex items-center gap-3 mb-2">
          <div className="text-xl">{m.icono}</div>
          <div>
            <h3 className="font-bold">{m.titulo}</h3>
            <p className="text-sm text-gray-400">
              {m.estado === 'bloqueado' ? 'Bloqueado' : `${m.completadas}/${m.totalLecciones} lecciones`}
            </p>
          </div>
        </div>
        {(m.estado === 'activo' || m.estado === 'completo') && (
          <div className="w-full h-2 bg-gray-700 rounded overflow-hidden mb-3">
            <div className="h-full bg-cyan-500" style={{ width: `${porcentaje}%` }} />
          </div>
        )}
        <button
          onClick={() => cargarLecciones(m)}
          className={`w-full py-2 rounded font-semibold text-sm ${
            m.estado === 'bloqueado'
              ? 'bg-slate-700 cursor-not-allowed'
              : m.estado === 'completo'
              ? 'bg-green-500 cursor-default'
              : 'bg-cyan-500 hover:bg-cyan-600'
          }`}
        >
          {m.estado === 'completo' ? 'COMPLETO' : m.estado === 'bloqueado' ? 'BLOQUEADO' : 'CONTINUAR'}
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
