import React from "react";
import type { Scope } from "../hooks/useRanking";

interface Props {
  scope: Scope;
  onChange: (s: Scope) => void;
}

const ScopeToggle: React.FC<Props> = ({ scope, onChange }) => {
  return (
    <div className="inline-flex rounded-full bg-[#0e1526] p-1 shadow-inner">
      {(["semanal", "global"] as Scope[]).map((s) => (
        <button
          key={s}
          onClick={() => onChange(s)}
          className={`px-4 sm:px-6 py-2 rounded-full text-sm font-semibold transition
            ${scope === s
              ? "bg-gradient-to-r from-cyan-500 to-blue-600 text-white"
              : "text-gray-300 hover:text-white"}`}
        >
          {s === "semanal" ? "Semanal" : "Global"}
        </button>
      ))}
    </div>
  );
};

export default ScopeToggle;
