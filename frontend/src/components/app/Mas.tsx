import React from 'react';
import { FaBook, FaUserFriends, FaInfoCircle, FaHeadset, FaExternalLinkAlt } from 'react-icons/fa';

const Mas: React.FC = () => {
  return (
    <div className="p-6 space-y-10 text-white">
      <h1 className="text-2xl font-bold mb-4">Más</h1>

      {/* Accesos directos */}
      <section className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div className="bg-[#121c30] p-4 rounded-lg flex items-center justify-between">
          <div>
            <h3 className="font-semibold text-lg">Diccionario financiero</h3>
            <p className="text-sm text-gray-400">Consulta términos financieros</p>
          </div>
          <FaBook className="text-cyan-400 text-2xl" />
        </div>
        <div className="bg-[#121c30] p-4 rounded-lg flex items-center justify-between">
          <div>
            <h3 className="font-semibold text-lg">Recursos adicionales</h3>
            <p className="text-sm text-gray-400">Material complementario</p>
          </div>
          <FaExternalLinkAlt className="text-cyan-400 text-2xl" />
        </div>
        <div className="bg-[#121c30] p-4 rounded-lg flex items-center justify-between">
          <div>
            <h3 className="font-semibold text-lg">Tutoriales</h3>
            <p className="text-sm text-gray-400">Guías paso a paso</p>
          </div>
          <FaInfoCircle className="text-cyan-400 text-2xl" />
        </div>
        <div className="bg-[#121c30] p-4 rounded-lg flex items-center justify-between">
          <div>
            <h3 className="font-semibold text-lg">Invitar amigos</h3>
            <p className="text-sm text-gray-400">Comparte la app</p>
          </div>
          <FaUserFriends className="text-cyan-400 text-2xl" />
        </div>
      </section>

      {/* FAQ */}
      <section>
        <h2 className="text-xl font-semibold mb-4">Preguntas frecuentes</h2>
        <div className="space-y-2">
          {[
            '¿Cómo funciona FinanzApp?',
            '¿Qué son los diamantes y cómo los consigo?',
            '¿Cómo mantengo mi racha diaria?',
            '¿Qué incluye la versión Premium?',
            '¿Cómo funcionan las ligas?',
          ].map((q, i) => (
            <details key={i} className="bg-[#121c30] p-4 rounded">
              <summary className="cursor-pointer font-medium">{q}</summary>
              <p className="text-sm text-gray-400 mt-2">Esta sección contendrá la respuesta a la pregunta.</p>
            </details>
          ))}
        </div>
      </section>

      {/* Acerca de */}
      <section>
        <h2 className="text-xl font-semibold mb-4">Acerca de</h2>
        <div className="bg-[#121c30] p-6 rounded text-center space-y-2">
          <div className="w-12 h-12 mx-auto rounded-full bg-cyan-500 flex items-center justify-center font-bold text-white text-lg">F</div>
          <h3 className="font-semibold">FinanzApp</h3>
          <p className="text-sm text-gray-400">Versión 1.0.0</p>
          <div className="flex justify-center gap-4 mt-2">
            <button className="text-sm text-cyan-400 hover:underline">Términos de uso</button>
            <button className="text-sm text-cyan-400 hover:underline">Política de privacidad</button>
          </div>
          <p className="text-xs text-gray-500 mt-2">© 2025 FinanzApp. Todos los derechos reservados.</p>
        </div>
      </section>

      {/* Soporte */}
      <section>
        <h2 className="text-xl font-semibold mb-4">Soporte</h2>
        <div className="bg-[#121c30] p-6 rounded text-center">
          <FaHeadset className="text-cyan-400 text-3xl mx-auto mb-2" />
          <p className="font-medium">¿Necesitás ayuda?</p>
          <p className="text-sm text-gray-400">Nuestro equipo de soporte está disponible para ayudarte con cualquier pregunta o problema.</p>
          <button className="mt-4 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm rounded">
            Contactar soporte
          </button>
        </div>
      </section>
    </div>
  );
};

export default Mas;
