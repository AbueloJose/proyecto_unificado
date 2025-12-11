<?php
require_once __DIR__ . '/../Config/Database.php';

class ChatController {
    
    private $conn;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // API: Obtener historial de chat (GET)
    public function history() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            exit;
        }

        $stmt = $this->conn->prepare("SELECT mensaje_usuario, mensaje_bot, fecha FROM chat_history WHERE user_id = ? ORDER BY fecha ASC");
        $stmt->execute([$_SESSION['user_id']]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($history);
    }

    // API: Enviar mensaje y recibir respuesta (POST)
    public function send() {
        header('Content-Type: application/json');

        // Leer JSON del body
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!isset($_SESSION['user_id']) || !isset($data['mensaje'])) {
            echo json_encode(['error' => 'No autorizado o mensaje vacío']);
            exit;
        }

        $mensajeUsuario = trim($data['mensaje']);
        $userId = $_SESSION['user_id'];
        
        // --- LÓGICA DE RESPUESTA AUTOMÁTICA (SIMULADA) ---
        $respuestaBot = $this->generarRespuesta($mensajeUsuario);

        // Guardar en BD
        $stmt = $this->conn->prepare("INSERT INTO chat_history (user_id, mensaje_usuario, mensaje_bot) VALUES (?, ?, ?)");
        if ($stmt->execute([$userId, $mensajeUsuario, $respuestaBot])) {
            echo json_encode(['respuesta' => $respuestaBot]);
        } else {
            echo json_encode(['respuesta' => 'Error al guardar el mensaje.']);
        }
    }

    // Función simple de palabras clave (puedes mejorarla luego)
    private function generarRespuesta($mensaje) {
        $msg = strtolower($mensaje);

        if (strpos($msg, 'hola') !== false) return "¡Hola " . $_SESSION['user_name'] . "! ¿En qué puedo ayudarte hoy?";
        if (strpos($msg, 'cv') !== false || strpos($msg, 'curriculum') !== false) return "Puedes subir tu CV en la sección 'Mi Perfil' del menú lateral.";
        if (strpos($msg, 'informe') !== false) return "Recuerda que los informes semanales se suben en la pestaña 'Mis Informes'.";
        if (strpos($msg, 'fecha') !== false) return "La fecha y hora actual es " . date('d/m/Y H:i');
        if (strpos($msg, 'gracias') !== false) return "¡De nada! Estoy para servirte.";
        
        return "Entiendo. Cuéntame más o contacta a tu docente supervisor si es urgente.";
    }
}
?>