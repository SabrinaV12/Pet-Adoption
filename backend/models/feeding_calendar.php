<?php

class FeedingCalendar
{
    public $id;
    public $pet_id;
    public $feed_date;
    public $food_type;

    /**
     * @param $id
     * @param $pet_id
     * @param $feed_date
     * @param $food_type
     */
    public function __construct($id, $pet_id, $feed_date, $food_type)
    {
        $this->id = $id;
        $this->pet_id = $pet_id;
        $this->feed_date = $feed_date;
        $this->food_type = $food_type;
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
    public function getFoodType()
    {
        return $this->food_type;
    }

    /**
     * @param mixed $food_type
     */
    public function setFoodType($food_type)
    {
        $this->food_type = $food_type;
    }

    /**
     * @return mixed
     */
    public function getFeedDate()
    {
        return $this->feed_date;
    }

    /**
     * @param mixed $feed_date
     */
    public function setFeedDate($feed_date)
    {
        $this->feed_date = $feed_date;
    }



}