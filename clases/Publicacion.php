<?php
class Publicacion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConexion();
    }

    public function crear($autor_id, $contenido)
    {
        $stmt = $this->db->prepare("INSERT INTO publicaciones (autor_id, contenido, estado_visibilidad) VALUES (?, ?, 'visible')");
        $stmt->bind_param("is", $autor_id, $contenido);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }

    public function obtenerPublicacionesIndex($rol_usuario = 'usuario', $usuario_actual_id = 0)
    {
        $sql = "SELECT p.id_publicacion, p.autor_id, p.contenido, p.fecha_creacion, p.estado_visibilidad, perf.usuario,
                (SELECT COUNT(*) FROM likes l WHERE l.publicacion_id = p.id_publicacion) AS total_likes,
                (SELECT COUNT(*) FROM likes l WHERE l.publicacion_id = p.id_publicacion AND l.usuario_id = ?) AS dio_like
                FROM publicaciones p 
                JOIN perfiles perf ON p.autor_id = perf.usuario_id 
                JOIN usuarios u ON p.autor_id = u.id
                WHERE u.baneado = 0";

        if (!in_array($rol_usuario, ['admin', 'moderador'])) {
            $sql .= " AND p.estado_visibilidad = 'visible'";
        }
        $sql .= " ORDER BY p.fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuario_actual_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $res;
    }

    public function obtenerPublicacionesPerfil($usuario_id, $offset, $limite, $es_dueno = false, $rol_visitante = 'usuario', $usuario_actual_id = 0)
    {
        $sql = "SELECT id_publicacion, autor_id, contenido, fecha_creacion, estado_visibilidad,
                (SELECT COUNT(*) FROM likes l WHERE l.publicacion_id = publicaciones.id_publicacion) AS total_likes,
                (SELECT COUNT(*) FROM likes l WHERE l.publicacion_id = publicaciones.id_publicacion AND l.usuario_id = ?) AS dio_like
                FROM publicaciones 
                WHERE autor_id = ?";

        if (!$es_dueno && !in_array($rol_visitante, ['admin', 'moderador'])) {
            $sql .= " AND estado_visibilidad = 'visible'";
        }

        $sql .= " ORDER BY fecha_creacion DESC LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $usuario_actual_id, $usuario_id, $limite, $offset);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $res;
    }

    public function contarPublicacionesPerfil($usuario_id, $es_dueno = false, $rol_visitante = 'usuario')
    {
        $sql = "SELECT COUNT(*) as total FROM publicaciones WHERE autor_id = ?";
        if (!$es_dueno && !in_array($rol_visitante, ['admin', 'moderador'])) {
            $sql .= " AND estado_visibilidad = 'visible'";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        return $total;
    }

    public function obtenerTodasModeracion($busqueda = '')
    {
        $sql = "SELECT p.id_publicacion, p.contenido, p.estado_visibilidad, perf.usuario 
                FROM publicaciones p 
                JOIN perfiles perf ON p.autor_id = perf.usuario_id";

        if (!empty($busqueda)) {
            $sql .= " WHERE p.contenido LIKE ? OR perf.usuario LIKE ?";
        }

        $sql .= " ORDER BY p.fecha_creacion DESC";

        if (!empty($busqueda)) {
            $stmt = $this->db->prepare($sql);
            $like = "%" . $busqueda . "%";
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $res;
        }

        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    public function cambiarVisibilidad($id_pub, $nuevo_estado)
    {
        $stmt = $this->db->prepare("UPDATE publicaciones SET estado_visibilidad = ? WHERE id_publicacion = ?");
        $stmt->bind_param("si", $nuevo_estado, $id_pub);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }

    public function toggleLike($publicacion_id, $usuario_id)
    {
        // Verificar si ya dio like
        $stmt = $this->db->prepare("SELECT id_like FROM likes WHERE publicacion_id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $publicacion_id, $usuario_id);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();

        if ($existe) {
            $stmt = $this->db->prepare("DELETE FROM likes WHERE publicacion_id = ? AND usuario_id = ?");
        } else {
            $stmt = $this->db->prepare("INSERT INTO likes (publicacion_id, usuario_id) VALUES (?, ?)");
        }
        $stmt->bind_param("ii", $publicacion_id, $usuario_id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
}
