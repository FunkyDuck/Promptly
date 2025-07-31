<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;

class QuestionCommand {
    protected static array $activeQuestions = [];
    protected const TIMEOUT = 300;

    public function execute(Message $message, array $params) {
        $userId = $message->author->id;
        $lang = $params[0] ?? null;

        $questions = json_decode(file_get_contents(__DIR__ . '/../../resources/questions.json'), true);

        if(!$questions) {
            $message->channel->sendMessage('Pas de question... Tu veux en parler au canard en plastique? 🦆');
            return;
        }

        // Filter by langage if is requested
        if($lang) {
            $filtered = array_filter($questions, fn($q) => strtolower($q['langage']) == strtolower($lang));
            if(empty($filtered)) {
                $message->channel->sendMessage("Pas de questions disponibles pour le langage '{$lang}' parce que le dev avait la flemme.");
                return;
            }
            $questions = array_values($filtered);
        }

        $question = $questions[array_rand($questions)];

        // register question for user
        $questionKey = "{$userId}-{$message->guild_id}-{$message->channel_id}";
        self::$activeQuestions[$questionKey] = [
            "question" => $question, 
            "timestamp" => time()
        ];

        $message->channel->sendMessage("## J'ai une question **'{$question['langage']}'** pour toi :\n**{$question['question']}**\n_Réponds directement dans ce canal._");
    }

    public static function tryAnswer(Message $message): void {
        $userId = $message->author->id;
        $content = trim($message->content);
        $questionKey = "{$userId}-{$message->guild_id}-{$message->channel_id}";

        if(!isset(self::$activeQuestions[$questionKey])) return;

//         error_log("tryAnswer: looking for key: $questionKey");
// error_log("current keys: " . implode(', ', array_keys(self::$activeQuestions)));

        $data = self::$activeQuestions[$questionKey];
        $question = $data['question'];

        
        // Kill the question (only one answer)
        unset(self::$activeQuestions[$questionKey]);
        
        if(time() - $data['timestamp'] > self::TIMEOUT) {
            $message->channel->sendMessage("⌛ Tu as oublié que tu avais une question en cours? Ton temps est écoulé <@{$userId}>...");
            return;
        }
        // Check answer
        if(self::checkAnswer($content, $question)) {
            $message->channel->sendMessage("👌 Bravo <@{$userId}> ! Bonne réponse 🎉\n{$question['explanation']}");
        }
        else {
            $message->channel->sendMessage("👎 Je connais un <@{$userId}> qui va faire planter son code 😆\nLa bonne réponse était : " . implode(' / ', $question['answers']) . "\n_Explication : {$question['explanation']}_");
        }
    }

    public static function checkAnswer(string $userAnswer, array $question) {
        $userAnswer = strtolower(trim($userAnswer));

        // Sanity check
        if (!isset($question['answers']) || !is_array($question['answers'])) {
            error_log("[Promptly] Question mal formée (missing 'answers')");
            return false;
        }

        // Check if matching 100% on answer
        foreach ($question['answers'] as $answer) {
            if($userAnswer === strtolower(trim($answer))) return true;
        }

        // Fuzzy test on accepted phrases
        if(!empty($question['accepted_phrases'])) {
            foreach ($question['accepted_phrases'] as $phrase) {
                if(stripos($userAnswer, $phrase) !== false) return true;
            }
        }
        
        return false;
    }
}