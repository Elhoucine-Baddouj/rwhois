<?php
// $organization_id, $organizations, $error sont disponibles
?>
<h2>Add Network Resource</h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="post">
    <?php if (empty($organization_id)): ?>
        <div class="form-group">
            <label>Organization</label>
            <select name="organization_id" class="form-control" required>
                <option value="">-- Select --</option>
                <?php foreach ($organizations as $org): ?>
                    <option value="<?php echo $org['id']; ?>"><?php echo htmlspecialchars($org['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <input type="hidden" name="organization_id" value="<?php echo $organization_id; ?>">
    <?php endif; ?>
    <div class="form-group">
        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="ASN">ASN</option>
            <option value="IPv4">IPv4</option>
            <option value="IPv6">IPv6</option>
        </select>
    </div>
    <div class="form-group">
        <label>Value</label>
        <input type="text" name="value" class="form-control" required placeholder="Ex: 64512 or 192.0.2.0/24 or 2001:db8::/32">
    </div>
    <div class="form-group">
        <label>Description</label>
        <input type="text" name="description" class="form-control" placeholder="Optional">
    </div>
    <button type="submit" class="btn btn-primary">Add</button>
    <a href="/network_resources<?php echo $organization_id ? '?organization_id=' . $organization_id : ''; ?>" class="btn btn-secondary">Cancel</a>
</form> 