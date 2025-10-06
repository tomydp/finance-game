<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Exercise extends Model
{
    public const STATUS_ACTIVO   = 'activo';
    public const STATUS_INACTIVO = 'inactivo';
    public const STATUSES        = [self::STATUS_ACTIVO, self::STATUS_INACTIVO];

    /**
     * Tipos válidos:
     * - 'mcq'        (opción múltiple con 1 respuesta correcta, guardada como string)
     * - 'true_false' ('true' | 'false' como string; en FE podés enviar boolean)
     * - 'fill_blank' (correct_answer como JSON string: ["respuesta1", "respuesta2", ...])
     */
    protected $fillable = [
        'lesson_id',
        'type',
        'question',
        'options',         // json para MCQ
        'correct_answer',  // string | json-string (según type)
        'explanation_md',  // ✅ nuevo campo
        'status',
    ];

    protected $casts = [
        'options' => 'array',
        'status'  => 'string',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVO,
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVO);
    }

    /**
     * Normaliza la(s) respuesta(s) correcta(s) a un array de strings.
     */
    public function correctAnswerArray(): array
    {
        $raw = $this->correct_answer;

        if ($this->type === 'fill_blank') {
            // Esperamos JSON string con array de respuestas válidas
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return array_map(static fn($v) => trim((string) $v), $decoded);
                }
            }
            // fallback: si por error guardaron texto plano
            return [$this->normalizeString((string) $raw)];
        }

        // 'mcq' (string simple con la opción correcta) o 'true_false' ('true'|'false')
        if (is_string($raw)) {
            return [trim($raw)];
        }

        // fallback defensivo (no debería ocurrir)
        if (is_array($raw)) {
            return array_map(static fn($v) => trim((string) $v), $raw);
        }

        return [];
    }

    /**
     * Devuelve una versión presentable de la solución (primer elemento).
     * Para true/false lo convierte a 'Verdadero' | 'Falso'.
     */
    public function publicSolution(): string
    {
        $ans = $this->correctAnswerArray();

        if ($this->type === 'true_false') {
            return strtolower($ans[0] ?? '') === 'true' ? 'Verdadero' : 'Falso';
        }

        return (string) ($ans[0] ?? '');
    }

    /**
     * Chequea la respuesta del usuario con normalización.
     * Acepta:
     *  - true/false: bool o string ('true'|'false'|'verdadero'|'falso')
     *  - mcq: string que iguale la opción correcta
     *  - fill_blank: string que iguale cualquiera de las respuestas válidas
     */
    public function checkAnswer($answer): bool
    {
        $shouldLog = app()->environment(['local', 'testing']) || (bool) config('app.debug');

        // --- TRUE/FALSE ---
        if ($this->type === 'true_false') {
            $target   = $this->normalizeString($this->publicSolution()); // 'verdadero' | 'falso'
            $incoming = is_bool($answer)
                ? ($answer ? 'verdadero' : 'falso')
                : $this->normalizeString((string) $answer);

            $result = ($incoming === $target);

            if ($shouldLog) {
                Log::debug('Exercise::checkAnswer [true_false]', [
                    'exercise_id' => $this->id,
                    'given'       => $incoming,
                    'correct'     => $target,
                    'result'      => $result,
                ]);
            }

            return $result;
        }

        // --- MCQ (opción única correcta como string) ---
        if ($this->type === 'mcq') {
            $expected = $this->normalizeString($this->publicSolution());
            $incoming = $this->normalizeString((string) $answer);
            $result   = ($incoming === $expected);

            if ($shouldLog) {
                Log::debug('Exercise::checkAnswer [mcq]', [
                    'exercise_id' => $this->id,
                    'given'       => $incoming,
                    'correct'     => $expected,
                    'result'      => $result,
                ]);
            }

            return $result;
        }

        // --- FILL_BLANK (varias respuestas válidas) ---
        if ($this->type === 'fill_blank') {
            $valids   = array_map([$this, 'normalizeString'], $this->correctAnswerArray());
            $incoming = $this->normalizeString((string) $answer);
            $result   = in_array($incoming, $valids, true);

            if ($shouldLog) {
                Log::debug('Exercise::checkAnswer [fill_blank]', [
                    'exercise_id' => $this->id,
                    'given'       => $incoming,
                    'valids'      => $valids,
                    'result'      => $result,
                ]);
            }

            return $result;
        }

        // Fallback (tipo desconocido)
        if ($shouldLog) {
            Log::warning('Exercise::checkAnswer [unknown type]', [
                'exercise_id' => $this->id,
                'type'        => $this->type,
            ]);
        }

        return false;
    }

    /**
     * Normaliza textos para comparar (minúsculas + colapsa espacios).
     */
    private function normalizeString(string $s): string
    {
        $s = preg_replace('/\s+/', ' ', trim($s));
        return mb_strtolower($s);
    }
}
