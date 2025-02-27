<?php
$host = "localhost";
$user = "pepe";
$password = "pepa";
$database = "card_exchange";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>