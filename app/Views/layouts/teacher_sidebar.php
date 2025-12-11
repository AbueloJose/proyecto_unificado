<div class="col-md-2 bg-white sidebar py-4 d-none d-md-block shadow-sm" style="min-height: 100vh;">
    <div class="text-center mb-4">
        <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['user_name'] ?>&background=ffc107&color=000" 
             class="rounded-circle mb-3 shadow-sm border border-3 border-light" width="80">
        <h6 class="fw-bold mb-0 text-dark">Docente</h6>
        <small class="text-muted">Supervisor</small>
    </div>

    <hr class="text-muted">

    <div class="nav flex-column nav-pills gap-2">
        <a href="<?= BASE_URL ?>teacher/dashboard" 
           class="nav-link <?= (isset($page) && $page == 'dashboard') ? 'active bg-warning text-dark fw-bold' : 'link-dark' ?>">
            <i class="bi bi-grid-1x2 me-2"></i> Dashboard
        </a>
        
        <a href="<?= BASE_URL ?>teacher/students" 
           class="nav-link <?= (isset($page) && $page == 'students') ? 'active bg-warning text-dark fw-bold' : 'link-dark' ?>">
            <i class="bi bi-people me-2"></i> Mis Alumnos
        </a>
        
        <a href="<?= BASE_URL ?>teacher/reviews" 
           class="nav-link <?= (isset($page) && $page == 'reviews') ? 'active bg-warning text-dark fw-bold' : 'link-dark' ?> d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-check me-2"></i> Revisiones</span>
            <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                <span class="badge bg-danger rounded-pill"><?= $pendingCount ?></span>
            <?php endif; ?>
        </a>
    </div>
    
    <div class="mt-auto pt-5 text-center">
        <a href="<?= BASE_URL ?>auth/logout" class="btn btn-outline-danger btn-sm w-75">
            <i class="bi bi-box-arrow-left me-1"></i> Cerrar Sesión
        </a>
    </div>
</div>