<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT marketplace.id, users.username AS seller, cards.name, cards.rarity, marketplace.price, marketplace.is_sold 
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
    <title>Marketplace - Card Exchange</title>
    <link rel="stylesheet" href="../assets/market.css">
</head>
<body>
    <header>
        <h1>Marketplace</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="game.php">Juego Diario</a></li>
                <li><a href="quest.php">Quests</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Cartas en venta</h2>
        <table>
            <tr>
                <th>Vendedor</th>
                <th>Nombre de Carta</th>
                <th>Rareza</th>
                <th>Precio</th>
                <th>Comprar</th>
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
                    </td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><a href="buy.php?id=<?php echo $row['id']; ?>">Comprar</a></td>
                </tr>
            <?php } ?>
        </table>
        <a href="../pages/sell_card.php">
            <br><br><button id="vender">Vender una Carta</button>
        </a>
    </main>
</body>
</html>
