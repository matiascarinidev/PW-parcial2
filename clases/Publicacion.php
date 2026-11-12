<?php

class Publicacion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConexion();
    }

    public function crear($usuario_id, $titulo, $contenido)
    {
        $stmt = $this->db->prepare("INSERT INTO publicaciones (autor_id, titulo, contenido, estado) VALUES (?, ?, ?, 'pendiente')");
        $stmt->bind_param("iss", $usuario_id, $titulo, $contenido);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
    public function obtenerPublicas()
    {
        return $this->db->query("
        SELECT p.id_publicacion, p.titulo, p.contenido, p.fecha_creacion, perf.usuario
        FROM publicaciones p
        JOIN perfiles perf ON p.autor_id = perf.usuario_id
        JOIN usuarios u ON p.autor_id = u.id
        WHERE p.oculta = 0 AND u.baneado = 0 AND p.estado = 'aprobada'
        ORDER BY p.fecha_creacion DESC
        ")->fetch_all(MYSQLI_ASSOC);
    }
    public function obtenerPorUsuario($usuario_id)
    {
        $stmt = $this->db->prepare("
       SELECT id_publicacion, titulo, contenido, estado, fecha_creacion
       FROM publicaciones WHERE autor_id = ? ORDER BY fecha_creacion DESC
       ");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $pubs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $pubs;
    }
    public function obtenerTodasModeracion()
    {
        return $this->db->query("
        SELECT p.id_publicacion, p.titulo, p.oculta, perf.usuario
        FROM publicaciones p JOIN perfiles perf ON p.autor_id = perf.usuario_id
        ORDER BY p.fecha_creacion ASC
        ")->fetch_all(MYSQLI_ASSOC);
    }
    public function cambiarVisibilidad($id_pub, $oculta)
    {
        $stmt = $this->db->prepare("UPDATE publicaciones SET oculta = ? WHERE id_publicacion = ?");
        $stmt->bind_param("ii", $oculta, $id_pub);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
    public function obtenerPublicacionesPerfil($usuario_id, $offset, $limite)
    {
        $stmt = $this->db->prepare("
            SELECT titulo, contenido, fecha_creacion 
            FROM publicaciones 
            WHERE autor_id = ? AND estado = 'aprobada' AND oculta = 0 
            ORDER BY fecha_creacion DESC LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("iii", $usuario_id, $limite, $offset);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $res;
    }

    public function contarPublicacionesPerfil($usuario_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM publicaciones WHERE autor_id = ? AND estado = 'aprobada' AND oculta = 0");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        return $total;
    }
}
