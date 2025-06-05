// src/components/CTASection.tsx
import React from 'react';

const CTASection: React.FC = () => {
  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  return (
    <section className="bg-[var(--Blue2)] text-white py-20 px-6 md:px-16">
      <div className="max-w-6xl mx-auto text-center">
        <h2 className="text-3xl md:text-4xl font-extrabold">
          ¿Listo para transformar tus finanzas?
        </h2>
        <p className="mt-2 text-gray-300">
          Únete a miles de personas que ya están mejorando su futuro financiero
        </p>

        <div className="mt-8">
          <button
            onClick={scrollToTop}
            className="px-8 py-4 bg-cyan-500 text-white font-semibold rounded hover:bg-cyan-600 transition"
          >
            COMENZAR AHORA 
          </button>
        </div>
      </div>
    </section>
  );
};

export default CTASection;
