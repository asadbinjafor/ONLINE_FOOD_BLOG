-- Online Food Blog — database name matches project folder: project3
-- Import this whole file in phpMyAdmin (Import tab), not one query at a time.
-- If you see "Table already exists", the DROP block below clears old tables first.

CREATE DATABASE IF NOT EXISTS project3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE project3;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS food_experience_comments;
DROP TABLE IF EXISTS food_experience_posts;
DROP TABLE IF EXISTS restaurant_reviews;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS restaurants;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'member') NOT NULL DEFAULT 'member',
    profile_picture VARCHAR(255) DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE restaurants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(160) NOT NULL,
    location VARCHAR(120) NOT NULL,
    area VARCHAR(120) NOT NULL,
    short_background TEXT NOT NULL,
    goals TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE menu_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT UNSIGNED NOT NULL,
    name VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_menu_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    menu_item_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_review_item FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    CONSTRAINT fk_review_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE restaurant_reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_rr_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    CONSTRAINT fk_rr_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE food_experience_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    post_type ENUM('restaurant', 'food', 'both') NOT NULL DEFAULT 'food',
    restaurant_id INT UNSIGNED DEFAULT NULL,
    menu_item_id INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_fep_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_fep_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE SET NULL,
    CONSTRAINT fk_fep_menu FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE food_experience_comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_fec_post FOREIGN KEY (post_id) REFERENCES food_experience_posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_fec_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Default admin (password: Admin@123) and demo member (password: Member@123)
INSERT INTO users (name, email, password_hash, role) VALUES
('Site Admin', 'admin@foodblog.local', '$2y$12$ViGxmPjpNbjvRczcJSURF.6rGVzjICYNkz9jr2JZ/TLaOmZdCnvxW', 'admin'),
('Demo Member', 'member@foodblog.local', '$2y$12$sCBP0Zyix4P/F/HwxTu.FenUX9ouohLlXk2OQj0qHGakaj.OpVM5i', 'member');

INSERT INTO restaurants (name, location, area, short_background, goals) VALUES
('Spice Garden', 'Dhaka', 'Gulshan', 'Authentic Bangladeshi cuisine with family recipes since 1998.', 'Celebrate local flavors and seasonal ingredients.'),
('Pasta Bella', 'Dhaka', 'Banani', 'Italian trattoria serving handmade pasta and wood-fired pizzas.', 'Bring classic Italian comfort food to the city.'),
('Sushi Harbor', 'Chittagong', 'Agrabad', 'Fresh seafood and traditional Japanese techniques.', 'Promote sustainable fishing and omakase experiences.');

INSERT INTO menu_items (restaurant_id, name, description, price) VALUES
(1, 'Kacchi Biryani', 'Slow-cooked mutton biryani with potatoes and aromatic spices.', 450.00),
(1, 'Beef Tehari', 'Fragrant rice with tender beef and caramelized onions.', 380.00),
(2, 'Truffle Pasta', 'Creamy fettuccine with truffle oil and parmesan.', 650.00),
(2, 'Margherita Pizza', 'Stone-baked pizza with fresh mozzarella and basil.', 520.00),
(3, 'Salmon Nigiri Set', 'Six pieces of fresh salmon nigiri with wasabi.', 890.00),
(3, 'Dragon Roll', 'Tempura shrimp roll topped with avocado and eel sauce.', 720.00);

INSERT INTO food_experience_posts (user_id, title, content, post_type, restaurant_id, menu_item_id) VALUES
(2, 'First bite at Spice Garden', 'The kacchi biryani was perfectly layered — tender meat and fragrant rice. A must-try for anyone new to Dhaka food scene.', 'both', 1, 1);
