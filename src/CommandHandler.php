<?php

namespace Promptly;

use Discord\Parts\Channel\Message;

class CommandHandler {
    public static function handle(Message $message): void {
        if($message->author->bot) return;

        echo "Message reçu : '{$message->content}' de {$message->author->username}\n";

        $content = $message->content;

        $message->channel->sendMessage('Message reçu loud and clear !');

        if(empty($content)) {
            file_put_contents(__DIR__ . '/empty_messages.log', date('c') . " Message vide reçu de {$message->author->username}\n", FILE_APPEND);
            return;
        }
        if(!str_starts_with($content, '!')) return;

        $args = explode(' ', ltrim($content, '!'));
        $command = ucfirst(strtolower($args[0]));
        $params = array_slice($args, 1);

        echo "Commande détectée : {$command}\n";

        $className = "Promptly\\Commands\\{$command}Command";

        if(class_exists($className)) {
            (new $className())->execute($message, $params);
        }
        else {
            $message->channel->sendMessage("??? Commande inconnue :: `!{$command}`");
        }
    }
}