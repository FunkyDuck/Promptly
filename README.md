# Promptly 🤖

**Promptly** est un bot Discord codé en PHP, pensé pour injecter un peu d’humour, de savoir et de réconfort dans ton salon #dev.

---

## 🛠️ Commandes disponibles

- `!ping`  
  Vérifie que le bot est vivant. Il répond "Pong!".

- `!joke`  
  Renvoie une blague tech (ou pas).

- `!fact`  
  Renvoie un fun fact sur l’informatique, avec sa source.

- `!tip`  
  Donne un conseil de développement utile ou amusant.

- `!help`  
  Affiche la liste des commandes disponibles.

---

## 🔍 Sources des contenus

- Les **facts** sont stockés dans un fichier JSON au format suivant :

```json
{
  "fact": "left-pad a été supprimé de npm, cassant des milliers de projets.",
  "name": "Wikipedia - Npm left-pad",
  "source": "https://en.wikipedia.org/wiki/Npm_left-pad_incident"
}```
    Les tips utilisent un format similaire :

```{
  "tip": "Ne code jamais fatigué. Jamais.",
  "context": "general"
}```

    Tu peux filtrer les tips par contexte (ex : php, frontend, career, etc.).

## 🧪 En cours de dev
    - Commande !question (quiz simple)
    - Commande !quizz (quiz multi-joueur avec score)
    - Commande !battle (quiz live à points)

## 🧬 Stack technique
    - PHP 8.4
    - DiscordPHP
    - JSON pour le contenu dynamique
    - Markdown pour les formats enrichis dans Discord