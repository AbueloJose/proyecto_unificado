<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f4f6f9;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Gestión de Empresas</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEmpresa">
                    <i class="bi bi-plus-circle me-2"></i> Nueva Empresa
                </button>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    Empresa registrada correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Empresa</th>
                                    <th>RUC</th>
                                    <th>Rubro</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($empresas)): ?>
                                    <?php foreach($empresas as $empresa): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold"><?= $empresa['nombre'] ?></td>
                                            <td><?= $empresa['ruc'] ?></td>
                                            <td><?= $empresa['rubro'] ?></td>
                                            <td><small><?= $empresa['email_contacto'] ?></small></td>
                                            <td><span class="badge bg-success">Activo</span></td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-dark"><i class="bi bi-pencil"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No hay empresas registradas.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEmpresa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title fw-bold">Registrar Empresa</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form action="<?= BASE_URL ?>admin/store_company" method="POST">
                    <div class="mb-3"><label class="form-label small fw-bold">Razón Social</label><input type="text" name="nombre" class="form-control" required></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label small fw-bold">RUC</label><input type="text" name="ruc" class="form-control" required></div>
                        <div class="col-6 mb-3"><label class="form-label small fw-bold">Rubro</label><input type="text" name="rubro" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label small fw-bold">Dirección</label><input type="text" name="direccion" class="form-control"></div>
                    <div class="mb-3"><label class="form-label small fw-bold">Contacto (Nombre)</label><input type="text" name="contacto" class="form-control"></div>
                    <div class="mb-3"><label class="form-label small fw-bold">Email Contacto</label><input type="email" name="email" class="form-control"></div>
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../app/Views/layouts/footer.php'; ?>