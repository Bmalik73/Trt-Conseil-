# EVALUATION :
## Développer la partie Back-End d'une Application Web


### Contexte de l'évaluation :
TRT Conseil est une agence de recrutement spécialisée dans l’hôtellerie et la restauration.
L’agence souhaite proposer pour l’instant une simple interface avec une authentification.

4 types d’utilisateur devront pouvoir se connecter :

 1. **Les recruteurs** : Une entreprise qui recherche un employé.
 2. **Les candidats** : Un serveur, responsable de la restauration, chef cuisinier etc.
 3. **Les consultants** : Missionnés par TRT Conseil pour gérer les liaisons sur le back-office entre
recruteurs et candidats.
 4. **L’administrateur** : La personne en charge de la maintenance de l’application.

### Déploiement en local :
Le déploiement en local de cette application suggère que vous disposez au minimum d'un serveur local (Apache), de PHP 8.1, de MySQL et de Symfony.



 - Installez les dépendances de l'application :
```
composer install
```
 - Modifiez le fichier environnement ( .env situé dans le dossier racine ) afin d'y lier votre propre base de données. L'URL de cette dernière doit être modifiée dans la ligne suivante :
 ```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8&charset=utf8mb4
```
 - Toujours dans ce même fichier .env, modifiez la variable d'environnement afin de la passer en développement, sinon, vos modifications ne seront pas prises en compte :
```
APP_ENV=dev
```
 - Remplacez "db_user" par votre nom d'utilisateur (en règle générale, "root" en local), "db_password" par votre mot de passe et "db_name" par le nom que vous souhaitez donner à votre base de données. Afin de valider cette manipulation, créez la base de données et effectuez la migration.
```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
 - Enfin, lancez le serveur local :
```
symfony server:start
```
