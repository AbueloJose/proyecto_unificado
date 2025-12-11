<div class="col-md-2 bg-white sidebar py-4 d-none d-md-block shadow-sm" style="min-height: 100vh; position: sticky; top: 0;">
    
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block">
            <img src="<?= !empty($_SESSION['user_photo']) ? BASE_URL . $_SESSION['user_photo'] : 'https://ui-avatars.com/api/?name='.$_SESSION['user_name'].'&background=0d6efd&color=fff' ?>" 
                 class="rounded-circle mb-2 shadow-sm border border-3 border-light" 
                 width="80" height="80" style="object-fit: cover;">
            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-light rounded-circle" style="transform: translate(-5px, -5px);"></span>
        </div>
        <h6 class="fw-bold mb-0 text-dark text-truncate px-3"><?= $_SESSION['user_name'] ?></h6>
        <small class="text-muted">Estudiante</small>
    </div>

    <hr class="text-muted opacity-25 my-3">

    <div class="nav flex-column nav-pills gap-2 px-2">
        
        <a href="<?= BASE_URL ?>student/dashboard" 
           class="nav-link d-flex align-items-center gap-3 <?= (isset($page) && $page == 'dashboard') ? 'active bg-primary shadow-sm' : 'link-dark hover-bg' ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="<?= BASE_URL ?>student/opportunities" 
           class="nav-link d-flex align-items-center gap-3 <?= (isset($page) && $page == 'opportunities') ? 'active bg-primary shadow-sm' : 'link-dark hover-bg' ?>">
            <i class="bi bi-briefcase-fill"></i>
            <span>Bolsa de Prácticas</span>
        </a>

        <a href="<?= BASE_URL ?>student/profile" 
           class="nav-link d-flex align-items-center gap-3 <?= (isset($page) && $page == 'profile') ? 'active bg-primary shadow-sm' : 'link-dark hover-bg' ?>">
            <i class="bi bi-person-badge-fill"></i>
            <span>Mi Perfil / FaceID</span>
        </a>
        
        <a href="<?= BASE_URL ?>student/reports" 
           class="nav-link d-flex align-items-center gap-3 <?= (isset($page) && $page == 'reports') ? 'active bg-primary shadow-sm' : 'link-dark hover-bg' ?>">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Mis Informes</span>
        </a>
    </div>
    
    <div class="mt-auto pt-5 text-center px-3">
        <a href="<?= BASE_URL ?>auth/logout" class="btn btn-outline-danger w-100 border-0 bg-danger bg-opacity-10 text-danger fw-bold">
            <i class="bi bi-box-arrow-left me-2"></i> Cerrar Sesión
        </a>
    </div>
</div>

<style>
    .hover-bg:hover {
        background-color: #f8f9fa;
        color: #0d6efd !important;
        transform: translateX(5px);
        transition: all 0.2s ease;
    }
    .nav-link { transition: all 0.2s; }
</style>