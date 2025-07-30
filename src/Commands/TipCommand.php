<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;

class TipCommand {
    public function execute(Message $message, array $params) {
        $file = __DIR__ . '/../../resources/tips.json';

        if(!file_exists($file)) {
            $message->channel->sendMessage("Le Tip-O-matic est actuellement déconnecté...");
            return;
        }

        $tips = json_decode(file_get_contents($file), true);

        if(empty($tips)) {
            $message->channel->sendMessage("Le Tip-O-matic attend d'être configuré...");
            return;
        }

        // TODO :: Ajouter un filtrage par paramètre
        $randomKey = array_rand($tips);
        $message->channel->sendMessage($tips[$randomKey]['tip']);
    }
}