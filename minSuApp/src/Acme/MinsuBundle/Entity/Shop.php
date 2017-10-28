<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_shop")
 */
class Shop {
    /**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $shop_id
	 */
	protected $shop_id;
	/**
	 * @ORM\Column(type="integer",name="user_id")
	 */
	protected $user_id;
	/**
	 * @ORM\Column(type="integer",name="shop_name")
	 */
	protected $shop_name;
	/**
	 * @ORM\Column(type="string",name="shop_goods")
	 */
	protected $shop_goods;

	/**
	 * @ORM\Column(type="integer",name="create_time")
	 */
	protected $create_time;
	/**
	 * @ORM\Column(type="integer",name="is_show")
	 */
	protected $is_show;

    /**
     * shop_id
     * @return unkown
     */
    public function getShop_id(){
        return $this->shop_id;
    }

    /**
     * id
     * @param unkown $id
     * @return Counpon
     */
    public function setShop_id($shop_id){
        $this->shop_id = $shop_id;
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
     * shop_name
     * @return unkown
     */
    public function getShop_name(){
        return $this->shop_name;
    }

    /**
     * shop_name
     * @param unkown $addtime
     * @return Counpon
     */
    public function setShop_name($shop_name){
        $this->shop_name = $shop_name;
        return $this;
    }

    /**
     * create_time
     * @return unkown
     */
    public function getCreate_time(){
        return $this->create_time;
    }

    /**
     * create_time
     * @param unkown $coupon_value
     * @return Counpon
     */
    public function setCreate_time($create_time){
        $this->create_time = $create_time;
        return $this;
    }

    /**
     * shop_goods
     * @return unkown
     */
    public function getShop_goods(){
        return $this->shop_goods;
    }

    /**
     * shop_goods
     * @param unkown $convert_points
     * @return Counpon
     */
    public function setShop_goods($shop_goods){
        $this->shop_goods = $shop_goods;
        return $this;
    }

    /**
     * is_show
     * @return unkown
     */
    public function getIs_show(){
        return $this->is_show;
    }

    /**
     * is_show
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setIs_show($is_show){
        $this->is_show = $is_show;
        return $this;
    }


}
