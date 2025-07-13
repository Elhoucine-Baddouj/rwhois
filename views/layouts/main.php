<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RWHOIS Dashboard - Gestion des Serveurs</title>

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
                            <p>Tableau de bord</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/servers" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'servers') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-server"></i>
                            <p>Serveurs RWHOIS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/organizations" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'organizations') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Organisations</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/resources" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'resources') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-network-wired"></i>
                            <p>Ressources Internet</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/users" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Utilisateurs</p>
                        </a>
                    </li>
                    <li class="nav-header">API</li>
                    <li class="nav-item">
                        <a href="/api/servers" class="nav-link">
                            <i class="nav-icon fas fa-code"></i>
                            <p>API Serveurs</p>
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
                            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                            <?php if (isset($breadcrumbs)): ?>
                                <?php foreach ($breadcrumbs as $breadcrumb): ?>
                                    <li class="breadcrumb-item <?php echo $breadcrumb['active'] ? 'active' : ''; ?>">
                                        <?php if (!$breadcrumb['active']): ?>
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
        <strong>Copyright &copy; 2024 <a href="#">RWHOIS Dashboard</a>.</strong> Tous droits réservés.
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
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            e.preventDefault();
        }
    });
    
    // Actions AJAX pour les serveurs
    $('.btn-install').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Installation...');
        
        $.get('/api/install-server?id=' + id, function(response) {
            if (response.success) {
                btn.removeClass('btn-success').addClass('btn-warning')
                   .html('<i class="fas fa-stop"></i> Désinstaller')
                   .removeClass('btn-install').addClass('btn-uninstall');
            } else {
                alert('Erreur lors de l\'installation');
                btn.prop('disabled', false).html('<i class="fas fa-download"></i> Installer');
            }
        });
    });
    
    $('.btn-uninstall').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Désinstallation...');
        
        $.get('/api/uninstall-server?id=' + id, function(response) {
            if (response.success) {
                btn.removeClass('btn-warning').addClass('btn-success')
                   .html('<i class="fas fa-download"></i> Installer')
                   .removeClass('btn-uninstall').addClass('btn-install');
            } else {
                alert('Erreur lors de la désinstallation');
                btn.prop('disabled', false).html('<i class="fas fa-stop"></i> Désinstaller');
            }
        });
    });
});
</script>
</body>
</html> 