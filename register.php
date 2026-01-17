<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if ($_POST && isset($_POST['register'])) {
    $nombre = limpiarDatos($_POST['nombre']);
    $email = limpiarDatos($_POST['email']);
    $password = $_POST['password'];
    $telefono = limpiarDatos($_POST['telefono']);
    $direccion = limpiarDatos($_POST['direccion']);
    
    $errores = [];
    
    // Validaciones del servidor
    if (empty($nombre)) {
        $errores[] = "El nombre es requerido";
    }
    
    if (empty($email) || !validarEmail($email)) {
        $errores[] = "Email válido es requerido";
    }
    
    if (empty($password) || !validarPassword($password)) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    if (empty($errores)) {
        $database = new Database();
        $db = $database->getConnection();
        
        // Verificar si el email ya existe
        $query = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            header("Location: index.php?error=email_existe");
            exit();
        }
        
        // Insertar nuevo usuario
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO usuarios (nombre, email, password, telefono, direccion) VALUES (:nombre, :email, :password, :telefono, :direccion)";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=registro_exitoso");
            exit();
        } else {
            header("Location: index.php?error=error_registro");
            exit();
        }
    } else {
        $mensaje_error = implode(", ", $errores);
        header("Location: index.php?error=" . urlencode($mensaje_error));
        exit();
    }
}
?>
