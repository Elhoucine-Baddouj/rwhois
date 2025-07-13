# RWHOIS Dashboard

Un tableau de bord web moderne pour gérer facilement des serveurs RWHOIS (Referral WHOIS) et les ressources internet associées.

## 🎯 Objectifs

Ce projet permet de :

- **Gérer les serveurs RWHOIS** : Installation/désinstallation automatique, contrôle à distance
- **Gérer les organisations** : Ajouter/modifier/supprimer des entreprises ou entités
- **Gérer les ressources internet** : ASN, adresses IPv4, adresses IPv6
- **Gérer les utilisateurs** : Contrôler les accès et permissions
- **API pour intégrations** : Interfaces pour communication avec d'autres logiciels

## 🛠️ Technologies utilisées

- **PHP** : Langage de programmation principal
- **AdminLTE** : Interface utilisateur moderne et responsive
- **Bootstrap** : Framework CSS pour le design
- **jQuery** : Manipulation DOM et AJAX
- **Chart.js** : Graphiques et statistiques

## 📁 Structure du projet

```
rwhois-dashboard/
├── index.php                 # Point d'entrée principal
├── app/
│   └── Controllers/          # Contrôleurs MVC
│       ├── BaseController.php
│       ├── DashboardController.php
│       ├── ServerController.php
│       ├── OrganizationController.php
│       ├── ResourceController.php
│       ├── UserController.php
│       └── ApiController.php
├── views/                    # Vues (templates)
│   ├── layouts/
│   │   └── main.php         # Layout principal avec AdminLTE
│   ├── dashboard/
│   │   └── index.php        # Tableau de bord
│   ├── servers/
│   │   ├── index.php        # Liste des serveurs
│   │   └── add.php          # Formulaire d'ajout
│   ├── organizations/
│   ├── resources/
│   └── users/
└── README.md
```

## 🚀 Installation

### Prérequis

- PHP 7.4 ou supérieur
- Serveur web (Apache, Nginx, ou serveur de développement PHP)
- Accès SSH pour l'installation automatique des serveurs

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```bash
   git clone [url-du-repo]
   cd rwhois-dashboard
   ```

2. **Configurer le serveur web**
   
   **Avec le serveur de développement PHP :**
   ```bash
   php -S localhost:8000
   ```
   
   **Avec Apache :**
   - Placer le projet dans le répertoire web
   - Configurer un VirtualHost si nécessaire
   
   **Avec Nginx :**
   ```nginx
   server {
       listen 80;
       server_name rwhois.local;
       root /path/to/rwhois-dashboard;
       index index.php;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_index index.php;
           include fastcgi_params;
       }
   }
   ```

3. **Accéder à l'application**
   - Ouvrir http://localhost:8000 dans votre navigateur
   - L'interface AdminLTE devrait s'afficher

## 📖 Utilisation

### Tableau de bord

Le tableau de bord principal affiche :
- **Statistiques** : Nombre de serveurs, organisations, ressources, utilisateurs
- **Serveurs récents** : Liste des derniers serveurs ajoutés
- **Organisations récentes** : Dernières organisations créées
- **Actions rapides** : Boutons pour ajouter rapidement des éléments
- **Graphiques** : Répartition des ressources et statut des serveurs

### Gestion des serveurs

1. **Voir tous les serveurs** : `/servers`
2. **Ajouter un serveur** : `/servers/add`
3. **Modifier un serveur** : `/servers/edit?id=X`
4. **Supprimer un serveur** : `/servers/delete?id=X`

**Fonctionnalités des serveurs :**
- Installation/désinstallation automatique
- Contrôle du statut en temps réel
- Association à une organisation
- Configuration SSH pour l'accès distant

### Gestion des organisations

1. **Voir toutes les organisations** : `/organizations`
2. **Ajouter une organisation** : `/organizations/add`
3. **Modifier une organisation** : `/organizations/edit?id=X`

