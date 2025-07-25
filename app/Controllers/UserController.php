<?php

namespace Controllers;

class UserController extends BaseController
{
    public function index()
    {
        $users = $this->getAllUsers();
        $this->render('users/index', ['users' => $users]);
    }
    
    public function add()
    {
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->createUser($data);
            
            if ($result) {
                $this->redirect('/users');
            } else {
                $this->render('users/add', ['error' => 'Erreur lors de la création de l\'utilisateur']);
            }
        } else {
            $roles = $this->getRoles();
            $organizations = $this->getOrganizations();
            $this->render('users/add', [
                'roles' => $roles,
                'organizations' => $organizations
            ]);
        }
    }
    
    public function edit()
    {
        $id = $this->getQueryParam('id');
        
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->updateUser($id, $data);
            
            if ($result) {
                $this->redirect('/users');
            } else {
                $user = $this->getUser($id);
                $roles = $this->getRoles();
                $organizations = $this->getOrganizations();
                $this->render('users/edit', [
                    'user' => $user,
                    'roles' => $roles,
                    'organizations' => $organizations,
                    'error' => 'Erreur lors de la modification'
                ]);
            }
        } else {
            $user = $this->getUser($id);
            $roles = $this->getRoles();
            $organizations = $this->getOrganizations();
            $this->render('users/edit', [
                'user' => $user,
                'roles' => $roles,
                'organizations' => $organizations
            ]);
        }
    }
    
    public function delete()
    {
        $id = $this->getQueryParam('id');
        $result = $this->deleteUser($id);
        
        if ($result) {
            $this->redirect('/users');
        } else {
            echo "Erreur lors de la suppression";
        }
    }
    
    public function permissions()
    {
        $id = $this->getQueryParam('id');
        $user = $this->getUser($id);
        $permissions = $this->getUserPermissions($id);
        
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->updateUserPermissions($id, $data);
            
            if ($result) {
                $this->redirect('/users');
            } else {
                $this->render('users/permissions', [
                    'user' => $user,
                    'permissions' => $permissions,
                    'error' => 'Erreur lors de la mise à jour des permissions'
                ]);
            }
        } else {
            $this->render('users/permissions', [
                'user' => $user,
                'permissions' => $permissions
            ]);
        }
    }
    
    public function view()
    {
        $id = $this->getQueryParam('id');
        $user = $this->getUser($id);
        $this->render('users/view', ['user' => $user]);
    }
    
    // Méthodes privées pour la gestion des données
    private function getAllUsers()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT u.*, o.name AS organization FROM users u LEFT JOIN organizations o ON u.organization_id = o.id ORDER BY u.id";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getUser($id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT u.*, o.name AS organization FROM users u LEFT JOIN organizations o ON u.organization_id = o.id WHERE u.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function getRoles()
    {
        return [
            ['id' => 'admin', 'name' => 'Administrateur'],
            ['id' => 'manager', 'name' => 'Gestionnaire'],
            ['id' => 'observer', 'name' => 'Observateur']
        ];
    }
    
    private function getOrganizations()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT id, name FROM organizations WHERE is_active = 1 ORDER BY name";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getUserPermissions($userId)
    {
        return [
            'servers' => ['view' => true, 'edit' => true, 'delete' => false],
            'organizations' => ['view' => true, 'edit' => false, 'delete' => false],
            'resources' => ['view' => true, 'edit' => true, 'delete' => false],
            'users' => ['view' => false, 'edit' => false, 'delete' => false]
        ];
    }
    
    private function createUser($data)
    {
        try {
            $pdo = getDbConnection();
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, full_name, password, role, organization_id, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['username'],
                $data['email'],
                $data['full_name'],
                $hashedPassword,
                $data['role'],
                $data['organization_id'],
                $data['status'] ?? 'active'
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function updateUser($id, $data)
    {
        try {
            $pdo = getDbConnection();
            $sql = "UPDATE users SET username=?, email=?, full_name=?, role=?, organization_id=?, status=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['username'],
                $data['email'],
                $data['full_name'],
                $data['role'],
                $data['organization_id'],
                $data['status'] ?? 'active',
                $id
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function deleteUser($id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "DELETE FROM users WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function updateUserPermissions($id, $data)
    {
        // Simulation - à remplacer par une vraie mise à jour DB
        return true;
    }
} 