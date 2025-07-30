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

        $content = file_get_contents($file);
        $jokes = json_decode($content, true);

        if(empty($jokes)) {
            $message->channel->sendMessage("Le Joke-O-matic est vide, veuillez le remplir...");
            return;
        }

        // TODO :: Ajouter un filtrage par paramètre, pour type de blague
        $randomJoke = array_rand($jokes);
        $message->channel->sendMessage($randomJoke['joke']);
    }
}