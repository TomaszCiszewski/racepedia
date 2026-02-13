CREATE DATABASE racepedia;
USE racepedia;

CREATE TABLE users(
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50),
 password VARCHAR(255)
);

CREATE TABLE posts(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 content TEXT,
 FOREIGN KEY (user_id) REFERENCES users(id)
);
