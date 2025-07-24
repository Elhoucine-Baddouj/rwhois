<?php
$pageTitle = 'Détails de l\'utilisateur';
$breadcrumbs = [
    ['url' => '/users', 'text' => 'Utilisateurs'],
    ['url' => '/users/view', 'text' => 'Détails', 'active' => true]
];
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Détails de l'utilisateur</h3>
        </div>
        <div class="card-body">
            <?php if (empty($user)): ?>
                <div class="alert alert-danger">Utilisateur introuvable.</div>
            <?php else: ?>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['username']); ?></li>
                    <li class="list-group-item"><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                    <li class="list-group-item"><strong>Nom complet :</strong> <?php echo htmlspecialchars($user['full_name']); ?></li>
                    <li class="list-group-item"><strong>Rôle :</strong> <?php echo htmlspecialchars(ucfirst($user['role'])); ?></li>
                    <li class="list-group-item"><strong>Organisation :</strong> <?php echo htmlspecialchars($user['organization'] ?? 'Aucune'); ?></li>
                    <li class="list-group-item"><strong>Statut :</strong> <?php echo !empty($user['status']) && $user['status'] === 'active' ? 'Actif' : 'Inactif'; ?></li>
                    <li class="list-group-item"><strong>Date de création :</strong> <?php echo htmlspecialchars($user['created_at']); ?></li>
                    <li class="list-group-item"><strong>Dernière modification :</strong> <?php echo htmlspecialchars($user['updated_at'] ?? 'Non modifié'); ?></li>
                </ul>
                <a href="/users" class="btn btn-secondary mt-3">Retour à la liste</a>
            <?php endif; ?>
        </div>
    </div>
</div> 