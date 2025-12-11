<?php
require_once __DIR__ . '/../Config/Database.php';

class Company {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ==========================================
    // GESTIÓN DE EMPRESAS (Repo 1: Instituciones)
    // ==========================================
    public function getAll() {
        $query = "SELECT * FROM companies ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $ruc, $rubro, $direccion, $contacto, $email) {
        try {
            $query = "INSERT INTO companies (nombre, ruc, rubro, direccion, nombre_contacto, email_contacto) 
                      VALUES (:nombre, :ruc, :rubro, :dir, :contacto, :email)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':nombre' => $nombre,
                ':ruc' => $ruc,
                ':rubro' => $rubro,
                ':dir' => $direccion,
                ':contacto' => $contacto,
                ':email' => $email
            ]);
            return true;
        } catch(PDOException $e) {
            return false; // Probablemente RUC duplicado
        }
    }

    // ==========================================
    // GESTIÓN DE VACANTES (Repo 1: Vacantes)
    // ==========================================
    public function getVacancies() {
        // JOIN para saber de qué empresa es la vacante
        $query = "SELECT v.*, c.nombre as empresa 
                  FROM vacancies v 
                  JOIN companies c ON v.company_id = c.id 
                  ORDER BY v.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function createVacancy($company_id, $titulo, $descripcion, $area, $cupos, $teacher_id) {
        $query = "INSERT INTO vacancies (company_id, titulo, descripcion, area, cupos, teacher_id, activa) 
                  VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$company_id, $titulo, $descripcion, $area, $cupos, $teacher_id]);
    }

    // ==========================================
    // ESTADÍSTICAS PARA EL DASHBOARD
    // ==========================================
    public function getStats() {
        $stats = [];
        
        // Total Estudiantes
        $stmt = $this->conn->query("SELECT COUNT(*) FROM users WHERE rol = 'estudiante'");
        $stats['estudiantes'] = $stmt->fetchColumn();

        // Total Empresas
        $stmt = $this->conn->query("SELECT COUNT(*) FROM companies");
        $stats['empresas'] = $stmt->fetchColumn();

        // Prácticas Activas
        $stmt = $this->conn->query("SELECT COUNT(*) FROM internships WHERE estado = 'en_curso'");
        $stats['practicas_activas'] = $stmt->fetchColumn();

        // Vacantes Disponibles
        $stmt = $this->conn->query("SELECT SUM(cupos) FROM vacancies WHERE activa = 1");
        $stats['vacantes_libres'] = $stmt->fetchColumn() ?: 0;

        return $stats;
    }
}
?>