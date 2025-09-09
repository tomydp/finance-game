<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonCollection;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonApiController extends Controller
{
    public function index(Course $course)
    {
        $lessons = $course->lessons()
            ->withCount('exercises')
            ->orderBy('order')
            ->get();
    
        return new LessonCollection($lessons);
    }

    /**
     * POST /api/lessons/{lesson}/complete
     *
     * Reglas por defecto:
     * - Si la lección tiene ejercicios, exige 100% correctos.
     * - Si no tiene ejercicios, permite completar.
     * - Opcional: admitir "force=true" para admins/QA.
     */
    public function complete(Request $request, Lesson $lesson)
    {
        $user = $request->user();

        // (Opcional) forzar finalización manual
        $force = filter_var($request->boolean('force'), FILTER_VALIDATE_BOOLEAN);

        // Total y completados (helpers del modelo Lesson)
        $total = $lesson->totalExercises();
        $done  = $lesson->completedExercises($user->id);

        // Si hay ejercicios y NO todos correctos, bloquear
        if ($total > 0 && !$force && $done < $total) {
            return response()->json([
                'ok'       => false,
                'message'  => 'Aún no resolviste todos los ejercicios correctamente.',
                'progress' => (int) round(($done / max(1, $total)) * 100),
                'stats'    => ['done' => $done, 'total' => $total],
            ], 422);
        }

        // Marcar como completada en pivot lesson_user
        $lesson->markCompletedFor($user->id);

        // Sugerir siguiente lección (no persiste "desbloqueo" todavía)
        $next = $lesson->unlockNextFor($user->id);

        return response()->json([
            'ok'         => true,
            'completed'  => true,
            'progress'   => 100,
            'next_lesson'=> $next ? [
                'id'    => $next->id,
                'title' => $next->title,
                'order' => $next->order,
            ] : null,
        ]);
    }
}
