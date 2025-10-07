import {
  format,
  startOfMonth,
  endOfMonth,
  eachDayOfInterval,
  getDay,
  isToday,
} from "date-fns";
import { es } from "date-fns/locale";

const ActivityCalendar = ({ activityDates }: { activityDates: string[] }) => {
  const today = new Date();
  const daysInMonth = eachDayOfInterval({
    start: startOfMonth(today),
    end: endOfMonth(today),
  });
  const startDay = getDay(startOfMonth(today));

  return (
    <div className="p-4 bg-gray-900 rounded-xl shadow-md max-w-md mx-auto">
      <h3 className="text-lg font-bold mb-1 text-center">📅 Actividad Reciente</h3>
      <p className="text-gray-400 text-sm text-center mb-3">
        {format(today, "MMMM yyyy", { locale: es })}
      </p>

      <div className="grid grid-cols-7 gap-1 text-center text-gray-400 mb-2 text-xs">
        {["L", "M", "X", "J", "V", "S", "D"].map((d) => (
          <div key={d} className="font-semibold">{d}</div>
        ))}
      </div>

      <div className="grid grid-cols-7 gap-1 text-center">
        {Array.from({ length: startDay === 0 ? 6 : startDay - 1 }).map((_, i) => (
          <div key={`empty-${i}`} />
        ))}
        {daysInMonth.map((date) => {
          const dateStr = format(date, "yyyy-MM-dd");
          const isActive = activityDates.includes(dateStr);
          return (
            <div
              key={dateStr}
              className={`w-8 h-8 flex items-center justify-center rounded-md text-xs font-medium
                ${isToday(date) ? "border-2 border-yellow-400" : "border border-transparent"}
                ${isActive ? "bg-cyan-500 text-white" : "bg-gray-800 text-gray-500"}`}
              title={format(date, "dd/MM/yyyy", { locale: es })}
            >
              {format(date, "d")}
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default ActivityCalendar;
