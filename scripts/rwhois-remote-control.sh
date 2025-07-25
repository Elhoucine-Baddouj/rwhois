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

# VPS IPs
LOCAL_IP="178.156.170.162"
REMOTE_IP="178.156.185.53"

# Mode d'exécution
if [ "$SERVER_IP" = "$LOCAL_IP" ]; then
    IS_LOCAL=1
else
    IS_LOCAL=0
fi

# Chemin de la clé SSH pour le serveur distant
SSH_KEY="/var/www/.ssh/id_rsa"

# Détection du dossier d'installation
INSTALL_DIR=""
CONFIG_DIR=""
LOG_DIR=""
if [ "$IS_LOCAL" -eq 1 ]; then
    if [ -d /root/rwhoisd/rwhoisd ]; then
        INSTALL_DIR="/root/rwhoisd/rwhoisd"
        CONFIG_DIR="/root/rwhoisd/rwhoisd"
        LOG_DIR="/root/rwhoisd/rwhoisd"
    elif [ -d /opt/rwhoisd/rwhoisd ]; then
        INSTALL_DIR="/opt/rwhoisd/rwhoisd"
        CONFIG_DIR="/opt/rwhoisd/rwhoisd"
        LOG_DIR="/opt/rwhoisd/rwhoisd"
    else
        error "No rwhoisd install dir found on local server!"
        exit 1
    fi
else
    if ssh -i "$SSH_KEY" root@"$SERVER_IP" "[ -d /root/rwhoisd/rwhoisd ]" 2>/dev/null; then
        INSTALL_DIR="/root/rwhoisd/rwhoisd"
        CONFIG_DIR="/root/rwhoisd/rwhoisd"
        LOG_DIR="/root/rwhoisd/rwhoisd"
    elif ssh -i "$SSH_KEY" root@"$SERVER_IP" "[ -d /opt/rwhoisd/rwhoisd ]" 2>/dev/null; then
        INSTALL_DIR="/opt/rwhoisd/rwhoisd"
        CONFIG_DIR="/opt/rwhoisd/rwhoisd"
        LOG_DIR="/opt/rwhoisd/rwhoisd"
    else
        error "No rwhoisd install dir found on $SERVER_IP!"
        exit 1
    fi
fi

# Wrapper pour exécuter une commande localement ou à distance
run_cmd() {
    if [ "$IS_LOCAL" -eq 1 ]; then
        bash -c "$1"
    else
        ssh -i "$SSH_KEY" root@"$SERVER_IP" "$1"
    fi
}

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
if [ "$IS_LOCAL" -eq 0 ]; then
    if ! ssh -i "$SSH_KEY" -o ConnectTimeout=10 -o BatchMode=yes root@"$SERVER_IP" "echo 'Connectivity OK'" > /dev/null 2>&1; then
        error "Impossible de joindre $SERVER_IP via SSH"
        exit 1
    fi
fi

# Vérification de l'accès SSH
log "Test de la connexion SSH..."
if [ "$IS_LOCAL" -eq 0 ]; then
    if ! ssh -i "$SSH_KEY" -o ConnectTimeout=10 -o BatchMode=yes root@"$SERVER_IP" "echo 'SSH OK'" > /dev/null 2>&1; then
        error "Impossible de se connecter en SSH à $SERVER_IP"
        exit 1
    fi
fi

# Détection du dossier d'installation
INSTALL_DIR=""
CONFIG_DIR=""
LOG_DIR=""
if [ "$IS_LOCAL" -eq 1 ]; then
    if [ -d /root/rwhoisd/rwhoisd ]; then
        INSTALL_DIR="/root/rwhoisd/rwhoisd"
        CONFIG_DIR="/root/rwhoisd/rwhoisd"
        LOG_DIR="/root/rwhoisd/rwhoisd"
    elif [ -d /opt/rwhoisd/rwhoisd ]; then
        INSTALL_DIR="/opt/rwhoisd/rwhoisd"
        CONFIG_DIR="/opt/rwhoisd/rwhoisd"
        LOG_DIR="/opt/rwhoisd/rwhoisd"
    else
        error "No rwhoisd install dir found on local server!"
        exit 1
    fi
