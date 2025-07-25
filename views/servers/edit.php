<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Server</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Server</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="/servers/edit?id=<?php echo $server['id']; ?>">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($server['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="organization_id">Organization</label>
                            <select name="organization_id" id="organization_id" class="form-control" required>
                                <?php foreach ($organizations as $org): ?>
                                    <option value="<?php echo $org['id']; ?>" <?php if ($org['id'] == $server['organization_id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($org['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="environment">Environment</label>
                            <select name="environment" id="environment" class="form-control" required>
                                <option value="production" <?php if ($server['environment'] == 'production') echo 'selected'; ?>>Production</option>
                                <option value="staging" <?php if ($server['environment'] == 'staging') echo 'selected'; ?>>Staging</option>
                                <option value="development" <?php if ($server['environment'] == 'development') echo 'selected'; ?>>Development</option>
                                <option value="testing" <?php if ($server['environment'] == 'testing') echo 'selected'; ?>>Testing</option>
                                <option value="backup" <?php if ($server['environment'] == 'backup') echo 'selected'; ?>>Backup</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"><?php echo htmlspecialchars($server['description']); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="/servers" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 