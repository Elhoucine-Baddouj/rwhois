<?php

namespace Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        // Statistiques pour le tableau de bord
        $stats = [
            'servers' => $this->getServerCount(),
            'organizations' => $this->getOrganizationCount(),
            'resources' => $this->getResourceCount(),
            'users' => $this->getUserCount()
        ];
        
        $recentServers = $this->getRecentServers();
        $recentOrganizations = $this->getRecentOrganizations();
        
        $this->render('dashboard/index', [
            'stats' => $stats,
            'recentServers' => $recentServers,
            'recentOrganizations' => $recentOrganizations
        ]);
    }
    
    private function getServerCount()
    {
        // Simulation - à remplacer par une vraie requête DB
        return 5;
    }
    
    private function getOrganizationCount()
    {
        return 12;
    }
    
    private function getResourceCount()
    {
        return 150;
    }
    
    private function getUserCount()
    {
        return 8;
    }
    
    private function getRecentServers()
    {
        // Simulation - à remplacer par une vraie requête DB
        return [
            ['id' => 1, 'name' => 'RWHOIS-Server-01', 'status' => 'active', 'ip' => '192.168.1.100'],
            ['id' => 2, 'name' => 'RWHOIS-Server-02', 'status' => 'active', 'ip' => '192.168.1.101'],
            ['id' => 3, 'name' => 'RWHOIS-Server-03', 'status' => 'inactive', 'ip' => '192.168.1.102']
        ];
    }
    
    private function getRecentOrganizations()
    {
        return [
            ['id' => 1, 'name' => 'TechCorp Inc.', 'type' => 'ISP'],
            ['id' => 2, 'name' => 'DataNet Solutions', 'type' => 'Hosting Provider'],
            ['id' => 3, 'name' => 'CloudTech Ltd.', 'type' => 'Cloud Provider']
        ];
    }
} 