**Types d'organisations supportés :**
- ISP (Fournisseur d'accès internet)
- Hosting Provider (Hébergeur)
- Cloud Provider (Fournisseur cloud)
- Enterprise (Entreprise)

### Gestion des ressources

1. **Voir toutes les ressources** : `/resources`
2. **Ajouter une ressource** : `/resources/add`

**Types de ressources :**
- **ASN** : Numéros de systèmes autonomes (ex: AS64512)
- **IPv4** : Adresses IPv4 avec masque (ex: 192.168.1.0/24)
- **IPv6** : Adresses IPv6 avec masque (ex: 2001:db8::/32)

### Gestion des utilisateurs

1. **Voir tous les utilisateurs** : `/users`
2. **Ajouter un utilisateur** : `/users/add`
3. **Gérer les permissions** : `/users/permissions?id=X`

**Rôles disponibles :**
- **Administrateur** : Accès complet à toutes les fonctionnalités
- **Utilisateur** : Accès limité selon les permissions
- **Lecteur** : Accès en lecture seule

## 🔌 API

L'application fournit une API REST pour l'intégration avec d'autres systèmes :

### Endpoints disponibles

- `GET /api/servers` - Liste tous les serveurs
- `GET /api/organizations` - Liste toutes les organisations
- `GET /api/resources` - Liste toutes les ressources
- `GET /api/users` - Liste tous les utilisateurs
- `GET /api/server-status?id=X` - Statut d'un serveur
- `GET /api/install-server?id=X` - Installer un serveur
- `GET /api/uninstall-server?id=X` - Désinstaller un serveur

### Exemple d'utilisation API

```bash
# Récupérer la liste des serveurs
curl http://localhost:8000/api/servers

# Installer un serveur
curl http://localhost:8000/api/install-server?id=1

# Vérifier le statut d'un serveur
curl http://localhost:8000/api/server-status?id=1
```

## 🔧 Configuration avancée

### Installation automatique des serveurs

Pour activer l'installation automatique des serveurs RWHOIS :

1. **Configurer SSH** :
   - Générer des clés SSH pour l'authentification
   - Ajouter les clés publiques sur les serveurs cibles
   - Configurer les permissions appropriées

2. **Scripts d'installation** :
   - Créer des scripts d'installation RWHOIS
   - Placer les scripts dans un répertoire accessible
   - Configurer les chemins dans les contrôleurs

### Base de données

Actuellement, l'application utilise des données simulées. Pour une utilisation en production :

1. **Créer une base de données** (MySQL, PostgreSQL, SQLite)
2. **Créer les tables** pour serveurs, organisations, ressources, utilisateurs
3. **Modifier les contrôleurs** pour utiliser de vraies requêtes SQL
4. **Ajouter un système d'authentification** sécurisé

### Sécurité

Recommandations pour la production :

- **HTTPS** : Configurer SSL/TLS
- **Authentification** : Implémenter un système de login sécurisé
- **Validation** : Renforcer la validation des données
- **Permissions** : Implémenter un système de permissions granulaire
- **Logs** : Ajouter des logs d'audit
- **Backup** : Mettre en place des sauvegardes régulières

## 🐛 Dépannage

### Problèmes courants

1. **Erreur 404** :
   - Vérifier la configuration du serveur web
   - S'assurer que le fichier `.htaccess` est présent (Apache)
   - Vérifier les permissions des fichiers

2. **Erreurs PHP** :
   - Vérifier la version de PHP (7.4+)
   - Activer les extensions nécessaires
   - Vérifier les logs d'erreur PHP

3. **Problèmes d'affichage** :
   - Vérifier que les CDN sont accessibles
   - Vérifier la console du navigateur pour les erreurs JavaScript
   - S'assurer que les fichiers CSS/JS se chargent correctement

## 🤝 Contribution

Pour contribuer au projet :

1. Fork le repository
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Créer une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

## 📞 Support

Pour toute question ou problème :

- Créer une issue sur GitHub
- Consulter la documentation
- Contacter l'équipe de développement

---

**RWHOIS Dashboard** - Gestion moderne des serveurs RWHOIS et ressources internet 