<?php require_once '../app/views/layouts/header.php'; ?>

<div class="login-wrapper">
    <div class="glass-card login-card">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>
        
        <?php if (isset($data['exito'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($data['exito']) ?></div>
        <?php endif; ?>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>/auth/login" method="POST">
            <div class="form-group">
                <label for="email">Correo Institucional</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="estudiante@ufide.ac.cr">
            </div>
            
            <div class="form-group mt-3">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block mt-4">Ingresar al Sistema</button>

            <div class="text-center mt-3">
                <p>¿No tienes una cuenta? <a href="<?= BASE_URL ?>/auth/registro">Regístrate aquí</a></p>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>