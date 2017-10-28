<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_shop_apply")
 */
class ShopApply {
    /**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;
	/**
	 * @ORM\Column(type="integer",name="user_id")
	 */
	protected $user_id;
	/**
	 * @ORM\Column(type="string",name="user_name")
	 */
	protected $user_name;
	/**
	 * @ORM\Column(type="integer",name="phone")
	 */
	protected $phone;
	/**
	 * @ORM\Column(type="string",name="shop_name")
	 */
	protected $shop_name;	
	/**
	 * @ORM\Column(type="string",name="shop_address")
	 */
	protected $shop_address;
	/**
	 * @ORM\Column(type="string",name="shop_logo")
	 */
	protected $shop_logo;
	/**
	 * @ORM\Column(type="integer",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="integer",name="is_checked")
	 */
	protected $is_checked;
	/**
	 * @ORM\Column(type="string",name="id_card1")
	 */
	protected $id_card1;
	/**
	 * @ORM\Column(type="string",name="id_card2")
	 */
	protected $id_card2;
	/**
	 * @ORM\Column(type="string",name="id_card3")
	 */
	protected $id_card3;
	/**
	 * @ORM\Column(type="string",name="business_license")
	 */
	protected $business_license;

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
     * @return Counpon
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * user_id
     * @return unkown
     */
    public function getUser_id(){
        return $this->user_id;
    }

    /**
     * user_id
     * @param unkown $sort
     * @return Counpon
     */
    public function setUser_id($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * user_name
     * @return unkown
     */
    public function getUser_name(){
        return $this->user_name;
    }

    /**
     * user_name
     * @param unkown $addtime
     * @return Counpon
     */
    public function setUser_name($user_name){
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * phone
     * @return unkown
     */
    public function getPhone(){
        return $this->Phone;
    }

    /**
     * phone
     * @param unkown $coupon_value
     * @return Counpon
     */
    public function setPhone($phone){
        $this->phone = $phone;
        return $this;
    }

    /**
     * shop_name
     * @return unkown
     */
    public function getShop_name(){
        return $this->shop_name;
    }

    /**
     * shop_name
     * @param unkown $convert_points
     * @return Counpon
     */
    public function setShop_name($shop_name){
        $this->shop_name = $shop_name;
        return $this;
    }

    /**
     * shop_address
     * @return unkown
     */
    public function getShop_address(){
        return $this->shop_address;
    }

    /**
     * shop_address
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setShop_address($shop_address){
        $this->shop_address = $shop_address;
        return $this;
    }
    /**
     * shop_logo
     * @return unkown
     */
    public function getShop_logo(){
        return $this->shop_logo;
    }
    
    /**
     * shop_logo
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setShop_logo($shop_logo){
        $this->shop_logo = $shop_logo;
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
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }
    /**
     * is_checked
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setIs_checked($is_checked){
        $this->is_checked = $is_checked;
        return $this;
    }
    /**
     * is_checked
     * @return unkown
     */
    public function getIs_checked(){
        return $this->is_checked;
    }
    /**
     * id_card1
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setId_card1($id_card1){
        $this->id_card1 = $id_card1;
        return $this;
    }
    /**
     *  id_card1
     * @return unkown
     */
    public function getId_card1(){
        return $this->id_card1;
    }
    /**
     * id_card2
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setId_card2($id_card2){
        $this->id_card2 = $id_card2;
        return $this;
    }
    /**
     *  id_card2
     * @return unkown
     */
    public function getId_card2(){
        return $this->id_card2;
    }
    /**
     * id_card3
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setId_card3($id_card3){
        $this->id_card3 = $id_card3;
        return $this;
    }
    /**
     *  id_card3
     * @return unkown
     */
    public function getId_card3(){
        return $this->id_card3;
    }
    /**
     * business_license
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setBusiness_license($business_license){
        $this->business_license = $business_license;
        return $this;
    }
    /**
     * business_license
     * @return unkown
     */
    public function getBusiness_license(){
        return $this->business_license;
    }
    

}