else
    if ssh -i "$SSH_KEY" root@"$SERVER_IP" "[ -d /root/rwhoisd/rwhoisd ]" 2>/dev/null; then
        INSTALL_DIR="/root/rwhoisd/rwhoisd"
        CONFIG_DIR="/root/rwhoisd/rwhoisd"
        LOG_DIR="/root/rwhoisd/rwhoisd"
    elif ssh -i "$SSH_KEY" root@"$SERVER_IP" "[ -d /opt/rwhoisd/rwhoisd ]" 2>/dev/null; then
        INSTALL_DIR="/opt/rwhoisd/rwhoisd"
        CONFIG_DIR="/opt/rwhoisd/rwhoisd"
        LOG_DIR="/opt/rwhoisd/rwhoisd"
    else
        error "No rwhoisd install dir found on $SERVER_IP!"
        exit 1
    fi
fi

# Vérification de l'existence du dossier d'installation
log "Vérification de l'existence du dossier d'installation sur $SERVER_IP..."
if [ "$IS_LOCAL" -eq 0 ]; then
    if ! ssh -i "$SSH_KEY" root@"$SERVER_IP" "[ -d '$INSTALL_DIR' ]" 2>/dev/null; then
        error "Le dossier d'installation $INSTALL_DIR n'existe pas sur $SERVER_IP"
        exit 1
    fi
else
    if ! [ -d "$INSTALL_DIR" ]; then
        error "Le dossier d'installation $INSTALL_DIR n'existe pas sur le serveur local"
        exit 1
    fi
fi

