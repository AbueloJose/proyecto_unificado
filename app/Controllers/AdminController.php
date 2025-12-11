<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Company.php';
require_once __DIR__ . '/../Config/Database.php';

class AdminController {
    
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // SEGURIDAD: Solo Admin puede entrar
        if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] != 'admin') {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $this->db = (new Database())->getConnection();
    }

    // ======================================================
    // 1. DASHBOARD PRINCIPAL
    // ======================================================
    public function dashboard() {
        $companyModel = new Company();
        $stats = $companyModel->getStats();
        
        // Define la página actual para el sidebar
        $page = 'dashboard'; 
        include '../app/Views/admin/dashboard.php';
    }

    // ======================================================
    // 2. GESTIÓN DE USUARIOS
    // ======================================================
    public function users() {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $page = 'users';
        include '../app/Views/admin/users.php';
    }

    public function toggle_user_status() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $this->db->prepare("UPDATE users SET activo = NOT activo WHERE id = ?");
            $stmt->execute([$id]);
        }
        header('Location: ' . BASE_URL . 'admin/users');
    }

    // ======================================================
    // 3. GESTIÓN DE EMPRESAS
    // ======================================================
    public function companies() {
        $companyModel = new Company();
        $empresas = $companyModel->getAll();
        
        $page = 'companies';
        include '../app/Views/admin/companies.php';
    }

    public function store_company() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $companyModel = new Company();
            $res = $companyModel->create(
                $_POST['nombre'], $_POST['ruc'], $_POST['rubro'], 
                $_POST['direccion'], $_POST['contacto'], $_POST['email']
            );
            if($res) header('Location: ' . BASE_URL . 'admin/companies?msg=creado');
            else header('Location: ' . BASE_URL . 'admin/companies?error=duplicado');
        }
    }

    // ======================================================
    // 4. GESTIÓN DE VACANTES
    // ======================================================
   public function vacancies() {
        $companyModel = new Company();
        $empresas = $companyModel->getAll();
        $vacantes = $companyModel->getVacancies();
        
        // NUEVO: Obtener lista de docentes para el "select"
        $docentes = $this->db->query("SELECT id, nombres, apellidos FROM users WHERE rol = 'docente' AND activo = 1")->fetchAll(PDO::FETCH_ASSOC);
        
        $page = 'vacancies';
        include '../app/Views/admin/vacancies.php';
    }

    public function store_vacancy() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $companyModel = new Company();
            $companyModel->createVacancy(
                $_POST['company_id'],
                $_POST['titulo'],
                $_POST['descripcion'],
                $_POST['area'],
                $_POST['cupos'],
                $_POST['teacher_id'] // <--- NUEVO CAMPO
            );
            header('Location: ' . BASE_URL . 'admin/vacancies?msg=creado');
        }
    }

    // ======================================================
    // 5. VALIDAR PRÁCTICAS
    // ======================================================
    public function validate_practices() {
        $sql = "SELECT a.id as app_id, u.nombres, u.apellidos, u.email, v.titulo as puesto, c.nombre as empresa, a.fecha_aplicacion, a.estado 
                FROM applications a
                JOIN users u ON a.user_id = u.id
                JOIN vacancies v ON a.vacancy_id = v.id
                JOIN companies c ON v.company_id = c.id
                WHERE a.estado = 'pendiente'
                ORDER BY a.fecha_aplicacion DESC";
        $stmt = $this->db->query($sql);
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $page = 'validate_practices';
        include '../app/Views/admin/validate_practices.php';
    }

    public function approve_application() {
        if(isset($_POST['app_id'])) {
            $appId = $_POST['app_id'];
            try {
                $this->db->beginTransaction();

                $stmt = $this->db->prepare("SELECT a.user_id, a.vacancy_id, v.company_id, v.titulo 
                                            FROM applications a 
                                            JOIN vacancies v ON a.vacancy_id = v.id 
                                            WHERE a.id = ?");
                $stmt->execute([$appId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$data) throw new Exception("Error datos");

                // Insertar en internships
                $sqlInternship = "INSERT INTO internships (student_id, company_id, application_id, puesto, estado, fecha_inicio) 
                                  VALUES (?, ?, ?, ?, 'en_curso', CURDATE())";
                $this->db->prepare($sqlInternship)->execute([
                    $data['user_id'], $data['company_id'], $appId, $data['titulo']
                ]);

                // Actualizar aplicación y vacante
                $this->db->prepare("UPDATE applications SET estado = 'aprobada' WHERE id = ?")->execute([$appId]);
                $this->db->prepare("UPDATE vacancies SET cupos = cupos - 1 WHERE id = ?")->execute([$data['vacancy_id']]);

                $this->db->commit();
                header('Location: ' . BASE_URL . 'admin/validate_practices?msg=aprobado');
            } catch (Exception $e) {
                $this->db->rollBack();
                header('Location: ' . BASE_URL . 'admin/validate_practices?error=sql');
            }
        }
    }

    public function reject_application() {
        if(isset($_GET['id'])) {
            $stmt = $this->db->prepare("UPDATE applications SET estado = 'rechazada' WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            header('Location: ' . BASE_URL . 'admin/validate_practices?msg=rechazado');
        }
    }

    // ======================================================
    // 6. REPORTES IA
    // ======================================================
    public function ai_reports() {
        // Promedios
        $sqlGlobal = "SELECT AVG(conocimiento_tecnico) as tec, AVG(comunicacion) as com, 
                             AVG(trabajo_equipo) as eq, AVG(resolucion_problemas) as res, AVG(puntualidad) as pun 
                      FROM evaluations";
        $promedios = $this->db->query($sqlGlobal)->fetch(PDO::FETCH_ASSOC);

        // Riesgo
        $sqlRiesgo = "SELECT u.nombres, u.apellidos, c.nombre as empresa, 
                             ((e.conocimiento_tecnico + e.comunicacion + e.trabajo_equipo + e.resolucion_problemas + e.puntualidad)/5) as promedio
                      FROM evaluations e
                      JOIN internships i ON e.internship_id = i.id
                      JOIN users u ON i.student_id = u.id
                      JOIN companies c ON i.company_id = c.id
                      HAVING promedio < 12";
        $alumnosRiesgo = $this->db->query($sqlRiesgo)->fetchAll(PDO::FETCH_ASSOC);

        // Top
        $sqlTop = "SELECT u.nombres, u.apellidos, c.nombre as empresa, 
                          ((e.conocimiento_tecnico + e.comunicacion + e.trabajo_equipo + e.resolucion_problemas + e.puntualidad)/5) as promedio
                   FROM evaluations e
                   JOIN internships i ON e.internship_id = i.id
                   JOIN users u ON i.student_id = u.id
                   JOIN companies c ON i.company_id = c.id
                   ORDER BY promedio DESC LIMIT 5";
        $topAlumnos = $this->db->query($sqlTop)->fetchAll(PDO::FETCH_ASSOC);

        $page = 'ai_reports';
        include '../app/Views/admin/ai_reports.php';
    }
}
?>