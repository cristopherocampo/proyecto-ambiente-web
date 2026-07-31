<?php require_once 'app/views/layouts/header.php'; ?>

<div class="login-wrapper">
    <div class="glass-card login-card">
        
        <div class="auth-header">
            <div class="logo-icon">📖</div>
            <h2>Registro de Estudiante</h2>
            <p class="subtitle">Únete a la economía circular académica más grande del país.</p>
        </div>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/registro/registrar" method="POST">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Daniel">
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control" required placeholder="Ocampo">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Correo universitario</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="tu.nombre@universidad.edu">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="institucion_id">Universidad</label>
                    <select name="institucion_id" id="institucion_id" class="form-control" required>
                        <option value="">Seleccionar</option>
                        <?php if (isset($data['instituciones'])): ?>
                            <?php foreach ($data['instituciones'] as $inst): ?>
                                <option value="<?= $inst['id'] ?>"><?= htmlspecialchars($inst['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="carrera_id">Carrera</label>
                    <select name="carrera_id" id="carrera_id" class="form-control" required>
                        <option value="">Seleccionar</option>
                        <?php if (isset($data['carreras'])): ?>
                            <?php foreach ($data['carreras'] as $carrera): ?>
                                <option value="<?= $carrera['id'] ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary">
                Crear cuenta ➔
            </button>

            <div class="divider"></div>

            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="<?= BASE_URL ?>/auth/index">Iniciar sesión</a></p>
            </div>
        </form>

    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>