USE parcial2;

--Tabla de usuarios
CREATE TABLE
  IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM ('admin', 'moderador', 'usuario') NOT NULL DEFAULT 'usuario',
    perfil_completo BOOLEAN NOT NULL DEFAULT FALSE,
    baneado BOOLEAN NOT NULL DEFAULT FALSE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE
  IF NOT EXISTS perfiles (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    biografia TEXT DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--Tabla de publicaciones
CREATE TABLE
  IF NOT EXISTS publicaciones (
    id_publicacion INT AUTO_INCREMENT PRIMARY KEY,
    autor_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    contenido TEXT NOT NULL,
    estado ENUM ('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    oculta BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios (id) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 4 Tabla de amistades
CREATE TABLE
  IF NOT EXISTS amistades (
    id_amistad INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    amigo_id INT NOT NULL,
    estado ENUM ('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE,
    FOREIGN KEY (amigo_id) REFERENCES usuarios (id) ON DELETE CASCADE,
    UNIQUE KEY (usuario_id, amigo_id)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- INSERCIÓN DE DATOS DE PRUEBA
-- Contraseña para todos los usuarios de prueba = 12345678
INSERT INTO
  usuarios (email, password, rol, perfil_completo, baneado)
VALUES
  (
    'admin@test.com',
    '$2y$10$e8...hash_de_prueba...',
    'admin',
    TRUE,
    FALSE
  ),
  (
    'moderador@test.com',
    '$2y$10$e8...hash_de_prueba...',
    'moderador',
    TRUE,
    FALSE
  ),
  (
    'usuario1@test.com',
    '$2y$10$e8...hash_de_prueba...',
    'usuario',
    TRUE,
    FALSE
  ),
  (
    'usuario2@test.com',
    '$2y$10$e8...hash_de_prueba...',
    'usuario',
    TRUE,
    FALSE
  );

-- Perfiles de prueba
INSERT INTO
  perfiles (
    usuario_id,
    usuario,
    nombre,
    apellido,
    telefono,
    biografia
  )
VALUES
  (
    1,
    'admin_foro',
    'Admin',
    'Principal',
    '123456789',
    'Administrador del foro.'
  ),
  (
    2,
    'mod_juan',
    'Juan',
    'Perez',
    '987654321',
    'Moderador de contenido.'
  ),
  (
    3,
    'pepe',
    'Jose',
    'Gomez',
    '555555555',
    'Usuario regular y amante del foro.'
  ),
  (
    4,
    'maria',
    'Maria',
    'Lopez',
    '444444444',
    'Nueva en el foro.'
  );

-- Publicaciones de prueba
INSERT INTO
  publicaciones (autor_id, titulo, contenido, estado, oculta)
VALUES
  (
    3,
    'Bienvenidos al foro',
    'Este es mi primer post. Saludos a todos.',
    'aprobada',
    FALSE
  ),
  (
    4,
    'Consulta sobre PHP',
    '¿Cómo puedo conectar a MySQLi de forma segura?',
    'pendiente',
    FALSE
  ),
  (
    3,
    'Mi experiencia',
    'Aprendiendo mucho en este foro.',
    'aprobada',
    FALSE
  );

-- Amistades de prueba
INSERT INTO
  amistades (usuario_id, amigo_id, estado)
VALUES
  (3, 4, 'aceptada'),
  (3, 2, 'pendiente');