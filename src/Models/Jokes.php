<?php

namespace Promptly\Models;

use FunkyDuck\Querychan\ORM\Model;
use FunkyDuck\Querychan\ORM\SchemaBuilder;

class Jokes extends Model {
    /**
     * Table name for the model
     * @var string
     */
    protected static string $table = 'jokes';

    /**
     * Table attributes
     * @var array
     */
    protected array $fillable = [
        'joke_text',
        'category'
    ];
}