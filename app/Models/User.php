<?php
require_once __DIR__ . '/../Config/Database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ==========================================================
    // 1. AUTENTICACIÓN
    // ==========================================================

    public function login($email, $password) {
        $query = "SELECT id, nombres, apellidos, password, rol, foto_perfil, codigo 
                  FROM " . $this->table . " 
                  WHERE email = :email AND activo = 1 LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    // [ACTUALIZADO] Ahora guarda el 'face_descriptor'
    public function register($nombres, $apellidos, $email, $password, $rol, $codigo = null, $fotoBiometria = null, $descriptor = null) {
        try {
            $check = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE email = ?");
            $check->execute([$email]);
            if($check->rowCount() > 0) return "existe";

            $query = "INSERT INTO " . $this->table . " 
                      (nombres, apellidos, email, password, rol, codigo, foto_biometria, face_descriptor, created_at) 
                      VALUES (:nombres, :apellidos, :email, :pass, :rol, :codigo, :foto, :desc, NOW())";
            
            $stmt = $this->conn->prepare($query);
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pass', $passwordHash);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':foto', $fotoBiometria);
            $stmt->bindParam(':desc', $descriptor); // <--- Aquí guardamos la matemática del rostro

            if($stmt->execute()) {
                $id = $this->conn->lastInsertId();
                if($rol == 'estudiante') $this->crearPerfilEstudiante($id);
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return "error: " . $e->getMessage();
        }
    }

    // ==========================================================
    // 2. BUSCADOR FACIAL (1 a N)
    // ==========================================================
    public function findUserByFace($descriptorEntrante) {
        // Traemos solo usuarios que tengan descriptor registrado
        $stmt = $this->conn->prepare("SELECT id, nombres, apellidos, rol, foto_perfil, face_descriptor FROM " . $this->table . " WHERE face_descriptor IS NOT NULL AND activo = 1");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $mejorCoincidencia = null;
        $menorDistancia = 100; 
        $umbral = 0.50; // Ajustable: 0.45 (Estricto) - 0.60 (Flexible)

        foreach($usuarios as $user) {
            $dbDescriptor = json_decode($user['face_descriptor']);
            
            if(is_array($descriptorEntrante) && is_array($dbDescriptor) && count($descriptorEntrante) == count($dbDescriptor)){
                // Cálculo de Distancia Euclidiana
                $distancia = 0;
                for($i = 0; $i < count($descriptorEntrante); $i++) {
                    $diff = $descriptorEntrante[$i] - $dbDescriptor[$i];
                    $distancia += $diff * $diff;
                }
                $distancia = sqrt($distancia);

                if ($distancia < $menorDistancia) {
                    $menorDistancia = $distancia;
                    $mejorCoincidencia = $user;
                }
            }
        }

        if ($menorDistancia < $umbral) {
            return $mejorCoincidencia;
        }
        return false;
    }

    // ==========================================================
    // 3. OTROS MÉTODOS
    // ==========================================================
    
    private function crearPerfilEstudiante($userId) {
        try {
            $this->conn->prepare("INSERT INTO student_profiles (user_id) VALUES (?)")->execute([$userId]);
        } catch (PDOException $e) {}
    }

    public function getFullProfile($id) {
        $query = "SELECT u.id, u.nombres, u.apellidos, u.email, u.telefono, u.foto_perfil, u.rol, u.codigo,
                         p.cv_path, p.ficha_tecnica_path, p.habilidades
                  FROM users u LEFT JOIN student_profiles p ON u.id = p.user_id WHERE u.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePhoto($id, $path) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET foto_perfil = ? WHERE id = ?");
        return $stmt->execute([$path, $id]);
    }

    public function updateCV($id, $path) {
        $this->crearPerfilEstudiante($id);
        $stmt = $this->conn->prepare("UPDATE student_profiles SET cv_path = ? WHERE user_id = ?");
        return $stmt->execute([$path, $id]);
    }

    public function updateData($id, $telefono, $email_respaldo) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET telefono = ?, email_respaldo = ? WHERE id = ?");
        return $stmt->execute([$telefono, $email_respaldo, $id]);
    }

    public function updateFaceDescriptor($id, $descriptorJSON) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET face_descriptor = :desc WHERE id = :id");
        $stmt->bindParam(':desc', $descriptorJSON);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>