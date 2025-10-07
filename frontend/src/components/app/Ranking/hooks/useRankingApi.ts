import { useEffect, useState } from "react";

export type Scope = "semanal" | "global"; // 👈 agregá esto

const API = "http://localhost/api";

export interface RankingEntry {
  pos: number;
  name: string;
  level: number;
  xp: number;
  avatar_url?: string;
  user_id?: number;
}

interface UseRankingApiResult {
  loading: boolean;
  error: string | null;
  data: RankingEntry[] | null;
  reload: () => void;
}

export function useRankingApi(scope: Scope = "semanal"): UseRankingApiResult {
  const [data, setData] = useState<RankingEntry[] | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);

  useEffect(() => {
    const ctrl = new AbortController();
    const fetchData = async () => {
      setLoading(true);
      setError(null);
      try {
        const res = await fetch(`${API}/analytics/rankings?scope=${scope}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const json = await res.json();

        const block = scope === "semanal" ? json?.weekly : json?.global;
        const list: any[] = Array.isArray(block?.top) ? block.top : [];

        const mapped = list
          .map((row: any, i: number) => {
            const u = row?.user ?? {};
            return {
              pos: row?.position ?? i + 1,
              name: u?.name ?? "—",
              level: Number(u?.level ?? 1),
              xp: Number(u?.correct ?? 0),
              avatar_url: u?.avatar_url ?? undefined,
              user_id: u?.id ?? undefined,
            };
          })
          .sort((a, b) => (b.xp - a.xp) || (b.level - a.level))
          .map((m, idx) => ({ ...m, pos: idx + 1 }));

        setData(mapped);
      } catch (e: any) {
        console.error(e);
        setError(e.message || "Error al cargar ranking");
      } finally {
        setLoading(false);
      }
    };

    fetchData();
    return () => ctrl.abort();
  }, [scope, reloadKey]);

  return { data, loading, error, reload: () => setReloadKey((k) => k + 1) };
}
