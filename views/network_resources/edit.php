<?php
// $resource, $error sont disponibles
?>
<h2>Edit Network Resource</h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="post">
    <input type="hidden" name="organization_id" value="<?php echo $resource['organization_id']; ?>">
    <div class="form-group">
        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="ASN" <?php if ($resource['type']==='ASN') echo 'selected'; ?>>ASN</option>
            <option value="IPv4" <?php if ($resource['type']==='IPv4') echo 'selected'; ?>>IPv4</option>
            <option value="IPv6" <?php if ($resource['type']==='IPv6') echo 'selected'; ?>>IPv6</option>
        </select>
    </div>
    <div class="form-group">
        <label>Value</label>
        <input type="text" name="value" class="form-control" required value="<?php echo htmlspecialchars($resource['value']); ?>">
    </div>
    <div class="form-group">
        <label>Description</label>
        <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($resource['description']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="/network_resources?organization_id=<?php echo $resource['organization_id']; ?>" class="btn btn-secondary">Cancel</a>
</form> 