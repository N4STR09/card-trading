<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT username FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Card Exchange</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <nav>
            <ul>
                <li><a href="game.php">Juego Diario</a></li>
                <li><a href="quest.php">Quests</a></li>
                <li><a href="marketplace.php">Marketplace</a></li>
                <li><a href="dinero.php">Agregar Fondos</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Tu Panel</h2>
        <p>Aquí puedes gestionar tu colección de cartas y participar en el intercambio.</p>
    </main>
</body>
</html>