# Exécution des commandes
case "$COMMAND" in
    "status")
        log "Vérification du statut du service $SERVICE_NAME..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "systemctl is-active --quiet $SERVICE_NAME"
        else
            run_cmd "systemctl is-active --quiet $SERVICE_NAME"
        fi
        ;;
    
    "start")
        log "Démarrage du service $SERVICE_NAME..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "systemctl start $SERVICE_NAME"
        else
            run_cmd "systemctl start $SERVICE_NAME"
        fi
        ;;
    
    "stop")
        log "Arrêt du service $SERVICE_NAME..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "systemctl stop $SERVICE_NAME"
        else
            run_cmd "systemctl stop $SERVICE_NAME"
        fi
        ;;
    
    "restart")
        log "Redémarrage du service $SERVICE_NAME..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "systemctl restart $SERVICE_NAME"
        else
            run_cmd "systemctl restart $SERVICE_NAME"
        fi
        ;;
    
    "reload")
        log "Rechargement de la configuration..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "systemctl daemon-reload"
            run_cmd "systemctl restart $SERVICE_NAME"
        else
            run_cmd "systemctl daemon-reload"
            run_cmd "systemctl restart $SERVICE_NAME"
        fi
        ;;
    
    "logs")
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "journalctl -u rwhoisd --no-pager -n 20"
        else
            run_cmd "journalctl -u rwhoisd --no-pager -n 20"
        fi
        ;;
    
    "config")
        log "Affichage de la configuration..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "echo \"=== Configuration RWHOIS Instance $INSTANCE_ID ===\" && echo \"Fichier: $CONFIG_DIR/rwhois.conf\" && echo \"\" && if [ -f \"$CONFIG_DIR/rwhois.conf\" ]; then cat \"$CONFIG_DIR/rwhois.conf\" else echo \"Fichier de configuration non trouvé\" fi"
        else
            run_cmd "echo \"=== Configuration RWHOIS Instance $INSTANCE_ID ===\" && echo \"Fichier: $CONFIG_DIR/rwhois.conf\" && echo \"\" && if [ -f \"$CONFIG_DIR/rwhois.conf\" ]; then cat \"$CONFIG_DIR/rwhois.conf\" else echo \"Fichier de configuration non trouvé\" fi"
        fi
        ;;
    
    "test")
        log "Test de la connexion RWHOIS..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "PORT=\$(grep \"listen.*:\" $CONFIG_DIR/rwhois.conf | grep -o '[0-9]\+' | head -1) && if [ ! -z \"\$PORT\" ]; then echo \"Test de connexion sur le port \$PORT...\" && if timeout 5 bash -c \"</dev/tcp/localhost/\$PORT\" 2>/dev/null; then echo \"✓ Connexion locale réussie\" else echo \"✗ Échec de la connexion locale\" fi && if command -v telnet &> /dev/null; then echo \"Test avec telnet...\" && timeout 5 telnet localhost \$PORT 2>/dev/null || echo \"Échec du test telnet\" fi && if command -v nc &> /dev/null; then echo \"Test avec netcat...\" && timeout 5 nc -zv localhost \$PORT 2>/dev/null || echo \"Échec du test netcat\" fi fi"
        else
            run_cmd "PORT=\$(grep \"listen.*:\" $CONFIG_DIR/rwhois.conf | grep -o '[0-9]\+' | head -1) && if [ ! -z \"\$PORT\" ]; then echo \"Test de connexion sur le port \$PORT...\" && if timeout 5 bash -c \"</dev/tcp/localhost/\$PORT\" 2>/dev/null; then echo \"✓ Connexion locale réussie\" else echo \"✗ Échec de la connexion locale\" fi && if command -v telnet &> /dev/null; then echo \"Test avec telnet...\" && timeout 5 telnet localhost \$PORT 2>/dev/null || echo \"Échec du test telnet\" fi && if command -v nc &> /dev/null; then echo \"Test avec netcat...\" && timeout 5 nc -zv localhost \$PORT 2>/dev/null || echo \"Échec du test netcat\" fi fi"
        fi
        ;;
    
    "info")
        log "Informations sur l'instance $INSTANCE_ID..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            run_cmd "echo \"=== Informations Instance $INSTANCE_ID ===\" && echo \"Serveur: $SERVER_IP\" && echo \"Instance ID: $INSTANCE_ID\" && echo \"Service: $SERVICE_NAME\" && echo \"Répertoire d'installation: $INSTALL_DIR\" && echo \"Répertoire de configuration: $CONFIG_DIR\" && echo \"Répertoire des logs: $LOG_DIR\" && echo \"\" && echo \"=== Statut du service ===\" && if systemctl is-active --quiet $SERVICE_NAME; then echo \"Service: Actif\" else echo \"Service: Inactif\" fi && PORT=\$(grep \"listen.*:\" $CONFIG_DIR/rwhois.conf | grep -o '[0-9]\+' | head -1) && if [ ! -z \"\$PORT\" ]; then echo \"Port configuré: \$PORT\" && if netstat -tlnp | grep -q \":\$PORT \"; then echo \"Port: Ouvert\" else echo \"Port: Fermé\" fi fi && echo \"\" && echo \"=== Taille des logs ===\" && if [ -f \"$LOG_DIR/rwhois.log\" ]; then ls -lh \"$LOG_DIR/rwhois.log\" else echo \"Fichier de log non trouvé\" fi && echo \"\" && echo \"=== Utilisation disque ===\" && du -sh $INSTALL_DIR $CONFIG_DIR $LOG_DIR 2>/dev/null || echo \"Impossible de calculer l'utilisation disque\""
        else
            run_cmd "echo \"=== Informations Instance $INSTANCE_ID ===\" && echo \"Serveur: $SERVER_IP\" && echo \"Instance ID: $INSTANCE_ID\" && echo \"Service: $SERVICE_NAME\" && echo \"Répertoire d'installation: $INSTALL_DIR\" && echo \"Répertoire de configuration: $CONFIG_DIR\" && echo \"Répertoire des logs: $LOG_DIR\" && echo \"\" && echo \"=== Statut du service ===\" && if systemctl is-active --quiet $SERVICE_NAME; then echo \"Service: Actif\" else echo \"Service: Inactif\" fi && PORT=\$(grep \"listen.*:\" $CONFIG_DIR/rwhois.conf | grep -o '[0-9]\+' | head -1) && if [ ! -z \"\$PORT\" ]; then echo \"Port configuré: \$PORT\" && if netstat -tlnp | grep -q \":\$PORT \"; then echo \"Port: Ouvert\" else echo \"Port: Fermé\" fi fi && echo \"\" && echo \"=== Taille des logs ===\" && if [ -f \"$LOG_DIR/rwhois.log\" ]; then ls -lh \"$LOG_DIR/rwhois.log\" else echo \"Fichier de log non trouvé\" fi && echo \"\" && echo \"=== Utilisation disque ===\" && du -sh $INSTALL_DIR $CONFIG_DIR $LOG_DIR 2>/dev/null || echo \"Impossible de calculer l'utilisation disque\""
        fi
        ;;
    
    "update")
        echo "Mise à jour du système en cours..."
        if [ "$IS_LOCAL" -eq 0 ]; then
            apt update && apt upgrade -y
        else
            apt update && apt upgrade -y
        fi
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