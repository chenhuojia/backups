<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_buyer_coupon")
 */
class BuyerCoupon {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="buyer_id")
	 */
	protected $buyer_id;

	/**
	 * @ORM\Column(type="integer",name="add_time")
	 */
	protected $add_time;
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
	 * @ORM\Column(type="string",name="deadline")
	 */
	protected $deadline;
	
	/**
	 * @ORM\Column(type="string",name="state")
	 */
	protected $state;
	
	/**
	 *
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param
	 *        	$id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getBuyerId() {
		return $this->buyer_id;
	}
	
	/**
	 *
	 * @param unknown_type $buyer_id        	
	 */
	public function setBuyerId($buyer_id) {
		$this->buyer_id = $buyer_id;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getAddTime() {
		return $this->add_time;
	}
	
	/**
	 *
	 * @param unknown_type $add_time        	
	 */
	public function setAddTime($add_time) {
		$this->add_time = $add_time;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getCouponValue() {
		return $this->coupon_value;
	}
	
	/**
	 *
	 * @param unknown_type $coupon_value        	
	 */
	public function setCouponValue($coupon_value) {
		$this->coupon_value = $coupon_value;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getConvertPoints() {
		return $this->convert_points;
	}
	
	/**
	 *
	 * @param unknown_type $convert_points        	
	 */
	public function setConvertPoints($convert_points) {
		$this->convert_points = $convert_points;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getMinAmount() {
		return $this->min_amount;
	}
	
	/**
	 *
	 * @param unknown_type $min_amount        	
	 */
	public function setMinAmount($min_amount) {
		$this->min_amount = $min_amount;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getCouponDscp() {
		return $this->coupon_dscp;
	}
	
	/**
	 *
	 * @param unknown_type $coupon_dscp        	
	 */
	public function setCouponDscp($coupon_dscp) {
		$this->coupon_dscp = $coupon_dscp;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getDeadline() {
		return $this->deadline;
	}
	
	/**
	 *
	 * @param unknown_type $deadline        	
	 */
	public function setDeadline($deadline) {
		$this->deadline = $deadline;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 *
	 * @param unknown_type $state        	
	 */
	public function setState($state) {
		$this->state = $state;
		return $this;
	}
	

}
