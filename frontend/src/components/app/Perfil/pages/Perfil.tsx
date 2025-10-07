// src/components/app/Perfil/pages/Perfil.tsx
import React, { useState, useMemo } from "react";
import toast, { Toaster } from "react-hot-toast";
import { format, subDays } from "date-fns";

// hooks & types
import { usePerfilState } from "../hooks/usePerfilState";
import type { Tab, UserSettings } from "../hooks/types";
import { useUserStats } from "../hooks/useUserStats";

// components
import Avatar from "../components/Avatar";
import QuickStats from "../components/QuickStats";
import ActivityCalendar from "../components/ActivityCalendar";
import FriendsList from "../components/FriendsList";
import ConfiguracionTab from "../components/ConfiguracionTab";

const avatars = [
  "/avatars/avatar1.png",
  "/avatars/avatar2.png",
  "/avatars/avatar3.png",
  "/avatars/avatar4.png",
  "/avatars/avatar5.png",
];

const defaultSettings: UserSettings = {
  name: "Usuario Demo",
  level: 5,
  avatar: avatars[0],
  memberSince: "mayo 2023",
  showStreak: true,
  showAchievements: true,
};

const Perfil: React.FC = () => {
  const [tab, setTab] = useState<Tab>("estadisticas");
  const { user, setUser } = usePerfilState(defaultSettings);

  // Demo activado: si no hay auth, muestra demo (incluye active_days de ejemplo)
  const { loading, error, data, needsAuth, isDemo, refetch } = useUserStats(undefined, { demo: true });

  const lessonsCompleted = data?.lessons_completed ?? 0;
  const streakDays = data?.streak_days ?? 0;
  const longestStreak = data?.longest_streak ?? 0;
  const lastActiveAt = data?.last_active_at ?? null;

  // Histórico: si vienen active_days los usamos; si no, hacemos fallback a la racha actual
  const activityDates = useMemo(() => {
    if (data?.active_days?.length) {
      return [...data.active_days].sort(); // YYYY-MM-DD
    }
    const today = new Date();
    return Array.from({ length: streakDays }, (_, i) =>
      format(subDays(today, i), "yyyy-MM-dd")
    );
  }, [data?.active_days, streakDays]);

  const formatLabel = (word: string) =>
    word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();

  return (
    <div className="flex bg-[var(--Blue1)] min-h-screen text-white">
      <Toaster position="top-right" reverseOrder={false} />

      {/* Contenido principal */}
      <div className="flex-1 p-6 md:p-12">
        {/* Header */}
        <div className="bg-[var(--Blue2)] rounded-xl p-6 shadow-md flex items-center justify-between">
          <div>
            <h2 className="text-2xl font-bold">
              {user.name} {isDemo && <span className="text-xs opacity-70">(demo)</span>}
            </h2>
            <p className="text-gray-400 text-sm">Miembro desde {user.memberSince}</p>
            <div className="flex items-center gap-2 mt-2">
              <span className="bg-cyan-500 px-3 py-1 rounded-full text-sm">Nivel {user.level}</span>
              {user.showStreak && (
                <span className="bg-purple-500 px-3 py-1 rounded-full text-sm">
                  Racha: {loading ? "…" : `${streakDays} días`}
                </span>
              )}
            </div>
          </div>
          <div className="flex flex-col items-center gap-2">
            <Avatar src={user.avatar} level={user.level} />
          </div>
        </div>

        {/* Tabs */}
        <div className="flex space-x-8 mt-6 border-b border-gray-700">
          {(["estadisticas", "amigos", "configuracion"] as Tab[]).map((t) => (
            <button
              key={t}
              className={`pb-2 px-1 ${tab === t ? "border-b-2 border-cyan-400 text-cyan-400" : "text-gray-400"}`}
              onClick={() => setTab(t)}
            >
              {formatLabel(t)}
            </button>
          ))}
        </div>

        {/* Contenido dinámico */}
        <div className="mt-6 space-y-8">
          {tab === "estadisticas" && (
            <>
              {needsAuth && isDemo && (
                <div>
                  <p>Mostrando estadísticas de demo (iniciá sesión para ver tus datos reales).</p>
                </div>
              )}

              {loading && (
                <div>
                  <p>Cargando estadísticas…</p>
                  <QuickStats lessonsCompleted={0} streak={0} />
                </div>
              )}

              {error && !isDemo && (
                <div>
                  <p>Ocurrió un error al cargar tus estadísticas.</p>
                  <button onClick={refetch}>Reintentar</button>
                </div>
              )}

              {!loading && (data || isDemo) && (
                <>
                  <QuickStats lessonsCompleted={lessonsCompleted} streak={streakDays} />

                  {/* 👇 Ahora el calendario puede mostrar meses pasados */}
                  <ActivityCalendar
                    activityDates={activityDates}  // tu array YYYY-MM-DD (histórico)
                    anchor="lastActive"            // arranca en el último mes con actividad
                    clampToActivityRange={false}   // poné true si querés limitar a meses con actividad
                  />
                </>
              )}
            </>
          )}

          {tab === "amigos" && <FriendsList />}

          {tab === "configuracion" && (
            <>
              {/* Configuración básica */}
              <div className="space-y-8">
                {/* Nombre */}
                <div>
                  <label className="block text-xs uppercase tracking-wide text-gray-400 mb-2">
                    Nombre de usuario
                  </label>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      value={user.name}
                      onChange={(e) => setUser({ ...user, name: e.target.value })}
                      className="flex-1 p-3 rounded-lg bg-[var(--Blue2)] border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500 transition"
                      placeholder="Escribe tu nombre"
                    />
                    <button
                      onClick={() => toast.success(`Nombre cambiado a: ${user.name}`)}
                      className="px-4 py-2 rounded-lg bg-cyan-500 hover:bg-cyan-600 font-semibold transition"
                    >
                      Guardar
                    </button>
                  </div>
                </div>

                {/* Avatares */}
                <div>
                  <label className="block text-xs uppercase tracking-wide text-gray-400 mb-2">
                    Elegí tu avatar
                  </label>
                  <div className="grid grid-cols-5 gap-4 mb-6">
                    {avatars.map((src, i) => (
                      <button
                        key={i}
                        onClick={() => setUser({ ...user, avatar: src })}
                        className={`rounded-full border-2 transition ${
                          user.avatar === src
                            ? "border-cyan-400 ring-2 ring-cyan-400"
                            : "border-transparent hover:border-gray-500"
                        }`}
                      >
                        <img src={src} alt={`avatar-${i}`} className="w-16 h-16 rounded-full object-cover" />
                      </button>
                    ))}
                  </div>
                </div>

                {/* Privacidad */}
                <div>
                  <label className="block text-xs uppercase tracking-wide text-gray-400 mb-4">
                    Privacidad
                  </label>
                  <div className="flex flex-col gap-4">
                    {/* Toggle Racha */}
                    <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
                      <span className="text-sm">Mostrar racha a amigos</span>
                      <button
                        onClick={() => setUser({ ...user, showStreak: !user.showStreak })}
                        className={`w-12 h-6 flex items-center rounded-full p-1 transition ${
                          user.showStreak ? "bg-cyan-500" : "bg-gray-600"
                        }`}
                      >
                        <div
                          className={`bg-white w-4 h-4 rounded-full shadow-md transform transition ${
                            user.showStreak ? "translate-x-6" : "translate-x-0"
                          }`}
                        />
                      </button>
                    </div>

                    {/* Toggle Logros */}
                    <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
                      <span className="text-sm">Mostrar logros a amigos</span>
                      <button
                        onClick={() =>
                          setUser({ ...user, showAchievements: !user.showAchievements })
                        }
                        className={`w-12 h-6 flex items-center rounded-full p-1 transition ${
                          user.showAchievements ? "bg-cyan-500" : "bg-gray-600"
                        }`}
                      >
                        <div
                          className={`bg-white w-4 h-4 rounded-full shadow-md transform transition ${
                            user.showAchievements ? "translate-x-6" : "translate-x-0"
                          }`}
                        />
                      </button>
                    </div>
                  </div>
                </div>

                {/* Resetear ajustes */}
                <button
                  onClick={() => {
                    setUser(defaultSettings);
                    localStorage.removeItem("settings");
                    toast("Configuración reseteada ✨");
                  }}
                  className="mt-6 px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-sm"
                >
                  Resetear ajustes
                </button>
              </div>

              <ConfiguracionTab />
            </>
          )}
        </div>
      </div>

      {/* Sidebar gamer — SIN tarjeta de experiencia */}
      <aside className="w-80 bg-[var(--Blue2)] p-6 hidden lg:block space-y-6">
        <div className="mt-6">
          <button
            onClick={() => (window.location.href = "/login")}
            className="w-full bg-red-500 py-3 rounded-lg font-semibold hover:bg-red-600 transition"
          >
            Cerrar sesión
          </button>
        </div>
      </aside>
    </div>
  );
};

export default Perfil;
