<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/error.log'); // un fichier error.log à la racine du projet

require 'vendor/autoload.php';

use Promptly\Bot;

$bot = new Bot();
$bot->run();