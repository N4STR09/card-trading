<?php
session_start();
include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Card Exchange</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>
    <header>
        <h1>Panel de Administrador</h1>
        <nav>
            <ul>
                <li><a href="dinero.php">Agregar Fondos</a></li>
                <li><a href="shop_admin.php">Shop Admin</a></li>
                <li><a href="card_admin.php">Card Admin</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        
    </main>
</body>
</html>