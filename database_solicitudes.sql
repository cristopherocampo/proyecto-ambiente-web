USE bookcycle;

CREATE TABLE IF NOT EXISTS publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    propietario_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    autor VARCHAR(150),
    descripcion TEXT,
    disponible TINYINT(1) DEFAULT 1,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (propietario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS estados_solicitud (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE
);

INSERT IGNORE INTO estados_solicitud (id, nombre) VALUES
(1, 'Pendiente'),
(2, 'Aceptada'),
(3, 'Rechazada'),
(4, 'Cancelada'),
(5, 'Completada');

CREATE TABLE IF NOT EXISTS solicitudes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    publicacion_id INT NOT NULL,
    solicitante_id INT NOT NULL,
    estado_solicitud_id INT NOT NULL DEFAULT 1,
    mensaje VARCHAR(500),
    motivo_rechazo VARCHAR(500),
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta DATETIME NULL,
    FOREIGN KEY (publicacion_id) REFERENCES publicaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (solicitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (estado_solicitud_id) REFERENCES estados_solicitud(id)
);

CREATE TABLE IF NOT EXISTS estados_intercambio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE
);

INSERT IGNORE INTO estados_intercambio (id, nombre) VALUES
(1, 'En proceso'),
(2, 'Completado'),
(3, 'Cancelado');

CREATE TABLE IF NOT EXISTS intercambios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitud_id INT NOT NULL UNIQUE,
    estado_intercambio_id INT NOT NULL DEFAULT 1,
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_finalizacion DATETIME NULL,
    FOREIGN KEY (solicitud_id) REFERENCES solicitudes(id) ON DELETE CASCADE,
    FOREIGN KEY (estado_intercambio_id) REFERENCES estados_intercambio(id)
);

CREATE TABLE IF NOT EXISTS historial_estados_solicitud (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitud_id INT NOT NULL,
    estado_solicitud_id INT NOT NULL,
    cambiado_por INT NOT NULL,
    comentario VARCHAR(500),
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (solicitud_id) REFERENCES solicitudes(id) ON DELETE CASCADE,
    FOREIGN KEY (estado_solicitud_id) REFERENCES estados_solicitud(id),
    FOREIGN KEY (cambiado_por) REFERENCES usuarios(id) ON DELETE CASCADE
);