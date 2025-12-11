<?php
require_once __DIR__ . '/../Config/Database.php';

class Internship {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener práctica activa del estudiante
    public function getActiveInternship($studentId) {
        // Hacemos JOIN con la tabla companies para sacar el nombre de la empresa
        $sql = "SELECT i.*, c.nombre as empresa_nombre, c.logo_path 
                FROM internships i
                JOIN companies c ON i.company_id = c.id
                WHERE i.student_id = ? AND i.estado = 'en_curso' 
                LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener competencias (promedio) para el gráfico de araña
    public function getCompetencias($internshipId) {
        // Obtenemos la última evaluación o el promedio
        $sql = "SELECT 
                    AVG(conocimiento_tecnico) as tecnico,
                    AVG(comunicacion) as comunicacion,
                    AVG(trabajo_equipo) as equipo,
                    AVG(resolucion_problemas) as resolucion,
                    AVG(puntualidad) as puntualidad
                FROM evaluations 
                WHERE internship_id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$internshipId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res && $res['tecnico'] != null) {
            // Devolvemos en el orden que espera el gráfico de ApexCharts
            return [
                round($res['tecnico'], 1),
                round($res['comunicacion'], 1),
                round($res['equipo'], 1),
                round($res['resolucion'], 1),
                round($res['puntualidad'], 1)
            ];
        }

        // Si no hay notas, devolvemos ceros
        return [0, 0, 0, 0, 0];
    }
}
?>