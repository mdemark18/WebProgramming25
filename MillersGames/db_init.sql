CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100)
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
    category VARCHAR(50),
    image_path VARCHAR(255)
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price_each DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Sample User
INSERT INTO users (email, password, first_name)
VALUES ('testuser@example.com', '$2y$10$7tXNNBqaNgArLZP3qpv0qukMP0UtFGmX7u6znIqcl8y8AhZ4VtxmO', 'Test');
-- password: password123

-- Sample Products
INSERT INTO products (name, description, price, category, image_path) VALUES
('PlayStation 5', 'Next-gen gaming console from Sony', 499.99, 'Console', 'images/ps5.jpg'),
('Xbox Series X', 'Powerful console by Microsoft', 499.99, 'Console', 'images/xbox.jpg'),
('Nintendo Switch OLED', 'Hybrid console from Nintendo', 349.99, 'Console', 'images/switch.jpg'),
('DualSense Wireless Controller', 'Sony controller for PS5', 69.99, 'Controller', 'images/dualsense.jpg'),
('Xbox Wireless Controller', 'Microsoft controller for Xbox', 59.99, 'Controller', 'images/xbox-controller.jpg'),
('Zelda: Tears of the Kingdom', 'Adventure game by Nintendo', 69.99, 'Game', 'images/zelda.jpg'),
('Elden Ring', 'Action RPG from FromSoftware', 59.99, 'Game', 'images/eldenring.jpg'),
('Super Smash Bros Ultimate', 'Fighting game for Switch', 59.99, 'Game', 'images/smash.jpg');
-- More Games
INSERT INTO products (name, description, price, category, image_path) VALUES
('Call of Duty: Modern Warfare II', 'First-person shooter.', 69.99, 'Game', 'images/cod.jpg'),
('Spider-Man: Miles Morales', 'Action-adventure superhero game.', 49.99, 'Game', 'images/spiderman.jpg'),
('Mario Kart 8 Deluxe', 'Arcade racing game.', 59.99, 'Game', 'images/mariokart.jpg');

-- More Controllers
INSERT INTO products (name, description, price, category, image_path) VALUES
('PowerA Enhanced Wired Controller', 'Third-party wired controller for Xbox.', 29.99, 'Accessory', 'images/powera.jpg'),
('SCUF Reflex Pro Controller', 'High-performance wireless controller.', 199.99, 'Accessory', 'images/scuf.jpg');

-- Headsets
INSERT INTO products (name, description, price, category, image_path) VALUES
('HyperX Cloud II', 'Comfortable gaming headset with surround sound.', 89.99, 'Accessory', 'images/hyperx.jpg'),
('Logitech G Pro X', 'High-end wired gaming headset.', 129.99, 'Accessory', 'images/logitech-gpro.jpg');

-- Keyboards
INSERT INTO products (name, description, price, category, image_path) VALUES
('SteelSeries Apex Pro', 'Adjustable mechanical gaming keyboard.', 199.99, 'Accessory', 'images/apexpro.jpg'),
('Corsair K70 RGB', 'Mechanical keyboard with RGB lighting.', 159.99, 'Accessory', 'images/k70rgb.jpg');
