## Déploiement de l'application

###Prérequis Laravel
* PHP >= 7.1.3
* Extension PHP : OpenSSL
* Extension PHP : PDO
* Extension PHP : Mbstring
* Extension PHP : Tokenizer
* Extension PHP : XML
* Extension PHP : Ctype
* Extension PHP : JSON

Plus d'informations ici : https://laravel.com/docs/5.4#installation

###Ã€ l'intÃ©rieur du rÃ©pertoire

* TÃ©lÃ©chargement des dÃ©pendances composer : composer install
* TÃ©lÃ©chargement des dÃ©pendances npm : npm install
* CrÃ©er un fichier nommÃ© ".env" Ã  la racine
* Copier le contenu du fichier ".env.example" Ã  l'intÃ©rieur du fichier .env et sauvegarder
* GÃ©nÃ©rer une nouvelle clÃ© pour l'application : php artisan key:generate
* Ouvrir Ã  nouveau le fichier .env et modifier uniquement les variables "APP_", "DB_" et "GOOGLE_". Puis sauvegarder et quitter...
* DÃ©ployer les tables dans la base de donnÃ©es : php artisan migrate
* Remplir les tables de la BDD avec des fausses donnÃ©es : php artisan db:seed
* Se rendre Ã  la racine du site /public

###Ã€ savoir
* Pour mettre les droits d'administrateur Ã  un utilisateur, il faut modifier la colonne "is_admin" dans la table users de 0 Ã  1
* La panel d'administration s'accÃ¨de depuis "/admin" ou depuis le lien visible sur la page d'accueil (le lien s'affiche si l'utilisateur est admin)