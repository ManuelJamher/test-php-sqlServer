# test-php-sqlServer
prueba de conexion



# instalar sql server en docker
docker run -e "ACCEPT_EULA=Y" -e "SA_PASSWORD=TuContraseñaFuerte!" \
   -p 1433:1433 --name sqlserver_dev -d \
   mcr.microsoft.com/mssql/server:2025-latest



Conéctate a tu contenedor:
Abre Azure Data Studio y crea una nueva conexión.
Server: localhost
Authentication type: SQL Login
User name: sa
Password: La que pusiste en el comando docker (TuContraseñaFuerte!).
Deja el resto de los campos como están y dale a "Connect"


Una vez conectado, haz clic derecho sobre tu servidor en el panel izquierdo y selecciona "New Query".
Pega y ejecuta los scripts de BD. Estos script crearán una base de datos, un login (para el servidor) y un usuario (para esa base de datos).



# Instalar PHP
La forma más fácil y limpia es usar un gestor de paquetes como Chocolatey.
Instala Chocolatey: Abre PowerShell como Administrador y ejecuta el comando que encuentras en su página de instalación.
Instala PHP: Una vez que tengas Chocolatey, cierra y vuelve a abrir PowerShell como Administrador y ejecuta:
code
Powershell
choco install php
Verifica: Cierra y abre una nueva terminal normal. Escribe php -v. ¡Debería funcionar! Chocolatey se encarga de agregar PHP a la variable de entorno PATH por ti.