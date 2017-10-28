<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_calendar")
 */
class TourCalendar {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $calendar_id
     */
    protected $calendar_id;

    /**
     * @ORM\Column(type="integer", name="tour_id")
     */
    protected $tour_id =0;

    /**
     * @ORM\Column(type="string",name="the_date")
     */ 
    protected $the_date ="";

     /**
     * @ORM\Column(type="integer",name="child_price")
     */ 
    protected $child_price =0;

    /**
     * @ORM\Column(type="integer",name="adult_price")
     */
    protected $adult_price =0;

    /**
     * @ORM\Column(type="integer",name="chief_id")
     */
    protected $chief_id =0;

    /**
     * @ORM\Column(type="string",name="chief_name")
     */
    protected $chief_name ="";

    /**
     * @ORM\Column(type="string",name="chief_avatar")
     */
    protected $chief_avatar ="";

     /**
     * @ORM\Column(type="integer",name="state")
     */
    protected $state =1;

     /**
     * @ORM\Column(type="integer",name="addtime")
     */
    protected $addtime =0;

    /**
     * @ORM\Column(type="integer",name="entered")
     */ 
    protected $entered =0;

    /**
     * @ORM\Column(type="string",name="group_name")
     */
    protected $group_name ="";

    /**
     * @ORM\Column(type="string",name="chat_room")
     */
    protected $chat_room ="";

    /**
     * @return int
     */
    public function getCalendarId()
    {
        return $this->calendar_id;
    }

    /**
     * @param int $calendar_id
     */
    public function setCalendarId($calendar_id)
    {
        $this->calendar_id = $calendar_id;
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
    public function getTheDate()
    {
        return $this->the_date;
    }

    /**
     * @param mixed $the_date
     */
    public function setTheDate($the_date)
    {
        $this->the_date = $the_date;
    }

    /**
     * @return mixed
     */
    public function getChildPrice()
    {
        return $this->child_price;
    }

    /**
     * @param mixed $child_price
     */
    public function setChildPrice($child_price)
    {
        $this->child_price = $child_price;
    }

    /**
     * @return mixed
     */
    public function getAdultPrice()
    {
        return $this->adult_price;
    }

    /**
     * @param mixed $adult_price
     */
    public function setAdultPrice($adult_price)
    {
        $this->adult_price = $adult_price;
    }

    /**
     * @return mixed
     */
    public function getChiefId()
    {
        return $this->chief_id;
    }

    /**
     * @param mixed $chief_id
     */
    public function setChiefId($chief_id)
    {
        $this->chief_id = $chief_id;
    }

    /**
     * @return mixed
     */
    public function getChiefName()
    {
        return $this->chief_name;
    }

    /**
     * @param mixed $chief_name
     */
    public function setChiefName($chief_name)
    {
        $this->chief_name = $chief_name;
    }

    /**
     * @return mixed
     */
    public function getChiefAvatar()
    {
        return $this->chief_avatar;
    }

    /**
     * @param mixed $chief_avatar
     */
    public function setChiefAvatar($chief_avatar)
    {
        $this->chief_avatar = $chief_avatar;
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

    /**
     * @return mixed
     */
    public function getAddtime()
    {
        return $this->addtime;
    }

    /**
     * @param mixed $addtime
     */
    public function setAddtime($addtime)
    {
        $this->addtime = $addtime;
    }

    /**
     * @return mixed
     */
    public function getEntered()
    {
        return $this->entered;
    }

    /**
     * @param mixed $entered
     */
    public function setEntered($entered)
    {
        $this->entered = $entered;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->group_name;
    }

    /**
     * @param mixed $group_name
     */
    public function setGroupName($group_name)
    {
        $this->group_name = $group_name;
    }

     /**
     * @return mixed
     */
    public function getChatRoom()
    {
        return $this->chat_room;
    }

    /**
     * @param mixed $chat_room
     */
    public function setChatRoom($chat_room)
    {
        $this->chat_room = $chat_room;
    }



}
