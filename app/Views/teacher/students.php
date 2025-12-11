<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../app/Views/layouts/teacher_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f8f9fa;">
            <h2 class="fw-bold text-dark mb-4">Mis Alumnos Asignados</h2>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Estudiante</th>
                                    <th>Empresa / Puesto</th>
                                    <th>Contacto</th>
                                    <th>Fecha Inicio</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($misAlumnos)): ?>
                                    <?php foreach($misAlumnos as $alumno): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= !empty($alumno['foto_perfil']) ? BASE_URL.$alumno['foto_perfil'] : 'https://ui-avatars.com/api/?name='.$alumno['nombres'].'&background=random' ?>" 
                                                         class="rounded-circle me-3 border" width="45" height="45" style="object-fit:cover;">
                                                    <div>
                                                        <div class="fw-bold"><?= $alumno['nombres'] . ' ' . $alumno['apellidos'] ?></div>
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill" style="font-size:0.75rem">En Prácticas</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= $alumno['empresa'] ?></div>
                                                <small class="text-muted"><?= $alumno['puesto'] ?></small>
                                            </td>
                                            <td>
                                                <a href="mailto:<?= $alumno['email'] ?>" class="text-decoration-none text-secondary">
                                                    <i class="bi bi-envelope me-1"></i> <?= $alumno['email'] ?>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="text-muted"><i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y', strtotime($alumno['fecha_inicio'])) ?></span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-primary" title="Ver Historial">
                                                    <i class="bi bi-journal-text"></i> Historial
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" width="64" class="opacity-25 mb-3">
                                            <p class="mb-0">No tienes alumnos asignados actualmente.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/Views/layouts/footer.php'; ?>