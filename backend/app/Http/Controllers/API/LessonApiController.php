<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonCollection;
use Illuminate\Http\Request;
use App\Models\Course;

class LessonApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        
    $lessons = $course->lessons()->orderBy('order')->get();
    return new LessonCollection($lessons);
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
