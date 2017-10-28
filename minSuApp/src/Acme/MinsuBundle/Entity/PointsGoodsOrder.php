<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_points_goods_order")
 */
class PointsGoodsOrder {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer",name="goods_id")
	 */
	protected $goods_id;

	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;

	/**
	 * @ORM\Column(type="string",name="buyer_name")
	 */
	protected $buyer_name;
	
	
	/**
	 * @ORM\Column(type="string",name="phone")
	 */
	protected $phone;
	/**
	 * @ORM\Column(type="string",name="addr")
	 */
	protected $addr;
	
	/**
	 * @ORM\Column(type="integer",name="member_id")
	 */
	protected $member_id;


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
     * @return PointsGoodsOrder
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * goods_id
     * @return unkown
     */
    public function getGoods_id(){
        return $this->goods_id;
    }

    /**
     * goods_id
     * @param unkown $goods_id
     * @return PointsGoodsOrder
     */
    public function setGoods_id($goods_id){
        $this->goods_id = $goods_id;
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
     * @return PointsGoodsOrder
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * buyer_name
     * @return unkown
     */
    public function getBuyer_name(){
        return $this->buyer_name;
    }

    /**
     * buyer_name
     * @param unkown $buyer_name
     * @return PointsGoodsOrder
     */
    public function setBuyer_name($buyer_name){
        $this->buyer_name = $buyer_name;
        return $this;
    }

    /**
     * phone
     * @return unkown
     */
    public function getPhone(){
        return $this->phone;
    }

    /**
     * phone
     * @param unkown $phone
     * @return PointsGoodsOrder
     */
    public function setPhone($phone){
        $this->phone = $phone;
        return $this;
    }

    /**
     * addr
     * @return unkown
     */
    public function getAddr(){
        return $this->addr;
    }

    /**
     * addr
     * @param unkown $addr
     * @return PointsGoodsOrder
     */
    public function setAddr($addr){
        $this->addr = $addr;
        return $this;
    }





    /**
     * member_id
     * @return unkown
     */
    public function getMember_id(){
        return $this->member_id;
    }

    /**
     * member_id
     * @param unkown $member_id
     * @return PointsGoodsOrder
     */
    public function setMember_id($member_id){
        $this->member_id = $member_id;
        return $this;
    }

}
