<?php

namespace Controllers;

class OrganizationController extends BaseController
{
    public function index()
    {
        $organizations = $this->getAllOrganizations();
        $this->render('organizations/index', ['organizations' => $organizations]);
    }
    
    public function add()
    {
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->createOrganization($data);
            
            if ($result) {
                $this->redirect('/organizations');
            } else {
                $this->render('organizations/add', ['error' => 'Erreur lors de la création de l\'organisation']);
            }
        } else {
            $this->render('organizations/add');
        }
    }
    
    public function edit()
    {
        $id = $this->getQueryParam('id');
        
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->updateOrganization($id, $data);
            
            if ($result) {
                $this->redirect('/organizations');
            } else {
                $organization = $this->getOrganization($id);
                $this->render('organizations/edit', ['organization' => $organization, 'error' => 'Erreur lors de la modification']);
            }
        } else {
            $organization = $this->getOrganization($id);
            $this->render('organizations/edit', ['organization' => $organization]);
        }
    }
    
    public function delete()
    {
        $id = $this->getQueryParam('id');
        $result = $this->deleteOrganization($id);
        
        if ($result) {
            $this->redirect('/organizations');
        } else {
            echo "Erreur lors de la suppression";
        }
    }

    public function view()
    {
        $id = $this->getQueryParam('id');
        $organization = $this->getOrganization($id);
        $this->render('organizations/view', ['organization' => $organization]);
    }
    
    // Méthodes privées pour la gestion des données
    private function getAllOrganizations()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT * FROM organizations ORDER BY id";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getOrganization($id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT * FROM organizations WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function createOrganization($data)
    {
        try {
            $pdo = getDbConnection();
            $sql = "INSERT INTO organizations (name, type, contact_email, contact_phone, address, description, is_active)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['type'],
                $data['contact_email'],
                $data['contact_phone'],
                $data['address'],
                $data['description'] ?? '',
                isset($data['is_active']) ? 1 : 0
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function updateOrganization($id, $data)
    {
        try {
            $pdo = getDbConnection();
            $sql = "UPDATE organizations SET name=?, type=?, contact_email=?, contact_phone=?, address=?, description=?, is_active=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['type'],
                $data['contact_email'],
                $data['contact_phone'],
                $data['address'],
                $data['description'] ?? '',
                isset($data['is_active']) ? 1 : 0,
                $id
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function deleteOrganization($id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "DELETE FROM organizations WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            return false;
        }
    }
} 