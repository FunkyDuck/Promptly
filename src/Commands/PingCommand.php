<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;

class PingCommand {
    public function execute(Message $message, array $params): void {
        $message->channel->sendMessage('🏓 Pong!');
    }
}