<?php
$pageTitle = 'Dashboard';
$breadcrumbs = [
    ['url' => '/dashboard', 'text' => 'Dashboard', 'active' => true]
];
?>

<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-server"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">RWHOIS Servers</span>
                <span class="info-box-number"><?php echo $stats['servers']; ?></span>
                <span class="info-box-text">
                    <small>Active servers</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Organizations</span>
                <span class="info-box-number"><?php echo $stats['organizations']; ?></span>
                <span class="info-box-text">
                    <small>Registered organizations</small>
                </span>
            </div>
        </div>
    </div>
    

    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number"><?php echo $stats['users']; ?></span>
                <span class="info-box-text">
                    <small>Active users</small>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Serveurs récents -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-server mr-1"></i>
                    Recent Servers
                </h3>
                <div class="card-tools">
                    <a href="/servers" class="btn btn-tool">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>IP</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentServers as $server): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($server['name']); ?></td>
                                <td><?php echo htmlspecialchars($server['ip']); ?></td>
                                <td>
                                    <?php if ($server['status'] === 'active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/servers/edit?id=<?php echo $server['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="/servers" class="btn btn-sm btn-secondary float-left">View all servers</a>
            </div>
        </div>
    </div>
    
    <!-- Organisations récentes -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building mr-1"></i>
                    Recent Organizations
                </h3>
                <div class="card-tools">
                    <a href="/organizations/add" class="btn btn-tool">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrganizations as $org): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($org['name']); ?></td>
                                <td>
                                    <span class="badge badge-info"><?php echo htmlspecialchars($org['type']); ?></span>
                                </td>
                                <td>
                                    <a href="/organizations/edit?id=<?php echo $org['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="/organizations" class="btn btn-sm btn-secondary float-left">View all organizations</a>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Suppression du bouton Ajouter un serveur -->
                    <div class="col-md-3">
                        <a href="/organizations/add" class="btn btn-success btn-block">
                            <i class="fas fa-building mr-2"></i>
                            Add Organization
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="/users/add" class="btn btn-info btn-block">
                            <i class="fas fa-user-plus mr-2"></i>
                            Add User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 