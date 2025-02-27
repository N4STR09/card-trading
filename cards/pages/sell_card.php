<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Procesar la venta de la carta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['card_id']) && isset($_POST['price'])) {
    $card_id = intval($_POST['card_id']);
    $price = floatval($_POST['price']);
    
    // Verificar que el usuario posee la carta
    $check_query = "SELECT id FROM user_cards WHERE user_id = '$user_id' AND card_id = '$card_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Insertar la carta en el marketplace
        $insert_query = "INSERT INTO marketplace (seller_id, card_id, price, is_sold, listed_at) VALUES ('$user_id', '$card_id', '$price', 0, NOW())";
        mysqli_query($conn, $insert_query);
        
        // Eliminar la carta del inventario del usuario
        mysqli_query($conn, "DELETE FROM user_cards WHERE user_id = '$user_id' AND card_id = '$card_id' LIMIT 1");
        
        $message = "¡Carta puesta en venta exitosamente!";
    } else {
        $message = "Error: No posees esta carta.";
    }
}

// Obtener las cartas del usuario
$cards_query = "SELECT user_cards.card_id, cards.name, cards.rarity, cards.image_url FROM user_cards 
               JOIN cards ON user_cards.card_id = cards.id WHERE user_cards.user_id = '$user_id'";
$cards_result = mysqli_query($conn, $cards_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender Carta - Card Exchange</title>
    <link rel="stylesheet" href="../assets/sell.css">
</head>
<body>
    <main>
        <h2>Poner una carta en venta</h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form action="sell_card.php" method="POST">
            <label for="card_id">Selecciona una carta:</label>
            <select name="card_id" required>
                <?php while ($row = mysqli_fetch_assoc($cards_result)) { ?>
                    <option value="<?php echo $row['card_id']; ?>">
                        <?php echo htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['rarity']) . ")"; ?>
                    </option>
                <?php } ?>
            </select>
            <label for="price">Precio ($):</label>
            <input type="number" step="0.01" name="price" required>
            <button type="submit">Poner en Venta</button><br>
        </form>
        <a href="../pages/marketplace.php">
            <button id="volver">Volver a Marketplace</button>
        </a>
    </main>
</body>
</html>
