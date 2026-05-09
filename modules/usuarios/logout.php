<?php
session_start();
session_destroy();

$pagina_anterior = isset($_GET['from']) ? $_GET['from'] : '/Eatstech/pages/index.php';
header("Location: " . $pagina_anterior);
exit();
?>