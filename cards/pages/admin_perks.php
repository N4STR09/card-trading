<?php
session_start();
include '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];

// Obtener el saldo del usuario
$balance_query = "SELECT balance FROM users WHERE id = '$user_id'";
$balance_result = mysqli_query($conn, $balance_query);
$balance = mysqli_fetch_assoc($balance_result)['balance'];

// Obtener las cartas del usuario
$cards_query = "SELECT cards.name, cards.rarity, cards.image_url FROM user_cards 
               JOIN cards ON user_cards.card_id = cards.id WHERE user_cards.user_id = '$user_id'";
$cards_result = mysqli_query($conn, $cards_query);

// Obtener notificaciones (ventas de cartas)
$notifications_query = "SELECT marketplace.price, cards.name FROM marketplace 
                        JOIN cards ON marketplace.card_id = cards.id 
                        WHERE marketplace.seller_id = '$user_id' AND marketplace.is_sold = 1 AND marketplace.notified = 0";
$notifications_result = mysqli_query($conn, $notifications_query);

// Marcar notificaciones como vistas
mysqli_query($conn, "UPDATE marketplace SET notified = 1 WHERE seller_id = '$user_id' AND is_sold = 1");
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
        <h2>Mi Dinero</h2>
        <p>Saldo actual: <strong>$<?php echo number_format($balance, 2); ?></strong></p>
        
        <h2>Mis Cartas</h2>
        <div class="cards-container">
            <?php while ($card = mysqli_fetch_assoc($cards_result)) { ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($card['image_url']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
                    <p><?php echo htmlspecialchars($card['name']); ?> - <?php echo htmlspecialchars($card['rarity']); ?></p>
                </div>
            <?php } ?>
        </div>

        <h2>Notificaciones</h2>
        <div class="notifications">
            <?php if (mysqli_num_rows($notifications_result) > 0) { ?>
                <ul>
                    <?php while ($notification = mysqli_fetch_assoc($notifications_result)) { ?>
                        <h3>Has vendido la carta <strong><?php echo htmlspecialchars($notification['name']); ?></strong> por $<?php echo number_format($notification['price'], 2); ?>.</h3>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No hay nuevas notificaciones.</p>
            <?php } ?>
        </div>
    </main>
</body>
</html>