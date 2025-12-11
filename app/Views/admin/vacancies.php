<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f4f6f9;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Vacantes Disponibles</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVacante">
                    <i class="bi bi-plus-lg me-2"></i> Publicar Vacante
                </button>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> Vacante publicada exitosamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php if(!empty($vacantes)): ?>
                    <?php foreach($vacantes as $vacante): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary"><?= $vacante['area'] ?></span>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($vacante['created_at'])) ?></small>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1"><?= $vacante['titulo'] ?></h5>
                                    <p class="text-muted small fw-semibold mb-2">
                                        <i class="bi bi-building me-1"></i> <?= $vacante['empresa'] ?>
                                    </p>
                                    <p class="text-secondary small mb-3">
                                        <?= substr($vacante['descripcion'], 0, 90) ?>...
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <div class="small">
                                            <span class="fw-bold d-block"><i class="bi bi-people-fill"></i> <?= $vacante['cupos'] ?> Cupos</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary">Ver Detalles</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-light text-center py-5 border">
                            <i class="bi bi-briefcase fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No hay vacantes publicadas</h5>
                            <p class="small text-muted">Crea una vacante para que los alumnos puedan postular.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalVacante" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Publicar Vacante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?= BASE_URL ?>admin/store_vacancy" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Empresa Aliada</label>
                        <select name="company_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($empresas as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= $emp['nombre'] ?> (RUC: <?= $emp['ruc'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Docente Supervisor</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">Seleccione profesor...</option>
                            <?php if(!empty($docentes)): ?>
                                <?php foreach($docentes as $doc): ?>
                                    <option value="<?= $doc['id'] ?>">Prof. <?= $doc['nombres'] . ' ' . $doc['apellidos'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text small">Encargado de revisar los informes.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Título del Puesto</label>
                        <input type="text" name="titulo" class="form-control" required placeholder="Ej: Practicante de TI">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Área</label>
                            <input type="text" name="area" class="form-control" placeholder="Ej: Sistemas">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Cupos</label>
                            <input type="number" name="cupos" class="form-control" value="1" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Requisitos y funciones..."></textarea>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary fw-bold">Publicar Ahora</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>.hover-light:hover { background-color: rgba(255,255,255,0.1); color: white !important; }</style>

<?php include '../app/Views/layouts/footer.php'; ?>