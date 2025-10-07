import React from "react";
import { FaMedal, FaCrown, FaRegStar, FaTrophy } from "react-icons/fa";
import type { RankingEntry } from "../hooks/useRanking";

const nf = new Intl.NumberFormat("es-AR");

type Variant = "gold" | "silver" | "bronze" | "default";

const variantStyles: Record<Variant, string> = {
  gold:   "ring-2 ring-yellow-400/70 shadow-[0_0_30px_rgba(250,204,21,.15)] bg-gradient-to-br from-yellow-900/10 via-transparent to-yellow-900/5",
  silver: "ring-2 ring-slate-300/70  shadow-[0_0_30px_rgba(203,213,225,.12)] bg-gradient-to-br from-slate-300/10 via-transparent to-slate-300/5",
  bronze: "ring-2 ring-amber-600/70  shadow-[0_0_30px_rgba(251,191,36,.12)]  bg-gradient-to-br from-amber-900/10 via-transparent to-amber-900/5",
  default:"ring-[1.5px] ring-slate-700/60 bg-[var(--Blue2)]",
};

function getVariant(pos: number): Variant {
  if (pos === 1) return "gold";
  if (pos === 2) return "silver";
  if (pos === 3) return "bronze";
  return "default";
}

const Medal: React.FC<{ pos: number }> = ({ pos }) => {
  const map = {
    1: "text-yellow-400",
    2: "text-slate-300",
    3: "text-amber-500",
  } as const;
  return <FaMedal className={`w-4 h-4 ${map[(pos as 1|2|3)] ?? "text-slate-400"}`} />;
};

const LevelBadge: React.FC<{ level: number; variant: Variant }> = ({ level, variant }) => {
  const ring =
    variant === "gold"   ? "ring-yellow-400"
  : variant === "silver" ? "ring-slate-300"
  : variant === "bronze" ? "ring-amber-500"
  : "ring-cyan-400";

  const Icon = variant === "gold" ? FaCrown : FaRegStar;

  return (
    <div className={`relative w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center bg-[#10192d] ring-2 ${ring}`}>
      <Icon className={`${variant === "gold" ? "text-yellow-400" : "text-cyan-300"} w-5 h-5 sm:w-6 sm:h-6`} />
      <span className="absolute -bottom-1 -right-1 text-[10px] px-2 py-0.5 rounded-full bg-purple-600 text-white font-bold shadow">
        {level}
      </span>
    </div>
  );
};

const PositionPill: React.FC<{ pos: number }> = ({ pos }) => (
  <div className="hidden xs:flex items-center gap-2 rounded-full border border-slate-700/60 px-3 py-1 bg-[#0e1526]/70 backdrop-blur-sm">
    <span className="w-4 text-center text-gray-300 font-semibold">{pos}</span>
    <Medal pos={pos} />
    <FaTrophy className="w-4 h-4 text-slate-500" />
  </div>
);

const LeaderboardCard: React.FC<{ entry: RankingEntry }> = ({ entry }) => {
  const variant = getVariant(entry.pos);

  return (
    <div className={`rounded-2xl px-5 sm:px-6 py-4 sm:py-5 ${variantStyles[variant]} backdrop-blur-[1px]`}>
      <div className="flex items-center gap-4 sm:gap-6">
        {/* izquierda: posición */}
        <div className="w-8 text-center font-bold text-gray-300 sm:w-10">{entry.pos}</div>

        {/* pill con medallas */}
        <PositionPill pos={entry.pos} />

        {/* badge de nivel */}
        <LevelBadge level={entry.level} variant={variant} />

        {/* centro: nombre + nivel */}
        <div className="flex-1 min-w-0">
          <div className="font-semibold truncate">{entry.name}</div>
          <div className="text-sm text-cyan-400">Nivel {entry.level}</div>
        </div>

        {/* derecha: XP */}
        <div className="text-right">
          <div className="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
            {nf.format(entry.xp)}
          </div>
          <div className="text-[10px] sm:text-xs text-gray-400 uppercase tracking-wide">XP</div>
        </div>
      </div>
    </div>
  );
};

export default LeaderboardCard;
