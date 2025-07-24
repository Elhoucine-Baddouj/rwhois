<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RWHOIS Dashboard - Server Management</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <!-- Custom CSS -->
    <style>
        .content-wrapper {
            background-color: #f4f6f9;
        }
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        .btn-group-sm > .btn, .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.2rem;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <i class="fas fa-server brand-image img-circle elevation-3" style="opacity: .8"></i>
            <span class="brand-text font-weight-light">RWHOIS Dashboard</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/servers" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'servers') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-server"></i>
                            <p>RWHOIS Servers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/organizations" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'organizations') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Organizations</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/users" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    <li class="nav-header">API</li>
                    <li class="nav-item">
                        <a href="/api/servers" class="nav-link">
                            <i class="nav-icon fas fa-code"></i>
                            <p>API Servers</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?php echo isset($pageTitle) ? $pageTitle : 'RWHOIS Dashboard'; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <?php if (isset($breadcrumbs)): ?>
                                <?php foreach ($breadcrumbs as $breadcrumb): ?>
                                    <li class="breadcrumb-item <?php echo !empty($breadcrumb['active']) ? 'active' : ''; ?>">
                                        <?php if (!empty($breadcrumb['active'])): ?>
                                            <a href="<?php echo $breadcrumb['url']; ?>"><?php echo $breadcrumb['text']; ?></a>
                                        <?php else: ?>
                                            <?php echo $breadcrumb['text']; ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php echo $content; ?>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            Version 1.0.0
        </div>
        <strong>Copyright &copy; 2024 <a href="#">RWHOIS Dashboard</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<!-- Custom JavaScript -->
<script>
$(document).ready(function() {
    // Initialisation des tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Confirmation de suppression
    $('.btn-delete').click(function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    });
    
    // Actions AJAX pour les serveurs
    $('.btn-install').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Installing...');
        
        $.get('/api/install-server?id=' + id, function(response) {
            if (response.success) {
                btn.removeClass('btn-success').addClass('btn-warning')
                   .html('<i class="fas fa-stop"></i> Uninstall')
                   .removeClass('btn-install').addClass('btn-uninstall');
            } else {
                alert('Error during installation');
                btn.prop('disabled', false).html('<i class="fas fa-download"></i> Install');
            }
        });
    });
    
    $('.btn-uninstall').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Uninstalling...');
        
        $.get('/api/uninstall-server?id=' + id, function(response) {
            if (response.success) {
                btn.removeClass('btn-warning').addClass('btn-success')
                   .html('<i class="fas fa-download"></i> Install')
                   .removeClass('btn-uninstall').addClass('btn-install');
            } else {
                alert('Error during uninstallation');
                btn.prop('disabled', false).html('<i class="fas fa-stop"></i> Uninstall');
            }
        });
    });

    // Contrôle start/stop serveur (amélioré, sans reload)
    $('.btn-control').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var action = $(this).data('action');
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.get('/servers/control', {id: id, action: action}, function(response) {
            // Après l'action, rafraîchir le statut du serveur
            $.get('/servers/refreshStatus', {id: id}, function(refresh) {
                // Mettre à jour dynamiquement le bouton et le statut
                var row = btn.closest('tr');
                var statusCell = row.find('td').eq(6); // colonne statut
                var actionsCell = row.find('td').eq(8); // colonne actions
                var wasActive = statusCell.find('.badge-success').length > 0;
                if (refresh.status === 'active') {
                    statusCell.html('<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Active</span>');
                    actionsCell.find('.btn-control').replaceWith('<button type="button" class="btn btn-warning btn-sm btn-control" data-id="'+id+'" data-action="stop" data-toggle="tooltip" title="Disable (Stop)"><i class="fas fa-pause"></i></button>');
                } else {
                    statusCell.html('<span class="badge badge-secondary"><i class="fas fa-times mr-1"></i>Inactive</span>');
                    actionsCell.find('.btn-control').replaceWith('<button type="button" class="btn btn-success btn-sm btn-control" data-id="'+id+'" data-action="start" data-toggle="tooltip" title="Enable (Start)"><i class="fas fa-play"></i></button>');
                }
                // Mettre à jour les compteurs en bas
                var activeCount = parseInt($('#activeCount').text());
                var inactiveCount = parseInt($('#inactiveCount').text());
                if (refresh.status === 'active' && !wasActive) {
                    $('#activeCount').text(activeCount + 1);
                    $('#inactiveCount').text(Math.max(0, inactiveCount - 1));
                } else if (refresh.status !== 'active' && wasActive) {
                    $('#activeCount').text(Math.max(0, activeCount - 1));
                    $('#inactiveCount').text(inactiveCount + 1);
                }
                // Réactiver tooltips et handlers
                $('[data-toggle="tooltip"]').tooltip();
                $('.btn-control').off('click').on('click', arguments.callee);
                btn.prop('disabled', false);
            }, 'json');
        }).fail(function() {
            alert('Error during server action.');
            btn.prop('disabled', false).html(action === 'stop' ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>');
        });
    });

    // Fonction pour afficher les informations du serveur
    window.showServerInfo = function(serverId) {
        // Affiche un loader dans la modale
        $('#serverInfoContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading...</div>');
        $('#serverInfoModal').modal('show');
        // Appel AJAX pour récupérer les infos du serveur
        $.get('/servers/info', {id: serverId}, function(response) {
            // Si la réponse est du HTML, l'afficher directement
            $('#serverInfoContent').html(response);
        }).fail(function() {
            $('#serverInfoContent').html('<div class="alert alert-danger">Error loading server information.</div>');
        });
    };
});
</script>
</body>
</html> 