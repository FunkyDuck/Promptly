<?php

namespace Promptly;

use Promptly\Commands\QuestionCommand;

use Discord\Discord;
use Discord\WebSockets\Event;

class Bot {
    public function run(): void {
        Bootstrap::init();

        $discord = new Discord([
            'token' => $_ENV['DISCORD_TOKEN'],
            'intents' => \Discord\WebSockets\Intents::getDefaultIntents() | \Discord\WebSockets\Intents::MESSAGE_CONTENT,
        ]);

        echo "Promptly launched :: {$discord->user->username}";

        $discord->on('ready', function ($discord) {
            echo "Bot ready !\n";

            $discord->on(Event::MESSAGE_CREATE, function ($message) {
                if($message->author->bot) return;

                // Try to treat it like an answer for opened question
                QuestionCommand::tryAnswer($message);

                // And now to a classic command
                CommandHandler::handle($message);
            });
        });

        $discord->run();
    }
}