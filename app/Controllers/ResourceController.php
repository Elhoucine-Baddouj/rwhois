<?php

namespace Controllers;

class ResourceController extends BaseController
{
    public function index()
    {
        $resources = $this->getAllResources();
        $this->render('resources/index', ['resources' => $resources]);
    }
    
    public function add()
    {
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->createResource($data);
            
            if ($result) {
                $this->redirect('/resources');
            } else {
                $this->render('resources/add', ['error' => 'Erreur lors de la création de la ressource']);
            }
        } else {
            $organizations = $this->getOrganizations();
            $this->render('resources/add', ['organizations' => $organizations]);
        }
    }
    
    public function edit()
    {
        $id = $this->getQueryParam('id');
        
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->updateResource($id, $data);
            
            if ($result) {
                $this->redirect('/resources');
            } else {
                $resource = $this->getResource($id);
                $organizations = $this->getOrganizations();
                $this->render('resources/edit', [
                    'resource' => $resource, 
                    'organizations' => $organizations,
                    'error' => 'Erreur lors de la modification'
                ]);
            }
        } else {
            $resource = $this->getResource($id);
            $organizations = $this->getOrganizations();
            $this->render('resources/edit', [
                'resource' => $resource,
                'organizations' => $organizations
            ]);
        }
    }
    
    public function delete()
    {
        $id = $this->getQueryParam('id');
        $result = $this->deleteResource($id);
        
        if ($result) {
            $this->redirect('/resources');
        } else {
            echo "Erreur lors de la suppression";
        }
    }
    
    // Méthodes privées pour la gestion des données
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
            ],
            [
                'id' => 3,
                'type' => 'IPv6',
                'value' => '2001:db8::/32',
                'description' => 'DataNet IPv6 Network',
                'organization' => 'DataNet Solutions',
                'status' => 'active',
                'created_at' => '2024-01-15'
            ],
            [
                'id' => 4,
                'type' => 'ASN',
                'value' => 'AS64513',
                'description' => 'DataNet Autonomous System',
                'organization' => 'DataNet Solutions',
                'status' => 'active',
                'created_at' => '2024-01-15'
            ]
        ];
    }
    
    private function getResource($id)
    {
        $resources = $this->getAllResources();
        foreach ($resources as $resource) {
            if ($resource['id'] == $id) {
                return $resource;
            }
        }
        return null;
    }
    
    private function getOrganizations()
    {
        return [
            ['id' => 1, 'name' => 'TechCorp Inc.'],
            ['id' => 2, 'name' => 'DataNet Solutions'],
            ['id' => 3, 'name' => 'CloudTech Ltd.']
        ];
    }
    
    private function createResource($data)
    {
        // Simulation - à remplacer par une vraie insertion DB
        return true;
    }
    
    private function updateResource($id, $data)
    {
        // Simulation - à remplacer par une vraie mise à jour DB
        return true;
    }
    
    private function deleteResource($id)
    {
        // Simulation - à remplacer par une vraie suppression DB
        return true;
    }
} 