import React, { useState, useEffect } from "react";
import toast from "react-hot-toast";

export default function ConfiguracionTab() {
  // Estado para el formulario de contraseña
  const [showPasswordForm, setShowPasswordForm] = useState(false);
  const [currentPassword, setCurrentPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [errors, setErrors] = useState<{ [key: string]: string }>({});

  // Estado de notificaciones
  const [notificationsEnabled, setNotificationsEnabled] = useState(false);

  useEffect(() => {
    const saved = localStorage.getItem("notificationsEnabled");
    if (saved) setNotificationsEnabled(JSON.parse(saved));
  }, []);

  useEffect(() => {
    localStorage.setItem("notificationsEnabled", JSON.stringify(notificationsEnabled));
  }, [notificationsEnabled]);

  // Validación de contraseña
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
      setCurrentPassword("");
      setNewPassword("");
      setConfirmPassword("");
      setShowPasswordForm(false);
    }
  };

  return (
    <div className="space-y-8">
   

      {/* 🔑 Contraseña */}
      <div>
        <label className="block text-xs uppercase tracking-wide text-gray-400 mb-4">
          Contraseña
        </label>

        {/* Botón Cambiar */}
        <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
          <span className="text-sm">Cambiar contraseña</span>
          <button
            onClick={() => setShowPasswordForm(!showPasswordForm)}
            className="px-3 py-1 bg-cyan-500 hover:bg-cyan-600 rounded-md text-sm font-semibold"
          >
            {showPasswordForm ? "Cerrar" : "Cambiar"}
          </button>
        </div>

        {/* Formulario desplegable */}
        {showPasswordForm && (
          <form onSubmit={handleSubmitPassword} className="mt-4 space-y-3 p-4 bg-[var(--Blue2)] rounded-lg">
            <input
              type="password"
              placeholder="Contraseña actual"
              value={currentPassword}
              onChange={(e) => setCurrentPassword(e.target.value)}
              className="w-full rounded-md bg-gray-800 text-white p-2 focus:ring-2 focus:ring-cyan-500"
            />

            <input
              type="password"
              placeholder="Nueva contraseña"
              value={newPassword}
              onChange={(e) => setNewPassword(e.target.value)}
              className="w-full rounded-md bg-gray-800 text-white p-2 focus:ring-2 focus:ring-cyan-500"
            />
            {errors.newPassword && (
              <p className="text-red-400 text-sm">{errors.newPassword}</p>
            )}

            <input
              type="password"
              placeholder="Confirmar nueva contraseña"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              className="w-full rounded-md bg-gray-800 text-white p-2 focus:ring-2 focus:ring-cyan-500"
            />
            {errors.confirmPassword && (
              <p className="text-red-400 text-sm">{errors.confirmPassword}</p>
            )}

            <button
              type="submit"
              className="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded-md"
            >
              Guardar nueva contraseña
            </button>
          </form>
        )}
      </div>

      {/* 🔔 Notificaciones */}
      <div>
        <label className="block text-xs uppercase tracking-wide text-gray-400 mb-4">
          Notificaciones
        </label>

        <div className="flex items-center justify-between bg-[var(--Blue2)] px-4 py-3 rounded-lg">
          <span className="text-sm">Recibir notificaciones</span>
          <button
            onClick={() => {
              setNotificationsEnabled(!notificationsEnabled);
              toast.success(
                notificationsEnabled
                  ? "Notificaciones desactivadas"
                  : "Notificaciones activadas"
              );
            }}
            className={`relative inline-flex h-6 w-12 items-center rounded-full transition ${
              notificationsEnabled ? "bg-cyan-600" : "bg-gray-600"
            }`}
          >
            <span
              className={`inline-block h-5 w-5 transform rounded-full bg-white transition ${
                notificationsEnabled ? "translate-x-6" : "translate-x-1"
              }`}
            />
          </button>
        </div>
      </div>

    
    </div>
  );
}
