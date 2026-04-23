# 🇨🇮 PDAI — Plateforme de Déclaration d’Activité Industrielle

![Tests](https://github.com/HubersonYAPI/gestion-entreprises/actions/workflows/ci.yml/badge.svg)

## 📌 À propos

**PDAI** est une plateforme web permettant aux entreprises de réaliser leurs **déclarations d’activité industrielle 100 % en ligne** en Côte d’Ivoire.

Elle simplifie un processus administratif souvent complexe en offrant une expérience **rapide, sécurisée et entièrement dématérialisée**.

---

## 🚀 Fonctionnalités principales

* 📝 **Dépôt numérique des déclarations**
  Soumission complète des dossiers sans déplacement

* 📊 **Suivi en temps réel**
  Statuts clairs : Brouillon → Soumise → Paiement → Validée

* 🔐 **Sécurité & traçabilité**
  Journalisation (audit trail) de toutes les actions

* 📄 **Attestation officielle PDF**
  Génération et téléchargement après validation

* 🔔 **Notifications**
  Alertes utilisateur (polling ou temps réel)

* 🧑‍💼 **Espace administrateur**

  * Gestion des utilisateurs
  * Rôles & permissions
  * Statistiques & rapports

---

## 🧭 Processus de déclaration

La plateforme suit un cycle simple en **4 phases** :

1. **Création du dossier**

   * Informations du gérant
   * Informations de l’entreprise
   * Upload des documents

2. **Soumission**

   * Vérification
   * Envoi du dossier

3. **Paiement**

   * Validation par un agent
   * Paiement des droits

4. **Validation**

   * Génération de l’attestation officielle

---

## 📂 Documents requis

### Obligatoires

* Informations du gérant
* Informations de l’entreprise
* Pièce d’identité

### Optionnels

* RCCM
* Numéro fiscal (NIF)

### Phase paiement

* Justificatif de paiement

---

## ⚙️ Stack technique

* **Backend** : Laravel
* **Frontend** : Blade + Tailwind CSS
* **Base de données** : MySQL
* **Temps réel** : Laravel Echo / Soketi (optionnel)
* **Déploiement** : Render

---

## 🖥️ Installation

```bash
git clone https://github.com/HubersonYAPI/gestion-entreprises.git
cd gestion-entreprises

composer install
cp .env.example .env
php artisan key:generate

# Configurer la base de données dans .env

php artisan migrate --seed
npm install && npm run build

php artisan serve
```

---

## 🔐 Accès

* Utilisateur : inscription via l’interface
* Admin : défini via seed ou base de données

---

## 📸 Aperçu

* Landing page moderne
* Dashboard utilisateur
* Gestion des déclarations
* Interface admin complète

---

## 📞 Support

* Email : **[pdai@commerce.gouv.ci](mailto:pdai@commerce.gouv.ci)**
* Téléphone : **+225 20 00 00 00**

Disponibilité : **Lundi – Vendredi, 07h30 – 16h30**

---

## 📄 Licence

Projet sous licence **MIT**

---

## ✨ Vision

Faire de **PDAI** une référence en matière de **digitalisation des démarches administratives industrielles en Côte d’Ivoire**.

---
