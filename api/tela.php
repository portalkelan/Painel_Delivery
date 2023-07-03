<?php
require_once('../conn.php');

error_reporting(0);
ini_set("display_errors", 0 );

$usuario_get = $_GET['login'];
$senha = $_GET['senha'];

$busca_cliente = "SELECT * FROM login WHERE email = '$usuario_get' AND senha = '$senha' AND status = 'ativo'";
$cliente = mysqli_query($conn, $busca_cliente);
$total_clientes = mysqli_num_rows($cliente);
echo $total_clientes;