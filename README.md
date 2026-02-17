project-root/
│
├── app/
│   ├── config/
│   │   ├── bootstrap.php        # Initialisation de Flight, autoload, config et services
│   │   ├── config.php           # Configuration générale et base de données
│   │   ├── services.php         # Services comme Debugger, PDO, etc.
│   │   └── routes.php           # Toutes les routes du projet
│   │
│   ├── controllers/
│   │   └── CooperativeController.php   # Toutes les actions pour véhicules, chauffeurs, affectations, trajets
│   │
│   ├── models/
│   │   ├── VehiculeModel.php           # Gestion des véhicules
│   │   ├── ChauffeurModel.php          # Gestion des chauffeurs
│   │   ├── AffectationModel.php        # Gestion des affectations
│   │   └── TrajetModel.php             # Gestion des trajets
│   │
│   ├── views/
│   │   ├── home.php                     # Page d'accueil
│   │   ├── vehicules.php                # Liste de tous les véhicules
│   │   ├── chauffeurs.php               # Liste de tous les chauffeurs
│   │   ├── affectations.php             # Liste de toutes les affectations
│   │   └── trajets.php                  # Liste des trajets d'une affectation
│
├── public/
│   ├── index.php                        # Point d'entrée du projet
│   ├── images/                          # Images des véhicules ou trajets si nécessaire
│   │   └── ...                          # Ex: 1.jpg, 2.jpg, ...
│   └── styles.css                        # Styles CSS
│
├── vendor/                               # Composer autoload et packages
│   └── autoload.php
│
├── base.sql                              # Script SQL complet pour créer et remplir la base
├── composer.json                         # Dépendances et autoload
├── composer.lock
├── Vagrantfile                            # Si tu utilises Vagrant
└── README.md                              # Optionnel, infos projet


URL de recherche:
/ → liste des véhicules

/vehicule/@id → détail véhicule

/chauffeurs → liste chauffeurs

/affectations/@date → liste des trajets pour une date


---

## 📊 Fonctionnalités principales

### 🚗 Véhicules & Chauffeurs
- Liste des véhicules
- Liste des chauffeurs
- Affectation chauffeur ↔ véhicule par jour

### 🛣 Gestion des trajets
- Enregistrement des trajets
- Distance, recette, carburant
- Aller / retour

### 📈 Statistiques
- Liste journalière des véhicules et chauffeurs
- Kilométrage, recette et carburant par jour
- Bénéfice total par véhicule
- Bénéfice total par jour
- Trajets les plus rentables

### 🔧 Pannes
- Enregistrement des pannes
- Véhicules disponibles par date
- Taux de panne mensuel par véhicule

### 💰 Salaires
- Versement minimum par véhicule
- Calcul automatique du salaire journalier
- Pourcentages configurables
- Historique des salaires conservé

---

## 🗄 Base de données
Le fichier `base.sql` contient :
- la création complète des tables
- les clés primaires et étrangères
- des données de test

### Tables principales :
- `cooperative_vehicule`
- `cooperative_chauffeur`
- `cooperative_affectation`
- `cooperative_trajet`
- `cooperative_panne`
- `cooperative_versement_min`

---

## ▶️ Installation

1. Cloner le projet
```bash
git clone <repo>
