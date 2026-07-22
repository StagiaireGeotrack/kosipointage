# 👥 Utilisateurs et Fonctionnalités du projet KOSI-TIME

D'après l'architecture et le code du projet (notamment le modèle `Administration.php` et les middlewares), on distingue **4 types d'utilisateurs**.

---

## 1. Le Super Admin (`IsSuperAdmin = 1` et `IsSeller = 0`)
C'est le gestionnaire global de la plateforme. Il a accès à l'intégralité du système, sans aucune restriction de périmètre (il voit les données de tous les clients/sièges).

**Fonctionnalités exclusives :**
- **Gestion des Administrateurs** : Création, modification, suppression et réinitialisation de mot de passe des Simple Admins.
- **Gestion des Vendeurs (Sellers)** : Création, modification, activation/désactivation (toggle) des comptes vendeurs et attribution de sièges.
- **Gestion des Sièges (CRUD)** : Seul le Super Admin peut créer, modifier et supprimer des `EntrepriseSiege`.
- **Logs d'activité (Audit)** : Accès à l'historique complet des actions effectuées sur la plateforme (qui a fait quoi et quand) avec export CSV.
- **Tableau de bord global** : Vue d'ensemble sur toute la plateforme (`/dashboard`).
- **Fonctionnalités héritées** : Il possède bien entendu tous les droits du "Simple Admin".

---

## 2. Le Simple Admin (`IsSuperAdmin = 0` et `IsSeller = 0`)
C'est le gestionnaire opérationnel d'une entreprise ou d'un siège spécifique. Son périmètre de données est restreint à son propre siège (grâce au filtrage global `SiegeScope`).

**Fonctionnalités :**
- **Tableau de bord dédié** : Suivi opérationnel de son périmètre (`/dashboard/simple-admin`).
- **Gestion des Entreprises** : Création et modification des entités clientes sous sa responsabilité.
- **Gestion des Employés** : Création, modification, désactivation, réinitialisation du Code PIN et configuration de la reconnaissance faciale/biométrique.
- **Gestion des Pointages** : 
    - Consultation des entrées/sorties avec visualisation des photos de pointage.
    - Ajout manuel, modification ou suppression de pointages.
    - Consultation détaillée du temps de travail par employé.
- **Gestion des Congés** : 
    - Saisie et modification des demandes de congés.
    - **Validation des congés** : Approuver ou rejeter les demandes (avec motif de refus).
- **Rapports** : Génération et export (Excel/PDF) des rapports journaliers et des rapports détaillés "Jour / Nuit".
- **Jours non travaillés** : Configuration des jours fériés ou chômés pour impacter le calcul du temps de travail.
- **Anomalies de pointage** : Suivi et acquittement (acknowledge) des erreurs ou exceptions de pointage remontées par les terminaux.

---

## 3. Le Vendeur / Seller (`IsSuperAdmin = 1` et `IsSeller = 1`)
C'est un rôle de "Consultant" ou de "Revendeur". Il possède un accès **en lecture seule** sur un ou plusieurs sièges spécifiques qui lui ont été attribués par le Super Admin.

**Fonctionnalités :**
- **Tableau de bord Vendeur** : Vue macroscopique (`/dashboard/seller`).
- **Accès restreint (Lecture seule)** : Il est systématiquement bloqué sur toutes les actions de création, modification ou suppression (middleware `block.sellers`).
- **Consultation** : Il peut voir la liste des sièges, des entreprises et des employés qui font partie de son portefeuille.
- **Vue détaillée** : Accès à une page "Détails employé" spécialement conçue pour lui pour suivre l'activité sans rien pouvoir altérer.

---

## 4. L'Employé (Utilisateur Final du Terminal)
Bien qu'il n'ait **pas d'accès à l'interface d'administration web**, c'est l'utilisateur cœur du système de pointage (modèle `Employe`).

**Fonctionnalités (sur le terrain / terminal) :**
- Pointer à l'entrée et à la sortie.
- S'authentifier via 3 méthodes possibles configurées par l'administrateur : 
    - Reconnaissance faciale (`FaceEncodingPath`).
    - Badge RFID/NFC (`BadgeID`).
    - Code PIN (`Pin`).
