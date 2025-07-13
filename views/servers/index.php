<?php
$pageTitle = 'Gestion des Serveurs RWHOIS';
$breadcrumbs = [
    ['url' => '/servers', 'text' => 'Serveurs', 'active' => true]
];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-server mr-1"></i>
                    Liste des Serveurs RWHOIS
                </h3>
                <div class="card-tools">
                    <a href="/servers/add" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Ajouter un serveur
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Adresse IP</th>
                                <th>Port</th>
                                <th>Organisation</th>
                                <th>Statut</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($servers as $server): ?>
                            <tr>
                                <td><?php echo $server['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($server['name']); ?></strong>
                                </td>
                                <td>
                                    <code><?php echo htmlspecialchars($server['ip']); ?></code>
                                </td>
                                <td><?php echo $server['port']; ?></td>
                                <td><?php echo htmlspecialchars($server['organization'] ?? '(Aucune)'); ?></td>
                                <td>
                                    <?php if ($server['status'] === 'active'): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>Actif
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times mr-1"></i>Inactif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($server['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-info" data-toggle="tooltip" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <?php if ($server['status'] === 'active'): ?>
                                            <button type="button" class="btn btn-warning btn-uninstall" data-id="<?php echo $server['id']; ?>" data-toggle="tooltip" title="Désinstaller">
                                                <i class="fas fa-stop"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-success btn-install" data-id="<?php echo $server['id']; ?>" data-toggle="tooltip" title="Installer">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <a href="/servers/edit?id=<?php echo $server['id']; ?>" class="btn btn-primary" data-toggle="tooltip" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form method="POST" action="/servers/delete" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer ce serveur ?');">
                                            <input type="hidden" name="id" value="<?php echo $server['id']; ?>">
                                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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

<!-- Statistiques des serveurs -->
<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Serveurs Actifs</span>
                <span class="info-box-number">
                    <?php 
                    $activeCount = 0;
                    foreach ($servers as $server) {
                        if ($server['status'] === 'active') $activeCount++;
                    }
                    echo $activeCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Serveurs Inactifs</span>
                <span class="info-box-number">
                    <?php 
                    $inactiveCount = 0;
                    foreach ($servers as $server) {
                        if ($server['status'] !== 'active') $inactiveCount++;
                    }
                    echo $inactiveCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Organisations</span>
                <span class="info-box-number">
                    <?php 
                    $orgs = array_unique(array_column($servers, 'organization'));
                    echo count($orgs);
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number"><?php echo count($servers); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Actions en lot -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks mr-1"></i>
                    Actions en Lot
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success btn-block" id="installAll">
                            <i class="fas fa-download mr-2"></i>
                            Installer tous les serveurs
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-warning btn-block" id="uninstallAll">
                            <i class="fas fa-stop mr-2"></i>
                            Désinstaller tous les serveurs
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-info btn-block" id="checkStatus">
                            <i class="fas fa-sync mr-2"></i>
                            Vérifier le statut
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-secondary btn-block" id="exportData">
                            <i class="fas fa-download mr-2"></i>
                            Exporter les données
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Actions en lot
    $('#installAll').click(function() {
        if (confirm('Voulez-vous installer tous les serveurs inactifs ?')) {
            // Logique pour installer tous les serveurs
            alert('Installation en cours...');
        }
    });
    
    $('#uninstallAll').click(function() {
        if (confirm('Voulez-vous désinstaller tous les serveurs actifs ?')) {
            // Logique pour désinstaller tous les serveurs
            alert('Désinstallation en cours...');
        }
    });
    
    $('#checkStatus').click(function() {
        // Logique pour vérifier le statut de tous les serveurs
        alert('Vérification du statut en cours...');
    });
    
    $('#exportData').click(function() {
        // Logique pour exporter les données
        window.open('/api/servers', '_blank');
    });
});
</script> 