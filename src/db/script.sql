
CREATE TABLE IF NOT EXISTS donaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donante VARCHAR(100) NOT NULL,
    titulo_libro VARCHAR(255) NOT NULL,
    fecha_donacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    procesado_inventario BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO donaciones (donante, titulo_libro, procesado_inventario) 
VALUES ('Juan Perez', 'Cien años de soledad', FALSE),
       ('Maria Lopez', 'El Quijote', TRUE),
       ('Carlos Ruiz', 'Cronica de una muerte anunciada', FALSE);
