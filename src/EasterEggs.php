<?php

namespace Promptly;

use Discord\Parts\Channel\Message;

class EasterEggs {
    public static function handle(Message $message) {
        $content = trim($message->content);

        if (preg_match('/(?<!\d)(42)(?!\d)|\bquarante[-\s]?deux\b/ui', $message->content)) {
            if(rand(0,2) == 0) {
                $responses = [
                    "💫 *La réponse à la grande question sur la vie, l’univers et le reste...* **42**.",
                    "🤖 *Tu es sûr de vouloir savoir ?*",
                    "📚 *Demande à Deep Thought.*",
                    "🚀 *N’oublie pas ta serviette.*"
                ];
                $message->reply($responses[array_rand($responses)]);
            }
            return true;
        }
        if(preg_match('/^hello there[!.]*$/i', trim($message->content))) {
            $message->reply("General Kenobi");
            return true;
        }
        return false;
    }
}