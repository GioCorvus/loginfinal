<div class="container container-admin">
    <h1>Bienvenido SuperAdministrador</h1>
    <p>Rellena los datos para crear un nuevo tipo de admin:</p>

    <form action="index.php?c=cLogin&m=crearNuevoUsuario" method="post">
        <label for="nombre">Nombre de usuario:</label>
        <input type="text" name="nombre" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>

        <label for="tipo_usuario_id">Rol:</label>
        <select name="tipo_usuario_id" required>
            <?php
            $roles = $controlador->getRoles();

            foreach ($roles as $rol) {
                if ($rol['idTipo'] !== 1) {
                    echo "<option value='{$rol['idTipo']}'>{$rol['tipo']}</option>";
                }
            }
            ?>
        </select>

        <button type="submit" name="crearUsuario">Crear Usuario</button>
    </form>

    <!-- Cerrar Sesión link -->
    <p><a href="index.php?c=cLogin&m=cerrarSesion">Cerrar Sesión</a></p>
</div>
