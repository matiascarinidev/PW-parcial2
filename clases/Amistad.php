<?php
class Amistad
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConexion();
    }
    public function enviarSolicitud($de_id, $para_id)
    {
        $stmt = $this->db->prepare("INSERT INTO amistades (usuario_id, amigo_id, estado) VALUES (?, ?, 'pendiente') ON DUPLICATE KEY UPDATE estado = 'pendiente'");
        $stmt->bind_param("ii", $de_id, $para_id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
    public function obtenerSolicitudesPendientes($usuario_id)
    {
        // Busca las solicitudes donde el usuario es el receptor (amigo_id)
        $stmt = $this->db->prepare("
            SELECT a.id_amistad, perf.usuario 
            FROM amistades a 
            JOIN perfiles perf ON a.usuario_id = perf.usuario_id 
            WHERE a.amigo_id = ? AND a.estado = 'pendiente'
        ");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $solicitudes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $solicitudes;
    }

    public function aceptarSolicitud($id_amistad, $usuario_id)
    {
        $stmt = $this->db->prepare("UPDATE amistades SET estado = 'aceptada' WHERE id_amistad = ? AND amigo_id = ?");
        $stmt->bind_param("ii", $id_amistad, $usuario_id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }

    public function rechazarSolicitud($id_amistad, $usuario_id)
    {
        $stmt = $this->db->prepare("DELETE FROM amistades WHERE id_amistad = ? AND amigo_id = ?");
        $stmt->bind_param("ii", $id_amistad, $usuario_id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }

    public function obtenerEstadoRelacion($usuario_id, $otro_usuario_id)
    {
        $stmt = $this->db->prepare("
            SELECT estado FROM amistades 
            WHERE (usuario_id = ? AND amigo_id = ?) 
               OR (usuario_id = ? AND amigo_id = ?)
        ");
        $stmt->bind_param("iiii", $usuario_id, $otro_usuario_id, $otro_usuario_id, $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res ? $res['estado'] : null;
    }
}
