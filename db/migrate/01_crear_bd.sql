-- 1. Crear una nueva base de datos para tu aplicación de prueba
CREATE DATABASE TestAppDB;
GO

-- 2. Cambiar el contexto a tu nueva base de datos
USE TestAppDB;
GO

-- 3. Crear el LOGIN (acceso a nivel de servidor)
--    Reemplaza 'UnaContraseñaSegura123' por la contraseña para tu app
CREATE LOGIN app_user WITH PASSWORD = 'UnaContraseñaSegura123';
GO

-- 4. Crear el USER (mapeado al LOGIN dentro de tu base de datos)
CREATE USER app_user FOR LOGIN app_user;
GO

-- 5. Darle permisos al usuario. Para desarrollo, db_owner es suficiente.
--    (En producción, darías permisos más específicos)
ALTER ROLE db_owner ADD MEMBER app_user;
GO

PRINT 'Base de datos, login y usuario creados exitosamente!';
