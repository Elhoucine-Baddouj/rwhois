<?php
// $resources, $organization_id, $organization_name, $general_view sont fournis
?>
<h2>Network Resources<?php if (!empty($organization_name)) echo ' for organization: <span style=\'color:#007bff\'>' . htmlspecialchars($organization_name) . '</span>'; ?></h2>
<a href="/network_resources/add<?php echo $organization_id ? '?organization_id=' . $organization_id : ''; ?>" class="btn btn-success mb-3">Add Resource</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <?php if (!empty($general_view)): ?><th>Organization</th><?php endif; ?>
            <th>Type</th>
            <th>Value</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resources as $res): ?>
        <tr>
            <?php if (!empty($general_view)): ?><td><?php echo htmlspecialchars($res['organization_name'] ?? ''); ?></td><?php endif; ?>
            <td><?php echo htmlspecialchars($res['type']); ?></td>
            <td><?php echo htmlspecialchars($res['value']); ?></td>
            <td><?php echo htmlspecialchars($res['description']); ?></td>
            <td>
                <a href="/network_resources/edit?id=<?php echo $res['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="/network_resources/delete?id=<?php echo $res['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this resource?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 