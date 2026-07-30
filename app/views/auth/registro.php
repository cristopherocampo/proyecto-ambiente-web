<?php require_once '../app/views/layouts/header.php'; ?>

<div class="login-wrapper">
    <div class="glass-card login-card">
        <h2 class="text-center mb-4">Registro de Estudiante</h2>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>/auth/registro" method="POST">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Tu nombre">
                </div>
                <div class="col-md-6 form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control" required placeholder="Tus apellidos">
                </div>
            </div>

            <div class="form-group mt-3">
                <label for="email">Correo Institucional</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="estudiante@ufide.ac.cr">
            </div>
            
            <div class="form-group mt-3">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="form-group mt-3">
                <label for="institucion_id">Institución Educativa</label>
                <select name="institucion_id" id="institucion_id" class="form-control" required>
                    <option value="">Selecciona tu institución</option>
                    <?php if (isset($data['instituciones'])): ?>
                        <?php foreach ($data['instituciones'] as $inst): ?>
                            <option value="<?= $inst['id'] ?>"><?= htmlspecialchars($inst['nombre']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="carrera_id">Carrera</label>
                <select name="carrera_id" id="carrera_id" class="form-control" required>
                    <option value="">Selecciona tu carrera</option>
                    <?php if (isset($data['carreras'])): ?>
                        <?php foreach ($data['carreras'] as $carrera): ?>
                            <option value="<?= $carrera['id'] ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block mt-4">Crear Cuenta</button>

            <div class="text-center mt-3">
                <p>¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/auth/login">Inicia sesión</a></p>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>