<?php

$es_local = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

if ($es_local) {
    // Ruta xampp
    define('BASE_URL', '/proyecto-foro-pw2/');
} else {
    // Ruta infinity
    define('BASE_URL', '/');
}
