<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookCycle - Plataforma de Intercambio Académico</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>/home/index">
                📚 BookCycle
            </a>
        </div>
        
        <div class="nav-menu">
            <a href="<?= BASE_URL ?>/home/index" class="btn-nav">Inicio</a>

            <a href="<?= BASE_URL ?>/perfil/index" class="btn-nav btn-primary-nav">
                👤 Mi Perfil
            </a>
            
            <span class="nav-user">
                😎 <strong><?= htmlspecialchars($_SESSION['user_nombre'] ?? $_SESSION['user_name'] ?? 'Estudiante') ?></strong>
            </span>
            
            <a href="<?= BASE_URL ?>/auth/logout" class="btn-nav btn-logout-nav">Cerrar Sesión</a>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container mt-4">