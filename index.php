<?php

require_once "header.php";
require_once "nav.php";

if (isset($_SESSION['usuario_id'])) {
    echo "<h1>Panel de Usuario</h1>";
    echo "<h2>Bienvenido, " . htmlspecialchars($_SESSION['usuario_nombre']) . "</h2>";
} else {
    echo "<h2>Bienvenido a la página principal</h2>";
    echo "<p>Por favor, <a href='login.php'>inicia sesión</a> o <a href='registro.php'>regístrate</a>.</p>";
}
