# 🖥️ API PHP - Gestion des Commandes

Cette API, développée en PHP, permet de gérer les produits, les clients, les commandes et les proformas.  
Elle est utilisée avec une application mobile React Native.

---

## 📦 Fonctionnalités

- 🔐 Authentification
- 📦 Produits : ajout, suppression, modification
- 👥 Clients : gestion complète
- 📋 Commandes : création 
- 🧾 Proformas

---

## 🛠️ Prérequis

- XAMPP (ou tout autre serveur Apache + PHP + MySQL)
- PHP >= 7.4
- MySQL
- phpMyAdmin

---

## 📥 Installation

1. **Cloner ou copier le projet :**

```bash
git clone https://github.com/rymamr/gestion_commandes_api.git
```

2. **Placer le dossier dans le répertoire XAMPP :**

```
C:\xampp\htdocs\gestion_commandes_api
```

3. **Importer la base de données via phpMyAdmin :**

- Créer une base (ex. : `gestion_commandes`)
- Importer le fichier `.sql` fourni

4. **Configurer la base de données dans `db.php` :**

```php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "gestion_commandes";
```

---

## ▶️ Utilisation de l’API

Démarrer Apache et MySQL via XAMPP

L’API sera disponible à cette adresse :

```url
http://localhost/gestion_commandes_api/
```

---

## 📱 Utilisation avec l’application mobile

Cette API est utilisée par une application mobile React Native :  
👉 [Gestion_commandes (frontend)](https://github.com/rymamr/Gestion_commandes)

### Configuration dans l'app mobile :

Exemple d’URL d’appel depuis l’application :

```url
http://192.168.1.13/gestion_commandes_api/produits.php
```

> Remplacez l’adresse IP par l’IP locale de votre PC  
> Le téléphone et le PC doivent être sur le **même réseau Wi-Fi**
