<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../app/Views/layouts/teacher_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f8f9fa;">
            <h2 class="fw-bold text-dark mb-4">Panel del Supervisor</h2>
            
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Bienvenido, Profe. <?= $_SESSION['user_name'] ?></strong>
                    <p class="mb-0 small">Recuerda revisar los informes semanales antes del cierre de ciclo.</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Alumnos a Cargo</h6>
                                <h2 class="fw-bold mb-0"><?= $totalAlumnos ?? 0 ?></h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded text-warning">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Informes por Revisar</h6>
                                <h2 class="fw-bold mb-0"><?= $pendingCount ?? 0 ?></h2>
                            </div>
                            <div class="bg-danger bg-opacity-10 p-3 rounded text-danger">
                                <i class="bi bi-file-earmark-text-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Gestión Académica</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Seleccione una acción para comenzar:</p>
                            <div class="d-flex gap-3">
                                <a href="<?= BASE_URL ?>teacher/reviews" class="btn btn-primary">
                                    <i class="bi bi-check2-square me-2"></i> Calificar Informes
                                </a>
                                <a href="<?= BASE_URL ?>teacher/students" class="btn btn-outline-dark">
                                    <i class="bi bi-list-ul me-2"></i> Ver Lista de Alumnos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../app/Views/layouts/footer.php'; ?>