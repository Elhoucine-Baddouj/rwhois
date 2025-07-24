#!/bin/bash

# Script de contrôle à distance des serveurs RWHOIS
# Utilisation: ./rwhois-remote-control.sh <server_ip> <instance_id> <command>

set -e

# Variables
SERVER_IP="$1"
INSTANCE_ID="$2"
COMMAND="$3"
INSTALL_DIR="/root/rwhoisd/rwhoisd"
CONFIG_DIR="/root/rwhoisd/rwhoisd"
LOG_DIR="/root/rwhoisd/rwhoisd"
SERVICE_NAME="rwhoisd"
# Chemin de la clé SSH à ADAPTER selon votre environnement
SSH_KEY="C:/users/LENOVO/.ssh/id_ed25519"
if [ ! -f "$SSH_KEY" ]; then
    echo -e "${RED}[ERREUR]${NC} Clé SSH non trouvée à l'emplacement $SSH_KEY. Modifiez SSH_KEY dans ce script pour pointer vers votre clé privée valide." >&2
    exit 2
fi

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
    error "Usage: $0 <server_ip> <instance_id> <command>"
    echo ""
    echo "Commandes disponibles:"
    echo "  status     - Vérifier le statut du service"
    echo "  start      - Démarrer le service"
    echo "  stop       - Arrêter le service"
    echo "  restart    - Redémarrer le service"
    echo "  reload     - Recharger la configuration"
    echo "  logs       - Afficher les logs en temps réel"
    echo "  config     - Afficher la configuration"
    echo "  test       - Tester la connexion RWHOIS"
    echo "  info       - Informations sur l'instance"
    echo "  update     - Mettre à jour le serveur RWHOIS"
    echo ""
    echo "Exemples:"
    echo "  $0 178.156.170.162 1 status"
    echo "  $0 178.156.170.162 1 restart"
    echo "  $0 178.156.170.162 1 logs"
    exit 1
fi

log "Contrôle à distance RWHOIS Instance $INSTANCE_ID sur $SERVER_IP"
log "Commande: $COMMAND"

# Vérification de la connectivité via SSH (plus fiable que ping)
log "Vérification de la connectivité vers $SERVER_IP..."
if ! ssh -i "$SSH_KEY" -o ConnectTimeout=10 -o BatchMode=yes root@"$SERVER_IP" "echo 'Connectivity OK'" > /dev/null 2>&1; then
    error "Impossible de joindre $SERVER_IP via SSH"
    exit 1
fi

# Vérification de l'accès SSH
log "Test de la connexion SSH..."
if ! ssh -i "$SSH_KEY" -o ConnectTimeout=10 -o BatchMode=yes root@"$SERVER_IP" "echo 'SSH OK'" > /dev/null 2>&1; then
    error "Impossible de se connecter en SSH à $SERVER_IP"
    exit 1
fi

# Vérification de l'existence du dossier d'installation
log "Vérification de l'existence du dossier d'installation sur $SERVER_IP..."
if ! ssh -i "$SSH_KEY" root@"$SERVER_IP" "[ -d '$INSTALL_DIR' ]" 2>/dev/null; then
    error "Le dossier d'installation $INSTALL_DIR n'existe pas sur $SERVER_IP"
    exit 1
fi

# Exécution des commandes
case "$COMMAND" in
    "status")
        log "Vérification du statut du service $SERVICE_NAME..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            # Retourner un statut simple pour le contrôleur PHP
            if systemctl is-active --quiet $SERVICE_NAME; then
                echo "active"
            else
                echo "inactive"
            fi
EOF
        ;;
    
    "start")
        log "Démarrage du service $SERVICE_NAME..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            systemctl start $SERVICE_NAME
            if systemctl is-active --quiet $SERVICE_NAME; then
                echo "Service $SERVICE_NAME démarré avec succès"
            else
                echo "Erreur lors du démarrage du service $SERVICE_NAME"
                systemctl status $SERVICE_NAME --no-pager
                exit 1
            fi
EOF
        success "Service $SERVICE_NAME démarré"
        ;;
    
    "stop")
        log "Arrêt du service $SERVICE_NAME..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            systemctl stop $SERVICE_NAME
            if ! systemctl is-active --quiet $SERVICE_NAME; then
                echo "Service $SERVICE_NAME arrêté avec succès"
            else
                echo "Erreur lors de l'arrêt du service $SERVICE_NAME"
                systemctl status $SERVICE_NAME --no-pager
                exit 1
            fi
EOF
        success "Service $SERVICE_NAME arrêté"
        ;;
    
    "restart")
        log "Redémarrage du service $SERVICE_NAME..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            systemctl restart $SERVICE_NAME
            sleep 2
            if systemctl is-active --quiet $SERVICE_NAME; then
                echo "Service $SERVICE_NAME redémarré avec succès"
            else
                echo "Erreur lors du redémarrage du service $SERVICE_NAME"
                systemctl status $SERVICE_NAME --no-pager
                exit 1
            fi
