import React from "react";

type Props = {
  level: number;
  xp: number;
  xpNext: number;
};

export default function LevelProgress({ level, xp, xpNext }: Props) {
  const size = 100; // tamaño del SVG
  const strokeWidth = 8; // grosor del círculo
  const radius = (size - strokeWidth) / 2;
  const circumference = 2 * Math.PI * radius;

  const progress = Math.min(xp / xpNext, 1); // porcentaje 0 a 1
  const offset = circumference - progress * circumference;

  return (
    <div className="flex flex-col items-center">
      <svg width={size} height={size}>
        {/* círculo de fondo */}
        <circle
          cx={size / 2}
          cy={size / 2}
          r={radius}
          stroke="#374151" // gris oscuro
          strokeWidth={strokeWidth}
          fill="none"
        />
        {/* progreso */}
        <circle
          cx={size / 2}
          cy={size / 2}
          r={radius}
          stroke="#06b6d4" // cian
          strokeWidth={strokeWidth}
          fill="none"
          strokeDasharray={circumference}
          strokeDashoffset={offset}
          strokeLinecap="round"
          transform={`rotate(-90 ${size / 2} ${size / 2})`}
        />
        {/* nivel en el centro */}
        <text
          x="50%"
          y="50%"
          dominantBaseline="middle"
          textAnchor="middle"
          fontSize="20"
          fill="white"
        >
          {level}
        </text>
      </svg>
      <p className="text-sm text-gray-400 mt-2">
        {xp}/{xpNext} XP
      </p>
    </div>
  );
}
