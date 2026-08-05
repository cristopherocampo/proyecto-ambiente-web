<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($data['titulo'] ?? 'BookCycle') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/theme.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/custom-selects.css">
</head>
<body class="<?= !empty($_SESSION['user_id']) ? 'app-body' : 'guest-body' ?>">

<?php if (!empty($_SESSION['user_id'])): ?>
    <aside class="sidebar">
        <a class="brand" href="<?= BASE_URL ?>/catalogo/index">
            <span class="brand-mark">B</span>
            <span>
                <strong>BookCycle</strong>
                <small>Intercambio académico</small>
            </span>
        </a>

        <nav class="side-nav">
            <span class="nav-label">PRINCIPAL</span>
            <a href="<?= BASE_URL ?>/catalogo/index">⌕ <span>Explorar</span></a>
            <a href="<?= BASE_URL ?>/publicaciones/mis-publicaciones">▤ <span>Mis publicaciones</span></a>
            <a href="<?= BASE_URL ?>/publicaciones/crear">＋ <span>Publicar material</span></a>

            <span class="nav-label">CUENTA</span>
            <a href="<?= BASE_URL ?>/perfil/index">♙ <span>Mi perfil</span></a>
        </nav>

        <div class="side-bottom">
            <a href="<?= BASE_URL ?>/catalogo/index">? <span>Ayuda</span></a>
            <a class="danger" href="<?= BASE_URL ?>/auth/logout">↪ <span>Cerrar sesión</span></a>
        </div>
    </aside>

    <div class="app-shell">
        <header class="topbar">
            <button
                class="menu-toggle"
                type="button"
                aria-label="Abrir menú"
                onclick="document.body.classList.toggle('sidebar-open')"
            >☰</button>

            <div class="top-search">⌕ <span>Busca libros, autores o materiales...</span></div>

            <div class="top-user">
                <span class="notification">♧</span>
                <span class="avatar">
                    <?= htmlspecialchars(mb_strtoupper(mb_substr($_SESSION['user_nombre'] ?? 'U', 0, 1))) ?>
                </span>
                <span>
                    <strong><?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario') ?></strong>
                    <small>Estudiante</small>
                </span>
            </div>
        </header>

        <main class="container app-container">
<?php else: ?>
    <main class="container guest-container">
<?php endif; ?>
