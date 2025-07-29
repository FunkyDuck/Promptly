<?php

namespace Promptly;

use Dotenv\Dotenv;

class Bootstrap {
    public static function init(): void {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }
}