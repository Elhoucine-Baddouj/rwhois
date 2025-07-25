<?php

namespace Controllers;

class ServerController extends BaseController
{
    public function index()
    {
        $servers = $this->getAllServers();
        $this->render('servers/index', ['servers' => $servers]);
    }
    
    public function refreshStatus()
    {
        $id = $this->getQueryParam('id');
        
        // Récupérer le statut réel du serveur
        $realStatus = $this->getRealServerStatus($id);
        
        // Mettre à jour la base de données
        $this->updateServerStatus($id, $realStatus);
        
        $this->json(['success' => true, 'status' => $realStatus]);
    }
    
    public function control()
    {
        $id = $this->getQueryParam('id');
        $action = $this->getQueryParam('action');
        
        $result = $this->executeRemoteCommand($id, $action);
        // Forcer la mise à jour du statut en base selon l'action
        if ($result) {
            if ($action === 'stop') {
                $this->updateServerStatus($id, 'inactive');
            } elseif ($action === 'start') {
                $this->updateServerStatus($id, 'active');
            }
        }
        $status = $this->getServerStatus($id);
        $this->json(['success' => $result, 'status' => $status]);
    }
    
    public function logs()
    {
        $id = $this->getQueryParam('id');
        $logs = $this->getServerLogs($id);
        
        $this->json(['logs' => $logs]);
    }
    
