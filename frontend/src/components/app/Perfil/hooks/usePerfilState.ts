import { useEffect, useState } from "react";
import type { UserSettings } from "./types";

/**
 * Maneja la persistencia de los ajustes del perfil en localStorage.
 * No asume un avatar por defecto: pasalo en `initial`.
 */
export function usePerfilState(
  initial: UserSettings,
  storageKey = "settings"
) {
  const [user, setUser] = useState<UserSettings>(initial);

  useEffect(() => {
    const saved = localStorage.getItem(storageKey);
    if (saved) {
      try {
        setUser((prev) => ({ ...prev, ...JSON.parse(saved) }));
      } catch (_) {
        /* noop */
      } 
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    localStorage.setItem(storageKey, JSON.stringify(user));
  }, [user, storageKey]);

  return { user, setUser };
}
