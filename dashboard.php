<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verificar sesión
verificarSesion();

$database = new Database();
$db = $database->getConnection();

$mensaje = '';
$tipo_mensaje = '';

// Obtener datos del usuario
$query = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Procesar actualización de perfil
if ($_POST && isset($_POST['actualizar_perfil'])) {
    if (!verificarTokenCSRF($_POST['csrf_token'])) {
        $mensaje = "Token de seguridad inválido";
        $tipo_mensaje = "error";
    } else {
        $nombre = limpiarDatos($_POST['nombre']);
        $telefono = limpiarDatos($_POST['telefono']);
        $direccion = limpiarDatos($_POST['direccion']);
        
        $query = "UPDATE usuarios SET nombre = :nombre, telefono = :telefono, direccion = :direccion WHERE id = :id";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':id', $_SESSION['usuario_id']);
        
        if ($stmt->execute()) {
            $_SESSION['usuario_nombre'] = $nombre;
            $mensaje = "Perfil actualizado correctamente";
            $tipo_mensaje = "success";
            
            // Recargar datos del usuario
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $_SESSION['usuario_id']);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $mensaje = "Error al actualizar el perfil";
            $tipo_mensaje = "error";
        }
    }
}

// Procesar eliminación de cuenta
if ($_POST && isset($_POST['eliminar_cuenta'])) {
    if (!verificarTokenCSRF($_POST['csrf_token'])) {
        $mensaje = "Token de seguridad inválido";
        $tipo_mensaje = "error";
    } else {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_SESSION['usuario_id']);
        
        if ($stmt->execute()) {
            session_destroy();
            header("Location: index.php?success=cuenta_eliminada");
            exit();
        } else {
            $mensaje = "Error al eliminar la cuenta";
            $tipo_mensaje = "error";
        }
    }
}

// Obtener productos para mostrar
$query = "SELECT * FROM productos ORDER BY fecha_creacion DESC LIMIT 8";
$stmt = $db->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar mensajes de URL
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'login_exitoso':
            $mensaje = "¡Bienvenido de vuelta!";
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
    <title>Dashboard - Elegance Store</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <h1 class="logo">Elegance Store</h1>
                <div class="user-info">
                    <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                    <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
                </div>
            </div>
        </header>

        <main class="dashboard-main">
            <div class="dashboard-content">
                <?php if ($mensaje): ?>
                    <?php echo mostrarMensaje($tipo_mensaje, $mensaje); ?>
                <?php endif; ?>

                <div class="dashboard-tabs">
                    <button class="tab-button active" onclick="mostrarDashboardTab('perfil')">Mi Perfil</button>
                    <button class="tab-button" onclick="mostrarDashboardTab('productos')">Productos</button>
                    <button class="tab-button" onclick="mostrarDashboardTab('configuracion')">Configuración</button>
                </div>

                <!-- Tab de Perfil -->
                <div id="perfil-tab" class="dashboard-tab active">
                    <div class="profile-section">
                        <h2>Información del Perfil</h2>
                        <div class="profile-info">
                            <div class="info-card">
                                <h3>Datos Personales</h3>
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono'] ?: 'No especificado'); ?></p>
                                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion'] ?: 'No especificada'); ?></p>
                                <p><strong>Miembro desde:</strong> <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></p>
                            </div>
                        </div>

                        <div class="profile-form">
                            <h3>Actualizar Perfil</h3>
                            <form method="POST" onsubmit="return validarActualizacion()">
                                <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>">
                                
                                <div class="form-group">
                                    <label for="nombre">Nombre Completo:</label>
                                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                                    <span class="error-message" id="nombre-update-error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="direccion">Dirección:</label>
                                    <textarea id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
                                </div>

                                <button type="submit" name="actualizar_perfil" class="btn btn-primary">Actualizar Perfil</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab de Productos -->
                <div id="productos-tab" class="dashboard-tab">
                    <h2>Nuestros Productos</h2>
                    <div class="productos-grid">
                        <?php foreach ($productos as $producto): ?>
                            <div class="producto-card">
                                <div class="producto-imagen">
                                    <img src="/placeholder.svg?height=200&width=200" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                </div>
                                <div class="producto-info">
                                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                                    <p class="producto-descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                    <div class="producto-detalles">
                                        <span class="precio">$<?php echo number_format($producto['precio'], 2); ?></span>
                                        <span class="talla">Talla: <?php echo htmlspecialchars($producto['talla']); ?></span>
                                        <span class="color">Color: <?php echo htmlspecialchars($producto['color']); ?></span>
                                    </div>
                                    <div class="producto-stock">
                                        <span class="stock">Stock: <?php echo $producto['stock']; ?> unidades</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tab de Configuración -->
                <div id="configuracion-tab" class="dashboard-tab">
                    <h2>Configuración de Cuenta</h2>
                    <div class="config-section">
                        <div class="danger-zone">
                            <h3>Zona de Peligro</h3>
                            <p>Las siguientes acciones son irreversibles. Proceda con precaución.</p>
                            
                            <form method="POST" onsubmit="return confirmarEliminacion()">
                                <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>">
                                <button type="submit" name="eliminar_cuenta" class="btn btn-danger">Eliminar Cuenta</button>
                            </form>
                        </div>
                    </div>
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
