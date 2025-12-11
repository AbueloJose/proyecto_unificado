<?php
// Importamos modelos y configuración
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Internship.php';
require_once __DIR__ . '/../Config/Database.php';

class StudentController {
    
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // SEGURIDAD: Verificar que el usuario esté logueado y sea estudiante
        if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] != 'estudiante') {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        // Inicializamos conexión a BD para consultas directas
        $this->db = (new Database())->getConnection();
    }

    // ==========================================================
    // 1. DASHBOARD PRINCIPAL (Con Datos Reales)
    // ==========================================================
    public function dashboard() {
        $internshipModel = new Internship();
        $studentId = $_SESSION['user_id'];
        
        // Buscar si el estudiante tiene una práctica activa ("en_curso")
        $practica = $internshipModel->getActiveInternship($studentId);
        
        // Valores por defecto (si no tiene práctica)
        $datosGrafico = [0, 0, 0, 0, 0]; 
        $tienePractica = false;
        $empresaNombre = "No asignada";
        
        // Variables para las tarjetas de estadísticas
        $conteoInformes = 0;
        $promedioGeneral = 0;
        $prediccionIA = "Sin datos";
        $clasePrediccion = "text-muted"; // Color gris por defecto

        if ($practica) {
            $tienePractica = true;
            $empresaNombre = $practica['empresa_nombre'];
            $internshipId = $practica['id'];

            // A. CONTAR INFORMES ENVIADOS
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM weekly_reports WHERE internship_id = ?");
            $stmt->execute([$internshipId]);
            $conteoInformes = $stmt->fetchColumn();

            // B. CALCULAR PROMEDIO DE EVALUACIONES (Del Docente)
            // Promedio de las 5 competencias
            $stmt = $this->db->prepare("
                SELECT AVG((conocimiento_tecnico + comunicacion + trabajo_equipo + resolucion_problemas + puntualidad) / 5) 
                FROM evaluations 
                WHERE internship_id = ?
            ");
            $stmt->execute([$internshipId]);
            $promedioRaw = $stmt->fetchColumn();
            
            if ($promedioRaw) {
                $promedioGeneral = round($promedioRaw, 1); // Redondear a 1 decimal
                
                // C. LÓGICA DE "IA" (Predicción basada en notas)
                if ($promedioGeneral >= 17) {
                    $prediccionIA = "95% Alta";
                    $clasePrediccion = "text-success"; // Verde
                } elseif ($promedioGeneral >= 13) {
                    $prediccionIA = "70% Media";
                    $clasePrediccion = "text-warning"; // Amarillo
                } else {
                    $prediccionIA = "30% Riesgo";
                    $clasePrediccion = "text-danger"; // Rojo
                }
            }

            // D. OBTENER DATOS PARA EL GRÁFICO DE ARAÑA
            $datosGrafico = $internshipModel->getCompetencias($internshipId);
        }

        // Convertir datos a JSON para el gráfico en la vista
        $jsonCompetencias = json_encode($datosGrafico);

        $page = 'dashboard'; // Activar botón en Sidebar
        include '../app/Views/student/dashboard.php';
    }

    // ==========================================================
    // 2. BOLSA DE PRÁCTICAS (OFERTAS)
    // ==========================================================
    public function opportunities() {
        // Consultar vacantes activas + nombre de empresa + nombre del profesor
        $sql = "SELECT v.*, c.nombre as empresa, c.rubro, u.nombres as doc_nom, u.apellidos as doc_ape 
                FROM vacancies v
                JOIN companies c ON v.company_id = c.id
                LEFT JOIN users u ON v.teacher_id = u.id
                WHERE v.activa = 1 AND v.cupos > 0
                ORDER BY v.created_at DESC";
        
        $ofertas = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Verificar si el alumno ya tiene una postulación pendiente (para bloquear el botón)
        $miPostulacion = $this->db->prepare("SELECT vacancy_id FROM applications WHERE user_id = ? AND estado = 'pendiente'");
        $miPostulacion->execute([$_SESSION['user_id']]);
        $postulacionActiva = $miPostulacion->fetchColumn();

        $page = 'opportunities'; // Activar botón en Sidebar
        include '../app/Views/student/opportunities.php';
    }

    // Acción de Postular a una vacante
    public function postulate() {
        if(isset($_GET['id'])) {
            $vacancyId = $_GET['id'];
            $studentId = $_SESSION['user_id'];

            // Insertar solicitud en la tabla applications
            $stmt = $this->db->prepare("INSERT INTO applications (user_id, vacancy_id, estado, fecha_aplicacion) VALUES (?, ?, 'pendiente', NOW())");
            
            if($stmt->execute([$studentId, $vacancyId])) {
                header('Location: ' . BASE_URL . 'student/opportunities?msg=postulado');
            } else {
                header('Location: ' . BASE_URL . 'student/opportunities?error=error');
            }
        }
    }

    // ==========================================================
    // 3. GESTIÓN DEL PERFIL
    // ==========================================================
    public function profile() {
        $userModel = new User();
        $userData = $userModel->getFullProfile($_SESSION['user_id']);
        
        $page = 'profile'; // Activar botón en Sidebar
        include '../app/Views/student/profile.php';
    }

    public function update_photo() {
        if (!empty($_FILES['foto']['name'])) {
            $id = $_SESSION['user_id'];
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            
            if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])) {
                $nombreArchivo = 'perfil_' . $id . '_' . time() . '.' . $ext;
                $rutaRelativa = 'uploads/perfiles/' . $nombreArchivo;
                
                // Crear carpeta si no existe
                if (!file_exists('uploads/perfiles')) mkdir('uploads/perfiles', 0777, true);
                
                if(move_uploaded_file($_FILES['foto']['tmp_name'], $rutaRelativa)) {
                    $userModel = new User();
                    $userModel->updatePhoto($id, $rutaRelativa);
                    $_SESSION['user_photo'] = $rutaRelativa; // Actualizar sesión al instante
                }
            }
        }
        header('Location: ' . BASE_URL . 'student/profile?msg=foto_ok');
    }

    public function update_cv() {
        if (!empty($_FILES['cv']['name'])) {
            $id = $_SESSION['user_id'];
            $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
            
            if(strtolower($ext) == 'pdf') {
                $nombreArchivo = 'cv_' . $id . '_' . time() . '.pdf';
                $rutaRelativa = 'uploads/cv/' . $nombreArchivo;
                
                if (!file_exists('uploads/cv')) mkdir('uploads/cv', 0777, true);
                
                if(move_uploaded_file($_FILES['cv']['tmp_name'], $rutaRelativa)) {
                    $userModel = new User();
                    $userModel->updateCV($id, $rutaRelativa);
                }
            } else {
                header('Location: ' . BASE_URL . 'student/profile?error=formato');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'student/profile?msg=cv_ok');
    }

    public function update_data() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new User();
            $userModel->updateData($_SESSION['user_id'], $_POST['telefono'], $_POST['email_respaldo']);
        }
        header('Location: ' . BASE_URL . 'student/profile?msg=datos_ok');
    }

    // ==========================================================
    // 4. FACE ID (AJAX desde el Perfil)
    // ==========================================================
    public function save_face() {
        header('Content-Type: application/json');
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if(isset($data['descriptor'])) {
            $userModel = new User();
            // Guardar descriptor como JSON string en la BD
            if($userModel->updateFaceDescriptor($_SESSION['user_id'], json_encode($data['descriptor']))) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar en BD']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se recibieron datos biométricos']);
        }
        exit;
    }

    // ==========================================================
    // 5. GESTIÓN DE INFORMES SEMANALES
    // ==========================================================
    public function reports() {
        $studentId = $_SESSION['user_id'];

        // Obtener ID de la práctica activa
        $stmt = $this->db->prepare("SELECT id FROM internships WHERE student_id = ? AND estado = 'en_curso' LIMIT 1");
        $stmt->execute([$studentId]);
        $internship = $stmt->fetch(PDO::FETCH_ASSOC);

        $historial = [];
        if($internship) {
            // Traer historial de informes
            $stmt = $this->db->prepare("SELECT * FROM weekly_reports WHERE internship_id = ? ORDER BY semana_numero DESC");
            $stmt->execute([$internship['id']]);
            $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $page = 'reports'; // Activar botón en Sidebar
        include '../app/Views/student/my_reports.php';
    }

    public function upload_report() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $internship_id = $_POST['internship_id'];
            $semana = $_POST['semana'];
            $actividad = $_POST['actividad'];
            
            // Subir PDF
            $archivoNombre = null;
            if(!empty($_FILES['archivo']['name'])) {
                $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
                if(strtolower($ext) == 'pdf') {
                    $archivoNombre = 'informe_sem' . $semana . '_' . time() . '.pdf';
                    $rutaRelativa = 'uploads/informes/' . $archivoNombre;
                    
                    if (!file_exists('uploads/informes')) mkdir('uploads/informes', 0777, true);
                    
                    move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaRelativa);
                }
            }

            // Guardar en BD
            $sql = "INSERT INTO weekly_reports (internship_id, semana_numero, descripcion, archivo_adjunto, estado) 
                    VALUES (?, ?, ?, ?, 'pendiente')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$internship_id, $semana, $actividad, $archivoNombre]);
            
            header('Location: ' . BASE_URL . 'student/reports?msg=enviado');
        }
    }
}
?>