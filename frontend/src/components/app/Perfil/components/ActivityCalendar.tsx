import React, { useMemo, useState } from "react";
import {
  addMonths,
  subMonths,
  startOfMonth,
  endOfMonth,
  startOfWeek,
  endOfWeek,
  isSameMonth,
  isSameDay,
  format,
} from "date-fns";
import { es } from "date-fns/locale";

type Props = {
  activityDates: string[];
  anchor?: "today" | "lastActive";
  clampToActivityRange?: boolean;
};

const pad = (n: number) => String(n).padStart(2, "0");
const ymd = (d: Date) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

const ActivityCalendar: React.FC<Props> = ({
  activityDates,
  anchor = "today",
  clampToActivityRange = false,
}) => {
  const activeSet = useMemo(() => new Set(activityDates), [activityDates]);
  const lastActive = useMemo(
    () => (activityDates.length ? new Date(activityDates[activityDates.length - 1] + "T00:00:00") : null),
    [activityDates]
  );

  const [currentMonth, setCurrentMonth] = useState<Date>(() => {
    if (anchor === "lastActive" && lastActive) {
      return new Date(lastActive.getFullYear(), lastActive.getMonth(), 1);
    }
    const t = new Date();
    return new Date(t.getFullYear(), t.getMonth(), 1);
  });

  const minMonth = useMemo(() => {
    if (!clampToActivityRange || !activityDates.length) return null;
    const first = new Date(activityDates[0] + "T00:00:00");
    return new Date(first.getFullYear(), first.getMonth(), 1);
  }, [activityDates, clampToActivityRange]);

  const maxMonth = useMemo(() => {
    if (!clampToActivityRange || !activityDates.length) return null;
    const last = new Date(activityDates[activityDates.length - 1] + "T00:00:00");
    return new Date(last.getFullYear(), last.getMonth(), 1);
  }, [activityDates, clampToActivityRange]);

  const canPrev = !minMonth || currentMonth > minMonth!;
  const canNext = !maxMonth || currentMonth < maxMonth!;

  const goPrev = () => canPrev && setCurrentMonth((m) => subMonths(m, 1));
  const goNext = () => canNext && setCurrentMonth((m) => addMonths(m, 1));

  const monthStart = startOfMonth(currentMonth);
  const monthEnd = endOfMonth(currentMonth);
  const gridStart = startOfWeek(monthStart, { weekStartsOn: 1 });
  const gridEnd = endOfWeek(monthEnd, { weekStartsOn: 1 });

  const today = new Date();
  const cells: Date[] = [];
  for (let d = new Date(gridStart); d <= gridEnd; d.setDate(d.getDate() + 1)) {
    cells.push(new Date(d));
  }

  const weekHeaders = ["L", "M", "X", "J", "V", "S", "D"];

  return (
    <div className="flex justify-center w-full mt-6">
      <div className="rounded-xl border border-gray-700 bg-[var(--Blue2)]/40 p-5 text-center w-fit scale-110">
        {/* Header */}
        <div className="flex items-center justify-center gap-3 mb-3">
          <button
            aria-label="Mes anterior"
            onClick={goPrev}
            disabled={!canPrev}
            className={`h-7 w-7 rounded-md border border-gray-700 flex items-center justify-center
              ${canPrev ? "hover:bg-gray-700/40" : "opacity-40 cursor-not-allowed"}`}
          >
            ‹
          </button>

          <div className="text-[12px] font-semibold tracking-wide select-none uppercase text-gray-200">
            {format(currentMonth, "LLLL yyyy", { locale: es })}
          </div>

          <button
            aria-label="Mes siguiente"
            onClick={goNext}
            disabled={!canNext}
            className={`h-7 w-7 rounded-md border border-gray-700 flex items-center justify-center
              ${canNext ? "hover:bg-gray-700/40" : "opacity-40 cursor-not-allowed"}`}
          >
            ›
          </button>
        </div>

        {/* Week headers */}
        <div className="grid grid-cols-7 gap-1 text-center text-[11px] text-gray-400 mb-2">
          {weekHeaders.map((h) => (
            <div key={h}>{h}</div>
          ))}
        </div>

        {/* Grid */}
        <div className="grid grid-cols-7 gap-1">
          {cells.map((d) => {
            const inMonth = isSameMonth(d, monthStart);
            const key = ymd(d);
            const hasActivity = activeSet.has(key);
            const isToday = isSameDay(d, today);

            const base =
              "flex items-center justify-center rounded-md text-[11px] md:text-sm select-none transition-colors";
            const size = "h-8 w-8 md:h-9 md:w-9";
            const border = "border border-gray-700";
            const text = inMonth ? "text-white" : "text-gray-600";

            const bg = hasActivity
              ? "bg-cyan-400 text-gray-900 hover:bg-cyan-300"
              : "bg-[var(--Blue1)]/30 hover:bg-[var(--Blue1)]/50";

            const ring = isToday && !hasActivity ? "ring-1 ring-cyan-400" : "";

            return (
              <div
                key={key}
                title={format(d, "d 'de' MMMM, yyyy", { locale: es })}
                className={`${base} ${size} ${border} ${text} ${bg} ${ring}`}
              >
                {d.getDate()}
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
};

export default ActivityCalendar;
