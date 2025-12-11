<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Config/Database.php';

class AuthController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function login() { include '../app/Views/auth/login.php'; }
    public function register() { include '../app/Views/auth/register.php'; }
    public function faceLogin() { include '../app/Views/auth/face_login.php'; }

    // ==========================================
    // LOGIN CLÁSICO
    // ==========================================
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $userModel = new User();
            $user = $userModel->login($email, $password);
            
            if ($user) $this->crearSesion($user);
            else header('Location: '.BASE_URL.'auth/login?error=credenciales');
        }
    }

    // ==========================================
    // REGISTRO AUTOMÁTICO
    // ==========================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombres = $_POST['nombres'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $rol = $_POST['rol'];
            
            // -----------------------------------------------------------
            // GENERACIÓN AUTOMÁTICA DEL CÓDIGO DE ESTUDIANTE
            // Formato: Año + 4 dígitos (Ej: 20254892)
            // -----------------------------------------------------------
            $codigo = date('Y') . rand(1000, 9999);

            // Recibimos descriptor facial
            $descriptor = $_POST['face_descriptor'] ?? null;

            // Guardar Foto
            $rutaFotoFinal = null;
            if (!empty($_POST['biometria_base64'])) {
                $carpetaDestino = __DIR__ . '/../../public/uploads/biometria/';
                if (!file_exists($carpetaDestino)) mkdir($carpetaDestino, 0777, true);
                
                $nombreArchivo = 'biometria_' . time() . '_' . rand(1000,9999) . '.png';
                if ($this->guardarImagenBase64($_POST['biometria_base64'], $carpetaDestino . $nombreArchivo)) {
                    $rutaFotoFinal = 'uploads/biometria/' . $nombreArchivo;
                }
            }

            $userModel = new User();
            $resultado = $userModel->register($nombres, $apellidos, $email, $password, $rol, $codigo, $rutaFotoFinal, $descriptor);

            if ($resultado === true) header('Location: ' . BASE_URL . 'auth/login?msg=registrado');
            elseif ($resultado === "existe") header('Location: ' . BASE_URL . 'auth/register?error=existe');
            else header('Location: ' . BASE_URL . 'auth/register?error=sql');
        }
    }

    // ==========================================
    // LOGIN FACIAL AUTOMÁTICO (1 a N)
    // ==========================================
    public function verify_face_auto() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['descriptor'])) {
            $userModel = new User();
            $user = $userModel->findUserByFace($input['descriptor']);

            if ($user) $this->crearSesion($user, true);
            else echo json_encode(['success' => false, 'message' => 'Rostro no encontrado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Sin datos.']);
        }
        exit;
    }
    
    // Alias por compatibilidad
    public function verify_face() { $this->verify_face_auto(); }
    public function get_user_photo() { /* ... (Si decides usar el método antiguo) ... */ }
    public function login_facial_confirm() { /* ... (Si decides usar el método antiguo) ... */ }

    // ==========================================
    // HELPERS
    // ==========================================
    private function crearSesion($user, $isAjax = false) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombres'] . ' ' . ($user['apellidos'] ?? '');
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['user_photo'] = $user['foto_perfil'];
        
        $redirect = BASE_URL . 'student/dashboard';
        if($user['rol'] == 'admin') $redirect = BASE_URL . 'admin/dashboard';
        if($user['rol'] == 'docente') $redirect = BASE_URL . 'teacher/dashboard';

        if ($isAjax) echo json_encode(['success' => true, 'user_name' => $user['nombres'], 'redirect' => $redirect]);
        else header("Location: $redirect");
    }

    private function guardarImagenBase64($base64_string, $ruta_completa) {
        $data = explode(',', $base64_string);
        if(count($data) < 2) return false;
        $contenido = base64_decode($data[1]);
        return file_put_contents($ruta_completa, $contenido);
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'auth/login');
    }
}
?>