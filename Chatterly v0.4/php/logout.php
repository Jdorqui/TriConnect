<?php
session_start(); //se accede a la sesion actual iniciandola 
session_unset(); //libera todas las variables guardadas
session_destroy(); //destruye la sesion

header("Location: ../html/index.html"); //te manda al index
exit();
?>