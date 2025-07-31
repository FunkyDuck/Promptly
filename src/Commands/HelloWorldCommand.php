<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;

class HelloWorldCommand {
    public function execute(Message $message, array $params) {
        $lang = $params[0] ?? null;

        $codes = json_decode(file_get_contents(__DIR__ . "/../../resources/hello_world.json"), true);

        if(!$codes) {
            $message->channel->sendMessage("💾 Désolé, mais le développeur n'a pas encore créé de Hello World!");
            return;
        }

        if($lang) {
            $filtered = array_filter($codes, fn($c) => strtolower($c['lang']) == strtolower($lang));
            if(empty($filtered)) {
                $message->channel->sendMessage("🖨️ Désolé mais, êtes-vous sur que ce langage existe? Nous n'avons pas de code de démonstration...");
                return;
            }
            $codes = array_values($filtered);
        }

        $code = $codes[array_rand($codes)];

        $message->channel->sendMessage("## Pour afficher `Hello World!` en `{$code['lang']}`\n```{$code['code']}```");
    }
}