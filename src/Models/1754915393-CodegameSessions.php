<?php

namespace Querychan\Models;

use FunkyDuck\Querychan\ORM\Model;
use FunkyDuck\Querychan\ORM\SchemaBuilder;

class CodegameSessions extends Model {
    protected static string $table = 'codegame_sessions';

    protected static function schema(): SchemaBuilder {
        $schema = new SchemaBuilder();
        $schema->id();
        $schema->foreign('player_id', 'players', 'id');
        $schema->foreign('exercise_id', 'codegame_exercises', 'id');
        $schema->enum('status', ['in progress', 'passed', 'failed']);
        $schema->int('score')->default(0);
        $schema->timestamp('ended_at')->nullable();
        $schema->timestamps();
        return $schema;
    }
}