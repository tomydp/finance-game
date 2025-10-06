// src/components/app/aprender/components/LessonCompleted.tsx
import React from "react";

interface Props {
  courseTitle: string;
  lessonTitle: string;
  totalLessons: number;
  completedLessons: number;
  stats: { totalExercises: number; multipleChoice: number; fillIn: number };
  onBackToCourse: () => void;
  onNext: () => void;
}

const LessonCompleted: React.FC<Props> = ({
  courseTitle,
  lessonTitle,
  totalLessons,
  completedLessons,
  stats,
  onBackToCourse,
  onNext,
}) => {
  return (
    <div className="p-6 text-white space-y-6">
      <div className="bg-[#121c30] p-6 rounded-2xl">
        <h2 className="text-2xl font-bold text-emerald-400">¡Lección Completada!</h2>
        <p className="text-gray-300 mt-2">
          <strong>{lessonTitle}</strong> — {courseTitle}
        </p>

        <div className="mt-4 grid grid-cols-3 gap-4 text-center">
          <div className="bg-[#0f1725] rounded-lg p-3">
            <div className="text-xl font-bold">{stats.totalExercises}</div>
            <div className="text-xs text-gray-400">Ejercicios</div>
          </div>
          <div className="bg-[#0f1725] rounded-lg p-3">
            <div className="text-xl font-bold">{stats.multipleChoice}</div>
            <div className="text-xs text-gray-400">Opción Múltiple</div>
          </div>
          <div className="bg-[#0f1725] rounded-lg p-3">
            <div className="text-xl font-bold">{stats.fillIn}</div>
            <div className="text-xs text-gray-400">Completar</div>
          </div>
        </div>

        <p className="mt-4 text-sm text-gray-400">
          Progreso: {completedLessons}/{totalLessons} lecciones
        </p>

        <div className="mt-6 flex gap-3">
          <button onClick={onBackToCourse} className="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">
            Volver al curso
          </button>
          <button onClick={onNext} className="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 rounded-lg">
            Siguiente
          </button>
        </div>
      </div>
    </div>
  );
};

export default LessonCompleted;
