import React from "react";
import { FaBook, FaFire } from "react-icons/fa";

interface QuickStatsProps {
  lessonsCompleted: number;
  streak: number;
}

const QuickStats: React.FC<QuickStatsProps> = ({ lessonsCompleted, streak }) => {
  const cards = [
    {
      label: "Lecciones completadas",
      value: lessonsCompleted,
      icon: <FaBook className="text-blue-400 text-2xl" />, // ícono más grande
    },
    {
      label: "Días de racha",
      value: streak,
      icon: <FaFire className="text-orange-400 text-2xl" />,
    },
  ];

  return (
    <div className="flex justify-center w-full mt-4 mb-6">
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-lg w-full">
        {cards.map((card, idx) => (
          <div
            key={idx}
            className="flex flex-col items-center justify-center text-center bg-[var(--Blue2)] rounded-xl p-6 shadow hover:shadow-xl transition"
          >
            <div className="mb-3">{card.icon}</div>
            <p className="text-gray-400 text-sm md:text-base">{card.label}</p>
            <p className="text-3xl font-bold text-white mt-1">{card.value}</p>
          </div>
        ))}
      </div>
    </div>
  );
};

export default QuickStats;
