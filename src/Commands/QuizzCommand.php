<?php

namespace Promptly\Commands;

use Discord\Parts\Channel\Message;
use React\EventLoop\Loop;

class QuizzCommand {
    protected static array $activeQuizzes = [];
    protected const TIMEOUT = 120;
    protected const TOTAL_QUESTIONS = 10;

    public function execute(Message $message, array $params) {
        $lang = $params[0] ?? null;
        $resourcesDir = __DIR__ . "/../../resources/data/";
        $quizzKey = self::getQuizzKey($message);
        $questions = [];
        
        if(isset(self::$activeQuizzes[$quizzKey])) {
            $message->channel->sendMessage("Ou tu sors, ou j'te sors, mais faudra prendre une décision ☎️\n_Un quizz est déjà actif ici, mais vous pouvez le rejoindre ou en créer un sur un autre chan._");
            return;
        }
        
        if($lang) {
            if($lang == 'js') $lang = 'javascript';
            if($lang == 'c#') $lang = 'csharp';
            
            $file = "{$resourcesDir}questions.{$lang}.json";
            
            if(!file_exists($file)) {
                $message->channel->sendMessage("Pas de questions pour le langage {$lang}. Êtes-vous sur que le langage existe? 🤔");
                return;
            }
            $questions = json_decode(file_get_contents($file), true);
        }
        else {
            $files = glob($resourcesDir . "questions.*.json");
            
            foreach ($files as $file) {
                $data = json_decode(file_get_contents($file), true);
                if(is_array($data)) $questions = array_merge($questions, $data);
            }
        }
        
        shuffle($questions);
        $questions = array_slice($questions, 0, self::TOTAL_QUESTIONS);

        if(empty($questions)) {
            $message->channel->sendMessage("On a perdu les questions!!!\n_On verra plus tard pour faire un quizz?_");
            return;
        }

        $quizz = &self::$activeQuizzes[$quizzKey];
        
        $quizz['questions'] = $questions;
        $quizz['indexQuestion'] = 0;
        $quizz['scores'] = [];
        $quizz['questionStartTime'] = time();
        
        $message->channel->sendMessage("# C'est parti pour le Quizz 🧠\n**Première question ::**\n{$questions[0]['question']}");
        self::startTimer($quizzKey, $message);
    }
    
    public static function tryAnswer(Message $message) {
        $quizzKey = self::getQuizzKey($message);
        
        if(!isset(self::$activeQuizzes[$quizzKey])) return;
        
        $quizz = &self::$activeQuizzes[$quizzKey];
        $quizzIndex = $quizz['indexQuestion'];
        $currentQuestion = $quizz['questions'][$quizzIndex];
        $userAnswer = strtolower(trim($message->content));
        $match = false;

        // Check if matching 100% on answer
        foreach ($currentQuestion['answers'] as $answer) {
            if($userAnswer === strtolower(trim($answer))) $match = true;
        }

        // Fuzzy test on accepted phrases
        if(!empty($currentQuestion['accepted_phrases'])) {
            foreach ($currentQuestion['accepted_phrases'] as $phrase) {
                if(stripos($userAnswer, $phrase) !== false) $match = true;
            }

            $authorId = $message->author->id;

            if($match) {
                $quizz['scores'][$authorId] = ($quizz['scores'][$authorId] ?? 0) + 1;
                $message->channel->sendMessage("✅ Bonne réponse <@{$message->userId}>");
                
                $quizz['indexQuestion']++;
                
                if($quizz['indexQuestion'] >= count($quizz['questions'])) {
                    // Quizz Ended
                    $scores = $quizz['scores'];
                    arsort($scores);
                    $result = "## Fin du quizz !\n**🏁 Classement 🏁**\n";
                    $result .= self::renderScore($scores);
                    $message->channel->sendMessage($result);
                    unset(self::$activeQuizzes[$quizzKey]);
                }
                else {
                    // Next question
                    $nextQuestion = $quizz['questions'][$quizz['indexQuestion']]['question'];
                    $message->channel->sendMessage("**Question suivante :**\n{$nextQuestion}");
                    $quizz['questionStartTime'] = time();
                    self::startTimer($quizzKey, $message);
                }
            }
        }
        return false;
    }

    protected static function startTimer(string $quizzKey, Message $message): void {
        Loop::addTimer(self::TIMEOUT, function () use ($quizzKey, $message) {
            if(!isset(self::$activeQuizzes[$quizzKey])) return;

            $quizz = &self::$activeQuizzes[$quizzKey];

            if(time() - $quizz['questionStartTime'] >= self::TIMEOUT) {
                $quizz['indexQuestion']++;

                if($quizz['indexQuestion'] >= count($quizz['questions'])) {
                    $scores = $quizz['scores'];
                    $result = "## Fin du quizz (temps écoulé) !\n **🏁 Classement 🏁**\n";
                    $result .= self::renderScore($scores);
                    $message->channel->sendMessage($result);
                    unset(self::$activeQuizzes[$quizzKey]);
                }
                else {
                    $nextQuestion = $quizz['questions'][$quizz['indexQuestion']]['question'];
                    $message->channel->sendMessage("### ⌛ Temps écoulé !\n**Question suivante:**\n{$nextQuestion}");
                    $quizz['questionStartTime'] = time();
                    self::startTimer($quizzKey, $message);
                }
            }
        });
    }

    protected static function getQuizzKey(Message $message): string {
        return "{$message->guild_id}-{$message->channel_id}";
    }

    protected static function renderScore(array $scores): string {
        arsort($scores);
        $result = "";
        $i = 1;
        foreach ($scores as $userId => $score) {
            $result .= "`#{$i} <@{$userId}> : {$score} pts`\n";
            $i++;
        }
        return $result;
    }
}