    public function info()
    {
        $id = $this->getQueryParam('id');
        $server = $this->getServer($id);
        if (!$server) {
            echo '<div class="alert alert-danger">Serveur introuvable.</div>';
            exit;
        }
        // Affichage HTML des infos du serveur
        echo '<ul class="list-group">';
        echo '<li class="list-group-item"><strong>Nom :</strong> ' . htmlspecialchars($server['name']) . '</li>';
        echo '<li class="list-group-item"><strong>Adresse IP :</strong> ' . htmlspecialchars($server['server_ip']) . '</li>';
        echo '<li class="list-group-item"><strong>Instance :</strong> ' . htmlspecialchars($server['instance_id']) . '</li>';
        echo '<li class="list-group-item"><strong>Port :</strong> ' . htmlspecialchars($server['port']) . '</li>';
        echo '<li class="list-group-item"><strong>Organisation :</strong> ' . htmlspecialchars($server['organization_name'] ?? '(Aucune)') . '</li>';
        echo '<li class="list-group-item"><strong>Statut :</strong> ' . htmlspecialchars($server['status']) . '</li>';
        echo '<li class="list-group-item"><strong>Environnement :</strong> ' . htmlspecialchars($server['environment']) . '</li>';
        echo '<li class="list-group-item"><strong>Description :</strong> ' . htmlspecialchars($server['description'] ?? '') . '</li>';
        echo '</ul>';
        exit;
    }
    
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $pdo = getDbConnection();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sql = "UPDATE servers SET name=?, organization_id=?, environment=?, description=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['name'],
                $_POST['organization_id'],
                $_POST['environment'],
                $_POST['description'],
                $id
            ]);
            header('Location: /servers');
            exit;
        } else {
            $stmt = $pdo->prepare("SELECT * FROM servers WHERE id=?");
            $stmt->execute([$id]);
            $server = $stmt->fetch();
            $organizations = $pdo->query("SELECT * FROM organizations")->fetchAll();
            include __DIR__ . '/../../views/servers/edit.php';
        }
    }
    
    public function delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->json(['success' => false, 'error' => 'ID manquant']);
            return;
        }
        $server = $this->getServer($id);
        if (!$server) {
            $this->json(['success' => false, 'error' => 'Serveur introuvable']);
            return;
        }
        // Suppression distante sur le VPS
        $scriptPath = BASE_PATH . '/scripts/rwhois-remote-control.sh';
        $command = sprintf('%s %s %d delete', $scriptPath, $server['server_ip'], $server['instance_id']);
        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            $this->json(['success' => false, 'error' => "Erreur suppression VPS: " . implode("\n", $output)]);
            return;
        }
        // Suppression en base de données
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare('DELETE FROM servers WHERE id = ?');
            $stmt->execute([$id]);
            $this->json(['success' => true]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    // Méthodes privées pour la gestion des données
    private function getAllServers()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT s.*, o.name AS organization_name FROM servers s 
                    LEFT JOIN organizations o ON s.organization_id = o.id 
                    ORDER BY s.server_ip, s.instance_id";
            $stmt = $pdo->query($sql);
            $servers = $stmt->fetchAll();
            
            // Ajouter des informations supplémentaires
            foreach ($servers as &$server) {
                $server['full_name'] = $server['name'] . ' (Instance ' . $server['instance_id'] . ')';
                $server['connection_string'] = $server['server_ip'] . ':' . $server['port'];
            }
            
            return $servers;
        } catch (\Exception $e) {
            echo "<pre>Erreur getAllServers: " . $e->getMessage() . "</pre>";
            return [];
        }
    }
    
    private function getServer($id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT s.*, o.name AS organization_name FROM servers s 
                    LEFT JOIN organizations o ON s.organization_id = o.id 
                    WHERE s.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function getServerStatus($id)
    {
        try {
            $server = $this->getServer($id);
            if (!$server) {
                return 'unknown';
            }
            
            // Vérifier le statut via SSH
            $scriptPath = BASE_PATH . '/scripts/rwhois-remote-control.sh';
            $command = sprintf('%s %s %d status', $scriptPath, $server['server_ip'], $server['instance_id']);
            
            $output = [];
            $returnCode = 0;
            exec($command . ' 2>&1', $output, $returnCode);
            
            if ($returnCode === 0) {
                // Le script retourne maintenant "active" ou "inactive" directement
                $status = trim(implode("\n", $output));
                if ($status === 'active' || $status === 'inactive') {
                    $this->updateServerStatus($id, $status);
                    return $status;
                } else {
                    // En cas d'erreur, retourner le statut de la base de données
                    $pdo = getDbConnection();
                    $sql = "SELECT status FROM servers WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id]);
                    $result = $stmt->fetch();
                    return $result ? $result['status'] : 'unknown';
                }
            } else {
                $this->updateServerStatus($id, 'error');
                return 'error';
            }
        } catch (\Exception $e) {
            return 'error';
        }
    }
    
    private function getServerLogs($id)
    {
        // Correction du chemin d'autoload pour phpseclib
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        // Debug : vérifier si l'autoload est bien inclus et si la classe SCP existe
        $included = get_included_files();
        $debug = "Autoload inclus :\n" . implode("\n", $included) . "\n";
        if (!class_exists('phpseclib3\\Net\\SCP')) {
            $debug .= "SCP class NOT FOUND after autoload\n";
        } else {
            $debug .= "SCP class FOUND\n";
        }
        file_put_contents('C:/Users/LENOVO/Desktop/prj/autoload_debug.txt', $debug);
        $server = $this->getServer($id);
        if (!$server) {
            return [];
        }
        $ip = $server['server_ip'];
        $sshUser = 'root'; // adapte si besoin
        $sshKey = 'C:/users/LENOVO/.ssh/id_ed25519'; // adapte le chemin de ta clé privée

        // Connexion SSH
        $ssh = new \phpseclib3\Net\SSH2($ip);
        $key = \phpseclib3\Crypt\PublicKeyLoader::loadPrivateKey(file_get_contents($sshKey));
        if (!$ssh->login($sshUser, $key)) {
            return ['Erreur SSH : connexion impossible'];
        }
        // Exécute la commande distante pour générer le fichier de logs (nom correct)
        $ssh->exec('journalctl -u rwhoisd --no-pager -n 20 > /tmp/rwhoisd_dashboard.log');

        // Récupère le fichier de logs via SCP (nom correct)
        $scp = new \phpseclib3\Net\SCP($ssh);
        $logs = $scp->read('/tmp/rwhoisd_dashboard.log');
        if ($logs === false) {
            return ['Erreur : impossible de lire le fichier de logs distant'];
        }

        // Vide le fichier distant pour le prochain usage (nom correct)
        $ssh->exec('> /tmp/rwhoisd_dashboard.log');

        // Retourne les logs sous forme de tableau de lignes
        return explode(PHP_EOL, trim($logs));
    }
    
    private function updateServerStatus($id, $status)
    {
        try {
            $pdo = getDbConnection();
            $sql = "UPDATE servers SET status = ?, last_status_check = NOW() WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function executeRemoteCommand($id, $action)
    {
        try {
            $server = $this->getServer($id);
            if (!$server) {
                return false;
            }
            // Pour l'action update, exécute le script shell avec l'argument update
            if ($action === 'update') {
                $scriptPath = BASE_PATH . '/scripts/rwhois-remote-control.sh';
                $command = sprintf('%s %s %d update', $scriptPath, $server['server_ip'], $server['instance_id']);
                $output = [];
                $returnCode = 0;
                exec($command . ' 2>&1', $output, $returnCode);
                $this->logActivity('update', 'server', $id, ['output' => implode("\n", $output)]);
                return $returnCode === 0;
            }
            // Exécuter la commande via le script de contrôle
            $scriptPath = BASE_PATH . '/scripts/rwhois-remote-control.sh';
            $command = sprintf('%s %s %d %s', $scriptPath, $server['server_ip'], $server['instance_id'], $action);
            
            $output = [];
            $returnCode = 0;
            exec($command . ' 2>&1', $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->logActivity($action, 'server', $id, ['output' => implode("\n", $output)]);
                return true;
            } else {
                $this->logActivity($action . '_failed', 'server', $id, ['error' => implode("\n", $output)]);
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function logActivity($action, $entityType, $entityId, $details = [])
    {
        try {
            $pdo = getDbConnection();
            $sql = "INSERT INTO activity_logs (user_id, action, entity_type, entity_id, details, ip_address, user_agent) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            $userId = $_SESSION['user_id'] ?? null;
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            return $stmt->execute([
                $userId,
                $action,
                $entityType,
                $entityId,
                json_encode($details),
                $ipAddress,
                $userAgent
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    // Ajout pour corriger l'appel manquant
    private function getRealServerStatus($id)
    {
        return $this->getServerStatus($id);
    }
} 