EOF
        success "Service $SERVICE_NAME redémarré"
        ;;
    
    "reload")
        log "Rechargement de la configuration..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            # Rechargement de la configuration systemd
            systemctl daemon-reload
            
            # Redémarrage du service pour appliquer les changements
            systemctl restart $SERVICE_NAME
            
            if systemctl is-active --quiet $SERVICE_NAME; then
                echo "Configuration rechargée avec succès"
            else
                echo "Erreur lors du rechargement de la configuration"
                systemctl status $SERVICE_NAME --no-pager
                exit 1
            fi
EOF
        success "Configuration rechargée"
        ;;
    
    "logs")
        ssh -i "$SSH_KEY" root@"$SERVER_IP" 'journalctl -u rwhoisd --no-pager -n 20'
        ;;
    
    "config")
        log "Affichage de la configuration..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            echo "=== Configuration RWHOIS Instance $INSTANCE_ID ==="
            echo "Fichier: $CONFIG_DIR/rwhois.conf"
            echo ""
            if [ -f "$CONFIG_DIR/rwhois.conf" ]; then
                cat "$CONFIG_DIR/rwhois.conf"
            else
                echo "Fichier de configuration non trouvé"
            fi
EOF
        ;;
    
    "test")
        log "Test de la connexion RWHOIS..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            # Récupération du port
            PORT=\$(grep "listen.*:" $CONFIG_DIR/rwhois.conf | grep -o '[0-9]\+' | head -1)
            
            if [ ! -z "\$PORT" ]; then
                echo "Test de connexion sur le port \$PORT..."
                
                # Test local
                if timeout 5 bash -c "</dev/tcp/localhost/\$PORT" 2>/dev/null; then
                    echo "✓ Connexion locale réussie"
                else
                    echo "✗ Échec de la connexion locale"
                fi
                
                # Test avec telnet
                if command -v telnet &> /dev/null; then
                    echo "Test avec telnet..."
                    timeout 5 telnet localhost \$PORT 2>/dev/null || echo "Échec du test telnet"
                fi
                
                # Test avec nc (netcat)
                if command -v nc &> /dev/null; then
                    echo "Test avec netcat..."
                    timeout 5 nc -zv localhost \$PORT 2>/dev/null || echo "Échec du test netcat"
                fi
            else
                echo "Impossible de déterminer le port de configuration"
            fi
EOF
        ;;
    
    "info")
        log "Informations sur l'instance $INSTANCE_ID..."
        ssh -i "$SSH_KEY" root@"$SERVER_IP" << EOF
            echo "=== Informations Instance $INSTANCE_ID ==="
            echo "Serveur: $SERVER_IP"
            echo "Instance ID: $INSTANCE_ID"
            echo "Service: $SERVICE_NAME"
            echo "Répertoire d'installation: $INSTALL_DIR"
            echo "Répertoire de configuration: $CONFIG_DIR"
            echo "Répertoire des logs: $LOG_DIR"
            echo ""
            
            # Statut du service
            echo "=== Statut du service ==="
            if systemctl is-active --quiet $SERVICE_NAME; then
                echo "Service: Actif"
            else
                echo "Service: Inactif"
            fi
            
            # Port utilisé
            PORT=\$(grep "listen.*:" $CONFIG_DIR/rwhois.conf | grep -o '[0-9]\+' | head -1)
            if [ ! -z "\$PORT" ]; then
                echo "Port configuré: \$PORT"
                if netstat -tlnp | grep -q ":\$PORT "; then
                    echo "Port: Ouvert"
                else
                    echo "Port: Fermé"
                fi
            fi
            
            # Taille des logs
            echo ""
            echo "=== Taille des logs ==="
            if [ -f "$LOG_DIR/rwhois.log" ]; then
                ls -lh "$LOG_DIR/rwhois.log"
            else
                echo "Fichier de log non trouvé"
            fi
            
            # Utilisation disque
            echo ""
            echo "=== Utilisation disque ==="
            du -sh $INSTALL_DIR $CONFIG_DIR $LOG_DIR 2>/dev/null || echo "Impossible de calculer l'utilisation disque"
EOF
        ;;
    
    "update")
        echo "Mise à jour du système en cours..."
        apt update && apt upgrade -y
        if [ $? -eq 0 ]; then
            echo "Mise à jour terminée avec succès."
        else
            echo "Erreur lors de la mise à jour."
        fi
        ;;
    
    *)
        error "Commande inconnue: $COMMAND"
        echo "Utilisez '$0 <server_ip> <instance_id> help' pour voir les commandes disponibles"
        exit 1
        ;;
esac

log "Commande $COMMAND exécutée avec succès sur l'instance $INSTANCE_ID" 