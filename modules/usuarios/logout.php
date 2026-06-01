<?php
session_start();
session_destroy();

$pagina_anterior = isset($_GET['from']) ? $_GET['from'] : '../../pages/index ';
header("Location: " . $pagina_anterior);
exit();
?>