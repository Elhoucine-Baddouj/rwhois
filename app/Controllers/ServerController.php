<?php

namespace Controllers;

class ServerController extends BaseController
{
    public function index()
    {
        $servers = $this->getAllServers();
        $this->render('servers/index', ['servers' => $servers]);
    }
    
    public function add()
    {
        if ($this->isPost()) {
            $data = $this->getPostData();
            // Debug : afficher les données reçues
            // var_dump($data); exit;
            $result = $this->createServer($data);
            
            if ($result === true) {
                $this->redirect('/servers');
            } else {
                $this->render('servers/add', ['error' => $result]);
            }
        } else {
            $this->render('servers/add');
        }
    }
    
    public function edit()
    {
        $id = $this->getQueryParam('id');
        
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->updateServer($id, $data);
            
            if ($result) {
                $this->redirect('/servers');
            } else {
                $server = $this->getServer($id);
                $this->render('servers/edit', ['server' => $server, 'error' => 'Erreur lors de la modification']);
            }
        } else {
            $server = $this->getServer($id);
            $this->render('servers/edit', ['server' => $server]);
        }
    }
    
    public function delete()
    {
        $id = $this->isPost() ? ($_POST['id'] ?? null) : $this->getQueryParam('id');
        $result = $this->deleteServer($id);
        
        if ($result) {
            $this->redirect('/servers');
        } else {
            echo "Erreur lors de la suppression";
        }
    }
    
    public function install()
    {
        $id = $this->getQueryParam('id');
        $result = $this->installServer($id);
        
        $this->json(['success' => $result]);
    }
    
    public function uninstall()
    {
        $id = $this->getQueryParam('id');
        $result = $this->uninstallServer($id);
        
        $this->json(['success' => $result]);
    }
    
    public function status()
    {
        $id = $this->getQueryParam('id');
        $status = $this->getServerStatus($id);
        
        $this->json(['status' => $status]);
    }
    
    // Méthodes privées pour la gestion des données
    private function getAllServers()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT s.*, o.name AS organization FROM servers s LEFT JOIN organizations o ON s.organization_id = o.id ORDER BY s.id DESC";
            $stmt = $pdo->query($sql);
            $servers = $stmt->fetchAll();
            // Ajouter un statut fictif pour l'affichage (à adapter selon ton modèle)
            foreach ($servers as &$server) {
                $server['status'] = 'active';
            }
            return $servers;
        } catch (\Exception $e) {
            echo "<pre>Erreur getAllServers: " . $e->getMessage() . "</pre>";
            return [];
        }
    }
    
    private function getServer($id)
    {
        $servers = $this->getAllServers();
        foreach ($servers as $server) {
            if ($server['id'] == $id) {
                return $server;
            }
        }
        return null;
    }
    
    private function createServer($data)
    {
        try {
            $pdo = getDbConnection();
            $sql = "INSERT INTO servers (name, ip, port, organization_id, description, location, environment) VALUES (:name, :ip, :port, :organization_id, :description, :location, :environment)";
            $stmt = $pdo->prepare($sql);
            $ok = $stmt->execute([
                'name' => $data['name'],
                'ip' => $data['ip'],
                'port' => $data['port'],
                'organization_id' => $data['organization_id'],
                'description' => $data['description'] ?? null,
                'location' => $data['location'] ?? null,
                'environment' => $data['environment'] ?? null
            ]);
            if (!$ok) {
                return "Erreur lors de l'insertion SQL.";
            }
            return true;
        } catch (\Exception $e) {
            return "Erreur createServer: " . $e->getMessage();
        }
    }
    
    private function updateServer($id, $data)
    {
        try {
            $pdo = getDbConnection();
            $sql = "UPDATE servers SET name = :name, ip = :ip, port = :port, organization_id = :organization_id, description = :description, location = :location, environment = :environment WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'name' => $data['name'],
                'ip' => $data['ip'],
                'port' => $data['port'],
                'organization_id' => $data['organization_id'],
                'description' => $data['description'] ?? null,
                'location' => $data['location'] ?? null,
                'environment' => $data['environment'] ?? null,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function deleteServer($id)
    {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare("DELETE FROM servers WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function installServer($id)
    {
        // Simulation de l'installation automatique
        // Ici vous pourriez exécuter des scripts d'installation
        return true;
    }
    
    private function uninstallServer($id)
    {
        // Simulation de la désinstallation automatique
        return true;
    }
    
    private function getServerStatus($id)
    {
        // Simulation de la vérification du statut
        return 'active';
    }
} 