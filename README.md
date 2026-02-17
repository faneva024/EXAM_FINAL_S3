# Gestionnaire de Tâches (ToDo List)

Ce projet est une application web de gestion de tâches (ToDo List) développée en PHP, utilisant le framework FlightPHP et une architecture MVC.

## Fonctionnalités

- Ajout, modification et suppression de tâches
- Gestion des achats (module Achat)
- Interface utilisateur simple et responsive
- Authentification (si implémentée)
- Persistance des données via une base de données SQL

## Structure du projet

```
.
├── app/
│   ├── commands/         # Commandes CLI (si présentes)
│   ├── config/           # Fichiers de configuration
│   ├── controllers/      # Contrôleurs MVC
│   ├── log/              # Logs de l'application
│   ├── middlewares/      # Middlewares HTTP
│   ├── models/           # Modèles de données
│   └── views/            # Vues (templates)
├── assets/               # Ressources statiques
├── public/               # Racine web (index.php, router.php, assets)
├── vendor/               # Dépendances Composer
├── composer.json         # Dépendances PHP
├── docker-compose.yml    # Configuration Docker
├── Vagrantfile           # Configuration Vagrant
├── README.md             # Ce fichier
└── ...
```

## Installation

1. **Cloner le dépôt :**
   ```sh
   git clone <url-du-repo>
   cd <nom-du-repo>
   ```

2. **Installer les dépendances PHP :**
   ```sh
   composer install
   ```

3. **Configurer l'environnement :**
   - Copier `app/config/config_sample.php` en `app/config/config.php` et adapter les paramètres (base de données, etc.).

4. **Initialiser la base de données :**
   - Importer le fichier `data.sql` dans votre SGBD (MySQL, MariaDB, etc.).

5. **Lancer l'application :**
   - Avec PHP :
     ```sh
     php -S localhost:8000 -t public
     ```
   - Ou via Docker :
     ```sh
     docker-compose up
     ```

## Utilisation

Accédez à [http://localhost:8000](http://localhost:8000) dans votre navigateur pour utiliser l'application.

## Dépendances principales

- [FlightPHP](https://flightphp.com/)
- [Tracy](https://tracy.nette.org/)
- PHP >= 7.4

## Contribution

Les contributions sont les bienvenues ! Merci de créer une issue ou une pull request.

## Licence

Ce projet est sous licence MIT.

---

*Pour plus d'informations, consultez la documentation dans le fichier `TODOLIST_PROJET.txt`.*