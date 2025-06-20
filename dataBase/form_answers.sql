CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    address_line1 VARCHAR(100) NOT NULL,
    address_line2 VARCHAR(100) NOT NULL,
    postcode VARCHAR(20) NOT NULL,
    town VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    mobile VARCHAR(20),
    verification_code VARCHAR(10),
    
    has_garden ENUM('Yes', 'No') NOT NULL,
    living_situation VARCHAR(100) NOT NULL, -- am nevoie de optiuni
    household_setting VARCHAR(100) NOT NULL, -- am nevoie de optiuni
    activity_level VARCHAR(100) NOT NULL, -- am nevoie de optiuni
    
    num_adults INT  CHECK (num_adults>=1),
    num_children INT NOT NULL,
    youngest_child_age INT,
    visiting_children ENUM('Yes', 'No'),
    visiting_children_ages INT, -- am nevoie de optiuni
    has_flatmates ENUM('Yes', 'No'),

    has_allergies TEXT NOT NULL,
    has_other_animals ENUM('Yes', 'No') NOT NULL,
    other_animals_info TEXT,
    neutered ENUM('Yes', 'No', 'Not Applicable') NOT NULL,
    vaccinated ENUM('Yes', 'No', 'Not Applicable') NOT NULL,
    experience TEXT,

    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
);
