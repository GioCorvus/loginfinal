<?php

require_once __DIR__ . '/../models/mlogin.php';

class CLogin {
    public $nombrePagina;

    public $view;

    public $objModelo;

    public $mensajeError;

    function __construct() {
        $this->objModelo = new MLogin();
    }

    public function mostrarInstalacion() {
        //compruebo si superadmin existe
        $superadminExists = $this->objModelo->superadminExists();
    
        if ($superadminExists) {
            $this->view = 'vlogin';
            $this->nombrePagina='';
        } else {
        //si no existe, vamos a la instalacion/registro del admin
            $this->view = 'vinstalacion';
            $this->nombrePagina = '';
        }
    }

    public function mostrarVistaSuperadmin() {
        $this->view = 'vsuperadmin';
        $this->nombrePagina = '';
    }

    public function mostrarFormularioLogin() {
        $this->view = 'vLogin';
        $this->nombrePagina = '';
    }

    public function altaAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // hasheo la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // por si acaso, compruebo si ya existe uno
            if ($this->objModelo->superadminExists()) {
                $this->mensajeError = "Ya existe un superadmin en la base de datos.";
                $this->mostrarFormularioAltaAdmin();
            } else {
                // inserto el superadmin en la bbdd
                $success = $this->objModelo->crearSuperadmin($username, $hashedPassword);

                if ($success) {
                    $this->mensajeError = "Superadmin creado con éxito.";
                    $this->mostrarFormularioLogin();
                } else {
                    $this->mensajeError = "Error al crear el superadmin. Por favor, inténtelo de nuevo.";
                    $this->mostrarFormularioAltaAdmin();
                }
            }
        }
    }

    public function mostrarFormularioAltaAdmin() {
        $this->view = 'vAltaAdmin';
        $this->nombrePagina = '';
    }

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            $usuario = $this->objModelo->verificarCredenciales($username, $password);
    
            if ($usuario) {
                // Iniciar sesión
                session_start();
    
                // Almacenar información del usuario en la sesión
                $_SESSION['usuario_id'] = $usuario['idUsuario'];
                $_SESSION['tipo_usuario_id'] = $usuario['tipo_usuario_id'];
    
                // Redireccionar según el tipo de usuario
                $this->redirectByUserRole($usuario['tipo_usuario_id']);
            } else {
                $this->mensajeError = "Credenciales incorrectas. Por favor, inténtelo de nuevo.";
                $this->mostrarFormularioLogin();
            }
        } else {
            // Mostrar formulario de login por defecto
            $this->mostrarFormularioLogin();
        }
    }
    
    

    private function redirectByUserRole($tipoUsuario) {
        $views = [
            1 => 'vSuperadmin',
            2 => 'vAdmin1',
            3 => 'vAdmin2',
        ];

        if (array_key_exists($tipoUsuario, $views)) {
            $this->view = $views[$tipoUsuario];
            $this->nombrePagina = '';
        } else {
            $this->mensajeError = "Error: Tipo de usuario no válido.";
            $this->mostrarFormularioLogin();
        }
    }

    public function getRoles() {
        $roles = $this->objModelo->obtenerRoles(); 
        return $roles;
    }

    /*CREAR USUARIOS CON EL SUPERADMIN*/
    public function crearNuevoUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearUsuario'])) {
            $nombre = $_POST['nombre'];
            $password = $_POST['password'];
            $tipoUsuarioId = $_POST['tipo_usuario_id'];

            // hasheo de la contraseña antes de almacenarla
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // llamo al método del modelo para crear el nuevo usuario
            $exitoso = $this->objModelo->crearNuevoUsuario($nombre, $hashedPassword, $tipoUsuarioId);

            if ($exitoso) {
                // si es exitoso, devuelvo al superadmin
                header("Location: index.php?c=cLogin&m=mostrarVistaSuperadmin");
                exit();
            } else {
                // sino, mensaje de error
                $this->mensajeError = "Error al crear el usuario. Por favor, inténtelo de nuevo.";
                $this->mostrarVistaSuperadmin();
            }
        } else {
            $this->mensajeError = "Acceso no válido.";
            $this->mostrarVistaSuperadmin();
        }
    }

    public function cerrarSesion() {

        session_start();
        session_destroy();

        header("Location: index.php");
        exit();
    }
}
?>