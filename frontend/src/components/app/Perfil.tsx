import React, { useState, useEffect } from "react";
import toast, { Toaster } from "react-hot-toast";
import {
  format,
  subDays,
  startOfMonth,
  endOfMonth,
  eachDayOfInterval,
  getDay,
  isToday,
} from "date-fns";
import { es } from "date-fns/locale";
import { FaStar, FaBook, FaFire } from "react-icons/fa";
import CountUp from "react-countup";

// ==== Avatares disponibles ====
const avatars = [
  "/avatars/avatar1.png",
  "/avatars/avatar2.png",
  "/avatars/avatar3.png",
  "/avatars/avatar4.png",
  "/avatars/avatar5.png",
];

// ==== Avatar ====
const getBorderColor = (level: number) => {
  if (level >= 10) return "border-yellow-400";
  if (level >= 7) return "border-purple-500";
  if (level >= 4) return "border-green-500";
  return "border-blue-500";
};
const Avatar = ({ src, level }: { src: string; level: number }) => (
  <div
    className={`w-24 h-24 rounded-full border-4 ${getBorderColor(
      level
    )} flex items-center justify-center overflow-hidden`}
  >
    <img src={src} alt="avatar" className="w-full h-full object-cover" />
  </div>
);

// ==== Calendario de Actividad ====
const ActivityCalendar = ({ activityDates }: { activityDates: string[] }) => {
  const today = new Date();
  const daysInMonth = eachDayOfInterval({
    start: startOfMonth(today),
    end: endOfMonth(today),
  });
  const startDay = getDay(startOfMonth(today));

  return (
    <div className="p-4 bg-gray-900 rounded-xl shadow-md max-w-md mx-auto">
      <h3 className="text-lg font-bold mb-1 text-center">📅 Actividad Reciente</h3>
      <p className="text-gray-400 text-sm text-center mb-3">
        {format(today, "MMMM yyyy", { locale: es })}
      </p>
      <div className="grid grid-cols-7 gap-1 text-center text-gray-400 mb-2 text-xs">
        {["L", "M", "X", "J", "V", "S", "D"].map((d) => (
          <div key={d} className="font-semibold">{d}</div>
        ))}
      </div>
      <div className="grid grid-cols-7 gap-1 text-center">
        {Array.from({ length: startDay === 0 ? 6 : startDay - 1 }).map((_, i) => (
          <div key={`empty-${i}`} />
        ))}
        {daysInMonth.map((date) => {
          const dateStr = format(date, "yyyy-MM-dd");
          const isActive = activityDates.includes(dateStr);
          return (
            <div
              key={dateStr}
              className={`w-8 h-8 flex items-center justify-center rounded-md text-xs font-medium
                ${isToday(date) ? "border-2 border-yellow-400" : "border border-transparent"}
                ${isActive ? "bg-cyan-500 text-white" : "bg-gray-800 text-gray-500"}`}
              title={format(date, "dd/MM/yyyy", { locale: es })}
            >
              {format(date, "d")}
            </div>
          );
        })}
      </div>
    </div>
  );
};

// ==== QuickStats ====
const QuickStats = ({
  xp,
  lessonsCompleted,
  streak,
}: {
  xp: number;
  lessonsCompleted: number;
  streak: number;
}) => {
  const stats = [
    { label: "XP Total", value: xp, icon: <FaStar className="text-yellow-400 text-2xl" /> },
    { label: "Lecciones completadas", value: lessonsCompleted, icon: <FaBook className="text-blue-400 text-2xl" /> },
    { label: "Días de racha", value: streak, icon: <FaFire className="text-orange-500 text-2xl" /> },
  ];
  return (
    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      {stats.map((stat, i) => (
        <div key={i} className="bg-[var(--Blue2)] p-4 rounded-lg flex flex-col items-center justify-center shadow hover:shadow-lg transition">
          {stat.icon}
          <h3 className="text-2xl font-bold mt-2">
            <CountUp end={stat.value} duration={1.5} />
          </h3>
          <p className="text-gray-400 text-sm text-center">{stat.label}</p>
        </div>
      ))}
    </div>
  );
};

// ==== Lista de amigos ====
const friends = [
  { id: 1, name: "Valentín", avatar: "/avatars/avatar2.png", level: 5, streak: 10 },
  { id: 2, name: "Agustín", avatar: "/avatars/avatar3.png", level: 3, streak: 4 },
  { id: 3, name: "Lucía", avatar: "/avatars/avatar4.png", level: 7, streak: 22 },
];
const FriendsList: React.FC = () => (
  <div className="grid gap-4">
    {friends.map((friend) => (
      <div key={friend.id} className="flex items-center gap-4 p-4 bg-[var(--Blue2)] rounded-lg shadow">
        <img src={friend.avatar} alt={friend.name} className="w-12 h-12 rounded-full object-cover" />
        <div className="flex-1">
          <h3 className="font-bold">{friend.name}</h3>
          <p className="text-sm text-gray-400">Nivel {friend.level} • 🔥 {friend.streak} días</p>
        </div>
      </div>
    ))}
  </div>
);

