<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $referral_code = isset($_POST['referral_code']) ? $_POST['referral_code'] : '';
    $bonus = 0;
    
    // Verificar si el código de referido es válido
    if (!empty($referral_code)) {
        $check_code_query = "SELECT id FROM codigos_promo WHERE code = '$referral_code'";
        $code_result = mysqli_query($conn, $check_code_query);
        
        if (mysqli_num_rows($code_result) > 0) {
            $bonus = 50;
        }
    }
    
    // Insertar el usuario en la base de datos con saldo si aplica
    $query = "INSERT INTO users (username, password, balance) VALUES ('$username', '$password', '$bonus')";
    mysqli_query($conn, $query);
    
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Card Exchange</title>
    <link rel="stylesheet" href="../assets/register.css">
    <script>
        function toggleReferralField() {
            let checkbox = document.getElementById("use_referral");
            let referralField = document.getElementById("referral_code_field");
            referralField.style.display = checkbox.checked ? "block" : "none";
        }
    </script>
</head>
<body>
    <h2>Registro</h2>
    <form action="register.php" method="POST">
        <label for="username">Usuario:</label>
        <input type="text" name="username" required>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        
        <label>
            <input type="checkbox" id="use_referral" onclick="toggleReferralField()"> Usar código de referido
        </label>
        
        <div id="referral_code_field" style="display: none;">
            <label for="referral_code">Código de referido:</label>
            <input type="text" name="referral_code">
        </div>
        
        <button type="submit">Registrarse</button>
    </form>
    <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
</body>
</html>