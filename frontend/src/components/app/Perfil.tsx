// src/components/app/Perfil.tsx
import React, { useState } from "react";

type Tab = "estadisticas" | "logros" | "amigos" | "configuracion";

const Perfil: React.FC = () => {
  const [tab, setTab] = useState<Tab>("estadisticas");

  return (
    <div className="flex bg-[var(--Blue1)] min-h-screen text-white">
      {/* Contenido principal */}
      <div className="flex-1 p-6 md:p-12">
        {/* Header */}
        <div className="bg-[var(--Blue2)] rounded-xl p-6 shadow-md flex items-center justify-between">
          <div>
            <h2 className="text-2xl font-bold">Usuario</h2>
            <p className="text-gray-400 text-sm">Miembro desde mayo 2023</p>
            <div className="flex items-center gap-2 mt-2">
              <span className="bg-cyan-500 px-3 py-1 rounded-full text-sm">
                Nivel 2
              </span>
              <span className="bg-purple-500 px-3 py-1 rounded-full text-sm">
                Racha: 3 días
              </span>
              <span className="bg-gray-700 px-3 py-1 rounded-full text-sm">
                230 XP
              </span>
            </div>
          </div>
          <div className="w-16 h-16 rounded-full bg-gray-600 flex items-center justify-center">
            🧑
          </div>
        </div>

        {/* Tabs */}
        <div className="flex space-x-8 mt-6 border-b border-gray-700">
          {(["estadisticas", "logros", "amigos", "configuracion"] as Tab[]).map(
            (t) => (
              <button
                key={t}
                className={`pb-2 px-1 capitalize ${
                  tab === t
                    ? "border-b-2 border-cyan-400 text-cyan-400"
                    : "text-gray-400"
                }`}
                onClick={() => setTab(t)}
              >
                {t}
              </button>
            )
          )}
        </div>

        {/* Contenido dinámico */}
        <div className="mt-6">
          {tab === "estadisticas" && (
            <>
              {/* Resumen */}
              <div className="grid grid-cols-3 gap-4 mb-6">
                <div className="bg-[var(--Blue2)] p-4 rounded-lg text-center">
                  <h3 className="text-2xl font-bold">230</h3>
                  <p className="text-gray-400">XP Total</p>
                </div>
                <div className="bg-[var(--Blue2)] p-4 rounded-lg text-center">
                  <h3 className="text-2xl font-bold">7</h3>
                  <p className="text-gray-400">Lecciones completadas</p>
                </div>
                <div className="bg-[var(--Blue2)] p-4 rounded-lg text-center">
                  <h3 className="text-2xl font-bold">3</h3>
                  <p className="text-gray-400">Días de racha</p>
                </div>
              </div>

              {/* Progreso */}
              <div className="space-y-4">
                <div className="bg-[var(--Blue2)] p-4 rounded-lg">
                  <p className="font-semibold">
                    Fundamentos financieros (4/5)
                  </p>
                  <div className="w-full bg-gray-700 h-3 rounded mt-2">
                    <div
                      className="bg-cyan-400 h-3 rounded"
                      style={{ width: "80%" }}
                    ></div>
                  </div>
                </div>
                <div className="bg-[var(--Blue2)] p-4 rounded-lg">
                  <p className="font-semibold">Presupuesto personal (2/3)</p>
                  <div className="w-full bg-gray-700 h-3 rounded mt-2">
                    <div
                      className="bg-purple-400 h-3 rounded"
                      style={{ width: "60%" }}
                    ></div>
                  </div>
                </div>
                <div className="bg-[var(--Blue2)] p-4 rounded-lg">
                  <p className="font-semibold">Inversiones básicas (0/3)</p>
                  <div className="w-full bg-gray-700 h-3 rounded mt-2">
                    <div
                      className="bg-teal-300 h-3 rounded"
                      style={{ width: "0%" }}
                    ></div>
                  </div>
                </div>
              </div>

              {/* Actividad */}
              <div className="mt-6">
                <h3 className="text-xl font-bold mb-4">Actividad reciente</h3>
                <div className="space-y-3">
                  <div className="bg-[var(--Blue2)] p-4 rounded-lg flex justify-between">
                    <p>Completaste: "Presupuesto personal"</p>
                    <span className="text-gray-400">Hoy</span>
                  </div>
                  <div className="bg-[var(--Blue2)] p-4 rounded-lg flex justify-between">
                    <p>Racha de 3 días</p>
                    <span className="text-gray-400">Hoy</span>
                  </div>
                  <div className="bg-[var(--Blue2)] p-4 rounded-lg flex justify-between">
                    <p>Desbloqueaste: "Primera lección"</p>
                    <span className="text-gray-400">Ayer</span>
                  </div>
                </div>
              </div>
            </>
          )}

          {tab === "logros" && <p>🎖 Aquí irán los logros del usuario.</p>}
          {tab === "amigos" && <p>👥 Aquí irán los amigos.</p>}
          {tab === "configuracion" && (
            <p>⚙️ Aquí irá la configuración de la cuenta.</p>
          )}
        </div>
      </div>

      {/* Sidebar */}
      <aside className="w-80 bg-[var(--Blue2)] p-6 hidden lg:block">
        <h3 className="font-bold mb-4">Estadísticas</h3>
        <p>Nivel: 2</p>
        <p>XP: 230/300</p>
        <div className="w-full bg-gray-700 h-3 rounded mt-2 mb-6">
          <div
            className="bg-cyan-400 h-3 rounded"
            style={{ width: "77%" }}
          ></div>
        </div>

        <h3 className="font-bold mb-4">Calendario de actividad</h3>
        <div className="bg-gray-800 p-4 rounded-lg text-center">
          📅 Aquí iría un calendario
        </div>

        <div className="mt-6">
          <button className="w-full bg-cyan-500 py-3 rounded-lg font-semibold hover:bg-cyan-600">
            Crear perfil
          </button>
        </div>
      </aside>
    </div>
  );
};

export default Perfil;
