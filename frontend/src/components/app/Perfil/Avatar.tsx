import React from "react";

type AvatarProps = {
  src: string;   // imagen del avatar
  level: number; // nivel del usuario
};

const getBorderColor = (level: number) => {
  if (level >= 10) return "border-yellow-400"; // dorado
  if (level >= 7) return "border-purple-500";  // morado
  if (level >= 4) return "border-green-500";   // verde
  return "border-blue-500";                    // azul
};

export default function Avatar({ src, level }: AvatarProps) {
  return (
    <div
      className={`w-24 h-24 rounded-full border-4 ${getBorderColor(
        level
      )} flex items-center justify-center overflow-hidden`}
    >
      <img src={src} alt="avatar" className="w-full h-full object-cover" />
    </div>
  );
}
