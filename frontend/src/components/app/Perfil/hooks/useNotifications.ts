import { useEffect, useState } from "react";

export function useNotifications(storageKey = "notificationsEnabled") {
  const [enabled, setEnabled] = useState(false);

  useEffect(() => {
    const saved = localStorage.getItem(storageKey);
    if (saved) setEnabled(JSON.parse(saved));
  }, [storageKey]);

  useEffect(() => {
    localStorage.setItem(storageKey, JSON.stringify(enabled));
  }, [enabled, storageKey]);

  const toggle = () => setEnabled((e) => !e);

  return { enabled, setEnabled, toggle };
}
