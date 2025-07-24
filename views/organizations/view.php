<?php
$pageTitle = 'Détails de l\'organisation';
$breadcrumbs = [
    ['url' => '/organizations', 'text' => 'Organisations'],
    ['url' => '/organizations/view', 'text' => 'Détails', 'active' => true]
];
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Détails de l'organisation</h3>
        </div>
        <div class="card-body">
            <?php if (empty($organization)): ?>
                <div class="alert alert-danger">Organisation introuvable.</div>
            <?php else: ?>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nom :</strong> <?php echo htmlspecialchars($organization['name']); ?></li>
                    <li class="list-group-item"><strong>Type :</strong> <?php echo htmlspecialchars($organization['type']); ?></li>
                    <li class="list-group-item"><strong>Email de contact :</strong> <?php echo htmlspecialchars($organization['contact_email']); ?></li>
                    <li class="list-group-item"><strong>Téléphone :</strong> <?php echo htmlspecialchars($organization['contact_phone']); ?></li>
                    <li class="list-group-item"><strong>Adresse :</strong> <?php echo htmlspecialchars($organization['address']); ?></li>
                    <li class="list-group-item"><strong>Description :</strong> <?php echo htmlspecialchars($organization['description']); ?></li>
                    <li class="list-group-item"><strong>Active :</strong> <?php echo !empty($organization['is_active']) ? 'Oui' : 'Non'; ?></li>
                    <li class="list-group-item"><strong>Date de création :</strong> <?php echo htmlspecialchars($organization['created_at']); ?></li>
                    <li class="list-group-item"><strong>Dernière modification :</strong> <?php echo htmlspecialchars($organization['updated_at']); ?></li>
                </ul>
                <a href="/organizations" class="btn btn-secondary mt-3">Retour à la liste</a>
            <?php endif; ?>
        </div>
    </div>
</div> 