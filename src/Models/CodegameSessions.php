<?php

namespace Promptly\Models;

use FunkyDuck\Querychan\ORM\Model;
use FunkyDuck\Querychan\ORM\SchemaBuilder;

class CodegameSessions extends Model {
    /**
     * Table name for the model
     * @var string
     */
    protected static string $table = 'codegame_sessions';

    /**
     * Table attributes
     * @var array
     */
    protected array $fillable = [
        'player_id',
        'exercise_id',
        'status',
        'score',
        'ended_at'
    ];
}