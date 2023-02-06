# SchoolMessage
Site de gestion d'envoie de mail
​Temps de réalisation environ : 10h pour un site fonctionnelle + 5h pour le front
# Require
​
- Symfony 6 (and CLI)
- MySQL 8
- Composer
​
L'envoie de mail à été tester avec <a href="https://mailtrap.io/" target="_blank">mailtrap</a>

# Lancer le projet
​
Cloner le projet, installer les dépendances
Modifier le .env selon votre configuration
​
```bash
brew install symfony-cli/tap/symfony-cli (mac OS)
git clone https://github.com/Cpasklaire/SchoolMessage.git SchoolMessage
cd SchoolMessage
composer install
cp .env.example .env
vi .env
```
​
Préparer la base de données
​
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony server:start
​
​
# Thanks
​
<a href="https://iconscout.com/icons/envelope-open" target="_blank">Envelope Open Icon</a> by <a href="https://iconscout.com/contributors/latesticon" target="_blank">Latest Icon</a>

# Possible follow-up
- Gestion des erreur d'entré dans le formulaire
- Test unitaire complet
- DB sécuriser car info sensible (email)