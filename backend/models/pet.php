<?php

class Pet
{
    public $id;
    public $name;
    public $gender;
    public $breed;
    public $age;
    public $color;
    public $weight;
    public $height;
    public $animal_type;
    public $image_path;
    public $size;
    public $vaccinated;
    public $house_trained;
    public $neutered;
    public $microchipped;
    public $good_with_children;
    public $shots_up_to_date;
    public $restrictions;
    public $recommended;
    public $adopted;
    public $adoption_date;
    public $description;
    public $user_id;
    public $created_at;

    /**
     * @param $id
     * @param $name
     * @param $breed
     * @param $gender
     * @param $age
     * @param $color
     * @param $weight
     * @param $height
     * @param $animal_type
     * @param $image_path
     * @param $size
     * @param $vaccinated
     * @param $house_trained
     * @param $neutered
     * @param $microchipped
     * @param $good_with_children
     * @param $shots_up_to_date
     * @param $restrictions
     * @param $recommended
     * @param $adopted
     * @param $adoption_date
     * @param $description
     * @param $user_id
     * @param $created_at
     */
    public function __construct($id, $name, $breed, $gender, $age, $color, $weight, $height, $animal_type, $image_path, $size, $vaccinated, $house_trained, $neutered, $microchipped, $good_with_children, $shots_up_to_date, $restrictions, $recommended, $adopted, $adoption_date, $description, $user_id, $created_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->breed = $breed;
        $this->gender = $gender;
        $this->age = $age;
        $this->color = $color;
        $this->weight = $weight;
        $this->height = $height;
        $this->animal_type = $animal_type;
        $this->image_path = $image_path;
        $this->size = $size;
        $this->vaccinated = $vaccinated;
        $this->house_trained = $house_trained;
        $this->neutered = $neutered;
        $this->microchipped = $microchipped;
        $this->good_with_children = $good_with_children;
        $this->shots_up_to_date = $shots_up_to_date;
        $this->restrictions = $restrictions;
        $this->recommended = $recommended;
        $this->adopted = $adopted;
        $this->adoption_date = $adoption_date;
        $this->description = $description;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getBreed()
    {
        return $this->breed;
    }

    /**
     * @param mixed $breed
     */
    public function setBreed($breed)
    {
        $this->breed = $breed;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getAnimalType()
    {
        return $this->animal_type;
    }

    /**
     * @param mixed $animal_type
     */
    public function setAnimalType($animal_type)
    {
        $this->animal_type = $animal_type;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getImagePath()
    {
        return $this->image_path;
    }

    /**
     * @param mixed $image_path
     */
    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getVaccinated()
    {
        return $this->vaccinated;
    }

    /**
     * @param mixed $vaccinated
     */
    public function setVaccinated($vaccinated)
    {
        $this->vaccinated = $vaccinated;
    }

    /**
     * @return mixed
     */
    public function getHouseTrained()
    {
        return $this->house_trained;
    }

    /**
     * @param mixed $house_trained
     */
    public function setHouseTrained($house_trained)
    {
        $this->house_trained = $house_trained;
    }

    /**
     * @return mixed
     */
    public function getNeutered()
    {
        return $this->neutered;
    }

    /**
     * @param mixed $neutered
     */
    public function setNeutered($neutered)
    {
        $this->neutered = $neutered;
    }

    /**
     * @return mixed
     */
    public function getMicrochipped()
    {
        return $this->microchipped;
    }

    /**
     * @param mixed $microchipped
     */
    public function setMicrochipped($microchipped)
    {
        $this->microchipped = $microchipped;
    }

    /**
     * @return mixed
     */
    public function getGoodWithChildren()
    {
        return $this->good_with_children;
    }

    /**
     * @param mixed $good_with_children
     */
    public function setGoodWithChildren($good_with_children)
    {
        $this->good_with_children = $good_with_children;
    }

    /**
     * @return mixed
     */
    public function getShotsUpToDate()
    {
        return $this->shots_up_to_date;
    }

    /**
     * @param mixed $shots_up_to_date
     */
    public function setShotsUpToDate($shots_up_to_date)
    {
        $this->shots_up_to_date = $shots_up_to_date;
    }

    /**
     * @return mixed
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * @param mixed $restrictions
     */
    public function setRestrictions($restrictions)
    {
        $this->restrictions = $restrictions;
    }

    /**
     * @return mixed
     */
    public function getRecommended()
    {
        return $this->recommended;
    }

    /**
     * @param mixed $recommended
     */
    public function setRecommended($recommended)
    {
        $this->recommended = $recommended;
    }

    /**
     * @return mixed
     */
    public function getAdopted()
    {
        return $this->adopted;
    }

    /**
     * @param mixed $adopted
     */
    public function setAdopted($adopted)
    {
        $this->adopted = $adopted;
    }

    /**
     * @return mixed
     */
    public function getAdoptionDate()
    {
        return $this->adoption_date;
    }

    /**
     * @param mixed $adoption_date
     */
    public function setAdoptionDate($adoption_date)
    {
        $this->adoption_date = $adoption_date;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
