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
        $schema->varchar('name', 128)->notNull();
        $schema->bigint('discord_id');
        $schema->bigint('server_id');
        $schema->int('level')->default(1);
        $schema->int('xp')->default(0);
        $schema->int('quizzes_played')->default(0);
        $schema->int('quizzes_won')->default(0);
        $schema->int('streak_days')->default(0);
        $schema->int('best_streak')->default(0);
        $schema->int('total_points')->default(0);
        $schema->json('badges');
        $schema->timestamps();

        // Create table
        $sql = $schema->toSql('players');
        Database::get()->exec($sql);
    }

    /**
     * Reverse migration
     */
    public function down(): void {
        Database::dropTableIfExists('players');
    }
};