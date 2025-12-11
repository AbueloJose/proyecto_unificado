<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f4f6f9;">
            <h2 class="fw-bold text-dark mb-4">Gestión de Usuarios</h2>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Usuario</th>
                                    <th>Rol</th>
                                    <th>Email / Código</th>
                                    <th>Biometría</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($usuarios)): ?>
                                    <?php foreach($usuarios as $u): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= !empty($u['foto_perfil']) ? BASE_URL.$u['foto_perfil'] : 'https://ui-avatars.com/api/?name='.$u['nombres'].'&background=random' ?>" 
                                                     class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold"><?= $u['nombres'] . ' ' . ($u['apellidos'] ?? '') ?></div>
                                                    <small class="text-muted">ID: <?= $u['id'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                            $rol = $u['rol'];
                                            if($rol=='admin') echo '<span class="badge bg-danger">Admin</span>';
                                            elseif($rol=='docente') echo '<span class="badge bg-warning text-dark">Docente</span>';
                                            else echo '<span class="badge bg-info text-dark">Estudiante</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold"><?= $u['email'] ?></div>
                                            <div class="small text-muted"><?= $u['codigo'] ?? '-' ?></div>
                                        </td>
                                        <td>
                                            <?php if(!empty($u['face_descriptor'])): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i> Registrado</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border">Pendiente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($u['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="<?= BASE_URL ?>admin/toggle_user_status?id=<?= $u['id'] ?>" 
                                               class="btn btn-sm btn-outline-dark" 
                                               title="Cambiar Estado">
                                                <i class="bi bi-power"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                                            No hay usuarios registrados en el sistema.
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