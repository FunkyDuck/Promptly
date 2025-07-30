<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;

class FactCommand {
    public function execute(Message $message, array $params) {
        $file = __DIR__ . '/../../resources/facts.json';

        if(!file_exists($file)) {
            $message->channel->sendMessage("Le Fact-O-Tron est déconnecté, veuillez patienter...");
            return;
        }

        $content = file_get_contents($file);
        $facts = json_decode($content, true);

        if(empty($facts)) {
            $message->channel->sendMessage("Le Fact-O-Tron est vide, veuillez attendre...");
            return;
        }

        // TODO :: Ajouter un filtrage par paramètre
        $randomKey = array_rand($facts);
        $message->channel->sendMessage($facts[$randomKey]['fact']);
    }
}