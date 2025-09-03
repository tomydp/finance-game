<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CourseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validación de query params
        $validated = $request->validate([
            'per_page'   => 'sometimes|integer|min:1|max:100',
            'page'       => 'sometimes|integer|min:1',
            'difficulty' => ['sometimes', 'string', Rule::in(['facil','medio','dificil'])],
            'search'     => 'sometimes|string|max:100',
            'sort'       => ['sometimes', 'string', Rule::in(['name','created_at','order'])],
            'dir'        => ['sometimes', 'string', Rule::in(['asc','desc'])],
        ]);

        $perPage    = (int) ($validated['per_page'] ?? 10);
        $difficulty = $validated['difficulty'] ?? null;
        $search     = $validated['search'] ?? null;
        $sort       = $validated['sort'] ?? 'order';
        $dir        = $validated['dir'] ?? 'asc';

        $q = Course::query()
            ->withCount('lessons')         // -> lessons_count
            ->when($difficulty, fn($qq) => $qq->where('difficulty', $difficulty))
            ->when($search, function ($qq) use ($search) {
                $term = "%".mb_strtolower($search)."%";
                $qq->where(function ($w) use ($term) {
                    $w->whereRaw('LOWER(name) LIKE ?', [$term])
                      ->orWhereRaw('LOWER(description) LIKE ?', [$term]);
                });
            });

        // Si no existe columna 'order' en courses, cambiá default a 'name'
        if (!in_array($sort, ['name','created_at','order'], true)) {
            $sort = 'order';
        }

        $q->orderBy($sort, $dir);

        $paginated = $q->paginate($perPage)->appends($request->query());

        // Devolver como Resource Collection (mantiene meta/links de Laravel)
        return (new CourseCollection($paginated))
            ->additional([
                'meta' => [
                    'filters' => [
                        'difficulty' => $difficulty,
                        'search'     => $search,
                    ],
                    'sort' => ['by' => $sort, 'dir' => $dir],
                ],
            ]);
    }


    public function progress(Request $request, \App\Models\Course $course)
{
    if (!Schema::hasTable('lesson_user')) {
        // Evita explotar en entornos donde aún no mergeaste la migración
        return response()->json([
            'ok'      => false,
            'message' => 'La tabla pivot lesson_user aún no está disponible en este entorno.',
            'hint'    => 'Ejecutá la migración que crea lesson_user y reintentá.',
        ], 503);
    }

    $userId = $request->user()->id;

    // Totales de lecciones del curso
    $totalLessons = (int) $course->lessons()->count();

    // Lecciones completadas por el usuario (pivot)
    $completedLessons = (int) DB::table('lesson_user')
        ->join('lessons', 'lesson_user.lesson_id', '=', 'lessons.id')
        ->where('lesson_user.user_id', $userId)
        ->where('lessons.course_id', $course->id)
        ->count();

    // (Opcional) Progreso por ejercicios para dashboards más finos
    $totalExercises = (int) DB::table('exercises')
        ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
        ->where('lessons.course_id', $course->id)
        ->count();

    $correctExercises = (int) DB::table('results')
        ->join('exercises', 'results.exercise_id', '=', 'exercises.id')
        ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
        ->where('lessons.course_id', $course->id)
        ->where('results.user_id', $userId)
        ->where('results.is_correct', 1)
        ->count();

    return response()->json([
        'ok'      => true,
        'course'  => ['id' => $course->id, 'name' => $course->name],
        'lessons' => [
            'completed' => $completedLessons,
            'total'     => $totalLessons,
            'percent'   => $totalLessons > 0 ? (int) floor($completedLessons * 100 / $totalLessons) : 0,
        ],
        'exercises' => [
            'correct' => $correctExercises,
            'total'   => $totalExercises,
            'percent' => $totalExercises > 0 ? (int) floor($correctExercises * 100 / $totalExercises) : 0,
        ],
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
