# MEDEASE


# Description

Ce projet contient la partie Back End du site Med Ease qui a été réalisé avec PHP Symfony. MedEase est une plateforme web qui vise à simplifier le parcours médical en permettant aux utilisateurs de prendre rendez-vous avec des médecins,
de faire des consultations en visioconférence et de générer automatiquement des factures médicales.


## Entity
Le projet comprend les entités suivantes :

### User

- `id` : number
- `name` : string
- `lastname` : string
- `location` : string
- `email` : string
- `phoneNumber` : number
- `password` : string
- `professionnal` : boolean
- `patient` : boolean
- `schedule` : Schedule (nullable)
- `specialty` : string (nullable)

### Bill

- `id` : number
- `patient` : User
- `professionnal` : User
- `link` : string

### Appointment

- `id` : number
- `patient` : User
- `professionnal` : User
- `date` : Schedule

### Schedule

- `id` : number
- `day` : Date
- `startTime` : Date
- `endTime` : Date
- `user` (professionnel) : User

## Controllers

Le projet comprend les controllers suivantes :

### Security Controller

Qui contient les routes suivantes:

#### Connexion

- **URL** : `https://localhost:8000/login`
- **Méthode** : POST
- **Corps de la requête** :
  ```json
  {
    "username": "votre_nom_utilisateur",
    "password": "votre_mot_de_passe"
  }

#### Inscription

- **URL** : `https://localhost:8000/signup`
- **Méthode** : POST
- **Corps de la requête** :
  ```json
  {
    "name"
   "lastname"
   "email"
   "password"
   "location"
   "phoneNumber"
   "patient"
   "professional"
   "specialty"
  }


#### Obtenir les professionnels par spécialité
- **URL** : `https://localhost:8000/specialty`
- **Méthode** : GET
- **Corps de la requête** :
  ```json
  {
   "specialty"
  }

### Schedule Controller

Qui contient les routes suivantes:


#### Obtenir les disponibilités d'un professionnel
- **URL** : `https://localhost:8000/freeHours/{id}`
- **Méthode** : GET

### Appointment Controller

#### Créer un rendez-vous

- **URL** : `https://localhost:8000/appointment`
- **Méthode** : POST
- **Corps de la requête** :
  ```json
  {
   "patient"
  "professional"
  "appointmentDate"
  "startTime"
  "endTime"
  }


#### Supprimer un rendez-vous
- **URL** : `https://localhost:8000/appointment/{id}`
- **Méthode** : DELETE


#### Obtenir les rendez-vous d'un utilisateur

- **URL** : `https://localhost:8000/appointmentUser/{id}`
- **Méthode** : DELETE




# Comment installer et démarrer l'application Symfony
- Assurez-vous d'avoir installer php8.1 et Symfony.
- Exécutez : composer install.
- Démarrez le serveur : symfony server:start -d
- Créez une base de données et une schema:
  php bin/console doctrine:database:create
  php bin/console doctrine:schema:create


