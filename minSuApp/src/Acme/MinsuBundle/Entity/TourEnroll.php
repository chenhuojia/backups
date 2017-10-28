<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_enroll")
 */
class TourEnroll {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $enroll_id
     */
    protected $enroll_id;

    /**
     * @ORM\Column(type="string", name="order_sn")
     */
    protected $order_sn ="0";

    /**
     * @ORM\Column(type="integer",name="rec_id")
     */ 
    protected $rec_id =0;

    /**
     * @ORM\Column(type="integer",name="tour_id")
     */ 
    protected $tour_id =0;

     /**
     * @ORM\Column(type="integer",name="calendar_id")
     */ 
    protected $calendar_id =0;

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
     * @ORM\Column(type="string",name="geohash ")
     */ 
    protected $geohash  ="";


    /**
     * @ORM\Column(type="integer",name="add_time")
     */
    protected $add_time =0;

    /**
     * @ORM\Column(type="integer",name="state")
     */
    protected $state =1;

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
    public function getOrderSn()
    {
        return $this->order_sn;
    }

    /**
     * @param mixed $order_sn
     */
    public function setOrderSn($order_sn)
    {
        $this->order_sn = $order_sn;
    }

    /**
     * @return mixed
     */
    public function getRecId()
    {
        return $this->rec_id;
    }

    /**
     * @param mixed $rec_id
     */
    public function setRecId($rec_id)
    {
        $this->rec_id = $rec_id;
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
    public function getCalendarId()
    {
        return $this->calendar_id;
    }

    /**
     * @param mixed $calendar_id
     */
    public function setCalendarId($calendar_id)
    {
        $this->calendar_id = $calendar_id;
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
    public function getGeohash()
    {
        return $this->geohash;
    }

    /**
     * @param mixed $geohash
     */
    public function setGeohash($geohash)
    {
        $this->geohash = $geohash;
    }

    /**
     * @return mixed
     */
    public function getAddTime()
    {
        return $this->add_time;
    }

    /**
     * @param mixed $enroll_time
     */
    public function setAddTime($add_time)
    {
        $this->add_time = $add_time;
    }

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

    
    

    

}
