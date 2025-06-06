import React, { useState } from 'react';
import { Trophy, Heart, Diamond, CloudLightning } from 'lucide-react';

const Ligas: React.FC = () => {
  const [tab, setTab] = useState<'current' | 'rewards' | 'history'>('current');

  return (
    <div className="p-6 space-y-8 text-white">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-2">
          <Trophy className="w-6 h-6 text-cyan-400" />
          <h1 className="text-2xl font-bold">Ligas</h1>
        </div>
        <div className="flex items-center gap-4">
          <div className="flex items-center gap-1">
            <Heart className="w-5 h-5 text-red-500 fill-red-500" />
            <span>5</span>
          </div>
          <div className="flex items-center gap-1">
            <CloudLightning className="w-5 h-5 text-yellow-400 fill-yellow-400" />
            <span>0</span>
          </div>
          <div className="flex items-center gap-1">
            <Diamond className="w-5 h-5 text-cyan-400 fill-cyan-400" />
            <span>500</span>
          </div>
        </div>
      </div>

      {/* League Status */}
      <div className="bg-gradient-to-r from-cyan-800/40 to-cyan-500/20 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between gap-6">
        <div className="flex items-center gap-4">
          <div className="relative">
            <div className="w-16 h-16 rounded-full bg-cyan-500/20 flex items-center justify-center">
              <Trophy className="w-8 h-8 text-cyan-400" />
            </div>
            <div className="absolute -bottom-2 -right-2 bg-cyan-400 text-white rounded-full text-xs w-6 h-6 flex items-center justify-center">5</div>
          </div>
          <div>
            <h2 className="font-bold text-lg mb-1">Liga Bronce</h2>
            <p className="text-sm text-gray-300">Completa 10 lecciones más para desbloquear la competición</p>
          </div>
        </div>
        <button className="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 rounded text-sm font-medium">DESBLOQUEAR</button>
      </div>

      {/* Tabs */}
      <div className="space-y-6">
        <div className="flex rounded overflow-hidden bg-[#1f2937]">
          {([
            ['current', 'Liga actual'],
            ['rewards', 'Recompensas'],
            ['history', 'Historial'],
          ] as const).map(([value, label]) => (
            <button
              key={value}
              onClick={() => setTab(value)}
              className={`flex-1 py-2 text-sm font-medium transition ${tab === value ? 'bg-[#0f172a] text-white' : 'text-gray-400 hover:bg-[#111827]'}`}
            >
              {label}
            </button>
          ))}
        </div>

        {tab === 'current' && (
          <div className="space-y-6">
            <h3 className="text-lg font-semibold">Clasificación semanal</h3>
            <div className="bg-[#121c30] p-6 rounded text-center space-y-4">
              <Trophy className="w-10 h-10 text-gray-500 mx-auto" />
              <h3 className="text-lg font-bold">Liga bloqueada</h3>
              <p className="text-sm text-gray-400">Completa 10 lecciones más para desbloquear la competición en ligas</p>
              <div className="w-full h-2 bg-[#0f172a] rounded">
                <div className="h-2 bg-cyan-500" style={{ width: '30%' }} />
              </div>
              <p className="text-xs text-gray-400">3/10 lecciones completadas</p>
              <button className="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 rounded text-sm font-medium">CONTINUAR APRENDIENDO</button>
            </div>
          </div>
        )}

        {tab === 'rewards' && (
          <div className="space-y-6">
            <h3 className="text-lg font-semibold">Recompensas por liga</h3>
            <div className="grid md:grid-cols-2 gap-4">
              <div className="bg-[#121c30] p-4 rounded space-y-2">
                <div className="flex items-center gap-2">
                  <Trophy className="w-5 h-5 text-cyan-400" />
                  <h4 className="font-semibold">Liga Bronce</h4>
                </div>
                <p className="text-sm text-gray-400">Top 20 avanzan a Plata</p>
                <ul className="text-sm space-y-1">
                  <li>1° lugar: <Diamond className="inline w-3 h-3 text-cyan-400" /> 100</li>
                  <li>2° - 5° lugar: <Diamond className="inline w-3 h-3 text-cyan-400" /> 50</li>
                  <li>6° - 20° lugar: <Diamond className="inline w-3 h-3 text-cyan-400" /> 25</li>
                </ul>
              </div>
            </div>
          </div>
        )}

        {tab === 'history' && (
          <div className="bg-[#121c30] p-6 rounded text-center space-y-4">
            <Trophy className="w-10 h-10 text-gray-500 mx-auto" />
            <h3 className="text-lg font-bold">Sin historial</h3>
            <p className="text-sm text-gray-400">Aún no has participado en ninguna liga</p>
            <button className="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 rounded text-sm font-medium">DESBLOQUEAR LIGAS</button>
          </div>
        )}
      </div>
    </div>
  );
};

export default Ligas;
