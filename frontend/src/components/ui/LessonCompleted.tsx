import React from "react";
import { motion } from "framer-motion";
import {
  Award,
  CheckCircle2,
  Target,
  BookOpen,
  Edit3,
  ChevronRight,
  ArrowLeft
} from "lucide-react";

/**
 * LessonCompleted
 * Pantalla de feedback al completar una lección.
 *
 * Props mínimas para integrarlo rápido en tu app.
 */
export type LessonCompletedProps = {
  courseTitle: string;
  lessonTitle: string;
  // progreso del curso
  totalLessons: number;
  completedLessons: number; // incluye la lección recién completada
  // métricas de la lección
  stats?: {
    totalExercises?: number;
    multipleChoice?: number;
    fillIn?: number;
  };
  // acciones
  onNext?: () => void; // ir a siguiente lección
  onBackToCourse?: () => void; // volver al índice del curso
};

const SparklesBackground: React.FC = () => {
  const dots = Array.from({ length: 28 });
  return (
    <div className="pointer-events-none absolute inset-0 overflow-hidden">
      {dots.map((_, i) => (
        <motion.span
          key={i}
          initial={{ opacity: 0, y: 20 }}
          animate={{
            opacity: [0, 0.9, 0],
            y: [20, -10, 20]
          }}
          transition={{ duration: 4 + (i % 6), repeat: Infinity, delay: i * 0.15 }}
          className="absolute h-1.5 w-1.5 rounded-full bg-teal-400/60 shadow-[0_0_12px_theme(colors.teal.400)]"
          style={{
            left: `${(i * 97) % 100}%`,
            top: `${(i * 37) % 100}%`
          }}
        />
      ))}
    </div>
  );
};

const MetricCard: React.FC<{
  icon: React.ReactNode;
  label: string;
  value: number | string;
}> = ({ icon, label, value }) => (
  <motion.div
    initial={{ opacity: 0, y: 16 }}
    animate={{ opacity: 1, y: 0 }}
    transition={{ duration: 0.45 }}
    className="flex flex-col items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-md dark:bg-white/5"
  >
    <div className="grid place-items-center rounded-xl bg-white/10 p-3 text-teal-300">
      {icon}
    </div>
    <div className="text-3xl font-bold text-white">{value}</div>
    <div className="text-sm text-white/70">{label}</div>
  </motion.div>
);

const ProgressBar: React.FC<{ percent: number }> = ({ percent }) => (
  <div className="w-full">
    <div className="mb-3 flex items-center justify-between text-sm">
      <div className="flex items-center gap-2 text-white/80">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" className="opacity-80"><circle cx="12" cy="12" r="10"/></svg>
        <span>Progreso del Curso</span>
      </div>
      <span className="text-white/80">{percent}%</span>
    </div>
    <div className="h-2 w-full rounded-full bg-white/10">
      <div
        className="h-2 rounded-full bg-teal-400 shadow-[0_0_24px_theme(colors.teal.400)]"
        style={{ width: `${Math.min(100, Math.max(0, percent))}%` }}
      />
    </div>
  </div>
);

const Button: React.FC<{
  children: React.ReactNode;
  onClick?: () => void;
  variant?: "primary" | "ghost";
}> = ({ children, onClick, variant = "primary" }) => (
  <button
    onClick={onClick}
    className={
      variant === "primary"
        ? "flex items-center gap-2 rounded-2xl bg-teal-400/90 px-5 py-2.5 font-medium text-slate-900 shadow-lg transition hover:bg-teal-300 focus:outline-none focus:ring-2 focus:ring-teal-400/60"
        : "flex items-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-5 py-2.5 font-medium text-white/90 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"
    }
  >
    {children}
  </button>
);

const LessonCompleted: React.FC<LessonCompletedProps> = ({
  courseTitle,
  lessonTitle,
  totalLessons,
  completedLessons,
  stats,
  onNext,
  onBackToCourse
}) => {
  const percent = Math.round((completedLessons / Math.max(1, totalLessons)) * 100);
  const finishedAll = completedLessons >= totalLessons;

  return (
    <div className="relative min-h-[92vh] w-full overflow-hidden bg-[radial-gradient(1200px_600px_at_50%_-100px,rgba(45,212,191,0.25),transparent),radial-gradient(800px_400px_at_0%_100%,rgba(59,130,246,0.18),transparent),radial-gradient(800px_400px_at_100%_100%,rgba(168,85,247,0.16),transparent)] px-6 py-14 text-white">
      <SparklesBackground />

      <div className="mx-auto max-w-5xl">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 18 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          className="mb-10 text-center"
        >
          <div className="mx-auto mb-6 grid h-24 w-24 place-items-center rounded-full bg-emerald-400/10 ring-1 ring-white/15">
            <div className="relative">
              <Award className="h-16 w-16 text-emerald-300" />
              <CheckCircle2 className="absolute -right-2 -top-2 h-7 w-7 text-sky-300" />
            </div>
          </div>
          <h1 className="mb-2 text-4xl font-black tracking-tight sm:text-5xl">¡Lección Completada!</h1>
          <p className="mx-auto max-w-2xl text-balance text-white/80">
            Has dominado exitosamente <span className="font-semibold text-teal-300">{lessonTitle}</span>
          </p>
          <p className="mt-1 text-white/60">{courseTitle}</p>
        </motion.div>

        {/* Metrics */}
        <div className="mb-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
          <MetricCard icon={<Target className="h-6 w-6" />} label="Ejercicios completados" value={stats?.totalExercises ?? 0} />
          <MetricCard icon={<BookOpen className="h-6 w-6" />} label="Preguntas de opción múltiple" value={stats?.multipleChoice ?? 0} />
          <MetricCard icon={<Edit3 className="h-6 w-6" />} label="Ejercicios de completar" value={stats?.fillIn ?? 0} />
        </div>

        {/* Progress */}
        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.45 }}
          className="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur"
        >
          <div className="mb-2 text-sm text-white/80">
            <span className="font-medium text-white">{completedLessons}</span> de <span className="font-medium text-white">{totalLessons}</span> lecciones completadas
          </div>
          <ProgressBar percent={percent} />

          <div className="mt-6 flex flex-wrap items-center justify-between gap-3">
            <div className="text-sm text-white/70">
              {finishedAll ? (
                <span>
                  ¡Completaste todo el curso! 🎉
                </span>
              ) : (
                <span>¡Excelente! Sigamos con la siguiente lección.</span>
              )}
            </div>
            <div className="flex gap-2">
              <Button variant="ghost" onClick={onBackToCourse}>
                <ArrowLeft className="h-4 w-4" /> Volver al curso
              </Button>
              <Button onClick={onNext}>
                {finishedAll ? (
                  <>Explorar más cursos <ChevronRight className="h-4 w-4" /></>
                ) : (
                  <>Siguiente lección <ChevronRight className="h-4 w-4" /></>
                )}
              </Button>
            </div>
          </div>
        </motion.div>
      </div>
    </div>
  );
};

export default LessonCompleted;

/**
 * Ejemplo de uso
 *
 * <LessonCompleted
 *   courseTitle="Fundamentos Financieros"
 *   lessonTitle="Ahorro e Inversión"
 *   totalLessons={3}
 *   completedLessons={1}
 *   stats={{ totalExercises: 3, multipleChoice: 2, fillIn: 1 }}
 *   onNext={() => navigate('/curso/fundamentos/leccion/2')}
 *   onBackToCourse={() => navigate('/curso/fundamentos')}
 * />
 */
