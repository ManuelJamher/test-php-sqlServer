// Espera a que todo el contenido del HTML esté cargado antes de ejecutar el script.
document.addEventListener('DOMContentLoaded', () => {
    
    // --- Variables y Constantes ---
    // La URL de nuestra API. Gracias al router, esta URL es manejada por index.php.
    const apiUrl = '/api/users.php';
    
    // Referencias a los elementos del formulario y la tabla en el HTML.
    const userForm = document.getElementById('user-form');
    const userList = document.getElementById('user-list');
    const userIdInput = document.getElementById('user-id');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const formTitle = document.getElementById('form-title');
    const cancelEditBtn = document.getElementById('cancel-edit');

    // --- Funciones Principales ---

    /**
     * Función para obtener todos los usuarios de la API y mostrarlos en la tabla.
     */
    const fetchUsers = async () => {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error(`Error de red: ${response.statusText}`);
            }
            const users = await response.json();
            
            // Limpiamos la tabla antes de añadir nuevas filas.
            userList.innerHTML = '';
            
            // Si no hay usuarios, mostramos un mensaje.
            if (users.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="4" class="text-center">No hay usuarios registrados.</td>`;
                userList.appendChild(tr);
                return;
            }

            // Creamos una fila (<tr>) por cada usuario.
            users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-edit" data-id="${user.id}" data-name="${user.name}" data-email="${user.email}">Editar</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${user.id}">Eliminar</button>
                    </td>
                `;
                userList.appendChild(tr);
            });
        } catch (error) {
            console.error('Error al obtener los usuarios:', error);
            userList.innerHTML = `<tr><td colspan="4" class="text-center text-danger">No se pudieron cargar los datos.</td></tr>`;
        }
    };

    /**
     * Resetea el formulario a su estado inicial (para añadir un nuevo usuario).
     */
    const resetForm = () => {
        userForm.reset(); // Limpia los campos de texto.
        userIdInput.value = ''; // Limpia el campo oculto del ID.
        formTitle.textContent = 'Añadir Usuario';
        cancelEditBtn.style.display = 'none'; // Oculta el botón de cancelar.
    };

    // --- Manejadores de Eventos ---

    /**
     * Se ejecuta cuando se envía el formulario (clic en "Guardar").
     */
    userForm.addEventListener('submit', async (e) => {
        e.preventDefault(); // Evita que la página se recargue.
        
        const id = userIdInput.value;
        const name = nameInput.value;
        const email = emailInput.value;

        // Preparamos los datos y la configuración para la petición fetch.
        const userData = { name, email };
        let method = 'POST'; // Por defecto, creamos un nuevo usuario (POST).
        let url = apiUrl;

        // Si hay un ID en el campo oculto, es una actualización (PUT).
        if (id) {
            method = 'PUT';
            userData.id = parseInt(id, 10); // Aseguramos que el ID sea un número.
        }

        try {
            await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(userData),
            });

            resetForm(); // Limpiamos el formulario después de guardar.
            fetchUsers(); // Volvemos a cargar la lista de usuarios para ver los cambios.
        } catch (error) {
            console.error('Error al guardar el usuario:', error);
            alert('No se pudo guardar el usuario. Revise la consola para más detalles.');
        }
    });

    /**
     * Se ejecuta cuando se hace clic en cualquier parte de la lista de usuarios.
     * Usamos "delegación de eventos" para no tener que añadir un listener a cada botón.
     */
    userList.addEventListener('click', async (e) => {
        // Si el clic fue en un botón de "Editar"...
        if (e.target.classList.contains('btn-edit')) {
            const id = e.target.dataset.id;
            const name = e.target.dataset.name;
            const email = e.target.dataset.email;

            // Llenamos el formulario con los datos del usuario a editar.
            userIdInput.value = id;
            nameInput.value = name;
            emailInput.value = email;
            formTitle.textContent = 'Editar Usuario';
      
            cancelEditBtn.style.display = 'inline-block'; // Mostramos el botón de cancelar.
            window.scrollTo(0, 0); // Hacemos scroll hacia arriba para ver el formulario.
        }

        // Si el clic fue en un botón de "Eliminar"...
        if (e.target.classList.contains('btn-delete')) {
            const id = e.target.dataset.id;
            if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                try {
                    await fetch(`${apiUrl}?id=${id}`, {
                        method: 'DELETE',
                    });
                    fetchUsers(); // Recargamos la lista para que el usuario desaparezca.
                } catch (error) {
                    console.error('Error al eliminar el usuario:', error);
                    alert('No se pudo eliminar el usuario. Revise la consola para más detalles.');
                }
            }
        }
    });

    /**
     * Se ejecuta cuando se hace clic en el botón "Cancelar" del formulario.
     */
    cancelEditBtn.addEventListener('click', resetForm);

    // --- Llamada Inicial ---
    
    // Al cargar la página, llamamos a fetchUsers() por primera vez para llenar la tabla.
    fetchUsers();
});