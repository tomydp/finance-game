// src/components/app/aprender/hooks/types.ts
import React from "react";

export interface Leccion {
  id: number;
  title: string;
  completed: boolean;
}

export interface Module {
  id: number;
  titulo: string;
  totalLecciones: number;
  completadas: number;
  estado: "activo" | "bloqueado" | "completo";
  icono: React.ReactNode;
  dificultad: string;
  orden: number;
}

export type TipoEjercicio = "multiple_choice" | "fill_blank";

export type Feedback =
  | {
      tipo: "correcto" | "incorrecto";
      mensaje: string;
      onContinue: () => void;
    }
  | null;

export interface FinLeccion {
  totalExercises: number;
  multipleChoice: number;
  fillIn: number;
  completedLessons: number;
  totalLessons: number;
  lessonTitle: string;
  courseTitle: string;
  lastLessonIndex: number;
}
