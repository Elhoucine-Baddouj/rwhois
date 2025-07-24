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
        try {
            $pdo = getDbConnection();
            $sql = "SELECT COUNT(*) as count FROM servers WHERE status = 'active'";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch();
            return $result['count'];
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getOrganizationCount()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT COUNT(*) as count FROM organizations WHERE is_active = 1";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch();
            return $result['count'];
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getUserCount()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT COUNT(*) as count FROM users WHERE status = 'active'";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch();
            return $result['count'];
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getRecentServers()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT id, name, server_ip as ip, status FROM servers ORDER BY id DESC LIMIT 5";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getRecentOrganizations()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT id, name, type FROM organizations WHERE is_active = 1 ORDER BY id DESC LIMIT 5";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }
} 