<?php
require_once 'config/db.php';

class Chat {
    private $conn;
    private $table = 'chat_history';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Guardar mensaje (Ya lo tenías, pero asegúrate que esté así)
    public function saveMessage($userId, $msg, $resp) {
        $sql = "INSERT INTO " . $this->table . " (user_id, mensaje_usuario, mensaje_bot) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $msg, $resp]);
    }

    // NUEVO: Recuperar historial
    public function getHistory($userId) {
        // Traemos los últimos 20 mensajes
        $sql = "SELECT mensaje_usuario, mensaje_bot, fecha FROM " . $this->table . " WHERE user_id = ? ORDER BY id ASC LIMIT 20";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>