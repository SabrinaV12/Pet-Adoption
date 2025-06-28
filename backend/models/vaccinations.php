<?php

class Vaccination
{
    public $id;
    public $pet_id;
    public $age_in_weeks;
    public $vaccine_name;

    /**
     * @param $id
     * @param $pet_id
     * @param $age_in_weeks
     * @param $vaccine_name
     */
    public function __construct($id, $pet_id, $age_in_weeks, $vaccine_name)
    {
        $this->id = $id;
        $this->pet_id = $pet_id;
        $this->age_in_weeks = $age_in_weeks;
        $this->vaccine_name = $vaccine_name;
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
    public function getAgeInWeeks()
    {
        return $this->age_in_weeks;
    }

    /**
     * @param mixed $age_in_weeks
     */
    public function setAgeInWeeks($age_in_weeks)
    {
        $this->age_in_weeks = $age_in_weeks;
    }

    /**
     * @return mixed
     */
    public function getVaccineName()
    {
        return $this->vaccine_name;
    }

    /**
     * @param mixed $vaccine_name
     */
    public function setVaccineName($vaccine_name)
    {
        $this->vaccine_name = $vaccine_name;
    }



}