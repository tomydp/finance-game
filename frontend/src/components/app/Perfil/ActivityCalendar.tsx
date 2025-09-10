import React from "react";
import { format, subDays } from "date-fns";
import { es } from "date-fns/locale";

type Props = {
  activityDates: string[]; // lista de fechas con actividad ["2025-09-07","2025-09-09"]
};

export default function ActivityCalendar({ activityDates }: Props) {
  // Generar últimos 7 días
  const today = new Date();
  const last7Days = Array.from({ length: 7 }, (_, i) =>
    subDays(today, 6 - i) // de más antiguo a hoy
  );

  return (
    <div className="flex justify-between">
      {last7Days.map((date) => {
        const dayLabel = format(date, "EEEEE", { locale: es }); // primera letra (L, M, X…)
        const dateStr = format(date, "yyyy-MM-dd"); // formato clave
        const isActive = activityDates.includes(dateStr);

        return (
          <div
            key={dateStr}
            className={`w-10 h-10 flex items-center justify-center rounded-full font-bold
              ${isActive ? "bg-cyan-500 text-white" : "bg-gray-700 text-gray-400"}
            `}
            title={format(date, "dd/MM")} // tooltip con fecha
          >
            {dayLabel.toUpperCase()}
          </div>
        );
      })}
    </div>
  );
}
