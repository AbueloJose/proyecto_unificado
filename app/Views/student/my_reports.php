<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/student_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f8f9fa;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Informes Semanales</h2>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'enviado'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Informe enviado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-cloud-upload-fill me-2"></i> Nuevo Informe</h6>
                        </div>
                        <div class="card-body">
                            <?php if(isset($internship) && $internship): ?>
                                <form action="<?= BASE_URL ?>student/upload_report" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="internship_id" value="<?= $internship['id'] ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Semana Nº</label>
                                        <select name="semana" class="form-select" required>
                                            <?php for($i=1; $i<=16; $i++): ?>
                                                <option value="<?= $i ?>">Semana <?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Actividad</label>
                                        <textarea name="actividad" class="form-control" rows="4" placeholder="Descripción..." required></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted">Archivo PDF</label>
                                        <input type="file" name="archivo" class="form-control" accept=".pdf" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-send-fill me-2"></i> Enviar
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-exclamation-circle fs-1 mb-2"></i>
                                    <p class="mb-0">No tienes una práctica activa.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i> Historial</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Semana</th>
                                            <th>Fecha</th>
                                            <th>Archivo</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($historial) > 0): ?>
                                            <?php foreach($historial as $reporte): ?>
                                                <tr>
                                                    <td class="ps-4 fw-bold">Semana <?= $reporte['semana_numero'] ?></td>
                                                    <td class="small text-muted"><?= date('d/m/Y', strtotime($reporte['fecha_subida'])) ?></td>
                                                    <td>
                                                        <?php if($reporte['archivo_adjunto']): ?>
                                                            <a href="<?= BASE_URL ?>uploads/informes/<?= $reporte['archivo_adjunto'] ?>" target="_blank" class="text-danger text-decoration-none"><i class="bi bi-file-pdf-fill"></i> PDF</a>
                                                        <?php else: ?> - <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            $est = strtolower($reporte['estado']);
                                                            if(strpos($est, 'aprobado')!==false) echo '<span class="badge bg-success">Aprobado</span>';
                                                            elseif(strpos($est, 'observado')!==false) echo '<span class="badge bg-danger">Observado</span>';
                                                            else echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center py-5 text-muted">Sin informes.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/Views/layouts/footer.php'; ?>