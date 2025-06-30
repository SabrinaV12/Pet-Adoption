CREATE TABLE vaccinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT,
    age_in_weeks INT,
    vaccine_name VARCHAR(255),
    FOREIGN KEY (pet_id) REFERENCES pets(id)
);
