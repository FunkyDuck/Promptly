<?php

namespace Querychan\Models;

use Querychan\ORM\Model;
use Querychan\ORM\SchemaBuilder;

class CodegameExercises extends Model {
    protected static string $table = 'codegame_exercises';

    protected static function schema(): SchemaBuilder {
        $schema = new SchemaBuilder();
        $schema->id();
        $schema->int('position_exercise');
        $schema->varchar('title', 255)->notNull();
        $schema->text('description');
        $schema->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
        $schema->int('time_limit')->default(120)->notNull();
        $schema->varchar('language', 16)->notNull();
        $schema->text('starter_code')->notNull();
        $schema->text('solution_code')->notNull();
        $schema->json('test')->nullable();
        $schema->timestamps();
        return $schema;
    }
}