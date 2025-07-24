<?php
$pageTitle = 'Modifier un utilisateur';
$breadcrumbs = [
    ['url' => '/users', 'text' => 'Utilisateurs'],
    ['url' => '/users/edit', 'text' => 'Modifier', 'active' => true]
];
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Modifier un utilisateur</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="full_name">Nom complet</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select class="form-control" id="role" name="role" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['id']; ?>" <?php if (($user['role'] ?? '') === $role['id']) echo 'selected'; ?>>
                                <?php echo $role['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="organization_id">Organisation</label>
                    <select class="form-control" id="organization_id" name="organization_id" required>
                        <option value="">Sélectionner une organisation</option>
                        <?php foreach ($organizations as $org): ?>
                            <option value="<?php echo $org['id']; ?>" <?php if (($user['organization_id'] ?? '') == $org['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($org['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="active" <?php if (!isset($user['status']) || $user['status'] === 'active') echo 'checked'; ?>>
                    <label class="form-check-label" for="status">Actif</label>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="/users" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div> 