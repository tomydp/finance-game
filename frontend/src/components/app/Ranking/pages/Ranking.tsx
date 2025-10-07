import React from "react";
import ScopeToggle from "../components/ScopeToggle";
import LeaderboardCard from "../components/LeaderboardCard";
import { useRanking } from "../hooks/useRanking";

const RankingPage: React.FC = () => {
  const { scope, setScope, data } = useRanking();

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
        <p className="text-gray-300 mt-2">
          Compite con otros jugadores y escala posiciones
        </p>

        <div className="mt-4">
          <ScopeToggle scope={scope} onChange={setScope} />
        </div>
      </div>

      {/* Lista */}
      <div className="mx-auto max-w-4xl space-y-5">
        {data.map((entry) => (
          <LeaderboardCard key={entry.pos} entry={entry} />
        ))}
      </div>
    </div>
  );
};

export default RankingPage;
