<?php

namespace Promptly\Models;

use FunkyDuck\Querychan\ORM\Model;
use FunkyDuck\Querychan\ORM\SchemaBuilder;

class Players extends Model {
    /**
     * Table name for the model
     * @var string
     */
    protected static string $table = 'players';

    /**
     * Table attributes
     * @var array
     */
    protected array $fillable = [
        'discord_id',
        'server_id',
        'level',
        'xp',
        'quizzes_played',
        'quizzes_won',
        'streak_days',
        'best_streak',
        'total_points',
        'badges'
    ];
}