CREATE DATABASE IF NOT EXISTS dandeliva_db;
USE dandeliva_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price INT NOT NULL,
    description TEXT,
    image VARCHAR(255),
    stock INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    youtube_link TEXT,
    description TEXT
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    total_price INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Default admin login: admin / admin123
INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$J0MemBVhDDAOQCPVbAi/BecLOa.TmyG3qikaRAKx9bpo949I9jg1S');

-- Dummy data for products
INSERT INTO products (name, price, description, image, stock) VALUES 
('Dandeliva Original Gummy', 150000, 'Gummy herbal inovatif dengan ekstrak daun dandelion murni, efektif untuk detoksifikasi dan kesehatan pencernaan.', 'default_product.jpg', 100),
('Dandeliva Ginger & Honey', 165000, 'Gummy diformulasikan khusus dengan madu dan jahe untuk meningkatkan imun tubuh.', 'default_product.jpg', 50);

-- Dummy data for articles
INSERT INTO articles (title, content, image) VALUES
('Manfaat Daun Dandelion untuk Kesehatan Wanita', 'Daun dandelion memiliki berbagai manfaat kesehatan, terutama untuk wanita. Kaya akan antioksidan...', 'default_article.jpg'),
('Pentingnya Pangan Fungsional di Era Modern', 'Pangan fungsional tidak hanya sekedar makanan, melainkan memberikan manfaat tambahan...', 'default_article.jpg');

-- Dummy data for videos
INSERT INTO videos (title, youtube_link, description) VALUES
('Edukasi Herbal dan Gaya Hidup Sehat', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Video penjelasan tentang berbagai khasiat tanaman herbal yang bisa menunjang keseharian.');
