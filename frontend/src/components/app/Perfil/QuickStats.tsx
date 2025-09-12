import React from "react";
import { FaStar, FaBook, FaFire } from "react-icons/fa";
import CountUp from "react-countup";

interface QuickStatsProps {
  xp: number;
  lessonsCompleted: number;
  streak: number;
}

const QuickStats: React.FC<QuickStatsProps> = ({
  xp,
  lessonsCompleted,
  streak,
}) => {
  const stats = [
    {
      label: "XP Total",
      value: xp,
      icon: <FaStar className="text-yellow-400 text-2xl" />,
    },
    {
      label: "Lecciones completadas",
      value: lessonsCompleted,
      icon: <FaBook className="text-blue-400 text-2xl" />,
    },
    {
      label: "Días de racha",
      value: streak,
      icon: <FaFire className="text-orange-500 text-2xl" />,
    },
  ];

  return (
    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      {stats.map((stat, i) => (
        <div
          key={i}
          className="bg-[var(--Blue2)] p-4 rounded-lg flex flex-col items-center justify-center shadow hover:shadow-lg transition"
        >
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

export default QuickStats;
