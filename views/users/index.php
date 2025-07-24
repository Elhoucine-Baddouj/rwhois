<?php
$pageTitle = 'User Management';
$breadcrumbs = [
    ['url' => '/users', 'text' => 'Users', 'active' => true]
];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    User List
                </h3>
                <div class="card-tools">
                    <a href="/users/add" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Add User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Full Name</th>
                                <th>Role</th>
                                <th>Organization</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td>
                                    <?php
                                    $roleColors = [
                                        'admin' => 'danger',
                                        'manager' => 'primary',
                                        'observer' => 'secondary'
                                    ];
                                    $color = $roleColors[$user['role']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?php echo $color; ?>">
                                        <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($user['organization']); ?></td>
                                <td>
                                    <?php if ($user['status'] === 'active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/users/view?id=<?php echo $user['id']; ?>" class="btn btn-info" data-toggle="tooltip" title="View details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/users/edit?id=<?php echo $user['id']; ?>" class="btn btn-primary" data-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/users/delete?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-delete" data-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 