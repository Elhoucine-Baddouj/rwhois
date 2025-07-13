#!/bin/bash

# Script de désinstallation RWHOIS
# Utilisation: ./uninstall-rwhois.sh <server_ip>

set -e

# Variables
SERVER_IP="$1"
INSTALL_DIR="/opt/rwhois"
CONFIG_DIR="/etc/rwhois"
LOG_DIR="/var/log/rwhois"

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction de logging
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERREUR]${NC} $1" >&2
}

success() {
    echo -e "${GREEN}[SUCCÈS]${NC} $1"
}

warning() {
    echo -e "${YELLOW}[ATTENTION]${NC} $1"
}

# Vérification des paramètres
if [ $# -lt 1 ]; then
    error "Usage: $0 <server_ip>"
    exit 1
fi

log "Début de la désinstallation RWHOIS sur $SERVER_IP"

# Vérification de la connectivité
log "Vérification de la connectivité vers $SERVER_IP..."
if ! ping -c 1 "$SERVER_IP" > /dev/null 2>&1; then
    error "Impossible de joindre $SERVER_IP"
    exit 1
fi

# Vérification de l'accès SSH
log "Test de la connexion SSH..."
if ! ssh -o ConnectTimeout=10 -o BatchMode=yes root@"$SERVER_IP" "echo 'SSH OK'" > /dev/null 2>&1; then
    error "Impossible de se connecter en SSH à $SERVER_IP"
    exit 1
fi

# Arrêt et désactivation du service
log "Arrêt du service RWHOIS..."
ssh root@"$SERVER_IP" << 'EOF'
    # Arrêt du service
    systemctl stop rwhois || true
    
    # Désactivation du service
    systemctl disable rwhois || true
    
    # Suppression du fichier de service
    rm -f /etc/systemd/system/rwhois.service
    
    # Rechargement de systemd
    systemctl daemon-reload
    systemctl reset-failed
EOF

# Suppression des fichiers d'installation
log "Suppression des fichiers d'installation..."
ssh root@"$SERVER_IP" << EOF
    # Sauvegarde des logs avant suppression
    if [ -d "$LOG_DIR" ]; then
        tar -czf /tmp/rwhois-logs-backup-$(date +%Y%m%d-%H%M%S).tar.gz -C $LOG_DIR .
        echo "Sauvegarde des logs créée: /tmp/rwhois-logs-backup-*.tar.gz"
    fi
    
    # Suppression des répertoires
    rm -rf $INSTALL_DIR
    rm -rf $CONFIG_DIR
    rm -rf $LOG_DIR
    
    # Suppression de l'utilisateur rwhois
    userdel rwhois 2>/dev/null || true
    groupdel rwhois 2>/dev/null || true
EOF

# Nettoyage des règles de firewall
log "Nettoyage des règles de firewall..."
ssh root@"$SERVER_IP" << 'EOF'
    # Suppression des règles UFW
    if command -v ufw &> /dev/null; then
        ufw delete allow 4321/tcp 2>/dev/null || true
        ufw reload
    fi
    
    # Suppression des règles iptables
    if ! command -v ufw &> /dev/null; then
        iptables -D INPUT -p tcp --dport 4321 -j ACCEPT 2>/dev/null || true
        iptables-save > /etc/iptables/rules.v4 2>/dev/null || true
    fi
EOF

# Nettoyage des processus restants
log "Nettoyage des processus restants..."
ssh root@"$SERVER_IP" << 'EOF'
    # Recherche et arrêt des processus rwhois
    pkill -f rwhois-server || true
    
    # Vérification qu'aucun processus ne reste
    if pgrep -f rwhois-server > /dev/null; then
        echo "ATTENTION: Des processus RWHOIS sont encore en cours d'exécution"
        ps aux | grep rwhois
    else
        echo "Aucun processus RWHOIS en cours d'exécution"
    fi
EOF

# Vérification de la désinstallation
log "Vérification de la désinstallation..."
ssh root@"$SERVER_IP" << 'EOF'
    # Vérification que le service n'existe plus
    if systemctl list-unit-files | grep -q rwhois; then
        echo "ATTENTION: Le service RWHOIS existe encore"
    else
        echo "Service RWHOIS supprimé"
    fi
    
    # Vérification que le port est fermé
    if netstat -tlnp | grep -q ":4321 "; then
        echo "ATTENTION: Le port 4321 est encore ouvert"
        netstat -tlnp | grep ":4321"
    else
        echo "Port 4321 fermé"
    fi
    
    # Vérification que les répertoires sont supprimés
    if [ -d "/opt/rwhois" ] || [ -d "/etc/rwhois" ] || [ -d "/var/log/rwhois" ]; then
        echo "ATTENTION: Certains répertoires RWHOIS existent encore"
        ls -la /opt/rwhois 2>/dev/null || true
        ls -la /etc/rwhois 2>/dev/null || true
        ls -la /var/log/rwhois 2>/dev/null || true
    else
        echo "Tous les répertoires RWHOIS supprimés"
    fi
EOF

# Nettoyage optionnel des dépendances
read -p "Voulez-vous supprimer les dépendances installées (Go, build-essential, etc.) ? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    log "Suppression des dépendances..."
    ssh root@"$SERVER_IP" << 'EOF'
        # Suppression de Go
        rm -rf /usr/local/go
        sed -i '/\/usr\/local\/go\/bin/d' /etc/profile
        
        # Suppression des paquets de développement
        apt-get remove -y build-essential git curl wget
        apt-get autoremove -y
        apt-get autoclean
EOF
    success "Dépendances supprimées"
else
    log "Conservation des dépendances"
fi

# Test final
log "Test final de la désinstallation..."
if timeout 5 bash -c "</dev/tcp/$SERVER_IP/4321" 2>/dev/null; then
    warning "Le port 4321 est encore accessible - vérifiez manuellement"
else
    success "Port 4321 fermé avec succès"
fi

success "Désinstallation RWHOIS terminée sur $SERVER_IP"

echo ""
echo "=== Résumé de la désinstallation ==="
echo "Serveur: $SERVER_IP"
echo "Service: Supprimé"
echo "Fichiers: Supprimés"
echo "Utilisateur: Supprimé"
echo "Firewall: Nettoyé"
echo ""
echo "Sauvegardes disponibles dans /tmp/rwhois-logs-backup-*.tar.gz"

log "Désinstallation terminée avec succès!" 