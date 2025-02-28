<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Obtener saldo actual del usuario
$saldo_query = "SELECT balance FROM users WHERE id = '$user_id'";
$saldo_result = mysqli_query($conn, $saldo_query);
$saldo = mysqli_fetch_assoc($saldo_result)['balance'];

// Procesar creación de fondos para otro usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount']) && isset($_POST['recipient_username'])) {
    $amount = floatval($_POST['amount']);
    $recipient_username = mysqli_real_escape_string($conn, trim($_POST['recipient_username']));

    // Obtener ID del destinatario
    $recipient_query = "SELECT id FROM users WHERE username = '$recipient_username' LIMIT 1";
    $recipient_result = mysqli_query($conn, $recipient_query);
    
    if (mysqli_num_rows($recipient_result) > 0) {
        $recipient_id = mysqli_fetch_assoc($recipient_result)['id'];
        
        if ($amount > 0) {
            // Agregar saldo al destinatario sin restar al usuario actual
            $update_query = "UPDATE users SET balance = balance + '$amount' WHERE id = '$recipient_id'";
            mysqli_query($conn, $update_query);

            $message = "Has agregado $$amount a la cuenta de $recipient_username.";
        } else {
            $message = "La cantidad debe ser mayor a 0.";
        }
    } else {
        $message = "El usuario destinatario no existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Fondos - Card Exchange</title>
    <link rel="stylesheet" href="../assets/dinero.css">
</head>
<body>
    <header>
        <h1>Agregar Fondos</h1>
        <nav>
            <ul>
                <li><a href="admin_perks.php">Panel de Control</a></li>
                <li><a href="shop_admin.php">Shop Admin</a></li>
                <li><a href="card_admin.php">Card Admin</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Saldo Actual: $<?php echo number_format($saldo, 2); ?></h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form action="dinero.php" method="POST">
            <label for="recipient_username">Usuario:</label>
            <input type="text" name="recipient_username" required>
            <label for="amount">Cantidad ($):</label>
            <input type="number" step="0.01" name="amount" required>
            <button type="submit">Agregar Fondos</button>
        </form>
    </main>
</body>
</html>
