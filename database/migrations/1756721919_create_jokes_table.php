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
        $schema->text('joke_text');
        $schema->enum('category', ['général', 'dev', 'réseau', 'sql', 'hardware'])->default('general');
        $schema->timestamps();

        // Create table
        $sql = $schema->toSql('jokes');
        Database::get()->exec($sql);
    }

    /**
     * Reverse migration
     */
    public function down(): void {
        Database::dropTableIfExists('jokes');
    }
};