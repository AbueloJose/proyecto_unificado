<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/student_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f8f9fa;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Bolsa de Prácticas</h2>
                    <p class="text-muted small">Postula a las ofertas disponibles para iniciar tus prácticas.</p>
                </div>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'postulado'): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-4 border-success">
                    <i class="bi bi-check-circle-fill me-2"></i> <strong>¡Postulación Enviada!</strong> El administrador revisará tu solicitud pronto.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error']) && $_GET['error'] == 'ya_postulaste'): ?>
                <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 border-start border-4 border-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Ya tienes una postulación en curso. Espera a que sea validada.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php if(!empty($ofertas)): ?>
                    <?php foreach($ofertas as $oferta): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                                <div class="card-body p-4">
                                    
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                                            <?= $oferta['area'] ?>
                                        </span>
                                        <small class="text-muted fw-semibold">
                                            <i class="bi bi-clock me-1"></i> <?= date('d/m/Y', strtotime($oferta['created_at'])) ?>
                                        </small>
                                    </div>
                                    
                                    <h4 class="fw-bold text-dark mb-1"><?= $oferta['titulo'] ?></h4>
                                    <h6 class="text-muted mb-3 d-flex align-items-center">
                                        <i class="bi bi-building me-2"></i> <?= $oferta['empresa'] ?> 
                                        <?php if(!empty($oferta['rubro'])): ?>
                                            <span class="badge bg-light text-secondary border ms-2 fw-normal"><?= $oferta['rubro'] ?></span>
                                        <?php endif; ?>
                                    </h6>
                                    
                                    <p class="text-secondary small mb-4" style="line-height: 1.6;">
                                        <?= nl2br($oferta['descripcion']) ?>
                                    </p>
                                    
                                    <div class="bg-light p-3 rounded-3 mb-4 border border-light">
                                        <small class="d-block text-muted fw-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Docente Supervisor</small>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white p-2 rounded-circle border me-2 text-primary shadow-sm">
                                                <i class="bi bi-person-video3"></i>
                                            </div>
                                            <div>
                                                <?php if(!empty($oferta['doc_nom'])): ?>
                                                    <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">Prof. <?= $oferta['doc_nom'] . ' ' . $oferta['doc_ape'] ?></span>
                                                    <small class="text-success fw-bold" style="font-size: 0.75rem;">● Disponible</small>
                                                <?php else: ?>
                                                    <span class="text-muted small">Por asignar</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <span class="fw-bold text-dark">
                                            <i class="bi bi-people-fill me-1 text-secondary"></i> <?= $oferta['cupos'] ?> Vacantes
                                        </span>
                                        
                                        <?php if(isset($postulacionActiva) && $postulacionActiva): ?>
                                            <button class="btn btn-secondary px-4 fw-bold opacity-75" disabled>
                                                Solicitud Pendiente
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= BASE_URL ?>student/postulate?id=<?= $oferta['id'] ?>" 
                                               class="btn btn-primary px-4 fw-bold shadow-sm"
                                               onclick="return confirm('¿Estás seguro de postular a esta vacante?');">
                                                Postular Ahora <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded-3 shadow-sm border">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="80" class="mb-3 opacity-25">
                            <h5 class="text-muted fw-bold">No hay ofertas disponibles</h5>
                            <p class="text-muted small mb-0">Vuelve más tarde o contacta con administración.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efecto hover para las tarjetas */
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        border-color: #0d6efd !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>

<?php include '../app/Views/layouts/footer.php'; ?>