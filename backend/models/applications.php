<?php

class Applications
{
    public $id;
    public $pet_id;
    public $applicant_id;
    public $address_line1;
    public $address_line2;
    public $postcode;
    public $city;
    public $phone_number;
    public $has_garden;
    public $living_situation;
    public $household_setting;
    public $activity_level;
    public $num_adults;
    public $num_children;
    public $youngest_child_age;
    public $visiting_children;
    public $visiting_children_ages;
    public $has_flatmates;
    public $has_allergies;
    public $has_other_animals;
    public $other_animals_info;
    public $neutered;
    public $vaccinated;
    public $experience;
    public $submitted_at;
    public $status;

    /**
     * @param $id
     * @param $pet_id
     * @param $applicant_id
     * @param $address_line1
     * @param $address_line2
     * @param $city
     * @param $postcode
     * @param $has_garden
     * @param $phone_number
     * @param $living_situation
     * @param $household_setting
     * @param $activity_level
     * @param $num_children
     * @param $num_adults
     * @param $youngest_child_age
     * @param $visiting_children
     * @param $visiting_children_ages
     * @param $has_flatmates
     * @param $has_allergies
     * @param $other_animals_info
     * @param $has_other_animals
     * @param $neutered
     * @param $vaccinated
     * @param $experience
     * @param $submitted_at
     * @param $status
     */
    public function __construct($id, $pet_id, $applicant_id, $address_line1, $address_line2, $city, $postcode, $has_garden, $phone_number, $living_situation, $household_setting, $activity_level, $num_children, $num_adults, $youngest_child_age, $visiting_children, $visiting_children_ages, $has_flatmates, $has_allergies, $other_animals_info, $has_other_animals, $neutered, $vaccinated, $experience, $submitted_at, $status)
    {
        $this->id = $id;
        $this->pet_id = $pet_id;
        $this->applicant_id = $applicant_id;
        $this->address_line1 = $address_line1;
        $this->address_line2 = $address_line2;
        $this->city = $city;
        $this->postcode = $postcode;
        $this->has_garden = $has_garden;
        $this->phone_number = $phone_number;
        $this->living_situation = $living_situation;
        $this->household_setting = $household_setting;
        $this->activity_level = $activity_level;
        $this->num_children = $num_children;
        $this->num_adults = $num_adults;
        $this->youngest_child_age = $youngest_child_age;
        $this->visiting_children = $visiting_children;
        $this->visiting_children_ages = $visiting_children_ages;
        $this->has_flatmates = $has_flatmates;
        $this->has_allergies = $has_allergies;
        $this->other_animals_info = $other_animals_info;
        $this->has_other_animals = $has_other_animals;
        $this->neutered = $neutered;
        $this->vaccinated = $vaccinated;
        $this->experience = $experience;
        $this->submitted_at = $submitted_at;
        $this->status = $status;
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
    public function getPetId()
    {
        return $this->pet_id;
    }

    /**
     * @param mixed $pet_id
     */
    public function setPetId($pet_id)
    {
        $this->pet_id = $pet_id;
    }

    /**
     * @return mixed
     */
    public function getApplicantId()
    {
        return $this->applicant_id;
    }

    /**
     * @param mixed $applicant_id
     */
    public function setApplicantId($applicant_id)
    {
        $this->applicant_id = $applicant_id;
    }

    /**
     * @return mixed
     */
    public function getAddressLine1()
    {
        return $this->address_line1;
    }

    /**
     * @param mixed $address_line1
     */
    public function setAddressLine1($address_line1)
    {
        $this->address_line1 = $address_line1;
    }

    /**
     * @return mixed
     */
    public function getAddressLine2()
    {
        return $this->address_line2;
    }

    /**
     * @param mixed $address_line2
     */
    public function setAddressLine2($address_line2)
    {
        $this->address_line2 = $address_line2;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getHasGarden()
    {
        return $this->has_garden;
    }

    /**
     * @param mixed $has_garden
     */
    public function setHasGarden($has_garden)
    {
        $this->has_garden = $has_garden;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getLivingSituation()
    {
        return $this->living_situation;
    }

    /**
     * @param mixed $living_situation
     */
    public function setLivingSituation($living_situation)
    {
        $this->living_situation = $living_situation;
    }

    /**
     * @return mixed
     */
    public function getHouseholdSetting()
    {
        return $this->household_setting;
    }

    /**
     * @param mixed $household_setting
     */
    public function setHouseholdSetting($household_setting)
    {
        $this->household_setting = $household_setting;
    }

    /**
     * @return mixed
     */
    public function getNumAdults()
    {
        return $this->num_adults;
    }

    /**
     * @param mixed $num_adults
     */
    public function setNumAdults($num_adults)
    {
        $this->num_adults = $num_adults;
    }

    /**
     * @return mixed
     */
    public function getActivityLevel()
    {
        return $this->activity_level;
    }

    /**
     * @param mixed $activity_level
     */
    public function setActivityLevel($activity_level)
    {
        $this->activity_level = $activity_level;
    }

    /**
     * @return mixed
     */
    public function getNumChildren()
    {
        return $this->num_children;
    }

    /**
     * @param mixed $num_children
     */
    public function setNumChildren($num_children)
    {
        $this->num_children = $num_children;
    }

    /**
     * @return mixed
     */
    public function getYoungestChildAge()
    {
        return $this->youngest_child_age;
    }

    /**
     * @param mixed $youngest_child_age
     */
    public function setYoungestChildAge($youngest_child_age)
    {
        $this->youngest_child_age = $youngest_child_age;
    }

    /**
     * @return mixed
     */
    public function getVisitingChildren()
    {
        return $this->visiting_children;
    }

    /**
     * @param mixed $visiting_children
     */
    public function setVisitingChildren($visiting_children)
    {
        $this->visiting_children = $visiting_children;
    }

    /**
     * @return mixed
     */
    public function getVisitingChildrenAges()
    {
        return $this->visiting_children_ages;
    }

    /**
     * @param mixed $visiting_children_ages
     */
    public function setVisitingChildrenAges($visiting_children_ages)
    {
        $this->visiting_children_ages = $visiting_children_ages;
    }

    /**
     * @return mixed
     */
    public function getHasFlatmates()
    {
        return $this->has_flatmates;
    }

    /**
     * @param mixed $has_flatmates
     */
    public function setHasFlatmates($has_flatmates)
    {
        $this->has_flatmates = $has_flatmates;
    }

    /**
     * @return mixed
     */
    public function getHasAllergies()
    {
        return $this->has_allergies;
    }

    /**
     * @param mixed $has_allergies
     */
    public function setHasAllergies($has_allergies)
    {
        $this->has_allergies = $has_allergies;
    }

    /**
     * @return mixed
     */
    public function getOtherAnimalsInfo()
    {
        return $this->other_animals_info;
    }

    /**
     * @param mixed $other_animals_info
     */
    public function setOtherAnimalsInfo($other_animals_info)
    {
        $this->other_animals_info = $other_animals_info;
    }

    /**
     * @return mixed
     */
    public function getHasOtherAnimals()
    {
        return $this->has_other_animals;
    }

    /**
     * @param mixed $has_other_animals
     */
    public function setHasOtherAnimals($has_other_animals)
    {
        $this->has_other_animals = $has_other_animals;
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
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getSubmittedAt()
    {
        return $this->submitted_at;
    }

    /**
     * @param mixed $submitted_at
     */
    public function setSubmittedAt($submitted_at)
    {
        $this->submitted_at = $submitted_at;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
