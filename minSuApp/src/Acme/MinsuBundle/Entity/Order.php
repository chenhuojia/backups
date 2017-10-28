<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_order")
 */
class Order {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $order_id
	 */
	protected $order_id;

	/**
	 * @ORM\Column(type="integer", name="order_sn")
	 */
	protected $order_sn;
	
	/**
	 * @ORM\Column(type="integer", name="homestay_id")
	 */
	protected $homestay_id;

	/**
	 * @ORM\Column(type="string", name="homestay_name")
	 */
	protected $homestay_name;
	
	/**
	 * @ORM\Column(type="string", name="homestay_addr")
	 */
	protected $homestay_addr;
	
	/**
	 * @ORM\Column(type="integer", name="room_id")
	 */
	protected $room_id;
	
	/**
	 * @ORM\Column(type="string", name="room_name")
	 */
	protected $room_name;
	
	/**
	 * @ORM\Column(type="string", name="room_bed_type")
	 */
	protected $room_bed_type;
	
	/**
	 * @ORM\Column(type="integer", name="room_bed")
	 */
	protected $room_bed;
	
	/**
	 * @ORM\Column(type="integer", name="buyer_id")
	 */
	protected $buyer_id;
	
	/**
	 * @ORM\Column(type="string", name="buyer_name")
	 */
	protected $buyer_name;
	
	/**
	 * @ORM\Column(type="string", name="buyer_phone")
	 */
	protected $buyer_phone;

	
	/**
	 * @ORM\Column(type="string", name="buyer_checkin")
	 */
	protected $buyer_checkin;
	
	/**
	 * @ORM\Column(type="string", name="buyer_checkout")
	 */
	protected $buyer_checkout;
	
	/**
	 * @ORM\Column(type="integer", name="buyer_num")
	 */
	protected $buyer_num;

	/**
	 * @ORM\Column(type="string", name="goods_amount")
	 */
	protected $goods_amount;
	
	/**
	 * @ORM\Column(type="string", name="order_amount")
	 */
	protected $order_amount;
	
	
	/**
	 * @ORM\Column(type="string", name="coupon_amount")
	 */
	protected $coupon_amount;
	
	
	/**
	 * @ORM\Column(type="text", name="restock_text")
	 */
	protected $restock_text;
	
	/**
	 * @ORM\Column(type="integer", name="add_time")
	 */
	protected $add_time;
	
	
	/**
	 * @ORM\Column(type="integer", name="buyer_day")
	 */
	protected $buyer_day;
	
	
	
	
	/**
	 *
	 * @return the integer
	 */
	public function getOrderId() {
		return $this->order_id;
	}
	
	/**
	 *
	 * @param
	 *        	$order_id
	 */
	public function setOrderId($order_id) {
		$this->order_id = $order_id;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getOrderSn() {
		return $this->order_sn;
	}
	
	/**
	 *
	 * @param unknown_type $order_sn        	
	 */
	public function setOrderSn($order_sn) {
		$this->order_sn = $order_sn;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getHomestayId() {
		return $this->homestay_id;
	}
	
	/**
	 *
	 * @param unknown_type $homestay_id        	
	 */
	public function setHomestayId($homestay_id) {
		$this->homestay_id = $homestay_id;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getHomestayName() {
		return $this->homestay_name;
	}
	
	/**
	 *
	 * @param unknown_type $homestay_name        	
	 */
	public function setHomestayName($homestay_name) {
		$this->homestay_name = $homestay_name;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getHomestayAddr() {
		return $this->homestay_addr;
	}
	
	/**
	 *
	 * @param unknown_type $homestay_addr        	
	 */
	public function setHomestayAddr($homestay_addr) {
		$this->homestay_addr = $homestay_addr;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRoomId() {
		return $this->room_id;
	}
	
	/**
	 *
	 * @param unknown_type $room_id        	
	 */
	public function setRoomId($room_id) {
		$this->room_id = $room_id;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRoomName() {
		return $this->room_name;
	}
	
	/**
	 *
	 * @param unknown_type $room_name        	
	 */
	public function setRoomName($room_name) {
		$this->room_name = $room_name;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRoomBedType() {
		return $this->room_bed_type;
	}
	
	/**
	 *
	 * @param unknown_type $room_bed_type        	
	 */
	public function setRoomBedType($room_bed_type) {
		$this->room_bed_type = $room_bed_type;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRoomBed() {
		return $this->room_bed;
	}
	
	/**
	 *
	 * @param unknown_type $room_bed        	
	 */
	public function setRoomBed($room_bed) {
		$this->room_bed = $room_bed;
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
	public function getBuyerName() {
		return $this->buyer_name;
	}
	
	/**
	 *
	 * @param unknown_type $buyer_name        	
	 */
	public function setBuyerName($buyer_name) {
		$this->buyer_name = $buyer_name;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getBuyerPhone() {
		return $this->buyer_phone;
	}
	
	/**
	 *
	 * @param unknown_type $buyer_phone        	
	 */
	public function setBuyerPhone($buyer_phone) {
		$this->buyer_phone = $buyer_phone;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getBuyerCheckin() {
		return $this->buyer_checkin;
	}
	
	/**
	 *
	 * @param unknown_type $buyer_checkin        	
	 */
	public function setBuyerCheckin($buyer_checkin) {
		$this->buyer_checkin = $buyer_checkin;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getBuyerCheckout() {
		return $this->buyer_checkout;
	}
	
	/**
	 *
	 * @param unknown_type $buyer_checkout        	
	 */
	public function setBuyerCheckout($buyer_checkout) {
		$this->buyer_checkout = $buyer_checkout;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getBuyerNum() {
		return $this->buyer_num;
	}
	
	/**
	 *
	 * @param unknown_type $buyer_num        	
	 */
	public function setBuyerNum($buyer_num) {
		$this->buyer_num = $buyer_num;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getGoodsAmount() {
		return $this->goods_amount;
	}
	
	/**
	 *
	 * @param unknown_type $goods_amount        	
	 */
	public function setGoodsAmount($goods_amount) {
		$this->goods_amount = $goods_amount;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getOrderAmount() {
		return $this->order_amount;
	}
	
	/**
	 *
	 * @param unknown_type $order_amount        	
	 */
	public function setOrderAmount($order_amount) {
		$this->order_amount = $order_amount;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getCouponAmount() {
		return $this->coupon_amount;
	}
	
	/**
	 *
	 * @param unknown_type $coupon_amount        	
	 */
	public function setCouponAmount($coupon_amount) {
		$this->coupon_amount = $coupon_amount;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRestockText() {
		return $this->restock_text;
	}
	
	/**
	 *
	 * @param unknown_type $restock_text        	
	 */
	public function setRestockText($restock_text) {
		$this->restock_text = $restock_text;
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
	public function getBuyerDay() {
		return $this->buyer_day;
	}
	
	/**
	 *
	 * @param unknown_type $buyer_day        	
	 */
	public function setBuyerDay($buyer_day) {
		$this->buyer_day = $buyer_day;
		return $this;
	}
	
	


}
