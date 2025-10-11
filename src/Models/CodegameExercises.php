<?php

namespace Promptly\Models;

use FunkyDuck\Querychan\ORM\Model;
use FunkyDuck\Querychan\ORM\SchemaBuilder;

class CodegameExercises extends Model {
    /**
     * Table name for the model
     * @var string
     */
    protected static string $table = 'codegame_exercises';

    /**
     * Table attributes
     * @var array
     */
    protected array $fillable = [
        'position_exercise',
        'title',
        'description',
        'difficulty',
        'time_limit',
        'language',
        'starter_code',
        'solution_code',
        'test'
    ];
}