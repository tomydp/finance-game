// src/components/app/Perfil/hooks/useUserStats.ts
import { useEffect, useRef, useState, useCallback } from "react";

const API = import.meta.env.VITE_API_BASE_URL ?? "http://localhost/api";

export type UserStats = {
  lessons_completed: number;
  streak_days: number;
  longest_streak: number;
  last_active_at: string | null;
  /** Opcional: lista de YYYY-MM-DD con actividad (histórico completo) */
  active_days?: string[];
};

type State = {
  loading: boolean;
  error: Error | null;
  data: UserStats | null;
  needsAuth: boolean;
  isDemo: boolean;
};

/** genera una demo con una racha pasada de 20 días y algunos días recientes */
function buildDemoDays(): string[] {
  const today = new Date();
  const pad = (n: number) => String(n).padStart(2, "0");
  const toStr = (d: Date) =>
    `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

  // racha pasada: 20 días seguidos que terminan hace 30 días
  const end = new Date(today);
  end.setDate(end.getDate() - 30);
  const start = new Date(end);
  start.setDate(end.getDate() - 19);

  const pastStreak: string[] = [];
  for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
    pastStreak.push(toStr(d));
  }

  // algunos días recientes sueltos (para contrastar)
  const recent = [1, 3, 7, 10].map((off) => {
    const x = new Date(today);
    x.setDate(x.getDate() - off);
    return toStr(x);
  });

  // una mini racha actual de 3 días (opcional)
  const current: string[] = [];
  for (let i = 0; i < 3; i++) {
    const x = new Date(today);
    x.setDate(x.getDate() - i);
    current.push(toStr(x));
  }

  // único + ordenado
  const set = new Set([...pastStreak, ...recent, ...current]);
  return Array.from(set).sort();
}

const DEFAULT_DEMO: UserStats = {
  lessons_completed: 3,
  streak_days: 3,
  longest_streak: 20,
  last_active_at: new Date().toISOString().slice(0, 10),
  active_days: buildDemoDays(),
};

export function useUserStats(
  customTz?: string,
  opts: { demo?: boolean; demoData?: UserStats } = { demo: true }
) {
  const { demo = true, demoData = DEFAULT_DEMO } = opts;

  const [state, setState] = useState<State>({
    loading: true,
    error: null,
    data: null,
    needsAuth: false,
    isDemo: false,
  });
  const abortRef = useRef<AbortController | null>(null);

  const load = useCallback(async () => {
    abortRef.current?.abort();
    const controller = new AbortController();
    abortRef.current = controller;

    setState((s) => ({ ...s, loading: true, error: null }));

    // TZ del navegador si no viene
    let tz: string | undefined = customTz;
    if (!tz) {
      try { tz = Intl.DateTimeFormat().resolvedOptions().timeZone; } catch {}
    }

    const url = new URL(`${API}/user/stats`);
    if (tz) url.searchParams.set("tz", tz);

    try {
      const token = localStorage.getItem("token") || null;
      const headers: Record<string, string> = { Accept: "application/json" };
      if (token) headers.Authorization = `Bearer ${token}`;

      const res = await fetch(url.toString(), {
        method: "GET",
        headers,
        signal: controller.signal,
        redirect: "manual",
      });

      if (controller.signal.aborted) return;

      if (res.status === 401 || res.status === 419) {
        if (demo) {
          setState({ loading: false, error: null, data: demoData, needsAuth: true, isDemo: true });
          return;
        }
        setState({ loading: false, error: null, data: null, needsAuth: true, isDemo: false });
        return;
      }

      if (!res.ok) {
        if (demo) {
          setState({ loading: false, error: null, data: demoData, needsAuth: false, isDemo: true });
          return;
        }
        const err = new Error(`HTTP ${res.status}`);
        (err as any).status = res.status;
        throw err;
      }

      const json = (await res.json()) as UserStats;

      console.debug("user_stats_loaded", {
        lessons_completed: json.lessons_completed,
        streak_days: json.streak_days,
      });

      setState({ loading: false, error: null, data: json, needsAuth: false, isDemo: false });
    } catch (err: any) {
      if (err?.name === "AbortError") return;
      if (demo) {
        setState({ loading: false, error: null, data: demoData, needsAuth: false, isDemo: true });
        return;
      }
      setState({
        loading: false,
        error: err instanceof Error ? err : new Error("Error al cargar estadísticas"),
        data: null,
        needsAuth: false,
        isDemo: false,
      });
    }
  }, [customTz, demo, demoData]);

  useEffect(() => {
    load();
    return () => abortRef.current?.abort();
  }, [load]);

  return {
    loading: state.loading,
    error: state.error,
    data: state.data,
    needsAuth: state.needsAuth,
    isDemo: state.isDemo,
    refetch: load,
  };
}
