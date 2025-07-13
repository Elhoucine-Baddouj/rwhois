#!/bin/bash

# Script d'installation automatique RWHOIS
# Utilisation: ./install-rwhois.sh <server_ip> <server_name> <organization>

set -e

# Variables
SERVER_IP="$1"
SERVER_NAME="$2"
ORGANIZATION="$3"
RWHOIS_PORT="${4:-4321}"
INSTALL_DIR="/opt/rwhois"
CONFIG_DIR="/etc/rwhois"
LOG_DIR="/var/log/rwhois"
SSH_KEY="C:/users/LENOVO/.ssh/id_rsa"

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
if [ $# -lt 3 ]; then
    error "Usage: $0 <server_ip> <server_name> <organization> [port]"
    exit 1
fi

log "Début de l'installation RWHOIS sur $SERVER_IP"

# Vérification de la connectivité
log "Vérification de la connectivité vers $SERVER_IP..."
if ! ping -c 1 "$SERVER_IP" > /dev/null 2>&1; then
    error "Impossible de joindre $SERVER_IP"
    exit 1
fi

# Vérification de l'accès SSH
log "Test de la connexion SSH..."
echo "DEBUG: ssh -i \"$SSH_KEY\" -o ConnectTimeout=10 -o BatchMode=yes root@\"$SERVER_IP\" \"echo 'SSH OK'\""
ssh -i "$SSH_KEY" -v -o ConnectTimeout=10 -o BatchMode=yes root@"$SERVER_IP" "echo 'SSH OK'"
if ! ssh -i "$SSH_KEY" -o ConnectTimeout=10 -o BatchMode=yes root@"$SERVER_IP" "echo 'SSH OK'" > /dev/null 2>&1; then
    error "Impossible de se connecter en SSH à $SERVER_IP"
    exit 1
fi

# Installation sur le serveur distant
log "Installation des paquets nécessaires..."
ssh -i "$SSH_KEY" root@"$SERVER_IP" << 'EOF'
    # Mise à jour du système
    apt-get update
    
    # Installation des dépendances
    apt-get install -y build-essential git curl wget
    
    # Installation de Go (pour rwhois-go ou alternative)
    if ! command -v go &> /dev/null; then
        wget https://golang.org/dl/go1.19.linux-amd64.tar.gz
        tar -C /usr/local -xzf go1.19.linux-amd64.tar.gz
        echo 'export PATH=$PATH:/usr/local/go/bin' >> /etc/profile
        export PATH=$PATH:/usr/local/go/bin
    fi
EOF

# Création des répertoires
log "Création des répertoires..."
ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
    mkdir -p $INSTALL_DIR
    mkdir -p $CONFIG_DIR
    mkdir -p $LOG_DIR
    chmod 755 $INSTALL_DIR $CONFIG_DIR $LOG_DIR
EOF

# Téléchargement et compilation de RWHOIS
log "Téléchargement et compilation de RWHOIS..."
ssh -i "$SSH_KEY" root@"$SERVER_IP" << 'EOF'
    cd /tmp
    
    # Téléchargement d'une implémentation RWHOIS (exemple avec rwhois-go)
    if [ ! -d "rwhois-go" ]; then
        git clone https://github.com/example/rwhois-go.git
    fi
    
    cd rwhois-go
    
    # Compilation
    export PATH=$PATH:/usr/local/go/bin
    go build -o rwhois-server cmd/server/main.go
    
    # Installation
    cp rwhois-server /opt/rwhois/
    chmod +x /opt/rwhois/rwhois-server
EOF

# Configuration du serveur RWHOIS
log "Configuration du serveur RWHOIS..."
ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
    cat > $CONFIG_DIR/rwhois.conf << 'CONFIG'
# Configuration RWHOIS
server {
    listen = "0.0.0.0:$RWHOIS_PORT"
    log_level = "info"
    log_file = "$LOG_DIR/rwhois.log"
}

# Organisations
organization "$ORGANIZATION" {
    description = "Organisation: $ORGANIZATION"
    contact_email = "admin@$ORGANIZATION.com"
    
    # Ressources par défaut
    resource "ASN" {
        description = "Autonomous System Numbers"
    }
    
    resource "IPv4" {
        description = "IPv4 Addresses"
    }
    
    resource "IPv6" {
        description = "IPv6 Addresses"
    }
}
CONFIG

    chmod 644 $CONFIG_DIR/rwhois.conf
EOF

# Création du service systemd
log "Création du service systemd..."
ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
    cat > /etc/systemd/system/rwhois.service << 'SERVICE'
[Unit]
Description=RWHOIS Server
After=network.target

[Service]
Type=simple
User=rwhois
Group=rwhois
ExecStart=/opt/rwhois/rwhois-server -config /etc/rwhois/rwhois.conf
Restart=always
RestartSec=5
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
SERVICE

    # Création de l'utilisateur rwhois
    useradd -r -s /bin/false -d $INSTALL_DIR rwhois || true
    
    # Attribution des permissions
    chown -R rwhois:rwhois $INSTALL_DIR $CONFIG_DIR $LOG_DIR
    
    # Rechargement de systemd
    systemctl daemon-reload
    
    # Activation et démarrage du service
    systemctl enable rwhois
    systemctl start rwhois
EOF

# Vérification de l'installation
log "Vérification de l'installation..."
ssh -i "$SSH_KEY" root@"$SERVER_IP" << 'EOF'
    # Vérification du statut du service
    if systemctl is-active --quiet rwhois; then
        echo "Service RWHOIS actif"
    else
        echo "Service RWHOIS inactif"
        systemctl status rwhois
        exit 1
    fi
    
    # Vérification du port
    if netstat -tlnp | grep -q ":$RWHOIS_PORT "; then
        echo "Port $RWHOIS_PORT ouvert"
    else
        echo "Port $RWHOIS_PORT fermé"
        exit 1
    fi
    
    # Test de connexion locale
    if timeout 5 bash -c "</dev/tcp/localhost/$RWHOIS_PORT"; then
        echo "Connexion locale réussie"
    else
        echo "Échec de la connexion locale"
        exit 1
    fi
EOF

# Configuration du firewall
log "Configuration du firewall..."
ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
    # UFW (Ubuntu)
    if command -v ufw &> /dev/null; then
        ufw allow $RWHOIS_PORT/tcp
        ufw reload
    fi
    
    # iptables (fallback)
    if ! command -v ufw &> /dev/null; then
        iptables -A INPUT -p tcp --dport $RWHOIS_PORT -j ACCEPT
        iptables-save > /etc/iptables/rules.v4
    fi
EOF

# Test final
log "Test final de l'installation..."
if timeout 10 bash -c "</dev/tcp/$SERVER_IP/$RWHOIS_PORT" 2>/dev/null; then
    success "Installation RWHOIS réussie sur $SERVER_IP:$RWHOIS_PORT"
    
    # Affichage des informations
    echo ""
    echo "=== Informations d'installation ==="
    echo "Serveur: $SERVER_NAME"
    echo "IP: $SERVER_IP"
    echo "Port: $RWHOIS_PORT"
    echo "Organisation: $ORGANIZATION"
    echo "Répertoire d'installation: $INSTALL_DIR"
    echo "Fichier de configuration: $CONFIG_DIR/rwhois.conf"
    echo "Logs: $LOG_DIR/rwhois.log"
    echo "Service: rwhois"
    echo ""
    echo "Commandes utiles:"
    echo "  ssh root@$SERVER_IP 'systemctl status rwhois'"
    echo "  ssh root@$SERVER_IP 'journalctl -u rwhois -f'"
    echo "  telnet $SERVER_IP $RWHOIS_PORT"
    
else
    error "Échec du test de connexion à $SERVER_IP:$RWHOIS_PORT"
    exit 1
fi

log "Installation terminée avec succès!" 