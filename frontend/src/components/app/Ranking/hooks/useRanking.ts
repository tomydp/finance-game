import { useMemo, useState } from "react";

export type Scope = "semanal" | "global";

export interface RankingEntry {
  pos: number;
  name: string;
  level: number;
  xp: number;
}

const weeklyData: RankingEntry[] = [
  { pos: 1, name: "Carlos Méndez", level: 42, xp: 15420 },
  { pos: 2, name: "Ana García",    level: 38, xp: 14850 },
  { pos: 3, name: "Luis Torres",   level: 35, xp: 13990 },
  { pos: 4, name: "Usuario Demo",  level: 5,  xp: 230   },
];

const globalData: RankingEntry[] = [
  { pos: 1, name: "Ana García",    level: 58, xp: 320_450 },
  { pos: 2, name: "Carlos Méndez", level: 55, xp: 305_120 },
  { pos: 3, name: "Luis Torres",   level: 51, xp: 287_910 },
  { pos: 4, name: "Usuario Demo",  level: 5,  xp: 2_430   },
];

export function useRanking() {
  const [scope, setScope] = useState<Scope>("semanal");
  const data = useMemo(() => (scope === "semanal" ? weeklyData : globalData), [scope]);
  return { scope, setScope, data };
}
