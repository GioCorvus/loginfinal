<div class="container">
        <form action="index.php?c=cLogin&m=autenticar" method="post">
            <label for="username">Usuario:</label>
            <input type="text" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>

            <?php
            if (!empty($controlador->mensajeError)) {
                echo '<div class="error-message">' . $controlador->mensajeError . '</div>'.'<br>';
            }
            ?>

            <button type="submit" name="login">Iniciar sesión</button>
        </form>
</div>