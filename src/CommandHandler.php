<?php

namespace Promptly;

use Discord\Parts\Channel\Message;

class CommandHandler {
    public static function handle(Message $message): void {
        if($message->author->bot) return;

        $content = $message->content;

        if(!str_starts_with($content, '!')) return;

        $args = explode(' ', ltrim($content, '!'));
        $command = ucfirst(strtolower($args[0]));
        $params = array_slice($args, 1);

        $className = "Promptly\\Commands\\{$command}Command";

        if(class_exists($className)) {
            (new $className())->execute($message, $params);
        }
        else {
            $message->channel->sendMessage("??? Commande inconnue :: `!{$command}`");
        }
    }
}