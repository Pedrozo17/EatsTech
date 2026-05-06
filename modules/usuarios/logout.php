<?php
session_start();
session_destroy();
header("Location: /Eatstech/pages/index.php");
exit();
?>