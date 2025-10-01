<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExerciseCollection extends ResourceCollection
{
    public $collects = ExerciseResource::class;

    public function toArray($request)
    {
        return parent::toArray($request); // data + meta/links si hay paginator
    }
}
