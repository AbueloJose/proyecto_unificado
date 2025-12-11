<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f4f6f9;">
            
            <h2 class="fw-bold text-dark mb-4">Resumen General</h2>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Estudiantes</h6>
                                    <h2 class="fw-bold mb-0"><?= isset($stats['estudiantes']) ? $stats['estudiantes'] : 0 ?></h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-people-fill text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Empresas</h6>
                                    <h2 class="fw-bold mb-0"><?= isset($stats['empresas']) ? $stats['empresas'] : 0 ?></h2>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-building text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">En Prácticas</h6>
                                    <h2 class="fw-bold mb-0"><?= isset($stats['practicas_activas']) ? $stats['practicas_activas'] : 0 ?></h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-person-workspace text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Vacantes Libres</h6>
                                    <h2 class="fw-bold mb-0"><?= isset($stats['vacantes_libres']) ? $stats['vacantes_libres'] : 0 ?></h2>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-briefcase-fill text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="fw-bold mb-0">Gestión Rápida</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-3 d-md-flex">
                                <a href="<?= BASE_URL ?>admin/companies" class="btn btn-outline-dark px-4 py-3">
                                    <i class="bi bi-building-add fs-5 d-block mb-2"></i>
                                    Nueva Empresa
                                </a>
                                <a href="<?= BASE_URL ?>admin/vacancies" class="btn btn-outline-dark px-4 py-3">
                                    <i class="bi bi-file-earmark-plus fs-5 d-block mb-2"></i>
                                    Publicar Vacante
                                </a>
                                <a href="<?= BASE_URL ?>admin/ai_reports" class="btn btn-outline-primary px-4 py-3">
                                    <i class="bi bi-file-earmark-bar-graph fs-5 d-block mb-2"></i>
                                    Ver Reportes IA
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold"><i class="bi bi-robot me-2"></i> Estado del Sistema</h5>
                            <p class="small opacity-75 mt-2">
                                La Inteligencia Artificial está monitoreando el progreso de los estudiantes.
                            </p>
                            <hr class="border-white">
                            <div class="d-flex justify-content-between">
                                <span>IA Chatbot</span>
                                <span class="badge bg-white text-primary">Activo</span>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>FaceID Server</span>
                                <span class="badge bg-white text-primary">Activo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../app/Views/layouts/footer.php'; ?>