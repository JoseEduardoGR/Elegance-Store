<?php
// Funciones de seguridad y utilidades

// Función para limpiar datos de entrada
function limpiarDatos($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para verificar si el usuario está logueado
function verificarSesion() {
    if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
        header("Location: index.php?error=sesion_requerida");
        exit();
    }
}

// Función para generar token CSRF
function generarTokenCSRF() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función para verificar token CSRF
function verificarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Función para validar email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para validar contraseña
function validarPassword($password) {
    return strlen($password) >= 6;
}

// Función para mostrar mensajes
function mostrarMensaje($tipo, $mensaje) {
    return "<div class='mensaje mensaje-{$tipo}'>{$mensaje}</div>";
}
?>
