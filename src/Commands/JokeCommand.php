<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;

class JokeCommand {
    public function execute(Message $message, array $params) {
        $file = __DIR__ . '/../../resources/jokes.json';

        if(!file_exists($file)) {
            $message->channel->sendMessage("Le Joke-O-matic 2000 est pété, veuillez le remplacer...");
            return;
        }

        $jokes = json_decode(file_get_contents($file), true);

        if(empty($jokes)) {
            $message->channel->sendMessage("Le Joke-O-matic 2000 est vide, veuillez le remplir...");
            return;
        }

        // TODO :: Ajouter un filtrage par paramètre, pour type de blague
        $randomKey = array_rand($jokes);
        $message->channel->sendMessage($jokes[$randomKey]['joke']);
    }
}