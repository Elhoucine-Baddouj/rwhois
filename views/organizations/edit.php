<?php
$pageTitle = 'Modifier une organisation';
$breadcrumbs = [
    ['url' => '/organizations', 'text' => 'Organisations'],
    ['url' => '/organizations/edit', 'text' => 'Modifier', 'active' => true]
];
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Modifier une organisation</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="name">Nom de l'organisation</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($organization['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="ISP" <?php if (($organization['type'] ?? '') === 'ISP') echo 'selected'; ?>>ISP</option>
                        <option value="Hosting Provider" <?php if (($organization['type'] ?? '') === 'Hosting Provider') echo 'selected'; ?>>Hosting Provider</option>
                        <option value="Cloud Provider" <?php if (($organization['type'] ?? '') === 'Cloud Provider') echo 'selected'; ?>>Cloud Provider</option>
                        <option value="Enterprise" <?php if (($organization['type'] ?? '') === 'Enterprise') echo 'selected'; ?>>Enterprise</option>
                        <option value="Other" <?php if (($organization['type'] ?? '') === 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contact_email">Email de contact</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($organization['contact_email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact_phone">Téléphone</label>
                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($organization['contact_phone'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($organization['address'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($organization['description'] ?? ''); ?></textarea>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php if (!isset($organization['is_active']) || $organization['is_active']) echo 'checked'; ?>>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="/organizations" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div> 