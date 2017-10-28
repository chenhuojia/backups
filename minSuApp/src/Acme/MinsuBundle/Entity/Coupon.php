<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_coupon")
 */
class Coupon {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="sort")
	 */
	protected $sort;

	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="integer",name="coupon_value")
	 */
	protected $coupon_value;
	
	/**
	 * @ORM\Column(type="integer",name="convert_points")
	 */
	protected $convert_points;
	
	/**
	 * @ORM\Column(type="integer",name="min_amount")
	 */
	protected $min_amount;
	/**
	 * @ORM\Column(type="string",name="coupon_dscp")
	 */
	protected $coupon_dscp;
	
	/**
	 * @ORM\Column(type="string",name="qouta_day")
	 */
	protected $qouta_day;
	
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
     * @return Counpon
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * sort
     * @return unkown
     */
    public function getSort(){
        return $this->sort;
    }

    /**
     * sort
     * @param unkown $sort
     * @return Counpon
     */
    public function setSort($sort){
        $this->sort = $sort;
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
     * @return Counpon
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * coupon_value
     * @return unkown
     */
    public function getCoupon_value(){
        return $this->coupon_value;
    }

    /**
     * coupon_value
     * @param unkown $coupon_value
     * @return Counpon
     */
    public function setCoupon_value($coupon_value){
        $this->coupon_value = $coupon_value;
        return $this;
    }

    /**
     * convert_points
     * @return unkown
     */
    public function getConvert_points(){
        return $this->convert_points;
    }

    /**
     * convert_points
     * @param unkown $convert_points
     * @return Counpon
     */
    public function setConvert_points($convert_points){
        $this->convert_points = $convert_points;
        return $this;
    }

    /**
     * min_amount
     * @return unkown
     */
    public function getMin_amount(){
        return $this->min_amount;
    }

    /**
     * min_amount
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setMin_amount($min_amount){
        $this->min_amount = $min_amount;
        return $this;
    }

    /**
     * coupon_dscp
     * @return unkown
     */
    public function getCoupon_dscp(){
        return $this->coupon_dscp;
    }

    /**
     * coupon_dscp
     * @param unkown $coupon_dscp
     * @return Counpon
     */
    public function setCoupon_dscp($coupon_dscp){
        $this->coupon_dscp = $coupon_dscp;
        return $this;
    }
	public function getQoutaDay() {
		return $this->qouta_day;
	}
	public function setQoutaDay($qouta_day) {
		$this->qouta_day = $qouta_day;
		return $this;
	}
	public function getState() {
		return $this->state;
	}
	public function setState($state) {
		$this->state = $state;
		return $this;
	}
	

}
