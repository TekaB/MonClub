# Installation 
- git clone https://github.com/TekaB/MonClub.git
- composer install

# Config

- Create .env.local
- change DATABASE_URL to use your own database
- run `php bin/console do:mi:mi` at the root of the project to generate Schema and table

/!\ No fixtures yet ! /!\

# Usage

- Configurer votre "Club" dans /club/edit ou bouton modifier depuis la page /club 
- Retrouver vos joueurs dans la page "Mes joueurs"
- Créer des joueurs depuis la page "Mes joueurs > Nouveau joueur"
- Modifier vos joueurs depuis la page "Mes joueurs > Modifier"
- Visualiser vos équipe depuis la page "Mes équipes"
- Créer vos équipes depuis la page "Mes équipes" > "Nouvelle équipe"
- Ajouter des joueurs dans votre équipe depuis "Mes équipes > Modifier"

# Travailler sur ce projet

- Symfony 6.3 https://symfony.com/doc/6.3/setup.html
- PHP 8.1+ (see composer.json)
- AssetMapper https://symfony.com/doc/6.3/frontend/asset_mapper.html
- Bootstrap CSS https://getbootstrap.com/docs/3.4/css/