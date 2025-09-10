import React from "react";
import {
  format,
  startOfMonth,
  endOfMonth,
  eachDayOfInterval,
  getDay,
  isToday,
} from "date-fns";
import { es } from "date-fns/locale";

type Props = {
  activityDates: string[]; // ejemplo: ["2025-09-07","2025-09-09"]
};

export default function ActivityCalendar({ activityDates }: Props) {
  const today = new Date();

  const daysInMonth = eachDayOfInterval({
    start: startOfMonth(today),
    end: endOfMonth(today),
  });

  const startDay = getDay(startOfMonth(today));

  return (
    <div className="p-4 bg-gray-900 rounded-xl shadow-md max-w-md mx-auto">
      {/* Título */}
      <h3 className="text-lg font-bold mb-1 text-center">📅 Actividad Reciente</h3>
      <p className="text-gray-400 text-sm text-center mb-3">
        {format(today, "MMMM yyyy", { locale: es })}
      </p>

      {/* Encabezados de días */}
      <div className="grid grid-cols-7 gap-1 text-center text-gray-400 mb-2 text-xs">
        {["L", "M", "X", "J", "V", "S", "D"].map((d) => (
          <div key={d} className="font-semibold">
            {d}
          </div>
        ))}
      </div>

      {/* Días del mes */}
      <div className="grid grid-cols-7 gap-1 text-center">
        {/* Espacios vacíos antes del primer día */}
        {Array.from({ length: startDay === 0 ? 6 : startDay - 1 }).map(
          (_, i) => (
            <div key={`empty-${i}`} />
          )
        )}

        {/* Renderizar cada día */}
        {daysInMonth.map((date) => {
          const dateStr = format(date, "yyyy-MM-dd");
          const isActive = activityDates.includes(dateStr);

          return (
            <div
              key={dateStr}
              className={`w-8 h-8 flex items-center justify-center rounded-md text-xs font-medium
                ${
                  isToday(date)
                    ? "border-2 border-yellow-400"
                    : "border border-transparent"
                }
                ${
                  isActive
                    ? "bg-cyan-500 text-white"
                    : "bg-gray-800 text-gray-500"
                }`}
              title={format(date, "dd/MM/yyyy", { locale: es })}
            >
              {format(date, "d")}
            </div>
          );
        })}
      </div>
    </div>
  );
}
