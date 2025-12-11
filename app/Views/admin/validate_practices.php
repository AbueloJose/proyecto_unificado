<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f4f6f9;">
            <h2 class="fw-bold text-dark mb-4">Solicitudes Pendientes</h2>

            <div class="row">
                <?php if(empty($solicitudes)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">No hay solicitudes pendientes</h5>
                    </div>
                <?php else: ?>
                    <?php foreach($solicitudes as $sol): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($sol['fecha_aplicacion'])) ?></small>
                                </div>
                                <h5 class="fw-bold mb-1"><?= $sol['nombres'] . ' ' . $sol['apellidos'] ?></h5>
                                <p class="text-muted small mb-3"><?= $sol['email'] ?></p>
                                
                                <div class="bg-light p-3 rounded mb-3 border">
                                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size:0.7rem">Postula a:</small>
                                    <div class="text-primary fw-bold"><?= $sol['puesto'] ?></div>
                                    <div class="small text-secondary"><i class="bi bi-building"></i> <?= $sol['empresa'] ?></div>
                                </div>

                                <div class="d-flex gap-2">
                                    <form action="<?= BASE_URL ?>admin/approve_application" method="POST" class="w-100">
                                        <input type="hidden" name="app_id" value="<?= $sol['app_id'] ?>">
                                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg"></i> Aprobar</button>
                                    </form>
                                    <a href="<?= BASE_URL ?>admin/reject_application?id=<?= $sol['app_id'] ?>" class="btn btn-outline-danger w-100"><i class="bi bi-x-lg"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../app/Views/layouts/footer.php'; ?>