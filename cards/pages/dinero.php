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

// Procesar recarga de saldo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    if ($amount > 0) {
        $update_query = "UPDATE users SET balance = balance + '$amount' WHERE id = '$user_id'";
        mysqli_query($conn, $update_query);
        $saldo += $amount;
        $message = "Has agregado $$amount a tu cuenta.";
    } else {
        $message = "La cantidad debe ser mayor a 0.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Fondos - Card Exchange</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Agregar Fondos</h1>
        <nav>
            <ul>
                <li><a href="admin_perks.php">Panel de Control</a></li>
                <li><a href="dinero.php">Agregar Fondos</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Saldo Actual: $<?php echo number_format($saldo, 2); ?></h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form action="dinero.php" method="POST">
            <label for="amount">Cantidad a agregar ($):</label>
            <input type="number" step="0.01" name="amount" required>
            <button type="submit">Agregar Fondos</button>
        </form>
    </main>
</body>
</html>
