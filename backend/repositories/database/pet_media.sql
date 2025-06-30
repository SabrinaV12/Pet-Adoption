CREATE TABLE pet_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type ENUM('image', 'video', 'audio') NOT NULL,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
);
