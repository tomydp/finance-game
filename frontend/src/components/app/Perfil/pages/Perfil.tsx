// src/components/app/Perfil/pages/Perfil.tsx
import React, { useState, useMemo, useEffect } from "react";
import toast, { Toaster } from "react-hot-toast";
import { format, subDays } from "date-fns";

// hooks & types
import { usePerfilState } from "../hooks/usePerfilState";
import type { Tab, UserSettings } from "../hooks/types";
import { useUserStats } from "../hooks/useUserStats";
import api from "../../../../services/api";

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

// Helper local para leer el usuario del storage sin services
const readStoredUser = () => {
  try {
    const raw = localStorage.getItem("user");
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
};

const Perfil: React.FC = () => {
  const [tab, setTab] = useState<Tab>("estadisticas");
  const { user, setUser } = usePerfilState(defaultSettings);

  // Estado local para el plan (Premium / Gratis)
  const [isPremium, setIsPremium] = useState<boolean>(
    () => Boolean(readStoredUser()?.has_membership)
  );

  // ---- Helper: trae el perfil probando rutas reales y sincroniza storage/UI ----
  const fetchProfileFallback = async () => {
    const current = readStoredUser();
    const token = current?.token;

    // Quitamos /auth/show para evitar el 404
    const candidates = [ "/profile"];

    let data: any = null;
    for (const path of candidates) {
      try {
        const res = await api.get(path);
        data = res.data;
        break;
      } catch (e: any) {
        if (e?.response?.status === 404) continue;
        throw e; // 401/500 etc.
      }
    }
    if (!data) throw new Error("ME_ENDPOINT_NOT_FOUND");

    // Merge al localStorage conservando token
    const merged = { ...(current ?? {}), ...data, token };
    localStorage.setItem("user", JSON.stringify(merged));

    // Actualiza UI
    setUser((prev) => ({
      ...prev,
      name: merged.name || prev.name,
    }));
    setIsPremium(Boolean(merged.has_membership));

    return merged;
  };

  // ---- Cargar perfil real y sincronizar storage/UI ----
  useEffect(() => {
    (async () => {
      try {
        const merged = await fetchProfileFallback();
        console.log("[Perfil] Perfil real:", merged);
      } catch (error) {
        console.log("[Perfil] Error al obtener perfil:", error);
      }
    })();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // Stats reales (demo apagado)
  const { loading, error, data, needsAuth, isDemo, refetch } = useUserStats(
    undefined,
    { demo: false }
  );

  // Fallbacks seguros si no hay datos
  const lessonsCompleted = data?.lessons_completed ?? 0;
  const streakDays = data?.streak_days ?? 0;

  const activityDates = useMemo(() => {
    if (data?.active_days?.length) return [...data.active_days].sort();
    const today = new Date();
    return Array.from({ length: streakDays }, (_, i) =>
      format(subDays(today, i), "yyyy-MM-dd")
    );
  }, [data?.active_days, streakDays]);

  const formatLabel = (word: string) =>
    word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();

  const handleSaveName = async () => {
    try {
      const current = readStoredUser();
      const email = current?.email ?? "";
      await api.put("/profile", { name: user.name, email }); // backend requiere email
      // sincronizo el storage para reflejar en Sidebar
      if (current) {
        localStorage.setItem(
          "user",
          JSON.stringify({ ...current, name: user.name })
        );
      }
      toast.success(`Nombre cambiado a: ${user.name}`);
    } catch {
      toast.error("No se pudo actualizar el nombre");
    }
  };

  const handleLogout = async () => {
    try {
      await api.post("/logout");
    } catch {}
    localStorage.removeItem("user");
    localStorage.removeItem("isAuthenticated");
    window.location.href = "/login";
  };

  // Mostrar "(demo)" solo si no hay auth y el hook está en modo demo
  const showDemoBadge = needsAuth && isDemo;

  return (
    <div className="flex bg-[var(--Blue1)] min-h-screen text-white">
      <Toaster position="top-right" reverseOrder={false} />

      {/* Contenido principal */}
      <div className="flex-1 p-6 md:p-12">
        {/* Header */}
        <div className="bg-[var(--Blue2)] rounded-xl p-6 shadow-md flex items-center justify-between">
          <div>
            <h2 className="text-2xl font-bold">
              {user.name} {showDemoBadge && <span className="text-xs opacity-70">(demo)</span>}
            </h2>

            {/* Línea debajo del nombre: estado de plan */}
            <div className="flex items-center gap-2 mt-2">
              <span
                className={`px-3 py-1 rounded-full text-sm ${
                  isPremium ? "bg-yellow-400 text-black" : "bg-gray-600 text-white"
                }`}
              >
                {isPremium ? "Premium" : "Usuario base"}
              </span>
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
              className={`pb-2 px-1 ${
                tab === t ? "border-b-2 border-cyan-400 text-cyan-400" : "text-gray-400"
              }`}
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
              {!loading && error && (
                <div className="text-sm text-red-400">
                  Ocurrió un error al cargar tus estadísticas.
                  <button onClick={refetch} className="ml-2 underline text-red-300">
                    Reintentar
                  </button>
                </div>
              )}

              {loading ? (
                <div>
                  <p>Cargando estadísticas…</p>
                  <QuickStats lessonsCompleted={0} streak={0} />
                </div>
              ) : (
                <>
                  <QuickStats lessonsCompleted={lessonsCompleted} streak={streakDays} />
                  <ActivityCalendar
                    activityDates={activityDates}
                    anchor="lastActive"
                    clampToActivityRange={false}
                  />
                </>
              )}
            </>
          )}

          {tab === "amigos" && <FriendsList />}

          {tab === "configuracion" && (
            <>
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
                      onClick={handleSaveName}
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
                        <img
                          src={src}
                          alt={`avatar-${i}`}
                          className="w-16 h-16 rounded-full object-cover"
                        />
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

                <button
                  onClick={() => {
                    // Reseteo local (no toca BD)
                    setUser(defaultSettings);
                    const auth = readStoredUser();
                    if (auth?.id) localStorage.removeItem(`profile:${auth.id}`);
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
            onClick={handleLogout}
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
