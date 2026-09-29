<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios - LAMP Stack</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 480px; margin: 40px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #0066cc; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:disabled { background: #999; cursor: not-allowed; }
        #mensaje { margin-top: 15px; padding: 12px; display: none; border-radius: 4px; font-size: 0.9em; }
        .exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <h2>Registro de Usuario</h2>

    <form id="formUsuario">
        <div class="form-group">
            <label for="nombre">Nombre Completo</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ej. Sergio Necega" required>
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@dominio.com" required>
        </div>

        <button type="submit" id="btnGuardar">Guardar Usuario</button>
    </form>

    <div id="mensaje"></div>

    <script>
        const form = document.getElementById('formUsuario');
        const mensajeDiv = document.getElementById('mensaje');
        const btnGuardar = document.getElementById('btnGuardar');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const nombre = document.getElementById('nombre').value.trim();
            const email = document.getElementById('email').value.trim();

            btnGuardar.disabled = true;
            mensajeDiv.style.display = 'none';

            try {
                const response = await fetch('guardar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nombre, email })
                });

                const data = await response.json();

                mensajeDiv.style.display = 'block';
                mensajeDiv.className = data.success ? 'exito' : 'error';
                mensajeDiv.textContent = data.message;

                if (data.success) {
                    form.reset();
                }
            } catch (error) {
                mensajeDiv.style.display = 'block';
                mensajeDiv.className = 'error';
                mensajeDiv.textContent = 'Error de comunicación con el servidor';
            } finally {
                btnGuardar.disabled = false;
            }
        });
    </script>
</body>
</html>