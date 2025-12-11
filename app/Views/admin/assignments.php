<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-2 bg-dark sidebar py-4 d-none d-md-block shadow" style="min-height: 100vh;">
            <div class="text-center mb-4">
                <div class="bg-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-shield-lock-fill fs-1 text-dark"></i>
                </div>
                <h6 class="fw-bold text-white mb-0">Administrador</h6>
            </div>
            <hr class="border-secondary">
            <div class="nav flex-column nav-pills gap-2">
                <a href="<?= BASE_URL ?>admin/dashboard" class="nav-link link-light">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="#" class="nav-link active bg-primary">
                    <i class="bi bi-person-fill-gear me-2"></i> Asignar Prácticas
                </a>
            </div>
        </div>

        <div class="col-md-10 p-4" style="background-color: #f1f5f9;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Asignación de Prácticas</h2>
                    <p class="text-muted">Vincula estudiantes con empresas y supervisores.</p>
                </div>
                <a href="<?= BASE_URL ?>admin/dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-pen"></i> Nueva Asignación</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            <form action="<?= BASE_URL ?>admin/store_assignment" method="POST">
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-primary">1. Seleccionar Estudiante</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                            <select name="student_id" class="form-select" required>
                                                <option value="" selected disabled>-- Elegir Alumno --</option>
                                                <?php foreach($estudiantes as $e): ?>
                                                    <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?> (<?= $e['email'] ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-text">Solo aparecen usuarios con rol 'Estudiante'.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-success">2. Seleccionar Empresa</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                            <select name="company_id" class="form-select" required>
                                                <option value="" selected disabled>-- Elegir Institución --</option>
                                                <?php foreach($empresas as $c): ?>
                                                    <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-text">Empresas con convenio vigente.</div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-info">3. Profesor Supervisor</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-mortarboard"></i></span>
                                            <select name="teacher_id" class="form-select" required>
                                                <option value="" selected disabled>-- Elegir Docente --</option>
                                                <?php foreach($profesores as $p): ?>
                                                    <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">4. Cargo / Puesto</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-briefcase"></i></span>
                                            <input type="text" name="puesto" class="form-control" placeholder="Ej. Desarrollador Web Junior" required>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success py-2 fw-bold shadow-sm">
                                        <i class="bi bi-check-circle-fill me-2"></i> Confirmar y Asignar Práctica
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-4 d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                        <div>
                            <strong>Nota:</strong> Al guardar, el estudiante verá la empresa en su Dashboard y podrá empezar a subir informes. El profesor recibirá la notificación para supervisar.
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>