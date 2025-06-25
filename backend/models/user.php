<?php
class User
{
    public $id;
    public $first_name;
    public $last_name;
    public $username;
    public $email;
    public $phone_number;
    public $hash_password;
    public $role;
    public $description;
    public $country;
    public $county;
    public $telegram_handle;
    public $profile_picture;
    public $banner_picture;

    /**
     * @param $id
     * @param $profile_picture
     * @param $first_name
     * @param $last_name
     * @param $username
     * @param $email
     * @param $phone_number
     * @param $hash_password
     * @param $description
     * @param $role
     * @param $county
     * @param $country
     * @param $telegram_handle
     * @param $banner_picture
     */
    public function __construct($id, $profile_picture, $first_name, $last_name, $username, $email, $phone_number, $hash_password, $description, $role, $county, $country, $telegram_handle, $banner_picture)
    {
        $this->id = $id;
        $this->profile_picture = $profile_picture;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->email = $email;
        $this->phone_number = $phone_number;
        $this->hash_password = $hash_password;
        $this->description = $description;
        $this->role = $role;
        $this->county = $county;
        $this->country = $country;
        $this->telegram_handle = $telegram_handle;
        $this->banner_picture = $banner_picture;
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
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
    public function getHashPassword()
    {
        return $this->hash_password;
    }

    /**
     * @param mixed $hash_password
     */
    public function setHashPassword($hash_password)
    {
        $this->hash_password = $hash_password;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     */
    public function setCounty($county)
    {
        $this->county = $county;
    }

    /**
     * @return mixed
     */
    public function getTelegramHandle()
    {
        return $this->telegram_handle;
    }

    /**
     * @param mixed $telegram_handle
     */
    public function setTelegramHandle($telegram_handle)
    {
        $this->telegram_handle = $telegram_handle;
    }

    /**
     * @return mixed
     */
    public function getBannerPicture()
    {
        return $this->banner_picture;
    }

    /**
     * @param mixed $banner_picture
     */
    public function setBannerPicture($banner_picture)
    {
        $this->banner_picture = $banner_picture;
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profile_picture;
    }

    /**
     * @param mixed $profile_picture
     */
    public function setProfilePicture($profile_picture)
    {
        $this->profile_picture = $profile_picture;
    }


}

