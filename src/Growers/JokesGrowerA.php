<?php

namespace Promptly\Growers;

use FunkyDuck\Querychan\ORM\QueryBuilder;
use FunkyDuck\NijiEcho\NijiEcho;
use Promptly\Models\Jokes;

class JokesGrowerA {
    public function run() {
        echo NijiEcho::info("--- Début du seeding de la table 'jokes' ---") . "\n";

        $projectRoot = realpath(__DIR__ . '/../../');
        $jsonPath = $projectRoot . '/resources/jokes.json';
        
        if (!file_exists($jsonPath)) {
            echo NijiEcho::error("!! Erreur : Fichier jokes.json introuvable.") . "\n";
            return;
        }

        $jokesData = json_decode(file_get_contents($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo NijiEcho::error("!! Erreur : Le fichier JSON est mal formé.") . "\n";
            return;
        }

        // --- DÉBUT DES AJOUTS DE DÉBOGAGE ---
        if (is_array($jokesData) && count($jokesData) > 0) {
            echo NijiEcho::info("   -> " . count($jokesData) . " blagues trouvées dans le fichier JSON.") . "\n";
        } else {
            echo NijiEcho::error("   -> Le fichier JSON est vide ou n'a pas pu être lu correctement.") . "\n";
            return;
        }
        // --- FIN DES AJOUTS DE DÉBOGAGE ---

        $query = new QueryBuilder(Jokes::class);
        $count = 0;

        foreach ($jokesData as $index => $item) {
            // --- DÉBUT DES AJOUTS DE DÉBOGAGE ---
            echo NijiEcho::text("   Traitement de la blague #" . ($index + 1) . "...")->color("light_gray") . "\n";
            // --- FIN DES AJOUTS DE DÉBOGAGE ---

            if (!isset($item['joke']) || !isset($item['category'])) {
                echo NijiEcho::warning("   -> Format incorrect, ignorée.") . "\n";
                continue;
            }
            
            $dataToInsert = [
                'joke_text' => $item['joke'],
                'category'  => $item['category']
            ];

            $existing = $query->where('joke_text', '=', $dataToInsert['joke_text'])->first();

            if ($existing) {
                echo NijiEcho::text("   -> Existe déjà, ignorée.")->color("light_gray") . "\n";
            } else {
                // --- DÉBUT DES AJOUTS DE DÉBOGAGE ---
                echo NijiEcho::info("   -> Nouvelle blague ! Tentative d'insertion...") . "\n";
                // --- FIN DES AJOUTS DE DÉBOGAGE ---
                $query->insert($dataToInsert);
                $count++;
            }
        }

        echo NijiEcho::success("--- Seeding terminé ! $count nouvelle(s) blague(s) ajoutée(s). ---") . "\n";
    }
}