CREATE TABLE IF NOT EXISTS pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    breed VARCHAR(50),
    age INT NOT NULL CHECK (age >= 0),
    color VARCHAR(50),
    weight DECIMAL(5,2) CHECK (weight >= 0),
    height DECIMAL(5,2) CHECK (height >= 0),
    animal_type ENUM('Dog', 'Cat', 'Capybara') NOT NULL,
    image_path VARCHAR(255),

    vaccinated BOOLEAN DEFAULT FALSE,
    house_trained BOOLEAN DEFAULT FALSE,
    neutered BOOLEAN DEFAULT FALSE,
    microchipped BOOLEAN DEFAULT FALSE,
    good_with_children BOOLEAN DEFAULT FALSE,
    shots_up_to_date BOOLEAN DEFAULT FALSE,

    restrictions TEXT,
    recommended TEXT,
    
    adopted BOOLEAN DEFAULT FALSE,
    adoption_date DATE DEFAULT NULL
);