// ==== Configuración avanzada ====
const ConfiguracionTab: React.FC = () => {
  const [showPasswordForm, setShowPasswordForm] = useState(false);
  const [currentPassword, setCurrentPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [errors, setErrors] = useState<{ [key: string]: string }>({});
  const [notificationsEnabled, setNotificationsEnabled] = useState(false);

  useEffect(() => {
    const saved = localStorage.getItem("notificationsEnabled");
    if (saved) setNotificationsEnabled(JSON.parse(saved));
  }, []);
  useEffect(() => {
    localStorage.setItem("notificationsEnabled", JSON.stringify(notificationsEnabled));
  }, [notificationsEnabled]);

  const validatePassword = (password: string) => {
    const errors: string[] = [];
    if (password.length < 8) errors.push("Debe tener al menos 8 caracteres");
    if (!/[A-Z]/.test(password)) errors.push("Debe contener una mayúscula");
    if (!/[a-z]/.test(password)) errors.push("Debe contener una minúscula");
    if (!/[0-9]/.test(password)) errors.push("Debe contener un número");
    if (!/[!@#$%^&*]/.test(password)) errors.push("Debe contener un símbolo (!@#$%^&*)");
    return errors;
  };

  const handleSubmitPassword = (e: React.FormEvent) => {
    e.preventDefault();
    const newErrors: { [key: string]: string } = {};
    const passwordErrors = validatePassword(newPassword);
    if (passwordErrors.length > 0) newErrors.newPassword = passwordErrors.join(", ");
    if (newPassword !== confirmPassword) newErrors.confirmPassword = "Las contraseñas no coinciden";
    setErrors(newErrors);

    if (Object.keys(newErrors).length === 0) {
      toast.success("Contraseña cambiada con éxito (modo demo)");
      setCurrentPassword(""); setNewPassword(""); setConfirmPassword("");
      setShowPasswordForm(false);
    }
  };

  return (
    <div className="space-y-8">
      {/* 🔑 Contraseña */}
      <div>
        <label className="block text-xs uppercase tracking-wide text-gray-400 mb-4">Contraseña</label>
        <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
          <span className="text-sm">Cambiar contraseña</span>
          <button onClick={() => setShowPasswordForm(!showPasswordForm)} className="px-3 py-1 bg-cyan-500 hover:bg-cyan-600 rounded-md text-sm font-semibold">
            {showPasswordForm ? "Cerrar" : "Cambiar"}
          </button>
        </div>
        {showPasswordForm && (
          <form onSubmit={handleSubmitPassword} className="mt-4 space-y-3 p-4 bg-[var(--Blue2)] rounded-lg">
            <input type="password" placeholder="Contraseña actual" value={currentPassword} onChange={(e) => setCurrentPassword(e.target.value)} className="w-full rounded-md bg-gray-800 text-white p-2 focus:ring-2 focus:ring-cyan-500" />
            <input type="password" placeholder="Nueva contraseña" value={newPassword} onChange={(e) => setNewPassword(e.target.value)} className="w-full rounded-md bg-gray-800 text-white p-2 focus:ring-2 focus:ring-cyan-500" />
            {errors.newPassword && <p className="text-red-400 text-sm">{errors.newPassword}</p>}
            <input type="password" placeholder="Confirmar nueva contraseña" value={confirmPassword} onChange={(e) => setConfirmPassword(e.target.value)} className="w-full rounded-md bg-gray-800 text-white p-2 focus:ring-2 focus:ring-cyan-500" />
            {errors.confirmPassword && <p className="text-red-400 text-sm">{errors.confirmPassword}</p>}
            <button type="submit" className="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded-md">Guardar nueva contraseña</button>
          </form>
        )}
      </div>

      {/* 🔔 Notificaciones */}
      <div>
        <label className="block text-xs uppercase tracking-wide text-gray-400 mb-4">Notificaciones</label>
        <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
          <span className="text-sm">Recibir notificaciones</span>
          <button
            onClick={() => {
              setNotificationsEnabled(!notificationsEnabled);
              toast.success(notificationsEnabled ? "Notificaciones desactivadas" : "Notificaciones activadas");
            }}
            className={`relative inline-flex h-6 w-12 items-center rounded-full transition ${notificationsEnabled ? "bg-cyan-600" : "bg-gray-600"}`}
          >
            <span className={`inline-block h-5 w-5 transform rounded-full bg-white transition ${notificationsEnabled ? "translate-x-6" : "translate-x-1"}`} />
          </button>
        </div>
      </div>
    </div>
  );
};

// ==== Datos simulados de usuario ====
const userData = { xp: 230, xpNext: 500, lessonsCompleted: 12, streak: 5 };

// ==== Perfil Principal ====
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
            <p className="text-gray-400 text-sm">Miembro desde {user.memberSince}</p>
            <div className="flex items-center gap-2 mt-2">
              <span className="bg-cyan-500 px-3 py-1 rounded-full text-sm">Nivel {user.level}</span>
              {user.showStreak && (
                <span className="bg-purple-500 px-3 py-1 rounded-full text-sm">Racha: {userData.streak} días</span>
              )}
              <span className="bg-gray-700 px-3 py-1 rounded-full text-sm">{userData.xp} XP</span>
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
              <QuickStats xp={userData.xp} lessonsCompleted={userData.lessonsCompleted} streak={userData.streak} />
              <ActivityCalendar activityDates={activityDates} />
            </>
          )}
          {tab === "amigos" && <FriendsList />}
          {tab === "configuracion" && (
            <>
              {/* Configuración básica */}
              <div className="space-y-8">
                {/* Nombre */}
                <div>
                  <label className="block text-xs uppercase tracking-wide text-gray-400 mb-2">Nombre de usuario</label>
                  <div className="flex gap-2">
                    <input type="text" value={user.name} onChange={(e) => setUser({ ...user, name: e.target.value })}
                      className="flex-1 p-3 rounded-lg bg-[var(--Blue2)] border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500 transition"
                      placeholder="Escribe tu nombre" />
                    <button onClick={() => toast.success(`Nombre cambiado a: ${user.name}`)}
                      className="px-4 py-2 rounded-lg bg-cyan-500 hover:bg-cyan-600 font-semibold transition">
                      Guardar
                    </button>
                  </div>
                </div>
                {/* Avatares */}
                <div>
                  <label className="block text-xs uppercase tracking-wide text-gray-400 mb-2">Elegí tu avatar</label>
                  <div className="grid grid-cols-5 gap-4 mb-6">
                    {avatars.map((src, i) => (
                      <button key={i} onClick={() => setUser({ ...user, avatar: src })}
                        className={`rounded-full border-2 transition ${
                          user.avatar === src ? "border-cyan-400 ring-2 ring-cyan-400"
                          : "border-transparent hover:border-gray-500"}`}>
                        <img src={src} alt={`avatar-${i}`} className="w-16 h-16 rounded-full object-cover" />
                      </button>
                    ))}
                  </div>
                </div>
                {/* Privacidad */}
                <div>
                  <label className="block text-xs uppercase tracking-wide text-gray-400 mb-4">Privacidad</label>
                  <div className="flex flex-col gap-4">
                    {/* Toggle Racha */}
                    <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
                      <span className="text-sm">Mostrar racha a amigos</span>
                      <button onClick={() => setUser({ ...user, showStreak: !user.showStreak })}
                        className={`w-12 h-6 flex items-center rounded-full p-1 transition ${
                          user.showStreak ? "bg-cyan-500" : "bg-gray-600"}`}>
                        <div className={`bg-white w-4 h-4 rounded-full shadow-md transform transition ${
                          user.showStreak ? "translate-x-6" : "translate-x-0"}`} />
                      </button>
                    </div>
                    {/* Toggle Logros */}
                    <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
                      <span className="text-sm">Mostrar logros a amigos</span>
                      <button onClick={() => setUser({ ...user, showAchievements: !user.showAchievements })}
                        className={`w-12 h-6 flex items-center rounded-full p-1 transition ${
                          user.showAchievements ? "bg-cyan-500" : "bg-gray-600"}`}>
                        <div className={`bg-white w-4 h-4 rounded-full shadow-md transform transition ${
                          user.showAchievements ? "translate-x-6" : "translate-x-0"}`} />
                      </button>
                    </div>
                  </div>
                </div>
                {/* Resetear ajustes */}
                <button onClick={() => {
                  setUser({
                    name: "Usuario Demo", level: 5, avatar: avatars[0],
                    memberSince: "mayo 2023", showStreak: true, showAchievements: true,
                  });
                  localStorage.removeItem("settings");
                  toast("Configuración reseteada ✨");
                }} className="mt-6 px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-sm">
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
        <div className="bg-[var(--Blue1)] p-4 rounded-lg shadow flex items-center gap-3">
          <span className="text-cyan-400 text-xl">⭐</span>
          <div>
            <p className="text-gray-400 text-xs">Nivel</p>
            <p className="font-bold">{user.level}</p>
          </div>
        </div>
        <div className="bg-[var(--Blue1)] p-4 rounded-lg shadow">
          <p className="text-gray-400 text-xs">Experiencia</p>
          <p className="font-bold mb-2">{userData.xp}/{userData.xpNext} XP</p>
          <div className="w-full bg-gray-700 h-3 rounded overflow-hidden">
            <div className="h-3 bg-gradient-to-r from-cyan-400 to-blue-500 rounded transition-all duration-700"
              style={{ width: `${(userData.xp / userData.xpNext) * 100}%` }} />
          </div>
        </div>
        <div className="mt-6">
          <button onClick={() => (window.location.href = "/login")}
            className="w-full bg-red-500 py-3 rounded-lg font-semibold hover:bg-red-600 transition">
            Cerrar sesión
          </button>
        </div>
      </aside>
    </div>
  );
};

export default Perfil;
