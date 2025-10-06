import { clsx, type ClassValue } from "clsx"
import { twMerge } from "tailwind-merge"

/**
 * Combina condicionalmente clases de Tailwind y las optimiza.
 * Ejemplo: cn("px-2", isActive && "bg-blue-500")
 */
export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}
