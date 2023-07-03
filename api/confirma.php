<?php
require_once('../conn.php');
$id = $_GET['id'];

$sql = "UPDATE envios SET status = '2' WHERE id='$id'";
$query = mysqli_query($conn,$sql);

print_r($_REQUEST);
?>