<?php

namespace Promptly;

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
                CommandHandler::handle($message);
            });
        });

        $discord->run();
    }
}