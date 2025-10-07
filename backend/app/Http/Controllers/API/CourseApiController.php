<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCollection;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class CourseApiController extends Controller
{
    /**
     * GET /api/courses
     * Lista de cursos con filtros, búsqueda y ordenamiento seguro
     * (sin requerir que exista la columna 'order' en la base).
     */
    public function index(Request $request)
{
    // columnas ordenables disponibles
    $hasOrder = Schema::hasColumn('courses', 'order');
    $sortable = ['custom', 'name', 'created_at'];
    if ($hasOrder) $sortable[] = 'order';

    // validar query params
    $validated = $request->validate([
        'per_page'   => 'sometimes|integer|min:1|max:100',
        'page'       => 'sometimes|integer|min:1',
        'difficulty' => ['sometimes', 'string', Rule::in(['facil','medio','dificil'])],
        'search'     => 'sometimes|string|max:100',
        'sort'       => ['sometimes', 'string', Rule::in($sortable)],
        'dir'        => ['sometimes', 'string', Rule::in(['asc','desc'])],
        'status'     => ['sometimes', 'string', Rule::in([Course::STATUS_ACTIVO, Course::STATUS_INACTIVO, 'todos'])],
    ]);

    $perPage    = (int) ($validated['per_page'] ?? 10);
    $difficulty = $validated['difficulty'] ?? null;
    $search     = $validated['search'] ?? null;
    // por defecto usamos el orden "custom" que pediste
    $sort       = $validated['sort'] ?? 'custom';
    $dir        = $validated['dir'] ?? 'asc';
    $status     = $validated['status'] ?? Course::STATUS_ACTIVO;

    $q = Course::query()
        ->withCount(['lessons as lessons_active_count' => fn ($qq) => $qq->where('status', Lesson::STATUS_ACTIVO)])
        ->when($difficulty, fn($qq) => $qq->where('difficulty', $difficulty))
        ->when($search, function ($qq) use ($search) {
            $term = "%".mb_strtolower($search)."%";
            $qq->where(function ($w) use ($term) {
                $w->whereRaw('LOWER(name) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(description) LIKE ?', [$term]);
            });
        })
        ->when($status !== 'todos', fn ($qq) => $qq->where('status', $status));

    if ($sort === 'custom') {
        // === ORDEN EXACTO QUE PEDISTE ===
        $names = [
            'Fundamentos Financieros',
            'Introducción a Inversiones',
        ];
        for ($i = 1; $i <= 9; $i++) {
            $names[] = "Crédito y Deuda Responsable {$i}";
        }

        // Construimos un CASE seguro (sin columnas nuevas)
        $case = 'CASE';
        foreach ($names as $i => $n) {
            $safe = str_replace("'", "''", $n);
            $case .= " WHEN name = '{$safe}' THEN {$i}";
        }
        $case .= ' ELSE 999 END';

        // primero por el CASE, luego por nombre como desempate
        $q->orderByRaw($case)->orderBy('name', 'asc');
    } else {
        // fallback: respetar sort estándar si te lo pasan por query
        if (!in_array($sort, $sortable, true)) $sort = 'name';
        $q->orderBy($sort, $dir);
    }

    $paginated = $q->paginate($perPage)->appends($request->query());

    return (new CourseCollection($paginated))->additional([
        'meta' => [
            'filters' => [
                'difficulty' => $difficulty,
                'search'     => $search,
                'status'     => $status,
            ],
            'sort' => ['by' => $sort, 'dir' => $dir],
        ],
    ]);
}


    /**
     * GET /api/courses/{course}/progress
     * Métricas de progreso del usuario en el curso.
     */
    public function progress(Request $request, \App\Models\Course $course)
    {
        if (!Schema::hasTable('lesson_user')) {
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

        // (Opcional) Progreso por ejercicios
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
