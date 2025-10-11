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
        $schema->int('position_exercise');
        $schema->varchar('title', 255)->notNull();
        $schema->text('description');
        $schema->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
        $schema->int('time_limit')->default(120)->notNull();
        $schema->varchar('language', 16)->notNull();
        $schema->text('starter_code')->notNull();
        $schema->text('solution_code')->notNull();
        $schema->json('test');
        $schema->timestamps();

        // Create table
        $sql = $schema->toSql('codegame_exercises');
        Database::get()->exec($sql);
    }

    /**
     * Reverse migration
     */
    public function down(): void {
        Database::dropTableIfExists('codegame_exercises');
    }
};