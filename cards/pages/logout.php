<?php
session_start(); // Iniciar la sesión

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, borrar también la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), // Nombre de la cookie de sesión
        '', // Valor vacío
        time() - 42000, // Tiempo de expiración en el pasado
        $params["path"], // Ruta de la cookie
        $params["domain"], // Dominio de la cookie
        $params["secure"], // Seguridad (HTTPS)
        $params["httponly"] // Acceso solo por HTTP
    );
}

// Destruir la sesión
session_destroy();

// Eliminar las cookies de "recordar sesión" si existen
if (isset($_COOKIE['user_id']) || isset($_COOKIE['username'])) {
    setcookie("user_id", "", time() - 3600, "/"); // Eliminar cookie de user_id
    setcookie("username", "", time() - 3600, "/"); // Eliminar cookie de username
}

// Redirigir al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
?>