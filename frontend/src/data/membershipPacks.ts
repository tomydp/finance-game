export interface MembershipPlan {
  id: string
  name: string
  description: string
  price: string
  period: string
  benefits: string[]
  featured?: boolean
  color?: string
  ringColor?: string
}

export const membershipPacks: MembershipPlan[] = [
  {
    id: "monthly",
    name: "Mensual Premium",
    description: "Perfecto para empezar",
    price: "$9.99",
    period: "/mes",
    benefits: [
      "Acceso completo a todas las funciones",
      "Soporte prioritario 24/7",
      "Actualizaciones automáticas",
      "Sin compromisos a largo plazo",
      "Cancela cuando quieras",
    ],
    color: "from-cyan-700 to-cyan-500",
    ringColor: "ring-cyan-400",
  },
  {
    id: "annual",
    name: "Anual Premium",
    description: "Ahorra 20% con el plan anual",
    price: "$95.99",
    period: "/año",
    benefits: [
      "Todas las funciones del plan mensual",
      "Ahorra $23.89 al año",
      "Soporte VIP prioritario",
      "Acceso anticipado a nuevas funciones",
      "Garantía de devolución de 30 días",
      "Sesiones de consultoría gratuitas",
    ],
    featured: true,
    color: "from-purple-700 to-indigo-600",
    ringColor: "ring-purple-400",
  },
  {
    id: "family",
    name: "Familiar Premium",
    description: "Hasta 5 miembros de la familia",
    price: "$14.99",
    period: "/mes",
    benefits: [
      "Todas las funciones premium",
      "Hasta 5 cuentas individuales",
      "Control parental avanzado",
      "Perfiles personalizados",
      "Almacenamiento compartido ilimitado",
      "Soporte familiar dedicado",
    ],
    color: "from-green-700 to-emerald-500",
    ringColor: "ring-green-400",
  },
]
