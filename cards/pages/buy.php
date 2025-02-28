<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Verificar si se ha recibido un ID de carta para comprar
if (isset($_GET['id'])) {
    $buy_id = intval($_GET['id']);
    
    // Obtener la carta seleccionada
    $card_query = "SELECT card_id, seller_id, price FROM marketplace WHERE id = '$buy_id' AND is_sold = 0";
    $card_result = mysqli_query($conn, $card_query);
    
    if ($card = mysqli_fetch_assoc($card_result)) {
        $price = $card['price'];
        $seller_id = $card['seller_id'];

        // Obtener el balance del comprador
        $balance_query = "SELECT balance FROM users WHERE id = '$user_id'";
        $balance_result = mysqli_query($conn, $balance_query);
        $buyer_balance = mysqli_fetch_assoc($balance_result)['balance'];
        
        if ($buyer_balance >= $price) {
            // Restar el precio al comprador
            mysqli_query($conn, "UPDATE users SET balance = balance - $price WHERE id = '$user_id'");
            
            // Sumar el precio al vendedor
            mysqli_query($conn, "UPDATE users SET balance = balance + $price WHERE id = '$seller_id'");
            
            // Marcar la carta como vendida
            mysqli_query($conn, "UPDATE marketplace SET is_sold = 1 WHERE id = '$buy_id'");
            
            // Transferir la carta al comprador
            mysqli_query($conn, "INSERT INTO user_cards (user_id, card_id, obtained_at) VALUES ('$user_id', '{$card['card_id']}', NOW())");
            
            $message = "¡Has comprado la carta exitosamente!";
        } else {
            $message = "No tienes suficiente saldo para comprar esta carta.";
        }
    } else {
        $message = "Esta carta ya ha sido vendida o no existe.";
    }
}

// Obtener las cartas disponibles para comprar
$query = "SELECT marketplace.id, users.username AS seller, cards.name, cards.rarity, cards.image_url, marketplace.price 
          FROM marketplace 
          JOIN users ON marketplace.seller_id = users.id 
          JOIN cards ON marketplace.card_id = cards.id 
          WHERE marketplace.is_sold = 0";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Cartas - Card Exchange</title>
    <link rel="stylesheet" href="../assets/market.css">
</head>
<body>
    <header>
        <h1>Comprar Cartas</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="marketplace.php">Marketplace</a></li>
                <li><a href="game.php">Juego Diario</a></li>
                <li><a href="dinero.php">Agregar Fondos</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Cartas Disponibles para Comprar</h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <table>
            <tr>
                <th>Vendedor</th>
                <th>Carta</th>
                <th>Rareza</th>
                <th>Precio</th>
                <th>Comprar</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['seller']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['rarity']); ?></td>
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