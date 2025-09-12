import React, { useState, useEffect } from "react";
import Avatar from "./Avatar";
import { avatars } from "./avatars";
import ActivityCalendar from "./ActivityCalendar";
import QuickStats from "./QuickStats";
import { format, subDays } from "date-fns";
import { userData } from "../../../data/userData";
import FriendsList from "./FriendsList";
import toast, { Toaster } from "react-hot-toast";
import ConfiguracionTab from "./ConfigurationTab";

type Tab = "estadisticas" | "amigos" | "configuracion";

const Perfil: React.FC = () => {
  const [tab, setTab] = useState<Tab>("estadisticas");

  const [user, setUser] = useState({
    name: "Usuario Demo",
    level: 5,
    avatar: avatars[0],
    memberSince: "mayo 2023",
    showStreak: true,
    showAchievements: true,
  });

  useEffect(() => {
    const saved = localStorage.getItem("settings");
    if (saved) setUser((prev) => ({ ...prev, ...JSON.parse(saved) }));
  }, []);

  useEffect(() => {
    localStorage.setItem("settings", JSON.stringify(user));
  }, [user]);

  const today = new Date();
  const activityDates = Array.from({ length: userData.streak }, (_, i) =>
    format(subDays(today, i), "yyyy-MM-dd")
  );

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
            <h2 className="text-2xl font-bold">{user.name}</h2>
            <p className="text-gray-400 text-sm">
              Miembro desde {user.memberSince}
            </p>
            <div className="flex items-center gap-2 mt-2">
              <span className="bg-cyan-500 px-3 py-1 rounded-full text-sm">
                Nivel {user.level}
              </span>
              {user.showStreak && (
                <span className="bg-purple-500 px-3 py-1 rounded-full text-sm">
                  Racha: {userData.streak} días
                </span>
              )}
              <span className="bg-gray-700 px-3 py-1 rounded-full text-sm">
                {userData.xp} XP
              </span>
            </div>
          </div>

          {/* Avatar centrado */}
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
                tab === t
                  ? "border-b-2 border-cyan-400 text-cyan-400"
                  : "text-gray-400"
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
              <QuickStats
                xp={userData.xp}
                lessonsCompleted={userData.lessonsCompleted}
                streak={userData.streak}
              />
              {/* Calendario mensual dentro de estadísticas */}
              <ActivityCalendar activityDates={activityDates} />
            </>
          )}

          {tab === "amigos" && <FriendsList />}

          {tab === "configuracion" && (
            <>
              {/* Bloque original de nombre/avatar/privacidad */}
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
                      onChange={(e) =>
                        setUser({ ...user, name: e.target.value })
                      }
                      className="flex-1 p-3 rounded-lg bg-[var(--Blue2)] border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500 transition"
                      placeholder="Escribe tu nombre"
                    />
                    <button
                      onClick={() =>
                        toast.success(`Nombre cambiado a: ${user.name}`)
                      }
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
                    {/* Toggle Racha */}
                    <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
                      <span className="text-sm">Mostrar racha a amigos</span>
                      <button
                        onClick={() =>
                          setUser({ ...user, showStreak: !user.showStreak })
                        }
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
                          setUser({
                            ...user,
                            showAchievements: !user.showAchievements,
                          })
                        }
                        className={`w-12 h-6 flex items-center rounded-full p-1 transition ${
                          user.showAchievements ? "bg-cyan-500" : "bg-gray-600"
                        }`}
                      >
                        <div
                          className={`bg-white w-4 h-4 rounded-full shadow-md transform transition ${
                            user.showAchievements
                              ? "translate-x-6"
                              : "translate-x-0"
                          }`}
                        />
                      </button>
                    </div>
                  </div>
                </div>

                {/* Resetear ajustes */}
                <button
                  onClick={() => {
                    setUser({
                      name: "Usuario Demo",
                      level: 5,
                      avatar: avatars[0],
                      memberSince: "mayo 2023",
                      showStreak: true,
                      showAchievements: true,
                    });
                    localStorage.removeItem("settings");
                    toast("Configuración reseteada ✨");
                  }}
                  className="mt-6 px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-sm"
                >
                  Resetear ajustes
                </button>
              </div>

              {/* Configuración avanzada */}
              <ConfiguracionTab />
            </>
          )}
        </div>
      </div>

      {/* Sidebar gamer */}
      <aside className="w-80 bg-[var(--Blue2)] p-6 hidden lg:block space-y-6">
        {/* Nivel */}
        <div className="bg-[var(--Blue1)] p-4 rounded-lg shadow flex items-center gap-3">
          <span className="text-cyan-400 text-xl">⭐</span>
          <div>
            <p className="text-gray-400 text-xs">Nivel</p>
            <p className="font-bold">{user.level}</p>
          </div>
        </div>

        {/* XP */}
        <div className="bg-[var(--Blue1)] p-4 rounded-lg shadow">
          <p className="text-gray-400 text-xs">Experiencia</p>
          <p className="font-bold mb-2">
            {userData.xp}/{userData.xpNext} XP
          </p>
          <div className="w-full bg-gray-700 h-3 rounded overflow-hidden">
            <div
              className="h-3 bg-gradient-to-r from-cyan-400 to-blue-500 rounded transition-all duration-700"
              style={{ width: `${(userData.xp / userData.xpNext) * 100}%` }}
            />
          </div>
        </div>

        

        {/* Botón Cerrar sesión */}
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
