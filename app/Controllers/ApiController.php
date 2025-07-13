<?php

namespace Controllers;

class ApiController extends BaseController
{
    public function servers()
    {
        $servers = $this->getAllServers();
        $this->json([
            'success' => true,
            'data' => $servers,
            'count' => count($servers)
        ]);
    }
    
    public function organizations()
    {
        $organizations = $this->getAllOrganizations();
        $this->json([
            'success' => true,
            'data' => $organizations,
            'count' => count($organizations)
        ]);
    }
    
    public function resources()
    {
        $resources = $this->getAllResources();
        $this->json([
            'success' => true,
            'data' => $resources,
            'count' => count($resources)
        ]);
    }
    
    public function users()
    {
        $users = $this->getAllUsers();
        $this->json([
            'success' => true,
            'data' => $users,
            'count' => count($users)
        ]);
    }
    
    public function serverStatus()
    {
        $id = $this->getQueryParam('id');
        $status = $this->getServerStatus($id);
        
        $this->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'status' => $status,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    }
    
    public function installServer()
    {
        $id = $this->getQueryParam('id');
        $result = $this->performInstallServer($id);
        
        $this->json([
            'success' => $result,
            'message' => $result ? 'Serveur installé avec succès' : 'Erreur lors de l\'installation'
        ]);
    }
    
    public function uninstallServer()
    {
        $id = $this->getQueryParam('id');
        $result = $this->performUninstallServer($id);
        
        $this->json([
            'success' => $result,
            'message' => $result ? 'Serveur désinstallé avec succès' : 'Erreur lors de la désinstallation'
        ]);
    }
    
    // Méthodes privées pour récupérer les données
    private function getAllServers()
    {
        return [
            [
                'id' => 1,
                'name' => 'RWHOIS-Server-01',
                'ip' => '192.168.1.100',
                'port' => 4321,
                'status' => 'active',
                'organization' => 'TechCorp Inc.',
                'created_at' => '2024-01-15'
            ],
            [
                'id' => 2,
                'name' => 'RWHOIS-Server-02',
                'ip' => '192.168.1.101',
                'port' => 4321,
                'status' => 'active',
                'organization' => 'DataNet Solutions',
                'created_at' => '2024-01-20'
            ]
        ];
    }
    
    private function getAllOrganizations()
    {
        return [
            [
                'id' => 1,
                'name' => 'TechCorp Inc.',
                'type' => 'ISP',
                'contact_email' => 'admin@techcorp.com',
                'contact_phone' => '+1-555-0123',
                'address' => '123 Tech Street, Silicon Valley, CA',
                'created_at' => '2024-01-10'
            ],
            [
                'id' => 2,
                'name' => 'DataNet Solutions',
                'type' => 'Hosting Provider',
                'contact_email' => 'info@datanet.com',
                'contact_phone' => '+1-555-0456',
                'address' => '456 Data Avenue, New York, NY',
                'created_at' => '2024-01-15'
            ]
        ];
    }
    
    private function getAllResources()
    {
        return [
            [
                'id' => 1,
                'type' => 'ASN',
                'value' => 'AS64512',
                'description' => 'TechCorp Autonomous System',
                'organization' => 'TechCorp Inc.',
                'status' => 'active',
                'created_at' => '2024-01-10'
            ],
            [
                'id' => 2,
                'type' => 'IPv4',
                'value' => '192.168.1.0/24',
                'description' => 'TechCorp IPv4 Network',
                'organization' => 'TechCorp Inc.',
                'status' => 'active',
                'created_at' => '2024-01-10'
            ]
        ];
    }
    
    private function getAllUsers()
    {
        return [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@rwhois.com',
                'full_name' => 'Administrateur Système',
                'role' => 'admin',
                'organization' => 'TechCorp Inc.',
                'status' => 'active',
                'created_at' => '2024-01-01'
            ],
            [
                'id' => 2,
                'username' => 'techcorp_user',
                'email' => 'user@techcorp.com',
                'full_name' => 'Utilisateur TechCorp',
                'role' => 'user',
                'organization' => 'TechCorp Inc.',
                'status' => 'active',
                'created_at' => '2024-01-10'
            ]
        ];
    }
    
    private function getServerStatus($id)
    {
        // Simulation - à remplacer par une vraie vérification
        return 'active';
    }
    
    private function performInstallServer($id)
    {
        // Simulation - à remplacer par une vraie installation
        return true;
    }
    
    private function performUninstallServer($id)
    {
        // Simulation - à remplacer par une vraie désinstallation
        return true;
    }
} 