<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$date = date('Y-m-d');
$message = "Completa el juego de memoria para recibir tus cartas.";
$cards_obtained = [];

// Verificar si el usuario ya jugó hoy
$check_query = "SELECT id FROM user_cards WHERE user_id = '$user_id' AND DATE(obtained_at) = '$date'";
$check_result = mysqli_query($conn, $check_query);
$already_claimed = mysqli_num_rows($check_result) > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($already_claimed) {
        echo json_encode(['message' => "Ya has reclamado tus cartas diarias.", 'cards' => []]);
        exit();
    }
    
    $cards_query = "SELECT id, name, rarity, image_url FROM cards ORDER BY RAND() LIMIT 5";
    $cards_result = mysqli_query($conn, $cards_query);
    
    while ($card = mysqli_fetch_assoc($cards_result)) {
        $card_id = $card['id'];
        $insert_query = "INSERT INTO user_cards (user_id, card_id, obtained_at) VALUES ('$user_id', '$card_id', NOW())";
        mysqli_query($conn, $insert_query);
        $cards_obtained[] = $card;
    }
    
    echo json_encode(['message' => "¡Has recibido 5 cartas nuevas!", 'cards' => $cards_obtained]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Diario - Card Exchange</title>
    <link rel="stylesheet" href="../assets/game.css">
</head>
<body>
    <header>
        <h1>Juego Diario</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="marketplace.php">Marketplace</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2 id="game-message"><?php echo $already_claimed ? "Ya has reclamado tus cartas diarias." : $message; ?></h2>
        <?php if (!$already_claimed): ?>
        <div class="memory-game" id="memory-game"></div>
        <button id="claim-cards" disabled>Reclamar Cartas</button>
        <?php endif; ?>
        <div id="reward-cards"></div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const alreadyClaimed = <?php echo $already_claimed ? 'true' : 'false'; ?>;
            if (alreadyClaimed) return; // Si ya reclamó, no ejecutar el juego
            
            const gameContainer = document.getElementById("memory-game");
            const claimButton = document.getElementById("claim-cards");
            const rewardContainer = document.getElementById("reward-cards");
            let cardImages = [
                { value: "A", img: "../assets/img/card1.jpg" },
                { value: "A", img: "../assets/img/card1.jpg" },
                { value: "B", img: "../assets/img/card2.jpg" },
                { value: "B", img: "../assets/img/card2.jpg" },
                { value: "C", img: "../assets/img/card3.jpg" },
                { value: "C", img: "../assets/img/card3.jpg" },
                { value: "D", img: "../assets/img/card4.jpg" },
                { value: "D", img: "../assets/img/card4.jpg" },
                { value: "E", img: "../assets/img/card5.jpg" },
                { value: "E", img: "../assets/img/card5.jpg" },
                { value: "F", img: "../assets/img/card6.jpg" },
                { value: "F", img: "../assets/img/card6.jpg" }
            ];
            let flippedCards = [];
            let matchedPairs = 0;
            
            cardImages = cardImages.sort(() => 0.5 - Math.random());
            
            cardImages.forEach(card => {
                const cardElement = document.createElement("div");
                cardElement.classList.add("card");
                cardElement.dataset.value = card.value;
                cardElement.innerHTML = `<img src="../assets/img/card-back.jpg" class="card-back">`;
                cardElement.addEventListener("click", () => flipCard(cardElement, card.img));
                gameContainer.appendChild(cardElement);
            });
            
            function flipCard(cardElement, imgSrc) {
                if (flippedCards.length < 2 && !cardElement.classList.contains("flipped")) {
                    cardElement.innerHTML = `<img src="${imgSrc}">`;
                    cardElement.classList.add("flipped");
                    flippedCards.push(cardElement);
                    
                    if (flippedCards.length === 2) {
                        setTimeout(checkMatch, 1000);
                    }
                }
            }
            
            function checkMatch() {
                if (flippedCards[0].dataset.value === flippedCards[1].dataset.value) {
                    matchedPairs++;
                    flippedCards = [];
                    if (matchedPairs === 6) {
                        claimButton.disabled = false;
                    }
                } else {
                    flippedCards.forEach(card => {
                        card.innerHTML = `<img src="../assets/img/card-back.jpg" class="card-back">`;
                        card.classList.remove("flipped");
                    });
                    flippedCards = [];
                }
            }
            
            claimButton.addEventListener("click", () => {
                fetch("game.php", { method: "POST" })
                    .then(response => response.json())
                    .then(data => {
                        claimButton.disabled = true;
                        rewardContainer.innerHTML = `<h3>${data.message}</h3>`;
                        data.cards.forEach(card => {
                            rewardContainer.innerHTML += `<div class='card'><img src='${card.image_url}'><p>${card.name} - ${card.rarity}</p></div>`;
                        });
                    });
            });
        });
    </script>
</body>
</html>