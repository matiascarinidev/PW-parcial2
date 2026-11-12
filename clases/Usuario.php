<?php
class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConexion();
    }

    public function buscarPorEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }
    public function crear($email, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (email, password, rol) VALUES (?, ? , 'usuario')");
        $stmt->bind_param("ss", $email, $hash);
        $exito = $stmt->execute();
        $id = $this->db->insert_id;
        $stmt->close();
        return $exito ? $id : false;
    }
    public function existeEmail($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function actualizarRol($id, $rol)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
        $stmt->bind_param("si", $rol, $id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
    public function cambiarEstadoBaneo($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET baneado = ? WHERE id = ?");
        $stmt->bind_param("ii", $estado, $id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $exito = $stmt->execute();
        $stmt->close();
        return $exito;
    }
    public function obtenerTodos()
    {
        $sql = "SELECT u.id, u.email, u.rol, u.baneado, p.id_perfil, p.usuario, p.nombre, p.apellido 
                FROM usuarios u 
                LEFT JOIN perfiles p ON u.id = p.usuario_id";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}
