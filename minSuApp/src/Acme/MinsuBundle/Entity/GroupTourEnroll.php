<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_group_tour_enroll")
 */
class GroupTourEnroll {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $enroll_id
     */
    protected $enroll_id;


    /**
     * @ORM\Column(type="integer",name="forecast_id")
     */ 
    protected $forecast_id =0;

    /**
     * @ORM\Column(type="integer",name="tour_id")
     */ 
    protected $tour_id =0;

    /**
     * @ORM\Column(type="integer",name="member_id")
     */ 
    protected $member_id =0;

     /**
     * @ORM\Column(type="string",name="avatar")
     */ 
    protected $avatar ="";

    /**
     * @ORM\Column(type="string",name="username")
     */ 
    protected $username ="";

    /**
     * @ORM\Column(type="string",name="latitude")
     */ 
    protected $latitude ="";

     /**
     * @ORM\Column(type="string",name="longitude")
     */ 
    protected $longitude ="";

    /**
     * @ORM\Column(type="integer",name="add_time")
     */
    protected $add_time =0;

    /**
     * @ORM\Column(type="integer",name="state")
     */
    protected $state =1;

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getEnrollId()
    {
        return $this->enroll_id;
    }

    /**
     * @param int $enroll_id
     */
    public function setEnrollId($enroll_id)
    {
        $this->enroll_id = $enroll_id;
    }

    /**
     * @return mixed
     */
    public function getForecastId()
    {
        return $this->forecast_id;
    }

    /**
     * @param mixed $forecast_id
     */
    public function setForecastId($forecast_id)
    {
        $this->forecast_id = $forecast_id;
    }

    /**
     * @return mixed
     */
    public function getTourId()
    {
        return $this->tour_id;
    }

    /**
     * @param mixed $tour_id
     */
    public function setTourId($tour_id)
    {
        $this->tour_id = $tour_id;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
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
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getAddTime()
    {
        return $this->add_time;
    }

    /**
     * @param mixed $add_time
     */
    public function setAddTime($add_time)
    {
        $this->add_time = $add_time;
    }

    


}
