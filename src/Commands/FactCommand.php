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

        $facts = json_decode(file_get_contents($file), true);

        if(empty($facts)) {
            $message->channel->sendMessage("Le Fact-O-Tron est vide, veuillez attendre...");
            return;
        }

        // TODO :: Ajouter un filtrage par paramètre
        $randomKey = array_rand($facts);
        $url = str_replace(
            ['(', ')', '_'],
            ['%28', '%29', '%5F'],
            $facts[$randomKey]['source']
        );
        $response = "### Fact\n{$facts[$randomKey]['fact']}\n_Source: [{$facts[$randomKey]['name']}]({$url})_";
        $message->channel->sendMessage($response);
    }
}