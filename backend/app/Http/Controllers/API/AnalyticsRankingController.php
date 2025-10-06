<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AnalyticsRankingController extends Controller
{
    private const DEFAULT_TOP = 10;
    private const MAX_TOP = 50;
    private const WEEKLY_DAYS = 7;

    public function index(Request $request)
    {
        $validated = $request->validate([
            'top'            => ['sometimes', 'integer', 'min:1', 'max:' . self::MAX_TOP],
            'include_user'   => ['sometimes', Rule::in(['0', '1', 0, 1, true, false])],
            'window_days'    => ['sometimes', 'integer', 'min:1', 'max:31'],
        ]);

        $top = (int) ($validated['top'] ?? self::DEFAULT_TOP);
        $includeUser = filter_var($validated['include_user'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $weeklyDays = (int) ($validated['window_days'] ?? self::WEEKLY_DAYS);

        $now = Carbon::now();
        $weeklySince = $now->copy()->subDays($weeklyDays)->startOfDay();

        $userId = $request->user()?->id;

        $weeklyScores = $this->aggregateScores($weeklySince);
        $globalScores = $this->aggregateScores();

        $weeklyRanking = $this->formatRanking($weeklyScores, $top, $userId, $includeUser);
        $globalRanking = $this->formatRanking($globalScores, $top, $userId, $includeUser);

        return response()->json([
            'ok' => true,
            'meta' => [
                'metric' => 'correct_answers',
                'weekly_window' => [
                    'days' => $weeklyDays,
                    'since' => $weeklySince->toISOString(),
                ],
                'generated_at' => $now->toISOString(),
                'top' => $top,
            ],
            'weekly' => $weeklyRanking,
            'global' => $globalRanking,
        ]);
    }

    private function aggregateScores(?Carbon $since = null): Collection
    {
        $query = DB::table('results')
            ->join('users', 'users.id', '=', 'results.user_id')
            ->selectRaw('users.id as user_id, users.name, users.email, '
                . 'SUM(results.is_correct) as correct, '
                . 'COUNT(*) as total, '
                . 'SUM(results.is_correct) / NULLIF(COUNT(*), 0) as accuracy, '
                . 'MAX(results.answered_at) as last_activity')
            ->groupBy('users.id', 'users.name', 'users.email');

        if ($since) {
            $query->where('results.answered_at', '>=', $since);
        }

        return collect($query->get())->map(function ($row) {
            $row->correct = (int) $row->correct;
            $row->total = (int) $row->total;
            $row->accuracy = $row->accuracy !== null ? (float) $row->accuracy : 0.0;
            $row->last_activity = $row->last_activity ? Carbon::parse($row->last_activity) : null;
            return $row;
        });
    }

    private function formatRanking(Collection $scores, int $top, ?int $userId, bool $includeUser): array
    {
        $sorted = $scores
            ->sort(function ($a, $b) {
                return ($b->correct <=> $a->correct)
                    ?: ($b->accuracy <=> $a->accuracy)
                    ?: ($b->total <=> $a->total)
                    ?: strcasecmp((string) $a->name, (string) $b->name);
            })
            ->values();

        $topRows = $sorted->take($top)->values();

        $result = [
            'total_users' => $sorted->count(),
            'top' => $topRows->map(function ($row, $index) {
                return $this->transformRow($row, $index + 1);
            })->all(),
        ];

        if ($includeUser && $userId) {
            $positionMap = $sorted->pluck('user_id')->flip();
            if ($positionMap->has($userId)) {
                $position = $positionMap[$userId] + 1;
                $row = $sorted[$position - 1];
                $result['you'] = $this->transformRow($row, $position);
            }
        }

        return $result;
    }

    private function transformRow(object $row, int $position): array
    {
        $accuracyPercent = round($row->accuracy * 100, 2);

        return [
            'position' => $position,
            'user' => [
                'id' => $row->user_id,
                'name' => $row->name,
                'email' => $row->email,
            ],
            'correct' => $row->correct,
            'total' => $row->total,
            'accuracy_percent' => $accuracyPercent,
            'accuracy_ratio' => round($row->accuracy, 4),
            'last_activity' => $row->last_activity?->toISOString(),
        ];
    }
}
