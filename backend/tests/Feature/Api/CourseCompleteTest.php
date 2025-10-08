<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseCompleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_finish_all_lessons_before_completing_course(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $course = Course::create([
            'name'        => 'Curso de Prueba',
            'description' => 'Descripción',
            'difficulty'  => 'facil',
            'status'      => Course::STATUS_ACTIVO,
        ]);

        Lesson::create([
            'course_id'   => $course->id,
            'title'       => 'Lección 1',
            'description' => 'Intro',
            'order'       => 1,
            'status'      => Lesson::STATUS_ACTIVO,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/courses/{$course->id}/complete");

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['ok' => false]);

        $this->assertDatabaseMissing('course_user', [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_user_can_complete_course_when_all_lessons_are_done(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $course = Course::create([
            'name'        => 'Curso Completo',
            'description' => 'Descripción',
            'difficulty'  => 'medio',
            'status'      => Course::STATUS_ACTIVO,
        ]);

        $lessons = collect([
            Lesson::create([
                'course_id'   => $course->id,
                'title'       => 'Lección 1',
                'description' => 'Intro',
                'order'       => 1,
                'status'      => Lesson::STATUS_ACTIVO,
            ]),
            Lesson::create([
                'course_id'   => $course->id,
                'title'       => 'Lección 2',
                'description' => 'Intro 2',
                'order'       => 2,
                'status'      => Lesson::STATUS_ACTIVO,
            ]),
        ]);

        foreach ($lessons as $lesson) {
            DB::table('lesson_user')->insert([
                'user_id'      => $user->id,
                'lesson_id'    => $lesson->id,
                'completed_at' => now(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/courses/{$course->id}/complete");

        $response
            ->assertOk()
            ->assertJsonFragment(['completed' => true])
            ->assertJsonPath('course.id', $course->id);

        $this->assertDatabaseHas('course_user', [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }
}
