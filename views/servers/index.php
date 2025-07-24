<?php
$pageTitle = 'RWHOIS Server Management';
$breadcrumbs = [
    ['url' => '/servers', 'text' => 'Servers', 'active' => true]
];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-server mr-1"></i>
                    RWHOIS Server List
                </h3>
                <!-- Suppression du bouton Ajouter un serveur -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Server IP</th>
                                <th>Instance</th>
                                <th>Port</th>
                                <th>Organization</th>
                                <th>Status</th>
                                <th>Environment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($servers as $server): ?>
                            <tr>
                                <td><?php echo $server['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($server['name']); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo htmlspecialchars($server['full_name']); ?></small>
                                </td>
                                <td>
                                    <code><?php echo htmlspecialchars($server['server_ip']); ?></code>
                                </td>
                                <td>
                                    <span class="badge badge-info">Instance <?php echo $server['instance_id']; ?></span>
                                </td>
                                <td><?php echo $server['port']; ?></td>
                                <td><?php echo htmlspecialchars($server['organization_name'] ?? '(None)'); ?></td>
                                <td>
                                    <?php if ($server['status'] === 'active'): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>Active
                                        </span>
                                    <?php elseif ($server['status'] === 'installing'): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-spinner fa-spin mr-1"></i>Installing
                                        </span>
                                    <?php elseif ($server['status'] === 'uninstalling'): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-spinner fa-spin mr-1"></i>Uninstalling
                                        </span>
                                    <?php elseif ($server['status'] === 'maintenance'): ?>
                                        <span class="badge badge-info">
                                            <i class="fas fa-tools mr-1"></i>Maintenance
                                        </span>
                                    <?php elseif ($server['status'] === 'error'): ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Error
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times mr-1"></i>Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $envColors = [
                                        'production' => 'danger',
                                        'staging' => 'warning',
                                        'development' => 'info',
                                        'testing' => 'secondary',
                                        'backup' => 'dark'
                                    ];
                                    $envColor = $envColors[$server['environment']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?php echo $envColor; ?>">
                                        <?php echo ucfirst($server['environment']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <!-- Bouton d'information -->
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="Informations" onclick="showServerInfo(<?php echo $server['id']; ?>)">
                                            <i class="fas fa-info"></i>
                                        </button>
                                        

                                        
                                        <!-- Boutons de contrôle -->
                                        <?php if ($server['status'] === 'active'): ?>
                                            <button type="button" class="btn btn-warning btn-sm btn-control" data-id="<?php echo $server['id']; ?>" data-action="stop" data-toggle="tooltip" title="Disable (Stop)">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        <?php elseif ($server['status'] === 'inactive'): ?>
                                            <button type="button" class="btn btn-success btn-sm btn-control" data-id="<?php echo $server['id']; ?>" data-action="start" data-toggle="tooltip" title="Enable (Start)">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <!-- Bouton de logs -->
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="View logs" onclick="showServerLogs(<?php echo $server['id']; ?>)">
                                            <i class="fas fa-file-alt"></i>
                                        </button>
                                        <!-- Bouton de mise à jour -->
                                        <button type="button" class="btn btn-primary btn-sm btn-update" data-id="<?php echo $server['id']; ?>" data-toggle="tooltip" title="Update server">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
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
                <span class="info-box-text">Active Servers</span>
                <span class="info-box-number" id="activeCount">
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
                <span class="info-box-text">Inactive Servers</span>
                <span class="info-box-number" id="inactiveCount">
                    <?php 
                    $inactiveCount = 0;
                    foreach ($servers as $server) {
                        if ($server['status'] === 'inactive') $inactiveCount++;
                    }
                    echo $inactiveCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">In Error</span>
                <span class="info-box-number">
                    <?php 
                    $errorCount = 0;
                    foreach ($servers as $server) {
                        if ($server['status'] === 'error') $errorCount++;
                    }
                    echo $errorCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Organizations</span>
                <span class="info-box-number">
                    <?php 
                    $orgs = array_unique(array_column($servers, 'organization_name'));
                    echo count($orgs);
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les informations du serveur -->
<div class="modal fade" id="serverInfoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informations du Serveur</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="serverInfoContent">
                <!-- Contenu dynamique -->
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les logs du serveur -->
<div class="modal fade" id="serverLogsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Logs du Serveur</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="serverLogsContent" style="max-height: 500px; overflow-y: auto;"></pre>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('Update handler attached');
    // Initialisation des tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Gestion des boutons de contrôle
    $('.btn-control').click(function() {
        var id = $(this).data('id');
        var action = $(this).data('action');
        var button = $(this);
        
        // Désactiver le bouton pendant l'exécution
        button.prop('disabled', true);
        
        $.get('/servers/control', {id: id, action: action})
            .done(function(response) {
                if (response.success) {
                    toastr.success('Commande ' + action + ' exécutée avec succès');
                    // Attendre un peu puis rafraîchir le statut réel
                    setTimeout(function() {
                        $.get('/servers/refreshStatus', {id: id})
                            .done(function(resp) {
                                if (resp.success) {
                                    var row = button.closest('tr');
                                    var statusCell = row.find('td').eq(6);
                                    var actionsCell = row.find('td').eq(8);
                                    var newStatus = resp.status;
                                    var badge = '';
                                    var btn = '';
                                    if (newStatus === 'active') {
                                        badge = '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Actif</span>';
                                        btn = '<button type="button" class="btn btn-warning btn-sm btn-control" data-id="'+id+'" data-action="stop" data-toggle="tooltip" title="Désactiver (Arrêter)"><i class="fas fa-pause"></i></button>';
                                    } else if (newStatus === 'inactive') {
                                        badge = '<span class="badge badge-secondary"><i class="fas fa-times mr-1"></i>Inactif</span>';
                                        btn = '<button type="button" class="btn btn-success btn-sm btn-control" data-id="'+id+'" data-action="start" data-toggle="tooltip" title="Activer (Démarrer)"><i class="fas fa-play"></i></button>';
                                    } else if (newStatus === 'error') {
                                        badge = '<span class="badge badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i>Erreur</span>';
                                        btn = '';
                                    } else {
                                        badge = '<span class="badge badge-secondary"><i class="fas fa-times mr-1"></i>Inactif</span>';
                                        btn = '<button type="button" class="btn btn-success btn-sm btn-control" data-id="'+id+'" data-action="start" data-toggle="tooltip" title="Activer (Démarrer)"><i class="fas fa-play"></i></button>';
                                    }
                                    statusCell.html(badge);
                                    // Remplacer uniquement le bouton start/stop
                                    var actions = actionsCell.find('.btn-group');
                                    actions.find('.btn-control').remove();
                                    actions.append(btn);
                                    // Réactiver les tooltips et les handlers
                                    $('[data-toggle="tooltip"]').tooltip();
                                    $('.btn-control').off('click').on('click', arguments.callee);
                                }
                            });
                    }, 1200); // délai pour laisser le temps au service de changer d'état
                } else {
                    toastr.error('Erreur lors de l\'exécution de la commande');
                }
            })
            .fail(function() {
                toastr.error('Erreur de communication avec le serveur');
            })
            .always(function() {
                button.prop('disabled', false);
            });
    });
    
    // Gestion des boutons d'installation
    $('.btn-install').click(function() {
        var id = $(this).data('id');
        var button = $(this);
        
        if (confirm('Voulez-vous installer ce serveur RWHOIS ?')) {
            button.prop('disabled', true);
            
            $.get('/servers/install', {id: id})
                .done(function(response) {
                    if (response.success) {
                        toastr.success('Installation démarrée');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        toastr.error('Erreur lors de l\'installation');
                    }
                })
                .fail(function() {
                    toastr.error('Erreur de communication');
                })
                .always(function() {
                    button.prop('disabled', false);
                });
        }
    });
    
    // Gestion des boutons de rafraîchissement du statut
    $('.btn-refresh-status').click(function() {
        var id = $(this).data('id');
        var button = $(this);
        
        // Désactiver le bouton et ajouter une animation de rotation
        button.prop('disabled', true);
        button.find('i').addClass('fa-spin');
        
        $.get('/servers/refreshStatus', {id: id})
            .done(function(response) {
                if (response.success) {
                    toastr.success('Statut mis à jour: ' + response.status);
                    // Recharger la page pour afficher le nouveau statut
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error('Erreur lors de la mise à jour du statut');
                }
            })
            .fail(function() {
                toastr.error('Erreur de communication avec le serveur');
            })
            .always(function() {
                button.prop('disabled', false);
                button.find('i').removeClass('fa-spin');
            });
    });

    // Gestion du bouton Update (version directe)
    $('.btn-update').click(function() {
        alert('Succès !');
    });
    

});

function showServerInfo(serverId) {
    // Charger les informations du serveur via AJAX
    $.get('/servers/info', {id: serverId})
        .done(function(response) {
            $('#serverInfoContent').html(response);
            $('#serverInfoModal').modal('show');
        })
        .fail(function() {
            toastr.error('Erreur lors du chargement des informations');
        });
}

function showServerLogs(serverId) {
    // Charger les logs du serveur via AJAX
    $.get('/servers/logs', {id: serverId})
        .done(function(response) {
            if (response.logs && response.logs.length > 0) {
                $('#serverLogsContent').text(response.logs.join('\n'));
            } else {
                $('#serverLogsContent').text('Aucun log disponible pour ce serveur.');
            }
            $('#serverLogsModal').modal('show');
        })
        .fail(function() {
            $('#serverLogsContent').text('Erreur lors du chargement des logs.');
            $('#serverLogsModal').modal('show');
        });
}
</script> 