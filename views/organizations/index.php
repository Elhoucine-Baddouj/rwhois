<?php
$pageTitle = 'Organization Management';
$breadcrumbs = [
    ['url' => '/organizations', 'text' => 'Organizations', 'active' => true]
];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building mr-1"></i>
                    Organization List
                </h3>
                <div class="card-tools">
                    <a href="/organizations/add" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Add Organization
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Contact Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizations as $org): ?>
                            <tr>
                                <td><?php echo $org['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($org['name']); ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $typeColors = [
                                        'ISP' => 'primary',
                                        'Hosting Provider' => 'success',
                                        'Cloud Provider' => 'info',
                                        'Enterprise' => 'warning'
                                    ];
                                    $color = $typeColors[$org['type']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?php echo $color; ?>">
                                        <?php echo htmlspecialchars($org['type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($org['contact_email']); ?>">
                                        <?php echo htmlspecialchars($org['contact_email']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($org['contact_phone']); ?></td>
                                <td>
                                    <small><?php echo htmlspecialchars($org['address']); ?></small>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($org['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/organizations/view?id=<?php echo $org['id']; ?>" class="btn btn-info" data-toggle="tooltip" title="View details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/organizations/edit?id=<?php echo $org['id']; ?>" class="btn btn-primary" data-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/organizations/delete?id=<?php echo $org['id']; ?>" class="btn btn-danger btn-delete" data-toggle="tooltip" title="Delete">
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

<!-- Statistiques des organisations -->
<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-network-wired"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ISPs</span>
                <span class="info-box-number">
                    <?php 
                    $ispCount = 0;
                    foreach ($organizations as $org) {
                        if ($org['type'] === 'ISP') $ispCount++;
                    }
                    echo $ispCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-server"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Hosting Providers</span>
                <span class="info-box-number">
                    <?php 
                    $hostingCount = 0;
                    foreach ($organizations as $org) {
                        if ($org['type'] === 'Hosting Provider') $hostingCount++;
                    }
                    echo $hostingCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-cloud"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Cloud Providers</span>
                <span class="info-box-number">
                    <?php 
                    $cloudCount = 0;
                    foreach ($organizations as $org) {
                        if ($org['type'] === 'Cloud Provider') $cloudCount++;
                    }
                    echo $cloudCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Enterprises</span>
                <span class="info-box-number">
                    <?php 
                    $enterpriseCount = 0;
                    foreach ($organizations as $org) {
                        if ($org['type'] === 'Enterprise') $enterpriseCount++;
                    }
                    echo $enterpriseCount;
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Graphique de répartition -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Distribution by Type
                </h3>
            </div>
            <div class="card-body">
                <canvas id="orgTypeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Organizations by Month
                </h3>
            </div>
            <div class="card-body">
                <canvas id="orgTimelineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Graphique de répartition par type
    var orgTypeCtx = document.getElementById('orgTypeChart').getContext('2d');
    var orgTypeChart = new Chart(orgTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['ISP', 'Hosting Provider', 'Cloud Provider', 'Enterprise'],
            datasets: [{
                data: [<?php echo $ispCount; ?>, <?php echo $hostingCount; ?>, <?php echo $cloudCount; ?>, <?php echo $enterpriseCount; ?>],
                backgroundColor: ['#007bff', '#28a745', '#17a2b8', '#ffc107'],
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
    
    // Graphique timeline (simulation)
    var orgTimelineCtx = document.getElementById('orgTimelineChart').getContext('2d');
    var orgTimelineChart = new Chart(orgTimelineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New organizations',
                data: [2, 3, 1, 4, 2, 3],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
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