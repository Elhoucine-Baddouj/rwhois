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
    
    // Méthodes privées pour la gestion des données
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
            ],
            [
                'id' => 3,
                'username' => 'datanet_admin',
                'email' => 'admin@datanet.com',
                'full_name' => 'Admin DataNet',
                'role' => 'admin',
                'organization' => 'DataNet Solutions',
                'status' => 'active',
                'created_at' => '2024-01-15'
            ]
        ];
    }
    
    private function getUser($id)
    {
        $users = $this->getAllUsers();
        foreach ($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
    
    private function getRoles()
    {
        return [
            ['id' => 'admin', 'name' => 'Administrateur'],
            ['id' => 'user', 'name' => 'Utilisateur'],
            ['id' => 'viewer', 'name' => 'Lecteur']
        ];
    }
    
    private function getOrganizations()
    {
        return [
            ['id' => 1, 'name' => 'TechCorp Inc.'],
            ['id' => 2, 'name' => 'DataNet Solutions'],
            ['id' => 3, 'name' => 'CloudTech Ltd.']
        ];
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
        // Simulation - à remplacer par une vraie insertion DB
        return true;
    }
    
    private function updateUser($id, $data)
    {
        // Simulation - à remplacer par une vraie mise à jour DB
        return true;
    }
    
    private function deleteUser($id)
    {
        // Simulation - à remplacer par une vraie suppression DB
        return true;
    }
    
    private function updateUserPermissions($id, $data)
    {
        // Simulation - à remplacer par une vraie mise à jour DB
        return true;
    }
} 