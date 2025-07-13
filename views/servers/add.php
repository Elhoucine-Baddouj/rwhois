<?php
$pageTitle = isset(
    $server
) ? 'Modifier un Serveur RWHOIS' : 'Ajouter un Serveur RWHOIS';
$breadcrumbs = [
    ['url' => '/servers', 'text' => 'Serveurs', 'active' => false],
    ['url' => isset($server) ? '/servers/edit' : '/servers/add', 'text' => isset($server) ? 'Modifier' : 'Ajouter', 'active' => true]
];
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-1"></i>
                    Nouveau Serveur RWHOIS
                </h3>
            </div>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo isset($server) ? '/servers/edit?id=' . $server['id'] : '/servers/add'; ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nom du serveur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       placeholder="ex: RWHOIS-Server-01" value="<?php echo isset($server) ? htmlspecialchars($server['name']) : ''; ?>">
                                <small class="form-text text-muted">Nom unique pour identifier le serveur</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="organization">Organisation <span class="text-danger">*</span></label>
                                <select class="form-control" id="organization" name="organization_id" required>
                                    <option value="">Sélectionner une organisation</option>
                                    <option value="1" <?php if(isset($server) && $server['organization_id']==1) echo 'selected'; ?>>TechCorp Inc.</option>
                                    <option value="2" <?php if(isset($server) && $server['organization_id']==2) echo 'selected'; ?>>DataNet Solutions</option>
                                    <option value="3" <?php if(isset($server) && $server['organization_id']==3) echo 'selected'; ?>>CloudTech Ltd.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ip">Adresse IP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ip" name="ip" required 
                                       placeholder="ex: 192.168.1.100" value="<?php echo isset($server) ? htmlspecialchars($server['ip']) : ''; ?>">
                                <small class="form-text text-muted">Adresse IPv4 ou IPv6 du serveur</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="port">Port <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="port" name="port" required 
                                       value="<?php echo isset($server) ? htmlspecialchars($server['port']) : '4321'; ?>" min="1" max="65535">
                                <small class="form-text text-muted">Port par défaut RWHOIS: 4321</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Nom d'utilisateur SSH</label>
                                <input type="text" class="form-control" id="username" name="ssh_username" 
                                       placeholder="ex: root" value="<?php echo isset($server) ? htmlspecialchars($server['ssh_username'] ?? '') : ''; ?>">
                                <small class="form-text text-muted">Pour l'installation automatique</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ssh_key">Clé SSH</label>
                                <textarea class="form-control" id="ssh_key" name="ssh_key" rows="3" 
                                          placeholder="Contenu de la clé SSH publique"><?php echo isset($server) ? htmlspecialchars($server['ssh_key'] ?? '') : ''; ?></textarea>
                                <small class="form-text text-muted">Clé publique pour l'authentification SSH</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Description du serveur et de son rôle"><?php echo isset($server) ? htmlspecialchars($server['description'] ?? '') : ''; ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Localisation</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       placeholder="ex: Datacenter Paris" value="<?php echo isset($server) ? htmlspecialchars($server['location'] ?? '') : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="environment">Environnement</label>
                                <select class="form-control" id="environment" name="environment">
                                    <option value="production" <?php if(isset($server) && $server['environment']=='production') echo 'selected'; ?>>Production</option>
                                    <option value="staging" <?php if(isset($server) && $server['environment']=='staging') echo 'selected'; ?>>Staging</option>
                                    <option value="development" <?php if(isset($server) && $server['environment']=='development') echo 'selected'; ?>>Développement</option>
                                    <option value="testing" <?php if(isset($server) && $server['environment']=='testing') echo 'selected'; ?>>Test</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!isset($server)): ?>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="auto_install" name="auto_install" value="1">
                            <label class="custom-control-label" for="auto_install">
                                Installer automatiquement le serveur après création
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                <?php echo isset($server) ? 'Enregistrer les modifications' : 'Créer le serveur'; ?>
                            </button>
                            <a href="/servers" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>
                                Annuler
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="text-muted">* Champs obligatoires</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Informations d'aide -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informations
                </h3>
            </div>
            <div class="card-body">
                <h6>Configuration RWHOIS</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success mr-1"></i>Port par défaut: 4321</li>
                    <li><i class="fas fa-check text-success mr-1"></i>Protocole: TCP</li>
                    <li><i class="fas fa-check text-success mr-1"></i>Authentification SSH recommandée</li>
                </ul>
                
                <hr>
                
                <h6>Installation automatique</h6>
                <p class="text-muted small">
                    L'installation automatique configure le serveur RWHOIS avec les paramètres de base.
                    Assurez-vous que l'accès SSH est configuré correctement.
                </p>
                
                <hr>
                
                <h6>Organisations</h6>
                <p class="text-muted small">
                    Chaque serveur doit être associé à une organisation existante.
                    <a href="/organizations/add">Créer une nouvelle organisation</a> si nécessaire.
                </p>
            </div>
        </div>
        
        <!-- Validation en temps réel -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-check-circle mr-1"></i>
                    Validation
                </h3>
            </div>
            <div class="card-body">
                <div id="validation-status">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Remplissez le formulaire pour valider les informations
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Validation en temps réel
    $('#ip').on('input', function() {
        var ip = $(this).val();
        var isValid = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ip) ||
                     /^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$/.test(ip);
        
        if (ip && !isValid) {
            $(this).addClass('is-invalid');
            $('#validation-status').html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Adresse IP invalide</div>');
        } else {
            $(this).removeClass('is-invalid');
            $('#validation-status').html('<div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i>Adresse IP valide</div>');
        }
    });
    
    // Validation du port
    $('#port').on('input', function() {
        var port = parseInt($(this).val());
        if (port < 1 || port > 65535) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Génération automatique du nom
    $('#organization').change(function() {
        var org = $(this).find('option:selected').text();
        if (org && !$('#name').val()) {
            var orgShort = org.replace(/[^A-Z]/g, '').substring(0, 3);
            var timestamp = new Date().getTime().toString().slice(-4);
            $('#name').val('RWHOIS-' + orgShort + '-' + timestamp);
        }
    });
});
</script> 