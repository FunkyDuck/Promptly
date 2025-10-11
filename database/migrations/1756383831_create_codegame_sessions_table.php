<?php

use FunkyDuck\Querychan\ORM\Database;
use FunkyDuck\Querychan\ORM\SchemaBuilder;

return new class {
    /**
     * Run migration
     */
    public function up(): void {
        $schema = new SchemaBuilder();

        $schema->id();
        $schema->foreign('player_id', 'players', 'id');
        $schema->foreign('exercise_id', 'codegame_exercises', 'id');
        $schema->enum('status', ['in progress', 'passed', 'failed']);
        $schema->int('score')->default(0);
        $schema->timestamp('ended_at')->default(null);
        $schema->timestamps();

        // Create table
        $sql = $schema->toSql('codegame_sessions');
        Database::get()->exec($sql);
    }

    /**
     * Reverse migration
     */
    public function down(): void {
        Database::dropTableIfExists('codegame_sessions');
    }
};