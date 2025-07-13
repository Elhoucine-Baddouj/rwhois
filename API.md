# API RWHOIS Dashboard

Documentation de l'API REST pour l'intégration avec le tableau de bord RWHOIS.

## Base URL

```
http://localhost:8000/api
```

## Authentification

Actuellement, l'API n'utilise pas d'authentification. Pour la production, il est recommandé d'implémenter :

- JWT (JSON Web Tokens)
- API Keys
- OAuth 2.0

## Format des réponses

Toutes les réponses sont au format JSON avec la structure suivante :

```json
{
    "success": true,
    "data": [...],
    "count": 5,
    "message": "Opération réussie"
}
```

## Endpoints

### Serveurs

#### GET /api/servers

Récupère la liste de tous les serveurs RWHOIS.

**Réponse :**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "RWHOIS-Server-01",
            "ip": "192.168.1.100",
            "port": 4321,
            "status": "active",
            "organization": "TechCorp Inc.",
            "created_at": "2024-01-15"
        }
    ],
    "count": 1
}
```

#### GET /api/server-status?id={id}

Récupère le statut d'un serveur spécifique.

**Paramètres :**
- `id` (requis) : ID du serveur

**Réponse :**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "status": "active",
        "timestamp": "2024-01-15 10:30:00"
    }
}
```

#### GET /api/install-server?id={id}

Installe un serveur RWHOIS.

**Paramètres :**
- `id` (requis) : ID du serveur

**Réponse :**
```json
{
    "success": true,
    "message": "Serveur installé avec succès"
}
```

#### GET /api/uninstall-server?id={id}

Désinstalle un serveur RWHOIS.

**Paramètres :**
- `id` (requis) : ID du serveur

**Réponse :**
```json
{
    "success": true,
    "message": "Serveur désinstallé avec succès"
}
```

### Organisations

#### GET /api/organizations

Récupère la liste de toutes les organisations.

**Réponse :**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "TechCorp Inc.",
            "type": "ISP",
            "contact_email": "admin@techcorp.com",
            "contact_phone": "+1-555-0123",
            "address": "123 Tech Street, Silicon Valley, CA",
            "created_at": "2024-01-10"
        }
    ],
    "count": 1
}
```

### Ressources

#### GET /api/resources

Récupère la liste de toutes les ressources internet.

**Réponse :**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "type": "ASN",
            "value": "AS64512",
            "description": "TechCorp Autonomous System",
            "organization": "TechCorp Inc.",
            "status": "active",
            "created_at": "2024-01-10"
        }
    ],
    "count": 1
}
```

### Utilisateurs

#### GET /api/users

Récupère la liste de tous les utilisateurs.

**Réponse :**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "username": "admin",
            "email": "admin@rwhois.com",
            "full_name": "Administrateur Système",
            "role": "admin",
            "organization": "TechCorp Inc.",
            "status": "active",
            "created_at": "2024-01-01"
        }
    ],
    "count": 1
}
```

## Codes d'erreur

| Code | Description |
|------|-------------|
| 200 | Succès |
| 400 | Requête invalide |
| 404 | Ressource non trouvée |
| 500 | Erreur serveur interne |

## Exemples d'utilisation

### cURL

```bash
# Récupérer tous les serveurs
curl http://localhost:8000/api/servers

# Installer un serveur
curl http://localhost:8000/api/install-server?id=1

# Vérifier le statut d'un serveur
curl http://localhost:8000/api/server-status?id=1
```

### JavaScript (Fetch)

```javascript
// Récupérer tous les serveurs
fetch('/api/servers')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Serveurs:', data.data);
        }
    });

// Installer un serveur
fetch('/api/install-server?id=1')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data.message);
        }
    });
```

### Python (requests)

```python
import requests

# Récupérer tous les serveurs
response = requests.get('http://localhost:8000/api/servers')
data = response.json()

if data['success']:
    servers = data['data']
    print(f"Nombre de serveurs: {data['count']}")

# Installer un serveur
response = requests.get('http://localhost:8000/api/install-server?id=1')
data = response.json()

if data['success']:
    print(data['message'])
```

## Endpoints futurs

Les endpoints suivants sont prévus pour les futures versions :

### POST /api/servers

Créer un nouveau serveur.

### PUT /api/servers/{id}

Mettre à jour un serveur existant.

### DELETE /api/servers/{id}

Supprimer un serveur.

### POST /api/organizations

Créer une nouvelle organisation.

### PUT /api/organizations/{id}

Mettre à jour une organisation existante.

### DELETE /api/organizations/{id}

Supprimer une organisation.

### POST /api/resources

Créer une nouvelle ressource.

### PUT /api/resources/{id}

Mettre à jour une ressource existante.

### DELETE /api/resources/{id}

Supprimer une ressource.

### POST /api/users

Créer un nouvel utilisateur.

### PUT /api/users/{id}

Mettre à jour un utilisateur existant.

### DELETE /api/users/{id}

Supprimer un utilisateur.

### POST /api/auth/login

Authentification utilisateur.

### POST /api/auth/logout

Déconnexion utilisateur.

### GET /api/auth/me

Récupérer les informations de l'utilisateur connecté.

## Rate Limiting

Pour la production, il est recommandé d'implémenter un rate limiting :

- 100 requêtes par minute par IP
- 1000 requêtes par heure par utilisateur authentifié

## Versioning

L'API utilise le versioning dans l'URL :

```
/api/v1/servers
/api/v2/servers
```

## Support

Pour toute question concernant l'API :

- Consulter la documentation
- Créer une issue sur GitHub
- Contacter l'équipe de développement 