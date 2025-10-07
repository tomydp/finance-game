<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserStatsController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
    
        // 1) Zona horaria: query ?tz=America/Argentina/Buenos_Aires
        //    o caemos a la del usuario/APP.
        $tz = $request->query('tz', $user->timezone ?? config('app.timezone', 'UTC'));
    
        // 2) Lecciones completadas (igual que antes)
        $lessonsCompleted = \App\Models\Lesson::active()
            ->whereDoesntHave('exercises', function ($q) use ($user) {
                $q->where('status', \App\Models\Exercise::STATUS_ACTIVO)
                  ->whereDoesntHave('results', function ($rq) use ($user) {
                      $rq->where('user_id', $user->id)
                         ->where('is_correct', true);
                  });
            })
            ->count();
    
        // 3) Racha: traemos timestamps y los convertimos al TZ del usuario
        $stamps = \App\Models\Result::where('user_id', $user->id)
            // ->where('is_correct', true) // descomenta si la racha cuenta sólo aciertos
            ->whereNotNull('answered_at')
            ->orderBy('answered_at', 'asc')
            ->pluck('answered_at');
    
        $days = $stamps
            ->map(fn ($ts) => Carbon::parse($ts, 'UTC')->setTimezone($tz)->toDateString())
            ->unique()
            ->values();
    
        [$currentStreak, $longestStreak, $lastActiveAt] = $this->computeStreaks($days, $tz);
    
        return response()->json([
            'lessons_completed' => (int) $lessonsCompleted,
            'streak_days'       => (int) $currentStreak,
            'longest_streak'    => (int) $longestStreak,
            'last_active_at'    => $lastActiveAt,
        ]);
    }
    
    /**
     * @param \Illuminate\Support\Collection<string> $days Fechas 'Y-m-d' en TZ del usuario
     */
    private function computeStreaks($days, string $tz): array
    {
        // Longest
        $longest = 0; $running = 0; $prev = null;
        foreach ($days as $d) {
            $running = $prev && $d === Carbon::parse($prev)->addDay()->toDateString()
                ? $running + 1 : 1;
            $longest = max($longest, $running);
            $prev = $d;
        }
    
        // Current (anclada a HOY del usuario)
        $current  = 0;
        $expected = Carbon::now($tz)->toDateString();
        foreach ($days->sortDesc()->values() as $d) {
            if ($d === $expected) {
                $current++;
                $expected = Carbon::parse($expected)->subDay()->toDateString();
            } else {
                if ($current === 0 || Carbon::parse($d)->lt(Carbon::parse($expected))) break;
            }
        }
    
        $lastActiveAt = $days->last() ?: null;
        return [$current, $longest, $lastActiveAt];
    }
}
