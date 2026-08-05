<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="auth-shell">
    <div class="card auth-card">
        <div class="logo">📖</div>
        <h1>Bienvenido a BookCycle</h1>
        <p class="muted">Dale una nueva historia a tus libros.</p>

        <?php if (!empty($data['exito'])): ?>
            <div class="alert success"><?= htmlspecialchars($data['exito']) ?></div>
        <?php endif; ?>

        <?php if (!empty($data['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/auth/login">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($data['csrf']) ?>">

            <label>
                Correo electrónico
                <input type="email" name="email" required autocomplete="email">
            </label>

            <label>
                Contraseña
                <input type="password" name="password" required autocomplete="current-password">
            </label>

            <button class="btn primary" type="submit">Iniciar sesión</button>
        </form>

        <p class="center muted">
            ¿No tienes cuenta?
            <a href="<?= BASE_URL ?>/registro/index">Regístrate</a>
        </p>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
