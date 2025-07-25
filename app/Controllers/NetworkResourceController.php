<?php

namespace Controllers;

class NetworkResourceController extends BaseController
{
    public function index()
    {
        $organization_id = $this->getQueryParam('organization_id');
        if ($organization_id) {
            $resources = $this->getAllResources($organization_id);
            $organization_name = '';
            $organization = $this->getOrganization($organization_id);
            $organization_name = $organization ? $organization['name'] : '';
            $this->render('network_resources/index', [
                'resources' => $resources,
                'organization_id' => $organization_id,
                'organization_name' => $organization_name
            ]);
        } else {
            // Vue générale : toutes les ressources, avec nom d'organisation
            $resources = $this->getAllResourcesWithOrg();
            $this->render('network_resources/index', [
                'resources' => $resources,
                'organization_id' => null,
                'organization_name' => null,
                'general_view' => true
            ]);
        }
    }

    public function add()
    {
        $organization_id = $this->getQueryParam('organization_id');
        $organizations = [];
        if (!$organization_id) {
            // Charger toutes les organisations pour le select
            $organizations = $this->getAllOrganizations();
        }
        if ($this->isPost()) {
            $data = $this->getPostData();
            if (empty($data['organization_id'])) {
                $this->render('network_resources/add', ['error' => "Veuillez sélectionner une organisation", 'organizations' => $organizations, 'organization_id' => $organization_id]);
                return;
            }
            $result = $this->createResource($data);
            if ($result) {
                // Redirige vers la liste générale si pas d'organisation précisée
                if (!empty($organization_id)) {
                    $this->redirect('/network_resources?organization_id=' . $data['organization_id']);
                } else {
                    $this->redirect('/network_resources');
                }
            } else {
                $this->render('network_resources/add', ['error' => "Erreur lors de l'ajout", 'organizations' => $organizations, 'organization_id' => $organization_id]);
            }
        } else {
            $this->render('network_resources/add', ['organizations' => $organizations, 'organization_id' => $organization_id]);
        }
    }

    public function edit()
    {
        $id = $this->getQueryParam('id');
        $resource = $this->getResource($id);
        if (!$resource) {
            echo "Ressource introuvable";
            return;
        }
        if ($this->isPost()) {
            $data = $this->getPostData();
            $result = $this->updateResource($id, $data);
            if ($result) {
                if (!empty($resource['organization_id'])) {
                    $this->redirect('/network_resources?organization_id=' . $resource['organization_id']);
                } else {
                    $this->redirect('/network_resources');
                }
            } else {
                $this->render('network_resources/edit', ['resource' => $resource, 'error' => "Erreur lors de la modification"]);
            }
        } else {
            $this->render('network_resources/edit', ['resource' => $resource]);
        }
    }

    public function delete()
    {
        $id = $this->getQueryParam('id');
        $resource = $this->getResource($id);
        if (!$resource) {
            echo "Ressource introuvable";
            return;
        }
        $result = $this->deleteResource($id);
        if ($result) {
            if (!empty($resource['organization_id'])) {
                $this->redirect('/network_resources?organization_id=' . $resource['organization_id']);
            } else {
                $this->redirect('/network_resources');
            }
        } else {
            echo "Erreur lors de la suppression";
        }
    }

    // --- Méthodes privées ---
    private function getAllResources($organization_id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT * FROM network_resources WHERE organization_id = ? ORDER BY type, value";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$organization_id]);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getResource($id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT * FROM network_resources WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function createResource($data)
    {
        try {
            $pdo = getDbConnection();
            $sql = "INSERT INTO network_resources (organization_id, type, value, description) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['organization_id'],
                $data['type'],
                $data['value'],
                $data['description'] ?? ''
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    private function updateResource($id, $data)
    {
        try {
            $pdo = getDbConnection();
            $sql = "UPDATE network_resources SET type=?, value=?, description=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['type'],
                $data['value'],
                $data['description'] ?? '',
                $id
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    private function deleteResource($id)
    {
        try {
            $pdo = getDbConnection();
            $sql = "DELETE FROM network_resources WHERE id=?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            return false;
        }
    }

    // Ajoute la méthode pour charger toutes les organisations
    private function getAllOrganizations()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT id, name FROM organizations ORDER BY name";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }

    // Ajoute la méthode getOrganization
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

    private function getAllResourcesWithOrg()
    {
        try {
            $pdo = getDbConnection();
            $sql = "SELECT nr.*, o.name AS organization_name FROM network_resources nr LEFT JOIN organizations o ON nr.organization_id = o.id ORDER BY o.name, nr.type, nr.value";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }
} 