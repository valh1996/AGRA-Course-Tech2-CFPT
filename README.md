# AGRA-Course-Tech2-CFPT
Cours pour nous rappeler les bases du web. Effectué au CFPT, à Genève.

## Déploiement de l'application

### Prérequis Laravel
* PHP >= 7.1.3
* Extension PHP : OpenSSL
* Extension PHP : PDO
* Extension PHP : Mbstring
* Extension PHP : Tokenizer
* Extension PHP : XML
* Extension PHP : Ctype
* Extension PHP : JSON

Plus d'informations ici : https://laravel.com/docs/5.6#installation

### À l'intérieur du répertoire "backend"

* Télécharger les dépendances composer : `composer install`
* Télécharger les dépendances npm : npm install
* Compiler les assets (css & javascript) : npm run dev
* créer le fichier ".env" à la racine
* Copier le contenu du fichier ".env.example" à l'intérieur du fichier .env et sauvegarder
* Générer une nouvelle clé pour l'application : php artisan key:generate
* Ouvrir à  nouveau le fichier .env et modifier uniquement les variables "APP_", "DB_". Puis sauvegarder et quitter...
* Déployer les tables dans la base de données (/!\ configurer .env avant) : php artisan migrate
* Se rendre à la racine du site /public ou configurer le virtualhost pour qu'il pointe dans le dossier public