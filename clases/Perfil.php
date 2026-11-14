<?php

class Perfil
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConexion();
    }

    public function existeUsuario($usuario)
    {
        $stmt = $this->db->prepare("SELECT id_perfil FROM perfiles WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function crear($usuario_id, $usuario, $nombre, $apellido, $telefono, $biografia)
    {
        $stmt = $this->db->prepare("INSERT INTO perfiles (usuario_id,usuario,nombre,apellido,telefono,biografia) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("isssss", $usuario_id, $usuario, $nombre, $apellido, $telefono, $biografia);
        $exito = $stmt->execute();
        $stmt->close();

        if ($exito) {
            $stmt_upd = $this->db->prepare("UPDATE usuarios SET perfil_completo = TRUE WHERE id = ?");
            $stmt_upd->bind_param("i", $usuario_id);
            $stmt_upd->execute();
            $stmt_upd->close();
        }
        return $exito;
    }

    public function obtenerPorUsuarioId($usuario_id)
    {
        $stmt = $this->db->prepare("SELECT usuario, nombre, apellido, telefono, biografia FROM perfiles WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $perfil = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $perfil;
    }

    public function obtenerAmigos($usuario_id)
    {
        $stmt = $this->db->prepare("
        SELECT perf.usuario
        FROM amistades a JOIN perfiles perf ON a.amigo_id = perf.usuario_id
        WHERE a.usuario_id = ? AND a.estado = 'aceptada'
        UNION
        SELECT perf.usuario
        FROM amistades a JOIN perfiles perf ON a.usuario_id = perf.usuario_id
        WHERE a.amigo_id = ? AND a.estado = 'aceptada'
        ");
        $stmt->bind_param("ii", $usuario_id, $usuario_id);
        $stmt->execute();
        $amigos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $amigos;
    }
    public function actualizarPropio($usuario_id, $usuario, $nombre, $apellido, $telefono, $biografia)
    {
        $stmt = $this->db->prepare("UPDATE perfiles SET usuario = ?, nombre = ?, apellido = ?, telefono = ?, biografia = ? WHERE usuario_id = ?");
        $stmt->bind_param("sssssi", $usuario, $nombre, $apellido, $telefono, $biografia, $usuario_id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
    public function existeUsuarioEnOtroPerfil($usuario, $usuario_id_actual)
    {
        $stmt = $this->db->prepare("SELECT id_perfil FROM perfiles WHERE usuario = ? AND usuario_id != ?");
        $stmt->bind_param("si", $usuario, $usuario_id_actual);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }
}
