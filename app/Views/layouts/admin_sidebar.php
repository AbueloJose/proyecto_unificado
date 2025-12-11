<div class="col-md-2 bg-dark text-white sidebar py-4 d-none d-md-block" style="min-height: 100vh; position: sticky; top: 0;">
    
    <div class="text-center mb-4">
        <div class="bg-white text-dark rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h6 class="fw-bold mb-0">Administrador</h6>
        <small class="text-white-50">Panel de Control</small>
    </div>

    <hr class="border-secondary my-3">

    <div class="nav flex-column nav-pills gap-2">
        
        <a href="<?= BASE_URL ?>admin/dashboard" 
           class="nav-link d-flex align-items-center gap-2 <?= (isset($page) && $page == 'dashboard') ? 'active bg-primary text-white' : 'text-white-50' ?>">
            <i class="bi bi-speedometer2"></i> 
            <span>Dashboard</span>
        </a>
        
        <a href="<?= BASE_URL ?>admin/companies" 
           class="nav-link d-flex align-items-center gap-2 <?= (isset($page) && $page == 'companies') ? 'active bg-primary text-white' : 'text-white-50' ?>">
            <i class="bi bi-building"></i> 
            <span>Empresas</span>
        </a>
        
        <a href="<?= BASE_URL ?>admin/vacancies" 
           class="nav-link d-flex align-items-center gap-2 <?= (isset($page) && $page == 'vacancies') ? 'active bg-primary text-white' : 'text-white-50' ?>">
            <i class="bi bi-briefcase"></i> 
            <span>Vacantes</span>
        </a>
        
        <a href="<?= BASE_URL ?>admin/users" 
           class="nav-link d-flex align-items-center gap-2 <?= (isset($page) && $page == 'users') ? 'active bg-primary text-white' : 'text-white-50' ?>">
            <i class="bi bi-people"></i> 
            <span>Usuarios</span>
        </a>
        
        <a href="<?= BASE_URL ?>admin/validate_practices" 
           class="nav-link d-flex align-items-center gap-2 <?= (isset($page) && $page == 'validate_practices') ? 'active bg-primary text-white' : 'text-white-50' ?>">
            <i class="bi bi-check-circle"></i> 
            <span>Validar Prácticas</span>
        </a>
        
        <a href="<?= BASE_URL ?>admin/ai_reports" 
           class="nav-link d-flex align-items-center gap-2 <?= (isset($page) && $page == 'ai_reports') ? 'active bg-primary text-white' : 'text-white-50' ?>">
            <i class="bi bi-robot"></i> 
            <span>Reportes IA</span>
        </a>
    </div>
    
    <div class="mt-auto pt-5 text-center">
        <a href="<?= BASE_URL ?>auth/logout" class="btn btn-outline-danger w-100">
            <i class="bi bi-box-arrow-left me-2"></i> Salir
        </a>
    </div>
</div>

<style>
    .nav-link.text-white-50:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff !important;
    }
    .nav-link.active {
        background-color: #0d6efd !important; /* Azul Bootstrap */
        color: white !important;
        font-weight: 500;
    }
    /* Arreglo visual para iconos */
    .nav-link i { font-size: 1.1rem; }
</style>