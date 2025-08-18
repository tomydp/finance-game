import axios from 'axios';

/* ==== Configuración base ==== */
const api = axios.create({
  baseURL: 'http://localhost/api',
  withCredentials: true,               // listo para Sanctum
});

/* ==== Tipos (borra si usas JS) ==== */
export interface Course   { id: number; name: string; description: string; difficulty: string; }
export interface Lesson   { id: number; title: string; order: number; }
export interface Exercise { id: number; type: string; question: string; options: string[]; }

/* ==== Funciones de acceso ==== */
export async function getCourses() {
  try {
    const { data } = await api.get<Course[]>('/courses');
    return data;
  } catch (err) {
    console.error('getCourses →', err);
    return [];
  }
}

export const getLessonsByCourseId = async (courseId: number) => {
  const res = await fetch(`http://localhost/api/courses/${courseId}/lessons`);
  const data = await res.json();
  return data.data; // Asegúrate de que esta sea la estructura correcta
};

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
