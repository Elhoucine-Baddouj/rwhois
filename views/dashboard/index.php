<?php
$pageTitle = 'Tableau de bord';
$breadcrumbs = [
    ['url' => '/dashboard', 'text' => 'Tableau de bord', 'active' => true]
];
?>

<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-server"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Serveurs RWHOIS</span>
                <span class="info-box-number"><?php echo $stats['servers']; ?></span>
                <span class="info-box-text">
                    <small>Serveurs actifs</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Organisations</span>
                <span class="info-box-number"><?php echo $stats['organizations']; ?></span>
                <span class="info-box-text">
                    <small>Organisations enregistrées</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-network-wired"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Ressources Internet</span>
                <span class="info-box-number"><?php echo $stats['resources']; ?></span>
                <span class="info-box-text">
                    <small>ASN, IPv4, IPv6</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Utilisateurs</span>
                <span class="info-box-number"><?php echo $stats['users']; ?></span>
                <span class="info-box-text">
                    <small>Utilisateurs actifs</small>
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
                    Serveurs Récents
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
                                <th>Nom</th>
                                <th>IP</th>
                                <th>Statut</th>
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
                                        <span class="badge badge-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactif</span>
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
                <a href="/servers" class="btn btn-sm btn-secondary float-left">Voir tous les serveurs</a>
            </div>
        </div>
    </div>
    
    <!-- Organisations récentes -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building mr-1"></i>
                    Organisations Récentes
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
                                <th>Nom</th>
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
                <a href="/organizations" class="btn btn-sm btn-secondary float-left">Voir toutes les organisations</a>
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
                    Actions Rapides
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="/servers/add" class="btn btn-primary btn-block">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter un serveur
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/organizations/add" class="btn btn-success btn-block">
                            <i class="fas fa-building mr-2"></i>
                            Ajouter une organisation
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/resources/add" class="btn btn-warning btn-block">
                            <i class="fas fa-network-wired mr-2"></i>
                            Ajouter une ressource
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/users/add" class="btn btn-info btn-block">
                            <i class="fas fa-user-plus mr-2"></i>
                            Ajouter un utilisateur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques détaillées -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Répartition des Ressources
                </h3>
            </div>
            <div class="card-body">
                <canvas id="resourcesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Statut des Serveurs
                </h3>
            </div>
            <div class="card-body">
                <canvas id="serversChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Graphique des ressources
    var resourcesCtx = document.getElementById('resourcesChart').getContext('2d');
    var resourcesChart = new Chart(resourcesCtx, {
        type: 'doughnut',
        data: {
            labels: ['ASN', 'IPv4', 'IPv6'],
            datasets: [{
                data: [30, 45, 25],
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Graphique des serveurs
    var serversCtx = document.getElementById('serversChart').getContext('2d');
    var serversChart = new Chart(serversCtx, {
        type: 'bar',
        data: {
            labels: ['Actifs', 'Inactifs', 'En maintenance'],
            datasets: [{
                label: 'Nombre de serveurs',
                data: [3, 1, 1],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script> 