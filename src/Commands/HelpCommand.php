<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;

class HelpCommand {
    public function execute(Message $message, array $params) {
        $file = __DIR__ . '/../../resources/help.json';

        if(!file_exists($file)) {
            $message->channel->sendMessage("Oups, l'aide est telle une 404");
            return;
        }

        $content = file_get_contents($file);
        $data = json_decode($content, true);

        if(empty($data) || empty($data['help'])) {
            $message->channel->sendMessage("Hmmmm... Le fichier d'aide est vide...");
            return;
        }

        $response = "# Commandes disponibles:\n";

        foreach($data['help'] as $entry) {
            $response .= "• `{$entry['command']}` : _{$entry['description']}_\n";
        }

        $message->channel->sendMessage($response);
    }
}