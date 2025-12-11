<?php
require_once __DIR__ . '/../Config/Database.php';

class TeacherController {
    
    private $db;
    private $teacherId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // 1. SEGURIDAD: Solo Docentes
        if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] != 'docente') {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $this->db = (new Database())->getConnection();
        $this->teacherId = $_SESSION['user_id'];
    }

    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    public function dashboard() {
        // Contar alumnos a cargo
        $sqlAlumnos = "SELECT COUNT(*) FROM internships WHERE teacher_id = ? AND estado = 'en_curso'";
        $stmt = $this->db->prepare($sqlAlumnos);
        $stmt->execute([$this->teacherId]);
        $totalAlumnos = $stmt->fetchColumn();

        // Contar informes pendientes
        // [CORREGIDO] 'estado_validacion' ahora es 'estado'
        $sqlPendientes = "SELECT COUNT(*) FROM weekly_reports w 
                          JOIN internships i ON w.internship_id = i.id 
                          WHERE i.teacher_id = ? AND w.estado = 'pendiente'";
        $stmt = $this->db->prepare($sqlPendientes);
        $stmt->execute([$this->teacherId]);
        $pendingCount = $stmt->fetchColumn(); 

        $page = 'dashboard';
        include '../app/Views/teacher/dashboard.php';
    }

    // ==========================================
    // 2. MIS ALUMNOS
    // ==========================================
    public function students() {
        $sql = "SELECT u.nombres, u.apellidos, u.email, u.foto_perfil, 
                       c.nombre as empresa, i.puesto, i.fecha_inicio
                FROM internships i
                JOIN users u ON i.student_id = u.id
                JOIN companies c ON i.company_id = c.id
                WHERE i.teacher_id = ? AND i.estado = 'en_curso'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->teacherId]);
        $misAlumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pendingCount = $this->getPendingCount();

        $page = 'students';
        include '../app/Views/teacher/students.php';
    }

    // ==========================================
    // 3. REVISIÓN DE INFORMES
    // ==========================================
    public function reviews() {
        // [CORREGIDO] 'estado_validacion' ahora es 'estado'
        $sql = "SELECT w.id as reporte_id, w.semana_numero, w.descripcion, w.archivo_adjunto, w.fecha_subida,
                       u.nombres, u.apellidos, c.nombre as empresa
                FROM weekly_reports w
                JOIN internships i ON w.internship_id = i.id
                JOIN users u ON i.student_id = u.id
                JOIN companies c ON i.company_id = c.id
                WHERE i.teacher_id = ? AND w.estado = 'pendiente'
                ORDER BY w.fecha_subida ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->teacherId]);
        $informes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pendingCount = count($informes);

        $page = 'reviews';
        include '../app/Views/teacher/reviews.php';
    }

    // Acción para Calificar (POST)
    public function grade_report() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reportId = $_POST['report_id'];
            $estado = $_POST['estado']; // 'aprobado' o 'observado'
            $comentario = $_POST['comentario'];

            // [CORREGIDO] Actualizamos la columna 'estado'
            $sql = "UPDATE weekly_reports SET estado = ?, comentarios_docente = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$estado, $comentario, $reportId]);

            header('Location: ' . BASE_URL . 'teacher/reviews?msg=calificado');
        }
    }

    // Auxiliar para no repetir código del contador
    private function getPendingCount() {
        // [CORREGIDO] 'estado_validacion' -> 'estado'
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM weekly_reports w JOIN internships i ON w.internship_id = i.id WHERE i.teacher_id = ? AND w.estado = 'pendiente'");
        $stmt->execute([$this->teacherId]);
        return $stmt->fetchColumn();
    }
}
?>