## 🏥 BedMed – Lit médicalisé connecté pour hôpital de jour

**Projet réalisé dans le cadre du BTS CIEL, en équipe de 3, en partenariat avec le CHU de Lille – Service ORL et Chirurgie Cervico-Faciale.**

### 📋 Contexte

BedMed est une solution web et matérielle visant à améliorer le confort des patients hospitalisés en service ORL/hôpital de jour, tout en facilitant le suivi par les soignants et la gestion administrative des chambres et utilisateurs.

### 🎯 Fonctionnalités principales

- **Interface Patient** : contrôle du lit médicalisé (hauteur, inclinaison), choix d'abonnement TV, envoi d'alertes au personnel soignant
- **Interface Soignant** : accès sécurisé par badge RFID, suivi des alertes patients en temps réel, consultation de l'emploi du temps, gestion des chambres et de la liste des patients
- **Interface Administrateur** : gestion des comptes utilisateurs (patients/soignants), gestion des chambres, consultation des données environnementales (température/humidité), historique des interventions

### 🛠️ Stack technique

- **Frontend** : HTML, CSS, JavaScript, Bootstrap, React.js / Vue.js
- **Backend** : PHP
- **Base de données** : MySQL (hébergée sur Clever Cloud)
- **Matériel** : Arduino Uno WiFi Rev2, lecteur RFID RC522, capteur de température/humidité SHT31, servomoteurs DS-MG90 (pour l'inclinaison/hauteur du lit)
- **Outils de conception** : Figma, Draw.io, PowerAMC (MCD/MPD), XMind

### 🔐 Sécurité & RGPD

- Aucune donnée réelle de patient utilisée (données anonymisées pour les tests)
- Mots de passe hachés (`password_hash()` / `password_verify()`)
- Gestion des sessions et contrôle d'accès par rôle (`session_start()`)
- Déconnexion sécurisée avec destruction de session
- Séparation stricte des interfaces selon les rôles (patient / soignant / administrateur)

### 👥 Équipe

- **Jean-Philippe Windels** – Interface Patient
- **Nicolas Picquet** – Interface Soignant
- **Victor Oliveira** – Interface Administrateur
