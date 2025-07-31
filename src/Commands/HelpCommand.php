<?php

namespace Promptly\Commands;

use Promptly\Config\Version;
use Discord\Parts\Channel\Message;

class HelpCommand {
    public function execute(Message $message, array $params) {
        $file = __DIR__ . '/../../resources/help.json';
        $command = $params[0] ?? null;

        if(!file_exists($file)) {
            $message->channel->sendMessage("Oups, l'aide est telle une 404");
            return;
        }

        $data = json_decode(file_get_contents($file), true);

        if(empty($data) || empty($data['help'])) {
            $message->channel->sendMessage("Hmmmm... Le fichier d'aide est vide...");
            return;
        }

        if($command) {
            $commandHelp = array_find($data['help'], fn($c) => strtolower($c['command']) == strtolower($command));

            if(!$commandHelp) {
                $message->channel->sendMessage("🧑‍🦯 La seule personne a avoir vu cette commande, c'est Gilbert Montagné.\n_Commande `{$command}`inexistante_");
                return;
            }
            $response = "# Commande `{$command}`:\n{$commandHelp['description']}\n_{$commandHelp['details']}_";
            $message->channel->sendMessage($response);
        }
        else {
            $response = "# Commandes disponibles:\n";
            
            foreach($data['help'] as $entry) {
                $response .= "• `{$entry['command']}` : _{$entry['description']}_\n";
            }

            $response .= "\n_**Promptly** Version : " . Version::VERSION . "_";
            
            $message->channel->sendMessage($response);
        }
    }
}