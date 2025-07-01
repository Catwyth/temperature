# Application de Surveillance de Température

Cette application web permet de loguer des températures via une API REST et de les visualiser sur un tableau de bord interactif. Elle est construite avec Symfony 7, FrankenPHP, Docker et MySQL.

## Fonctionnalités

- **API REST** : Permet à un capteur de température de soumettre des données (température et horodatage) via un endpoint sécurisé par un token.
- **Tableau de bord** :
    - Affiche la dernière température reçue.
    - Graphique des températures de la journée en cours (par heure).
    - Graphique des températures des 30 derniers jours (agrégées par jour).

## Prérequis

- Docker et Docker Compose installés sur votre machine.

## Installation et Utilisation

Suivez les étapes ci-dessous pour installer et lancer l'application localement.

1.  **Cloner le dépôt** (si ce n'est pas déjà fait) :

    ```bash
    git clone <URL_DU_DEPOT>
    cd <NOM_DU_DOSSIER>
    ```

2.  **Construire et démarrer les conteneurs Docker** :

    ```bash
    docker-compose up --build -d
    ```

    Ceci va construire les images Docker (FrankenPHP pour PHP et MySQL) et démarrer les services en arrière-plan. La première construction peut prendre un certain temps.

3.  **Installer les dépendances Composer** (si ce n'est pas déjà fait par le build) :

    ```bash
    docker-compose run --rm php composer install
    ```

4.  **Exécuter les migrations de la base de données** :

    ```bash
    docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction
    ```

5.  **Installer les dépendances JavaScript et compiler les assets** :

    ```bash
    docker-compose run --rm php npm install
    docker-compose run --rm php npm run build
    ```

6.  **Accéder à l'application** :

    L'application sera accessible via votre navigateur à l'adresse suivante :

    [http://localhost](http://localhost)

    Le tableau de bord affichera la dernière température et les liens vers les graphiques.

## Configuration de l'API

L'API est protégée par un token simple. Vous devez configurer ce token dans le fichier `config/services.yaml` :

```yaml
parameters:
    app.api_token: 'your_secret_api_token' # Changez ceci par un token sécurisé
```

Assurez-vous de remplacer `your_secret_api_token` par une valeur sécurisée de votre choix.

## Utilisation de l'API

Pour envoyer une température au capteur, utilisez une requête `POST` à l'endpoint `/api/log` avec un en-tête `Authorization: Bearer VOTRE_TOKEN` et un corps JSON contenant la température :

```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_secret_api_token" \
  -d '{"temperature": 25.5}' \
  http://localhost/api/log
```

Remplacez `your_secret_api_token` par le token que vous avez configuré et `25.5` par la température souhaitée.

## Structure du Projet

- `docker-compose.yml` : Configuration Docker pour FrankenPHP et MySQL.
- `Dockerfile` : Instructions pour construire l'image PHP avec FrankenPHP.
- `src/Entity/Temperature.php` : Entité Doctrine pour stocker les températures.
- `src/Repository/TemperatureRepository.php` : Repository personnalisé pour les requêtes de température.
- `src/Controller/ApiController.php` : Contrôleur pour l'API REST.
- `src/Controller/DashboardController.php` : Contrôleur pour le tableau de bord.
- `templates/` : Fichiers Twig pour le rendu de l'interface utilisateur.
- `assets/` : Fichiers JavaScript et CSS pour le frontend (gérés par Webpack Encore).


## Info
Construit avec https://blog.google/technology/developers/introducing-gemini-cli-open-source-ai-agent/ et ce prompt

```
Génère une application web complète en PHP 8.4 avec le framework Symfony 7, utilisant FrankenPHP, Docker, et MySQL.
Fonctionnalités attendues :
1. API :

    Une API REST permettant à un capteur de température de transmettre une valeur numérique (température en degrés Celsius) avec un horodatage.

    Endpoint protégé par une clé API ou token simple.

2. Interface utilisateur :

    Une page qui affiche la dernière température reçue.

    Une page avec un graphique de la température de la journée en cours (par heure).

    Une page avec un graphique des températures des 30 derniers jours (agrégées par jour).

Contraintes techniques :

    Base de données : MySQL, avec une table pour stocker les températures (id, temperature, created_at).

    Docker : Fournir un docker-compose complet incluant FrankenPHP et MySQL.

    Utiliser FrankenPHP pour le serveur HTTP PHP natif.

    Le frontend peut utiliser Twig ou une solution simple intégrée à Symfony.

    Les graphiques peuvent être générés avec une bibliothèque JS comme Chart.js.

Livrables :

    Code source complet de l'application Symfony.

    Fichier docker-compose.yml opérationnel.

    Instructions pour l’installation et l’utilisation.

Fournis un code prêt à déployer ou exécuter localement.
```