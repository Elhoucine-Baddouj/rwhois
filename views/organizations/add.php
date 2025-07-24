<?php
$pageTitle = 'Add Organization';
$breadcrumbs = [
    ['url' => '/organizations', 'text' => 'Organizations'],
    ['url' => '/organizations/add', 'text' => 'Add', 'active' => true]
];
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Add Organization</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="name">Organization Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="ISP">ISP</option>
                        <option value="Hosting Provider">Hosting Provider</option>
                        <option value="Cloud Provider">Cloud Provider</option>
                        <option value="Enterprise">Enterprise</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contact_email">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                </div>
                <div class="form-group">
                    <label for="contact_phone">Phone</label>
                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
                <a href="/organizations" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div> 