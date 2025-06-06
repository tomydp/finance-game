import React from 'react';

const Sonidos: React.FC = () => {
  const pistas = [
    {
      id: 1,
      titulo: 'Ingreso Pasivo',
      url: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
    },
    {
      id: 2,
      titulo: 'Ahorro Estratégico',
      url: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
    },
  ];

  return (
    <div className="p-6 space-y-6 text-white">
      <h1 className="text-2xl font-bold mb-4">Sonidos</h1>
      <p className="text-gray-400 mb-4">
        Reproduce las pistas para escuchar la pronunciación de algunos conceptos.
      </p>
      <div className="space-y-4">
        {pistas.map(pista => (
          <div
            key={pista.id}
            className="bg-[#121c30] p-4 rounded flex items-center justify-between"
          >
            <span className="font-medium">{pista.titulo}</span>
            <audio controls className="w-48">
              <source src={pista.url} type="audio/mpeg" />
              Tu navegador no soporta el elemento <code>audio</code>.
            </audio>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Sonidos;
