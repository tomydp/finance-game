"use client"

import { useState } from "react"
import type { MembershipPlan } from "../../data/membershipPacks"
import { membershipPacks } from "../../data/membershipPacks"

// importa los componentes de UI desde tu carpeta local
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "../ui/card"
import { Button } from "../ui/button"
import { Check } from "lucide-react"

export default function StorePage() {
  const [selectedPlan, setSelectedPlan] = useState<MembershipPlan | null>(null)

  const handleSelectPlan = (plan: MembershipPlan) => {
    setSelectedPlan(plan)
  }

  const handleCancel = () => {
    setSelectedPlan(null)
  }

  const handleConfirm = () => {
    alert(`¡Suscripción confirmada al plan ${selectedPlan?.name}!`)
    setSelectedPlan(null)
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        {/* Header */}
        <div className="text-center mb-12">
          <h1 className="text-4xl font-bold text-white mb-4">Elige tu Plan Premium</h1>
          <p className="text-xl text-gray-400">Selecciona el plan que mejor se adapte a tus necesidades</p>
        </div>

        {/* Plans Grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          {membershipPacks.map((plan) => (
            <Card
              key={plan.id}
              className={`relative bg-gray-900/50 border-gray-800 hover:border-cyan-500/50 transition-all duration-300 ${
                plan.featured ? "ring-2 ring-cyan-500/50" : ""
              } ${selectedPlan?.id === plan.id ? "ring-2 ring-green-500/50 border-green-500/50" : ""}`}
            >
              {plan.featured && (
                <div className="absolute -top-4 left-1/2 -translate-x-1/2">
                  <span className="bg-gradient-to-r from-cyan-500 to-green-500 text-white text-sm font-bold px-4 py-1 rounded-full">
                    Más Popular
                  </span>
                </div>
              )}

              <CardHeader>
                <CardTitle className="text-2xl text-white">{plan.name}</CardTitle>
                <CardDescription className="text-gray-400">{plan.description}</CardDescription>
              </CardHeader>

              <CardContent>
                <div className="mb-6">
                  <span className="text-5xl font-bold text-white">{plan.price}</span>
                  <span className="text-gray-400 text-lg">{plan.period}</span>
                </div>

                <ul className="space-y-3">
                  {plan.benefits.slice(0, 3).map((benefit, index) => (
                    <li key={index} className="flex items-start gap-2 text-gray-300">
                      <Check className="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" />
                      <span className="text-sm">{benefit}</span>
                    </li>
                  ))}
                </ul>
              </CardContent>

              <CardFooter>
                <Button
                  onClick={() => handleSelectPlan(plan)}
                  className={`w-full ${
                    selectedPlan?.id === plan.id ? "bg-green-600 hover:bg-green-700" : "bg-cyan-600 hover:bg-cyan-700"
                  } text-white transition-colors`}
                >
                  {selectedPlan?.id === plan.id ? "Seleccionado" : "Seleccionar"}
                </Button>
              </CardFooter>
            </Card>
          ))}
        </div>

        {/* Collapsible Details Panel */}
        <div
          className={`transition-all duration-500 ease-in-out overflow-hidden ${
            selectedPlan ? "max-h-[600px] opacity-100" : "max-h-0 opacity-0"
          }`}
        >
          {selectedPlan && (
            <div className="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl shadow-2xl border border-gray-700 p-8">
              <div className="max-w-4xl mx-auto">
                {/* Plan Header */}
                <div className="text-center mb-8">
                  <h2 className="text-3xl font-bold text-white mb-2">{selectedPlan.name}</h2>
                  <p className="text-gray-400">{selectedPlan.description}</p>
                </div>

                {/* Price Display */}
                <div className="text-center mb-8">
                  <div className="inline-block bg-gradient-to-r from-cyan-500/10 to-green-500/10 border border-cyan-500/30 rounded-2xl px-8 py-6">
                    <span className="text-6xl font-bold bg-gradient-to-r from-cyan-400 to-green-400 bg-clip-text text-transparent">
                      {selectedPlan.price}
                    </span>
                    <span className="text-2xl text-gray-400 ml-2">{selectedPlan.period}</span>
                  </div>
                </div>

                {/* Benefits List */}
                <div className="mb-8">
                  <h3 className="text-xl font-semibold text-white mb-4 text-center">Beneficios Incluidos</h3>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {selectedPlan.benefits.map((benefit, index) => (
                      <div
                        key={index}
                        className="flex items-start gap-3 bg-gray-800/50 rounded-lg p-4 border border-gray-700/50"
                      >
                        <div className="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center">
                          <Check className="w-4 h-4 text-green-500" />
                        </div>
                        <span className="text-gray-300">{benefit}</span>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Action Buttons */}
                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                  <Button
                    onClick={handleConfirm}
                    size="lg"
                    className="bg-gradient-to-r from-cyan-500 to-green-500 hover:from-cyan-600 hover:to-green-600 text-white font-semibold px-8 py-6 text-lg shadow-lg shadow-cyan-500/25 transition-all"
                  >
                    🚀 Confirmar Suscripción
                  </Button>
                  <Button
                    onClick={handleCancel}
                    size="lg"
                    variant="outline"
                    className="bg-gray-800 hover:bg-gray-700 text-gray-300 border-gray-700 px-8 py-6 text-lg transition-all"
                  >
                    Cancelar
                  </Button>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
