import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost/api',
});

// Interceptor → agrega Authorization automáticamente
api.interceptors.request.use((config) => {
  const userRaw = localStorage.getItem('user');
  if (userRaw) {
    try {
      const user = JSON.parse(userRaw);
      if (user?.token) {
        config.headers = config.headers || {};
        (config.headers as any).Authorization = `Bearer ${user.token}`;
      }
    } catch {}
  }
  return config;
});

export interface Course   { id: number; name: string; description: string; difficulty: string; }
export interface Lesson   { id: number; title: string; order: number; }
export interface Exercise { id: number; type: string; question: string; options: string[]; }

export async function getCourses() {
  try {
    const { data } = await api.get<Course[]>('/courses');
    return data;
  } catch (err) {
    console.error('getCourses →', err);
    return [];
  }
}

export async function getLessons(courseId: number) {
  try {
    const { data } = await api.get<Lesson[]>(`/courses/${courseId}/lessons`);
    return data;
  } catch (err) {
    console.error('getLessons →', err);
    return [];
  }
}

export async function getExercises(lessonId: number) {
  try {
    const { data } = await api.get<Exercise[]>(`/lessons/${lessonId}/exercises`);
    return data;
  } catch (err) {
    console.error('getExercises →', err);
    return [];
  }
}

export default api;
