<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AnalyticsDashboard extends Component
{
    public array $kpis = [];
    public array $signups30 = [];
    public array $dau30 = [];
    public array $results30 = [];
    public array $course7 = [];
    public array $types7 = [];

    private const TYPE_LABELS = [
        'mcq'        => 'Opción múltiple',
        'true_false' => 'Verdadero/Falso',
        'fill_blank' => 'Completar',
    ];

    public function mount(): void
    {
        $this->loadKpis();
        $this->loadSeries();
    }

    protected function loadKpis(): void
    {
        $today = now()->startOfDay();

        $nuevosHoy = User::whereBetween('created_at', [$today, $today->copy()->endOfDay()])->count();
        $nuevos7   = User::where('created_at', '>=', now()->subDays(7))->count();
        $nuevos30  = User::where('created_at', '>=', now()->subDays(30))->count();

        // DAU (sessions table con driver DB)
        $dau = DB::table('sessions')
            ->where('last_activity', '>=', now()->startOfDay()->timestamp)
            ->distinct('user_id')
            ->count('user_id');

        $ejHoy = DB::table('results')
            ->whereBetween('answered_at', [$today, $today->copy()->endOfDay()])
            ->count();

        $acc7 = DB::table('results')
            ->where('answered_at', '>=', now()->subDays(7))
            ->avg('is_correct') ?? 0;
        $acc7 = round($acc7 * 100, 1);

        $topCourse7 = DB::table('results as r')
            ->join('exercises as e', 'e.id', '=', 'r.exercise_id')
            ->join('lessons as l', 'l.id', '=', 'e.lesson_id')
            ->join('courses as c', 'c.id', '=', 'l.course_id')
            ->where('r.answered_at', '>=', now()->subDays(7))
            ->selectRaw('c.name as course, COUNT(*) as total')
            ->groupBy('c.name')
            ->orderByDesc('total')
            ->limit(1)
            ->first();

        $this->kpis = [
            'nuevos_hoy'     => $nuevosHoy,
            'nuevos_7d'      => $nuevos7,
            'nuevos_30d'     => $nuevos30,
            'dau'            => $dau,
            'ejercicios_hoy' => $ejHoy,
            'accuracy_7d'    => $acc7,
            'top_curso_7d'   => $topCourse7?->course ?? '-',
        ];
    }

    protected function loadSeries(): void
    {
        $end   = now()->endOfDay();
        $start = $end->copy()->subDays(29)->startOfDay();
        $days  = $this->dayRange($start, $end);

        $signupsRaw = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c', 'd');

        $this->signups30 = $days->map(fn (string $date) => [
            'd' => $date,
            'c' => (int) ($signupsRaw[$date] ?? 0),
        ])->toArray();

        $dauRaw = DB::table('sessions')
            ->selectRaw('DATE(FROM_UNIXTIME(last_activity)) as d, COUNT(DISTINCT user_id) as dau')
            ->whereBetween('last_activity', [$start->timestamp, $end->timestamp])
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('dau', 'd');

        $this->dau30 = $days->map(fn (string $date) => [
            'd'   => $date,
            'dau' => (int) ($dauRaw[$date] ?? 0),
        ])->toArray();

        $resultsRaw = DB::table('results')
            ->selectRaw('DATE(answered_at) as d, COUNT(*) as total, AVG(is_correct)*100 as accuracy')
            ->whereBetween('answered_at', [$start, $end])
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $this->results30 = $days->map(function (string $date) use ($resultsRaw) {
            $row = $resultsRaw[$date] ?? null;

            return [
                'd'        => $date,
                'total'    => $row?->total ? (int) $row->total : 0,
                'accuracy' => $row?->accuracy ? round((float) $row->accuracy, 2) : 0,
            ];
        })->toArray();

        $sevenDaysAgo = $end->copy()->subDays(6)->startOfDay();

        $this->course7 = DB::table('results as r')
            ->join('exercises as e', 'e.id', '=', 'r.exercise_id')
            ->join('lessons as l', 'l.id', '=', 'e.lesson_id')
            ->join('courses as c', 'c.id', '=', 'l.course_id')
            ->where('r.answered_at', '>=', $sevenDaysAgo)
            ->selectRaw('c.name as course,
                        COUNT(*) as total,
                        SUM(r.is_correct=1) as correctos,
                        SUM(r.is_correct=0) as incorrectos')
            ->groupBy('course')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->toArray();

        $this->types7 = DB::table('results as r')
            ->join('exercises as e', 'e.id', '=', 'r.exercise_id')
            ->where('r.answered_at', '>=', $sevenDaysAgo)
            ->selectRaw('e.type as type, COUNT(*) as total')
            ->groupBy('e.type')
            ->get()
            ->map(function ($row) {
                $type = (string) $row->type;

                return [
                    'type'  => $type,
                    'label' => self::TYPE_LABELS[$type] ?? ucfirst(str_replace('_', ' ', $type)),
                    'total' => (int) $row->total,
                ];
            })
            ->values()
            ->toArray();
    }

    private function dayRange(Carbon $start, Carbon $end): Collection
    {
        $days = collect();
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $days->push($cursor->toDateString());
            $cursor->addDay();
        }

        return $days;
    }

    public function render()
    {
        // IMPORTANTE: fuera de /admin
        return view('livewire.analytics-dashboard');
    }
}
