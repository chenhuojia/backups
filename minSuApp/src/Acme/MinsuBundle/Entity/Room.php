<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_room")
 */
class Room {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="homestay_id")
	 */
	protected $homestay_id;

	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="string",name="room_title")
	 */
	protected $room_title;
	/**
	 * @ORM\Column(type="string",name="room_price")
	 */
	protected $room_price;
	/**
	 * @ORM\Column(type="string",name="room_single_bed")
	 */
	protected $room_single_bed;
	/**
	 * @ORM\Column(type="string",name="room_double_bed")
	 */
	protected $room_double_bed;
	/**
	 * @ORM\Column(type="string",name="room_num")
	 */
	protected $room_num;
	/**
	 * @ORM\Column(type="string",name="room_hall")
	 */
	protected $room_hall;
	/**
	 * @ORM\Column(type="string",name="room_kitchen")
	 */
	protected $room_kitchen;
	/**
	 * @ORM\Column(type="string",name="room_toilet")
	 */
	protected $room_toilet;
	/**
	 * @ORM\Column(type="string",name="room_balcony")
	 */
	protected $room_balcony;

	/**
	 * @ORM\Column(type="string",name="repast")
	 */
	protected $repast;
	
	/**
	 * @ORM\Column(type="string",name="cash")
	 */
	protected $cash;
	/**
	 * @ORM\Column(type="string",name="state")
	 */
	protected $state;
	
	
    /**
     * id
     * @return unkown
     */
    public function getId(){
        return $this->id;
    }

    /**
     * id
     * @param unkown $id
     * @return Room
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * homestay_id
     * @return unkown
     */
    public function getHomestay_id(){
        return $this->homestay_id;
    }

    /**
     * homestay_id
     * @param unkown $homestay_id
     * @return Room
     */
    public function setHomestay_id($homestay_id){
        $this->homestay_id = $homestay_id;
        return $this;
    }

    /**
     * addtime
     * @return unkown
     */
    public function getAddtime(){
        return $this->addtime;
    }

    /**
     * addtime
     * @param unkown $addtime
     * @return Room
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * room_title
     * @return unkown
     */
    public function getRoom_title(){
        return $this->room_title;
    }

    /**
     * room_title
     * @param unkown $room_title
     * @return Room
     */
    public function setRoom_title($room_title){
        $this->room_title = $room_title;
        return $this;
    }

    /**
     * room_price
     * @return unkown
     */
    public function getRoom_price(){
        return $this->room_price;
    }

    /**
     * room_price
     * @param unkown $room_price
     * @return Room
     */
    public function setRoom_price($room_price){
        $this->room_price = $room_price;
        return $this;
    }

    /**
     * room_single_bed
     * @return unkown
     */
    public function getRoom_single_bed(){
        return $this->room_single_bed;
    }

    /**
     * room_single_bed
     * @param unkown $room_single_bed
     * @return Room
     */
    public function setRoom_single_bed($room_single_bed){
        $this->room_single_bed = $room_single_bed;
        return $this;
    }

    /**
     * room_double_bed
     * @return unkown
     */
    public function getRoom_double_bed(){
        return $this->room_double_bed;
    }

    /**
     * room_double_bed
     * @param unkown $room_double_bed
     * @return Room
     */
    public function setRoom_double_bed($room_double_bed){
        $this->room_double_bed = $room_double_bed;
        return $this;
    }

    /**
     * room_num
     * @return unkown
     */
    public function getRoom_num(){
        return $this->room_num;
    }

    /**
     * room_num
     * @param unkown $room_num
     * @return Room
     */
    public function setRoom_num($room_num){
        $this->room_num = $room_num;
        return $this;
    }

    /**
     * room_hall
     * @return unkown
     */
    public function getRoom_hall(){
        return $this->room_hall;
    }

    /**
     * room_hall
     * @param unkown $room_hall
     * @return Room
     */
    public function setRoom_hall($room_hall){
        $this->room_hall = $room_hall;
        return $this;
    }

    /**
     * room_kitchen
     * @return unkown
     */
    public function getRoom_kitchen(){
        return $this->room_kitchen;
    }

    /**
     * room_kitchen
     * @param unkown $room_kitchen
     * @return Room
     */
    public function setRoom_kitchen($room_kitchen){
        $this->room_kitchen = $room_kitchen;
        return $this;
    }

    /**
     * room_toilet
     * @return unkown
     */
    public function getRoom_toilet(){
        return $this->room_toilet;
    }

    /**
     * room_toilet
     * @param unkown $room_toilet
     * @return Room
     */
    public function setRoom_toilet($room_toilet){
        $this->room_toilet = $room_toilet;
        return $this;
    }

    /**
     * room_balcony
     * @return unkown
     */
    public function getRoom_balcony(){
        return $this->room_balcony;
    }

    /**
     * room_balcony
     * @param unkown $room_balcony
     * @return Room
     */
    public function setRoom_balcony($room_balcony){
        $this->room_balcony = $room_balcony;
        return $this;
    }


    /**
     * repast
     * @return unkown
     */
    public function getRepast(){
        return $this->repast;
    }

    /**
     * repast
     * @param unkown $repast
     * @return Room
     */
    public function setRepast($repast){
        $this->repast = $repast;
        return $this;
    }

    /**
     * cash
     * @return unkown
     */
    public function getCash(){
        return $this->cash;
    }

    /**
     * cash
     * @param unkown $cash
     * @return Room
     */
    public function setCash($cash){
        $this->cash = $cash;
        return $this;
    }

    /**
     * state
     * @return unkown
     */
    public function getState(){
        return $this->state;
    }

    /**
     * state
     * @param unkown $state
     * @return Room
     */
    public function setState($state){
        $this->state = $state;
        return $this;
    }

}
