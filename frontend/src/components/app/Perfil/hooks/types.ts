export type Tab = "estadisticas" | "amigos" | "configuracion";

export interface UserSettings {
  name: string;
  level: number;
  avatar: string;
  memberSince: string;
  showStreak: boolean;
  showAchievements: boolean;
}
