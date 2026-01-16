# ViteGourmand

Site de commande de menus en livraison pour le restaurant VITE ET GOURMAND, développé dans le cadre de ma formation GRADUATE DEVELOPPEUR ANGULAR 2026 @ STUDI

## Démo en ligne

Le site est accessible ici : http://vitegourmand.rf.gd

## Code source

Dépôt GitHub : (https://github.com/vitegourmand2026/viteGourmand)


### Étapes d'installation en local

##  Structure du projet

htdocs/
├── .gitignore
├── README.md
├── code/
│   ├── css/
│   ├── database/
│   │   └── viteGourmand.sql
│   ├── js/
│   └── php/
│       ├── config.exemple.php    
│       └── config.php           
├── images/
└── ressources/

#### 1. Cloner le projet

git clone https://github.com/VOTRE-NOM/viteGourmand.git
cd viteGourmand

#### 2. Déplacer le projet dans un serveur local type MAMP

**Important :** Ce projet doit être placé dans le dossier racine `htdocs/` de MAMP.


#### 3. Créer la base de données

1. Nom : `viteGourmand`
2. Interclassement : `utf8mb4_unicode_ci`
 

#### 4. Importer les données

Sélectionner `database/viteGourmand.sql`


#### 5. Configurer la connexion à la base de données

1. Le projet utilise 3 fichiers de configuration séparés pour gérer local et production :

2. Modifier `config_local.php` ou `config_production.php` avec vos identifiants.

3. Créer les fichiers de configuration depuis les exemples et selon vos besoins d'environnement local ou     production 

4. Le fichier config.php détecte automatiquement l'environnement


#### 5.1 . Configurer api mail brevo

1. Dans le dossier process renommer le fichier `config_brevoExemple.php` en `config_brevo.php` et inserer la clé API founie dans la partie "LIENS" du pdf 

#### 6. Accéder au site


### Déploiement en production

#### 1. Créer la base de données sur l'hébergeur


#### 2. Importer la base de données

Importer le fichier `database/viteGourmand.sql`

#### 3. Uploader les fichiers


#### 4. Configurer config.php en production


## Technologies utilisées

- **Frontend** : HTML5, CSS3, JavaScript
- **Backend** : PHP 7.4
- **Base de données** : MySQL 5.7
- **Serveur local** : MAMP


## Auteur

THOMAS BRUSTON


## Ne jamais commit ce fichier sur Git !!!
