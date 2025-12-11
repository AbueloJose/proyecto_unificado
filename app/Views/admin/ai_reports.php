<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f4f6f9;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Analítica de Rendimiento IA</h2>
                <button class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-2"></i> Exportar</button>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 fw-bold border-0">Promedio Global de Competencias</div>
                        <div class="card-body">
                            <div id="chart-global"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                        <div class="card-header bg-white py-3 fw-bold text-danger border-0">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Alumnos en Riesgo
                        </div>
                        <div class="card-body">
                            <?php if(!empty($alumnosRiesgo)): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach($alumnosRiesgo as $al): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div><div class="fw-bold"><?= $al['nombres'] ?></div><small class="text-muted"><?= $al['empresa'] ?></small></div>
                                            <span class="badge bg-danger"><?= number_format($al['promedio'], 1) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center text-success py-5">
                                    <i class="bi bi-check-circle-fill fs-1 mb-2"></i>
                                    <p class="mb-0">Todo en orden. No hay alumnos en riesgo.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var scores = [
        <?= $promedios['tec']??0 ?>, <?= $promedios['com']??0 ?>, 
        <?= $promedios['eq']??0 ?>, <?= $promedios['res']??0 ?>, <?= $promedios['pun']??0 ?>
    ];
    var options = {
        series: [{ name: 'Promedio Global', data: scores }],
        chart: { height: 300, type: 'radar', toolbar: { show: false } },
        labels: ['Técnico', 'Comunicación', 'Equipo', 'Resolución', 'Puntualidad'],
        yaxis: { max: 20, min: 0, show: false },
        fill: { opacity: 0.2, colors: ['#0d6efd'] },
        stroke: { width: 2, colors: ['#0d6efd'] }
    };
    new ApexCharts(document.querySelector("#chart-global"), options).render();
</script>

<?php include '../app/Views/layouts/footer.php'; ?>