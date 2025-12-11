<?php
session_start();

// Definir constante para la ruta base si la necesitas en las vistas
// Ajusta esto según el nombre de tu carpeta en htdocs
define('BASE_URL', 'http://localhost/Proyecto_Unificado/public/');

// Carga de archivos de configuración (Notar el "../app")
// Si tienes un config.php con constantes, ponlo en app/Config y descomenta la linea de abajo:
// require_once '../app/Config/config.php'; 
require_once '../app/Config/Database.php';

// Capturar la URL
$url = isset($_GET['url']) ? $_GET['url'] : 'auth/login';
$url = rtrim($url, '/');
$arrUrl = explode('/', $url);

// Definir Controlador y Método
// Si la URL es "auth/login", el controlador será "AuthController"
$controllerName = isset($arrUrl[0]) ? ucfirst($arrUrl[0]) . 'Controller' : 'AuthController';
$method = isset($arrUrl[1]) ? $arrUrl[1] : 'index';

// Ruta al archivo del controlador dentro de app
$path = '../app/Controllers/' . $controllerName . '.php';

if (file_exists($path)) {
    require_once $path;
    $controller = new $controllerName();
    
    if (method_exists($controller, $method)) {
        // Limpiamos la URL para pasar solo los parámetros restantes
        unset($arrUrl[0]); 
        unset($arrUrl[1]);
        $params = array_values($arrUrl);
        
        // Ejecutamos el método
        call_user_func_array([$controller, $method], $params);
    } else { 
        echo "Error 404: El método '$method' no existe en $controllerName"; 
    }
} else { 
    echo "Error 404: El controlador '$controllerName' no se encuentra en $path"; 
}
?>