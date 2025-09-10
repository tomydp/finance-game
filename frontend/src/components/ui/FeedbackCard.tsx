// src/components/ui/FeedbackCard.tsx
import React from "react";

interface FeedbackCardProps {
  type: "success" | "error";
  message: string;
  onClose: () => void;
  onContinue?: () => void;
}

const FeedbackCard: React.FC<FeedbackCardProps> = ({
  type,
  message,
  onClose,
  onContinue,
}) => {
  const isSuccess = type === "success";

  return (
    <div
      className={`p-4 rounded-lg mt-4 text-white ${
        isSuccess ? "bg-green-600" : "bg-red-600"
      }`}
    >
      <h2 className="text-lg font-bold mb-2">
        {isSuccess ? "¡Correcto! 🎉" : "Incorrecto ❌"}
      </h2>
      <p className="mb-4">{message}</p>

      <div className="flex gap-2">
        {!isSuccess && (
          <button
            onClick={onClose}
            className="bg-white text-red-600 font-semibold px-3 py-1 rounded hover:bg-gray-200 transition"
          >
            Reintentar
          </button>
        )}

        {isSuccess && onContinue && (
          <button
            onClick={onContinue}
            className="bg-white text-green-600 font-semibold px-3 py-1 rounded hover:bg-gray-200 transition"
          >
            Continuar
          </button>
        )}
      </div>
    </div>
  );
};

export default FeedbackCard;
