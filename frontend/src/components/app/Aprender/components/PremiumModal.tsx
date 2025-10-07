// src/components/app/aprender/components/PremiumModal.tsx
import React from "react";

interface Props {
  open: boolean;
  onClose: () => void;
  onGoStore?: () => void;
}

const PremiumModal: React.FC<Props> = ({ open, onClose, onGoStore }) => {
  if (!open) return null;

  return (
    <div className="fixed inset-0 flex items-center justify-center backdrop-blur-md bg-black/20 z-50">
      <div className="bg-[#101828]/95 text-white p-8 rounded-2xl shadow-2xl w-[90%] max-w-md text-center space-y-5 border border-cyan-600 backdrop-blur-sm">
        <h2 className="text-2xl font-bold text-cyan-400">Contenido Premium</h2>
        <p className="text-gray-300">
          Para continuar con las próximas lecciones necesitás tener una membresía{" "}
          <span className="text-cyan-400 font-semibold">Premium</span>.
        </p>
        <div className="flex justify-center gap-4 mt-6">
          <button onClick={onClose} className="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition">
            Volver
          </button>
          <button
            onClick={onGoStore}
            className="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 rounded-lg font-semibold transition"
          >
            Ir a la tienda
          </button>
        </div>
      </div>
    </div>
  );
};

export default PremiumModal;
