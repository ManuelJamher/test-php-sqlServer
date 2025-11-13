CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(100) NOT NULL,
    email NVARCHAR(100) NOT NULL UNIQUE
);

-- Insertar algunos datos de prueba
INSERT INTO users (name, email) VALUES ('Alice Smith', 'alice@example.com');
INSERT INTO users (name, email) VALUES ('Bob Johnson', 'bob@example.com');