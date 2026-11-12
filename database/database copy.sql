ALTER TABLE usuarios
DROP COLUMN nombre,
DROP COLUMN apellido,
DROP COLUMN telefono,
ADD COLUMN perfil_completo BOOLEAN NOT NULL DEFAULT FALSE;

CREATE TABLE
  perfiles (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    biografia TEXT DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
  );

CREATE TABLE
  publicaciones (
    id_publicacion INT AUTO_INCREMENT PRIMARY KEY,
    autor_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    contenido TEXT NOT NULL,
    estado ENUM ('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id) ON DELETE CASCADE
  );

CREATE TABLE
  amistades (
    id_amistad INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    amigo_id INT NOT NULL,
    estado ENUM ('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE,
    FOREIGN KEY (amigo_id) REFERENCES usuarios (id) ON DELETE CASCADE,
    UNIQUE KEY (usuario_id, amigo_id)
  );

UPDATE usuarios
SET
  perfil_completo = TRUE
WHERE
  rol = 'admin';