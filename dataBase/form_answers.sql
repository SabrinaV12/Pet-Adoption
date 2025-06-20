CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    address_line1 VARCHAR(100) NOT NULL,
    address_line2 VARCHAR(100),
    postcode VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    
    has_garden ENUM('Yes', 'No') NOT NULL,
    living_situation TEXT NOT NULL,
    household_setting TEXT NOT NULL,
    activity_level INT NOT NULL CHECK (activity_level BETWEEN 1 AND 10),
    
    num_adults INT  CHECK (num_adults>=1),
    num_children INT NOT NULL,
    youngest_child_age INT CHECK (youngest_child_age BETWEEN 1 AND 18),
    visiting_children ENUM('Yes', 'No'),
    visiting_children_ages INT CHECK (visiting_children_ages BETWEEN 1 AND 18),

    has_flatmates ENUM('Yes', 'No'),

    has_allergies TEXT NOT NULL,
    has_other_animals ENUM('Yes', 'No') NOT NULL,
    other_animals_info TEXT,
    neutered ENUM('Yes', 'No', 'Not Applicable') NOT NULL,
    vaccinated ENUM('Yes', 'No', 'Not Applicable') NOT NULL,
    experience TEXT,

    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
);
