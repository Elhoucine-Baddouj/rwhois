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
    
    // Méthodes privées pour la gestion des données
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
            ],
            [
                'id' => 3,
                'name' => 'CloudTech Ltd.',
                'type' => 'Cloud Provider',
                'contact_email' => 'support@cloudtech.com',
                'contact_phone' => '+1-555-0789',
                'address' => '789 Cloud Boulevard, Seattle, WA',
                'created_at' => '2024-01-20'
            ]
        ];
    }
    
    private function getOrganization($id)
    {
        $organizations = $this->getAllOrganizations();
        foreach ($organizations as $org) {
            if ($org['id'] == $id) {
                return $org;
            }
        }
        return null;
    }
    
    private function createOrganization($data)
    {
        // Simulation - à remplacer par une vraie insertion DB
        return true;
    }
    
    private function updateOrganization($id, $data)
    {
        // Simulation - à remplacer par une vraie mise à jour DB
        return true;
    }
    
    private function deleteOrganization($id)
    {
        // Simulation - à remplacer par une vraie suppression DB
        return true;
    }
} 