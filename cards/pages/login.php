<?php
session_start();
include '../includes/db_connect.php';

// Configuración LDAP
$ldap_host = "ldap://192.168.0.128";
$ldap_base_dn = "ou=usuarios,dc=peppo,dc=com";

if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $is_admin = false;
    $ldap_auth = false;

    // Intentar autenticación LDAP
    $ldap_conn = @ldap_connect($ldap_host);
    if ($ldap_conn) {
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_conn, LDAP_OPT_NETWORK_TIMEOUT, 2); // Timeout de 2 segundos

        $ldap_dn = "uid=$username,$ldap_base_dn";

        if (@ldap_bind($ldap_conn, $ldap_dn, $password)) {
            $_SESSION['user_id'] = $username;
            $_SESSION['username'] = $username;
            $ldap_auth = true;
        }
    }

    // Si no autenticó por LDAP, intentar en MySQL
    if (!$ldap_auth) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);
    
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            if ($user['id'] == 6) {
                $is_admin = true;
            }
        }
    }

    // Redirección según tipo de usuario
    if (isset($_SESSION['user_id'])) {
        if ($is_admin) {
            header("Location: admin_perks.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Card Exchange</title>
    <link rel="stylesheet" href="../assets/register.css">
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form action="login.php" method="POST">
        <label for="username">Usuario:</label>
        <input type="text" name="username" required>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <label>
            <input type="checkbox" name="remember"> Recordar sesión
        </label>
        <button type="submit">Ingresar</button>
    </form>
    <a href="register.php">¿No tienes cuenta? Regístrate</a>
</body>
</html>