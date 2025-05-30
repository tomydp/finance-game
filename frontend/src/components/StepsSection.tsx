// src/components/StepsSection.tsx
import React from 'react';

const steps = [
  {
    number: '1',
    title: 'Regístrate gratis',
    description:
      'Crea tu cuenta en menos de 30 segundos y comienza tu viaje financiero inmediatamente.',
    color: 'bg-cyan-500',
  },
  {
    number: '2',
    title: 'Aprende jugando',
    description:
      'Completa lecciones interactivas, resuelve desafíos y gana recompensas por cada logro.',
    color: 'bg-purple-400',
  },
  {
    number: '3',
    title: 'Aplica lo aprendido',
    description:
      'Usa simuladores reales y herramientas prácticas para mejorar tu situación financiera.',
    color: 'bg-teal-300',
  },
];

const StepsSection: React.FC = () => (
  <section className="bg-[var(--Blue1)] text-white py-20 px-6 md:px-16">
    <div className="max-w-6xl mx-auto text-center">
      {/* Título */}
      <h2 className="text-3xl md:text-4xl font-extrabold">
        Así de{' '}
        <span className="text-cyan-400">
          fácil
        </span>{' '}
        es empezar
      </h2>
      <p className="mt-2 text-gray-300">
        En solo 3 pasos estarás dominando tus finanzas personales
      </p>

      {/* Pasos */}
      <div className="mt-12 flex items-center justify-center gap-8 relative">
        {steps.map((step, idx) => (
          <React.Fragment key={step.number}>
            <div className="flex flex-col items-center px-4">
              <div
                className={`w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-semibold ${step.color}`}
              >
                {step.number}
              </div>
              <h3 className="mt-4 text-xl font-semibold">{step.title}</h3>
              <p className="mt-2 text-gray-300 text-sm">{step.description}</p>
            </div>
            {/* Punto decorativo entre pasos */}
            {idx < steps.length - 1 && (
              <div className="w-3 h-3 rounded-full bg-gray-600" />
            )}
          </React.Fragment>
        ))}
      </div>
    </div>
  </section>
);

export default StepsSection;
