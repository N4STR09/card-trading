<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Crear tabla de progreso si no existe
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS user_progress (
    user_id INT PRIMARY KEY,
    progress INT NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Obtener el progreso actualizado
$progress_query = "SELECT progress FROM user_progress WHERE user_id = '$user_id'";
$progress_result = mysqli_query($conn, $progress_query);
$row = mysqli_fetch_assoc($progress_result);
$progress = $row ? $row['progress'] : 0;

// Si se recibe una solicitud para actualizar el progreso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_card']) && $_POST['new_card'] == 1) {
    $progress++;
    mysqli_query($conn, "INSERT INTO user_progress (user_id, progress) VALUES ('$user_id', $progress) 
        ON DUPLICATE KEY UPDATE progress = $progress");
    
    // Si el progreso alcanza 25, agregar $10 y reiniciar
    if ($progress >= 25) {
        mysqli_query($conn, "UPDATE users SET balance = balance + 10 WHERE id = '$user_id'");
        mysqli_query($conn, "UPDATE user_progress SET progress = 0 WHERE user_id = '$user_id'");
        $progress = 0;
    }
}

// HTML para la barra de progreso
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quest - Card Exchange</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script>
        function updateProgress() {
            fetch('quest.php')
                .then(response => response.json())
                .then(data => {
                    let progressBar = document.getElementById("progress-bar");
                    let progressText = document.getElementById("progress-text");
                    progressBar.style.width = (data.progress * 4) + "%";
                    progressText.textContent = data.progress + "/25 Cartas Conseguida";
                });
        }

        function simulateNewCard() {
            fetch('quest.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'new_card=1'
            }).then(() => updateProgress());
        }
    </script>
</head>
<body>
    <header>
        <h1>Quest Diaria</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="marketplace.php">Marketplace</a></li>
                <li><a href="buy.php">Comprar Cartas</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Progreso de la Quest</h2>
        <div class="progress-container" style="width: 100%; background: #ddd; border-radius: 10px; overflow: hidden; height: 30px;">
            <div id="progress-bar" style="width: <?php echo ($progress * 4); ?>%; background: #4caf50; height: 100%; transition: width 0.5s ease;"></div>
        </div>
        <p id="progress-text" style="margin-top: 10px; font-weight: bold;"> <?php echo $progress; ?>/25 Cartas Conseguida</p>
        <button onclick="simulateNewCard()">Simular Nueva Carta</button>
    </main>
</body>
</html>
