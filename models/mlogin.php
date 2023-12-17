<?php

class mLogin {
    private $conexion;

    function __construct() {
        require_once __DIR__ . '/../config/configdb.php';
        $this->conexion = new mysqli(SERVIDOR, USUARIO, CONTRASENIA, BBDD);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        if (!$this->conexion->set_charset("utf8")) {
            printf("Error al establecer la conexión a UTF-8: %s\n", $this->conexion->error);
            exit();
        }
    }

    public function superadminExists() {
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE tipo_usuario_id = 1";
        $result = $this->conexion->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['count'] > 0; // Return true if at least one superadmin exists
        }
    
        return false;
    }

    public function crearSuperadmin($username, $hashedPassword) {
        $username = $this->conexion->real_escape_string($username);

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO usuarios (nombre, contrasenia, tipo_usuario_id) VALUES (?, ?, 1)";
        $stmt = $this->conexion->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $username, $hashedPassword);

        // Execute the statement
        $success = $stmt->execute();

        // Close the statement
        $stmt->close();

        return $success;
    }

    public function verificarCredenciales($username, $password) {
        $username = $this->conexion->real_escape_string($username);

        $sql = "SELECT idUsuario, nombre, contrasenia, tipo_usuario_id FROM usuarios WHERE nombre = '$username'";
        $result = $this->conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Use password_verify to check the hashed password
            if (password_verify($password, $user['contrasenia'])) {
                return $user;
            }
        }

        return null;
    }

    public function obtenerRoles() {
        $sql = "SELECT idTipo, tipo FROM tipos_usuario WHERE idTipo <> 1"; // Skip SuperAdmin role
        $result = $this->conexion->query($sql);
    
        $roles = [];
    
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }
    
        return $roles;
    }

    public function crearNuevoUsuario($nombre, $hashedPassword, $tipoUsuarioId) {
        $nombre = $this->conexion->real_escape_string($nombre);

        $sql = "INSERT INTO usuarios (nombre, contrasenia, tipo_usuario_id) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        // vinculo los parámetros
        $stmt->bind_param("ssi", $nombre, $hashedPassword, $tipoUsuarioId);

        // ejecuto la declaración
        $exitoso = $stmt->execute();

        // cierro la declaración
        $stmt->close();

        return $exitoso;
    }

}
?>
