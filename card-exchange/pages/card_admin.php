<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Obtener lista de cartas
$cards_query = "SELECT id, name FROM cards";
$cards_result = mysqli_query($conn, $cards_query);

// Procesar la acción de agregar o eliminar una carta del usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recipient_username']) && isset($_POST['card_id']) && isset($_POST['action'])) {
    $recipient_username = mysqli_real_escape_string($conn, trim($_POST['recipient_username']));
    $card_id = intval($_POST['card_id']);
    $action = $_POST['action'];

    // Obtener ID del destinatario
    $recipient_query = "SELECT id FROM users WHERE username = '$recipient_username' LIMIT 1";
    $recipient_result = mysqli_query($conn, $recipient_query);
    
    if (mysqli_num_rows($recipient_result) > 0) {
        $recipient_id = mysqli_fetch_assoc($recipient_result)['id'];

        // Verificar que el card_id existe en la tabla cards
        $check_card_query = "SELECT id FROM cards WHERE id = '$card_id' LIMIT 1";
        $check_card_result = mysqli_query($conn, $check_card_query);

        if (mysqli_num_rows($check_card_result) > 0) {
            // Acción de agregar carta
            if ($action == 'add') {
                // Verificar si la carta ya ha sido agregada
                $check_user_card_query = "SELECT id FROM user_cards WHERE user_id = '$recipient_id' AND card_id = '$card_id' LIMIT 1";
                $check_user_card_result = mysqli_query($conn, $check_user_card_query);

                if (mysqli_num_rows($check_user_card_result) == 0) {
                    // Agregar carta al usuario
                    $update_query = "INSERT INTO user_cards (user_id, card_id) VALUES ('$recipient_id', '$card_id')";
                    if (mysqli_query($conn, $update_query)) {
                        $message = "Has agregado la carta al usuario $recipient_username.";
                    } else {
                        $message = "Hubo un error al agregar la carta: " . mysqli_error($conn);
                    }
                } else {
                    $message = "La carta ya está asociada a este usuario.";
                }
            }
            // Acción de eliminar carta
            elseif ($action == 'remove') {
                // Eliminar carta del usuario
                $delete_query = "DELETE FROM user_cards WHERE user_id = '$recipient_id' AND card_id = '$card_id'";
                if (mysqli_query($conn, $delete_query)) {
                    $message = "Has eliminado la carta del usuario $recipient_username.";
                } else {
                    $message = "Hubo un error al eliminar la carta: " . mysqli_error($conn);
                }
            }
        } else {
            $message = "La carta seleccionada no existe.";
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
    <title>Agregar o Eliminar Carta - Card Exchange</title>
    <link rel="stylesheet" href="../assets/dinero.css">
</head>
<body>
    <header>
        <h1>Agregar o Eliminar Carta</h1>
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
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form action="dinero.php" method="POST">
            <label for="recipient_username">Usuario:</label>
            <input type="text" name="recipient_username" required>
            
            <label for="card_id">Selecciona Carta:</label>
            <select name="card_id" required>
                <option value="">Selecciona una carta</option>
                <?php while ($card = mysqli_fetch_assoc($cards_result)) { ?>
                    <option value="<?php echo $card['id']; ?>"><?php echo htmlspecialchars($card['name']); ?></option>
                <?php } ?>
            </select>
            
            <label for="action">Acción:</label>
            <select name="action" required>
                <option value="add">Agregar Carta</option>
                <option value="remove">Eliminar Carta</option>
            </select>
            
            <button type="submit">Confirmar Acción</button>
        </form>
    </main>
</body>
</html>
