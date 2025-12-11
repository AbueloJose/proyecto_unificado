<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../app/Views/layouts/teacher_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f8f9fa;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Revisiones Pendientes</h2>
                <?php if($pendingCount > 0): ?>
                    <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill"><?= $pendingCount ?> informes por revisar</span>
                <?php else: ?>
                    <span class="badge bg-success fs-6 px-3 py-2 rounded-pill"><i class="bi bi-check-lg me-1"></i> Todo al día</span>
                <?php endif; ?>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'calificado'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> Informe calificado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php if(!empty($informes)): ?>
                    <?php foreach($informes as $informe): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-warning text-dark">Semana <?= $informe['semana_numero'] ?></span>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($informe['fecha_subida'])) ?></small>
                                </div>
                                <div class="card-body">
                                    <h6 class="fw-bold mb-1"><?= $informe['nombres'] . ' ' . $informe['apellidos'] ?></h6>
                                    <p class="text-muted small mb-3"><i class="bi bi-building"></i> <?= $informe['empresa'] ?></p>
                                    
                                    <div class="bg-light p-3 rounded mb-3 small text-secondary" style="max-height: 80px; overflow-y: auto;">
                                        <em>"<?= substr($informe['descripcion'], 0, 100) ?>..."</em>
                                    </div>

                                    <div class="d-flex gap-2 mt-auto">
                                        <?php if($informe['archivo_adjunto']): ?>
                                            <a href="<?= BASE_URL ?>uploads/informes/<?= $informe['archivo_adjunto'] ?>" target="_blank" class="btn btn-outline-danger w-100">
                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                            </a>
                                        <?php endif; ?>
                                        
                                        <button type="button" class="btn btn-primary w-100" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalCalificar<?= $informe['reporte_id'] ?>">
                                            Calificar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalCalificar<?= $informe['reporte_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Calificar Informe Semanal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= BASE_URL ?>teacher/grade_report" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="report_id" value="<?= $informe['reporte_id'] ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Decisión</label>
                                                <select name="estado" class="form-select" required>
                                                    <option value="aprobado">✅ Aprobar Informe</option>
                                                    <option value="observado">⚠️ Observar (Requiere corrección)</option>
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Feedback para el estudiante</label>
                                                <textarea name="comentario" class="form-control" rows="4" placeholder="Escribe aquí tus observaciones o felicitaciones..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Calificación</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-50 mb-3">
                        <h5 class="text-muted">No hay revisiones pendientes</h5>
                        <p class="text-muted small">Tus alumnos están al día o no han enviado informes aún.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../app/Views/layouts/footer.php'; ?>