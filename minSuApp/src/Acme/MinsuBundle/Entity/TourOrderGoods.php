<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_order_goods")
 */
class TourOrderGoods {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer rec_id
	 */
	protected $rec_id;

	/**
     * @ORM\Column(type="integer", name="tour_id")
     */
    protected $tour_id =0;

    /**
     * @ORM\Column(type="integer", name="calendar_id")
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
     * @ORM\Column(type="string",name="account")
     */ 
    protected $account ="";

     /**
     * @ORM\Column(type="string",name="identity_card")
     */ 
    protected $identity_card ="";


    /**
     * @ORM\Column(type="string",name="order_sn")
     */
    protected $order_sn ="";


    /**
     * @ORM\Column(type="integer",name="enroll_time")
     */
    protected $enroll_time =0;

    /**
     * @ORM\Column(type="integer",name="state")
     */
    protected $state =0;

     /**
     * @ORM\Column(type="integer",name="type")
     */
    protected $type =0;

     /**
     * @ORM\Column(type="string",name="price")
     */
    protected $price =0;

    /**
     * @return int
     */
    public function getRecId()
    {
        return $this->rec_id;
    }

    /**
     * @param int $rec_id
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
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getIdentityCard()
    {
        return $this->identity_card;
    }

    /**
     * @param mixed $identity_card
     */
    public function setIdentityCard($identity_card)
    {
        $this->identity_card = $identity_card;
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
    public function getEnrollTime()
    {
        return $this->enroll_time;
    }

    /**
     * @param mixed $enroll_time
     */
    public function setEnrollTime($enroll_time)
    {
        $this->enroll_time = $enroll_time;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }





}
