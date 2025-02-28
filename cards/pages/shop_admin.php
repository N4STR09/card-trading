<?php
session_start();
include '../includes/db_connect.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== '6') {
    header("Location: shop_admin.php");
    exit();
}

$message = "";

// Marcar oferta como vendida (actualizar is_sold a 1)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['offer_id'])) {
    $offer_id = intval($_POST['offer_id']);
    $check_query = "SELECT is_sold FROM marketplace WHERE id = '$offer_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if ($row = mysqli_fetch_assoc($check_result)) {
        if ($row['is_sold'] == 0) {
            $update_query = "UPDATE marketplace SET is_sold = 1 WHERE id = '$offer_id'";
            if (mysqli_query($conn, $update_query)) {
                $message = "Oferta marcada como vendida.";
            } else {
                $message = "Error al actualizar el estado de la oferta.";
            }
        } else {
            $message = "Esta oferta ya está vendida.";
        }
    } else {
        $message = "La oferta no existe.";
    }
}

// Obtener todas las ofertas activas (no vendidas)
$query = "SELECT marketplace.id, users.username AS seller, cards.name, cards.rarity, marketplace.price 
          FROM marketplace 
          JOIN users ON marketplace.seller_id = users.id 
          JOIN cards ON marketplace.card_id = cards.id 
          WHERE marketplace.is_sold = 0";
$result = mysqli_query($conn, $query);

function getRarityColor($rarity) {
    switch (strtolower(trim($rarity))) {
        case 'common': return '#00000'; // Gris
        case 'rare': return '#007bff'; // Azul
        case 'epic': return '#800080'; // Morado
        case 'legendary': return '#ff8c00'; // Naranja
        default: return '#000000'; // Negro
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Marketplace - Card Exchange</title>
    <link rel="stylesheet" href="../assets/shop_admin.css">
</head>
<body>
    <header>
        <h1>Administrar Marketplace</h1>
        <nav>
            <ul>
                <li><a href="admin_perks.php">Panel de Control</a></li>
                <li><a href="dinero.php">Agregar Fondos</a></li>
                <li><a href="card_admin.php">Card Admin</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Ofertas en el Marketplace</h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <table>
            <tr>
                <th>Vendedor</th>
                <th>Nombre de Carta</th>
                <th>Rareza</th>
                <th>Precio</th>
                <th>Eliminar Oferta</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['seller']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <?php 
                        $rarityColor = getRarityColor($row['rarity']); 
                        error_log("Rareza: " . $row['rarity'] . " - Color: " . $rarityColor);
                    ?>
                    <td style="color: <?php echo getRarityColor($row['rarity']); ?> !important; font-weight: bold;">
                        <?php echo htmlspecialchars($row['rarity']); ?>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <form action="shop_admin.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="offer_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </main>
</body>
</html>
