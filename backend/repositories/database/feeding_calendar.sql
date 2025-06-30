CREATE TABLE feeding_calendar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT,
    feed_date DATE,
    food_type VARCHAR(255),
    FOREIGN KEY (pet_id) REFERENCES pets(id)
);
