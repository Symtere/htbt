<p align="center"><img src="https://user-images.githubusercontent.com/62699215/140902270-dd1f7b7a-c8c2-488d-8a2c-2df22f382a8a.png" width="181"></p>

## Installation

```php
cd www/
git clone git@github.com:Symtere/htbt.git htbt
cd htbt
npm i
```

## Accès (Local, Preprod, Prod) :
- user: `admin`, pass: `vwiAkjHou81BO+p5`
- url preprod: https://htbt.webexprxxx.ovh/, [plesk](https://xxx/)
- url prod:

## Vhost
- vhost : `htbt.local`

## NPM
- `npm i` installation des dependances
- `npm run start` lance watch + scss
- `npm run scss`  node-sass => scss compilation
- `npm run watch` browser-sync => watch *.php, *.css, *.js files

## Plugins
- [ACF Pro](https://www.advancedcustomfields.com/resources/) `MDBmNGNmODIxZWQ4OWYwZGViOGUzZWVhYmY1NDg1MjkyMDZlM2MwNGU5YzI0YmIyY2NkOTE0`
- [WPshapere] `8dd94d20-2e6d-46e4-9977-9c91971038b4`
- [Ninja form] : `a7e5ef3f2010c48640712f14f06c076f`
- [Query Monitor](https://github.com/johnbillion/query-monitor) Pour le debug (être connecté en tant qu'admin)

## WP CLI
Exporter la base de données pour staging et déployer le code en preprod
- `local.sql` est le backup du local,<br />
- `staging.sql` est le dump à remplacer en base de données sur le serveur de preprod
```cmd
    cd config/migrations
    sh local-staging.sh
```

## Réglages Plesk Déploiement auto
```cmd
    rm -rf README.md
    rm -rf readme.html
    rm -rf license.txt
    rm -rf *.json
    rm -rf wp-cli.yml
    rm -rf config
```

## Déploiement automatique via Github et WP-CLI en preprod
1. Lancer la commande `cd config/migrations; sh local-staging.sh`
2. Faire une Pull Request de `master` vers `staging`. Merger la PR <br><br> `staging` <= `master`
![Screenshot_7](https://user-images.githubusercontent.com/62699215/128326438-5191b849-df59-4206-b63f-f122c04c561d.png)
3. Synchroniser les fichiers ignorés (plugins, uploads) si nouveaux => dans le BO de la preprod depuis le back office installer les plugins, via FTP envoyer le dossier /uploads
4. Se connecter au plesk, dans le phpmyadmin supprimer toutes les tables, importer la base de données `staging.sql`
