<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

$mensaje = '';
$tipo_mensaje = '';

// Procesar login
if ($_POST && isset($_POST['login'])) {
    $email = limpiarDatos($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $mensaje = "Por favor, complete todos los campos";
        $tipo_mensaje = "error";
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT id, nombre, email, password FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                header("Location: dashboard.php?success=login_exitoso");
                exit();
            } else {
                $mensaje = "Credenciales incorrectas";
                $tipo_mensaje = "error";
            }
        } else {
            $mensaje = "Usuario no encontrado";
            $tipo_mensaje = "error";
        }
    }
}

// Mostrar mensajes de URL
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'sesion_requerida':
            $mensaje = "Debe iniciar sesión para acceder";
            $tipo_mensaje = "error";
            break;
        case 'sesion_cerrada':
            $mensaje = "Sesión cerrada correctamente";
            $tipo_mensaje = "success";
            break;
    }
}

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'registro_exitoso':
            $mensaje = "Registro exitoso. Puede iniciar sesión";
            $tipo_mensaje = "success";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegance Store - Tienda de Ropa Elegante</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1 class="logo">Elegance Store</h1>
            <p class="tagline">Moda elegante para cada ocasión</p>
        </header>

        <main class="main-content">
            <div class="auth-container">
                <div class="auth-tabs">
                    <button class="tab-button active" onclick="mostrarTab('login')">Iniciar Sesión</button>
                    <button class="tab-button" onclick="mostrarTab('register')">Registrarse</button>
                </div>

                <?php if ($mensaje): ?>
                    <?php echo mostrarMensaje($tipo_mensaje, $mensaje); ?>
                <?php endif; ?>

                <!-- Formulario de Login -->
                <div id="login-form" class="auth-form active">
                    <h2>Iniciar Sesión</h2>
                    <form method="POST" onsubmit="return validarLogin()">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                            <span class="error-message" id="email-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" id="password" name="password" required>
                            <span class="error-message" id="password-error"></span>
                        </div>

                        <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
                    </form>
                </div>

                <!-- Formulario de Registro -->
                <div id="register-form" class="auth-form">
                    <h2>Crear Cuenta</h2>
                    <form method="POST" action="register.php" onsubmit="return validarRegistro()">
                        <div class="form-group">
                            <label for="reg-nombre">Nombre Completo:</label>
                            <input type="text" id="reg-nombre" name="nombre" required>
                            <span class="error-message" id="nombre-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="reg-email">Email:</label>
                            <input type="email" id="reg-email" name="email" required>
                            <span class="error-message" id="reg-email-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="reg-password">Contraseña:</label>
                            <input type="password" id="reg-password" name="password" required>
                            <span class="error-message" id="reg-password-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="reg-telefono">Teléfono:</label>
                            <input type="tel" id="reg-telefono" name="telefono">
                        </div>

                        <div class="form-group">
                            <label for="reg-direccion">Dirección:</label>
                            <textarea id="reg-direccion" name="direccion" rows="3"></textarea>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary">Crear Cuenta</button>
                    </form>
                </div>
            </div>
        </main>

        <footer class="footer">
            <p>&copy; 2024 Elegance Store. Todos los derechos reservados.</p>
        </footer>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
