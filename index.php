<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro y Listado de Usuarios - LAMP Stack</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 650px; margin: 40px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #0066cc; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:disabled { background: #999; cursor: not-allowed; }
        #mensaje { margin-top: 15px; padding: 12px; display: none; border-radius: 4px; font-size: 0.9em; }
        .exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        /* Estilos de la Tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .sin-datos { text-align: center; color: #777; font-style: italic; }
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

    <h2>Usuarios Registrados</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody id="tablaUsuarios">
            <tr><td colspan="4" class="sin-datos">Cargando usuarios...</td></tr>
        </tbody>
    </table>

    <script>
        const form = document.getElementById('formUsuario');
        const mensajeDiv = document.getElementById('mensaje');
        const btnGuardar = document.getElementById('btnGuardar');
        const tablaUsuarios = document.getElementById('tablaUsuarios');

        // Función para obtener y listar usuarios desde el backend
        async function cargarUsuarios() {
            try {
                const response = await fetch('listar.php');
                const result = await response.json();

                if (result.success && result.data.length > 0) {
                    tablaUsuarios.innerHTML = result.data.map(u => `
                        <tr>
                            <td>${u.id}</td>
                            <td>${escapeHTML(u.nombre)}</td>
                            <td>${escapeHTML(u.email)}</td>
                            <td>${u.creado_en}</td>
                        </tr>
                    `).join('');
                } else {
                    tablaUsuarios.innerHTML = '<tr><td colspan="4" class="sin-datos">No hay usuarios registrados</td></tr>';
                }
            } catch (error) {
                tablaUsuarios.innerHTML = '<tr><td colspan="4" class="sin-datos">Error al cargar la lista</td></tr>';
            }
        }

        // Función para evitar ataques XSS
        function escapeHTML(str) {
            return str.replace(/[&<>'"]/g, 
                tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
            );
        }

        // Evento de envío del formulario
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
                    cargarUsuarios(); // Recargar la tabla automáticamente sin refrescar la página
                }
            } catch (error) {
                mensajeDiv.style.display = 'block';
                mensajeDiv.className = 'error';
                mensajeDiv.textContent = 'Error de comunicación con el servidor';
            } finally {
                btnGuardar.disabled = false;
            }
        });

        // Cargar usuarios al iniciar la página
        cargarUsuarios();
    </script>
</body>
</html>
