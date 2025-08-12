<?php

namespace Querychan\Models;

use Querychan\ORM\Model;
use Querychan\ORM\SchemaBuilder;

class Players extends Model {
    protected static string $table = 'players';

    protected static function schema(): SchemaBuilder {
        $schema = new SchemaBuilder();
        $schema->id();
        $schema->bigint('discord_id');
        $schema->bigint('server_id');
        $schema->int('level')->default(1);
        $schema->int('xp')->default(0);
        $schema->int('quizzes_played')->default(0);
        $schema->int('quizzes_won')->default(0);
        $schema->int('streak_days')->default(0);
        $schema->int('best_streak')->default(0);
        $schema->int('total_points')->default(0);
        $schema->json('badges')->nullable();
        $schema->timestamps();
        return $schema;
    }
}