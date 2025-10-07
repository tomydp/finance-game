import React, { useState } from "react";
import ScopeToggle from "../components/ScopeToggle";
import LeaderboardCard from "../components/LeaderboardCard";
import { useRankingApi, type Scope } from "../hooks/useRankingApi";

const RankingPage: React.FC = () => {
  const [scope, setScope] = useState<Scope>("semanal");
  const { data, loading, error, reload } = useRankingApi(scope);

  return (
    <div className="flex-1 p-6 md:p-12 text-white bg-[var(--Blue1)] min-h-screen">
      {/* Header */}
      <div className="text-center mb-8">
        <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight">
          <span className="align-middle mr-2">🏆</span>
          <span className="bg-gradient-to-r from-cyan-400 via-white to-purple-400 bg-clip-text text-transparent">
            Ranking
          </span>
        </h1>
        <p className="text-gray-300 mt-2">Compite con otros jugadores y escala posiciones</p>

        <div className="mt-4">
          <ScopeToggle scope={scope} onChange={setScope} />
        </div>
      </div>

      {/* Estados de carga / error */}
      {loading && (
        <div className="mx-auto max-w-4xl space-y-4">
          {[...Array(4)].map((_, i) => (
            <div key={i} className="h-20 rounded-2xl bg-[var(--Blue2)]/60 animate-pulse" />
          ))}
        </div>
      )}

      {error && !loading && (
        <div className="mx-auto max-w-2xl bg-red-900/30 border border-red-500/40 text-red-200 rounded-xl p-4 mb-6">
          <p className="font-semibold">No pudimos cargar el ranking.</p>
          <p className="text-sm opacity-80 break-all mt-1">{error}</p>
          <button onClick={reload} className="mt-3 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600">
            Reintentar
          </button>
        </div>
      )}

      {/* Lista */}
      {!loading && !error && (
        <div className="mx-auto max-w-4xl space-y-5">
          {data?.map((entry) => (
            <LeaderboardCard key={entry.pos} entry={entry} />
          ))}
        </div>
      )}
    </div>
  );
};

export default RankingPage;
