<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_order")
 */
class TourOrder {
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
	 * @ORM\Column(type="integer", name="tour_id")
	 */
	protected $tour_id =0;

	/**
	 * @ORM\Column(type="integer", name="calendar_id")
	 */
	protected $calendar_id =0;


	/**
	 * @ORM\Column(type="integer", name="agency_id")
	 */
	protected $agency_id;

	/**
	 * @ORM\Column(type="integer", name="member_id")
	 */
	protected $member_id;

	/**
	 * @ORM\Column(type="string", name="member_avatar")
	 */
	protected $member_avatar;

	/**
	 * @ORM\Column(type="string", name="member_name")
	 */
	protected $member_name;

	/**
	 * @ORM\Column(type="integer", name="order_status")
	 */
	protected $order_status=0;

	/**
	 * @ORM\Column(type="integer", name="pay_status")
	 */
	protected $pay_status=0;

	/**
	 * @ORM\Column(type="string", name="goods_name")
	 */
	protected $goods_name;


	/**
	 * @ORM\Column(type="string", name="tour_img")
	 */
	protected $tour_img = "";

	
	/**
	 * @ORM\Column(type="string", name="period")
	 */
	protected $period='1';
	
	/**
	 * @ORM\Column(type="integer", name="starting_time")
	 */
	protected $starting_time;
	
	/**
	 * @ORM\Column(type="string", name="starting_place")
	 */
	protected $starting_place;
	
	/**
	 * @ORM\Column(type="string", name="consignee")
	 */
	protected $consignee;
	
	/**
	 * @ORM\Column(type="string", name="identity_card")
	 */
	protected $identity_card;
	
	/**
	 * @ORM\Column(type="string", name="mobile")
	 */
	protected $mobile ="";

	/**
	 * @ORM\Column(type="string", name="emergency_contact")
	 */
	protected $emergency_contact="";

	/**
	 * @ORM\Column(type="string", name="pay_name")
	 */
	protected $pay_name ="";

	/**
	 * @ORM\Column(type="string", name="invoice_title")
	 */
	protected $invoice_title = "";

	/**
	 * @ORM\Column(type="string", name="goods_price")
	 */
	protected $goods_price= "0.00";

	/**
	 * @ORM\Column(type="string", name="user_money")
	 */
	protected $user_money = "0.00";

	/**
	 * @ORM\Column(type="string", name="coupon_price")
	 */
	protected $coupon_price = "0.00";

	/**
	 * @ORM\Column(type="integer", name="integral")
	 */
	protected $integral = 0;

	/**
	 * @ORM\Column(type="string", name="integral_money")
	 */
	protected $integral_money = "0.00";

	/**
	 * @ORM\Column(type="string", name="order_amount")
	 */
	protected $order_amount= "0.00";

	/**
	 * @ORM\Column(type="string", name="total_amount")
	 */
	protected $total_amount= "0.00";

	/**
	 * @ORM\Column(type="integer", name="add_time")
	 */
	protected $add_time;

	/**
	 * @ORM\Column(type="integer", name="pay_time")
	 */
	protected $pay_time = 0;

	/**
	 * @ORM\Column(type="integer", name="order_prom_id")
	 */
	protected $order_prom_id = 0;

	/**
	 * @ORM\Column(type="string", name="order_prom_amount")
	 */
	protected $order_prom_amount = "0.00";

	/**
	 * @ORM\Column(type="integer", name="is_comment")
	 */
	protected $is_comment = 0;

	/**
	 * @ORM\Column(type="string", name="user_note")
	 */
	protected $user_note = "";

	/**
	 * @ORM\Column(type="string", name="admin_note")
	 */
	protected $admin_note = "";

	/**
	 * @ORM\Column(type="string", name="adult_price")
	 */
	protected $adult_price = "0";

	/**
	 * @ORM\Column(type="string", name="child_price")
	 */
	protected $child_price = "0";

	/**
	 * @ORM\Column(type="integer", name="adult_num")
	 */
	protected $adult_num = 0;

	/**
	 * @ORM\Column(type="integer", name="child_num")
	 */
	protected $child_num = 0;

	

	/**
	 * @return int
	 */
	public function getOrderId()
	{
		return $this->order_id;
	}

	/**
	 * @param int $order_id
	 */
	public function setOrderId($order_id)
	{
		$this->order_id = $order_id;
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
	public function getOrderStatus()
	{
		return $this->order_status;
	}

	/**
	 * @param mixed $order_status
	 */
	public function setOrderStatus($order_status)
	{
		$this->order_status = $order_status;
	}

	/**
	 * @return mixed
	 */
	public function getPayStatus()
	{
		return $this->pay_status;
	}

	/**
	 * @param mixed $pay_status
	 */
	public function setPayStatus($pay_status)
	{
		$this->pay_status = $pay_status;
	}

	/**
	 * @return mixed
	 */
	public function getGoodsName()
	{
		return $this->goods_name;
	}

	/**
	 * @param mixed $goods_name
	 */
	public function setGoodsName($goods_name)
	{
		$this->goods_name = $goods_name;
	}

	/**
	 * @return mixed
	 */
	public function getTourImg()
	{
		return $this->tour_img;
	}

	/**
	 * @param mixed $tour_img
	 */
	public function setTourImg($tour_img)
	{
		$this->tour_img = $tour_img;
	}



	/**
	 * @return mixed
	 */
	public function getPeriod()
	{
		return $this->period;
	}

	/**
	 * @param mixed $period
	 */
	public function setPeriod($period)
	{
		$this->period = $period;
	}

	/**
	 * @return mixed
	 */
	public function getStartingTime()
	{
		return $this->starting_time;
	}

	/**
	 * @param mixed $starting_time
	 */
	public function setStartingTime($starting_time)
	{
		$this->starting_time = $starting_time;
	}

	/**
	 * @return mixed
	 */
	public function getStartingPlace()
	{
		return $this->starting_place;
	}

	/**
	 * @param mixed $starting_place
	 */
	public function setStartingPlace($starting_place)
	{
		$this->starting_place = $starting_place;
	}

	/**
	 * @return mixed
	 */
	public function getConsignee()
	{
		return $this->consignee;
	}

	/**
	 * @param mixed $consignee
	 */
	public function setConsignee($consignee)
	{
		$this->consignee = $consignee;
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
	public function getMobile()
	{
		return $this->mobile;
	}

	/**
	 * @param mixed $mobile
	 */
	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
	}

	/**
	 * @return mixed
	 */
	public function getEmergencyContact()
	{
		return $this->emergency_contact;
	}

	/**
	 * @param mixed $emergency_contact
	 */
	public function setEmergencyContact($emergency_contact)
	{
		$this->emergency_contact = $emergency_contact;
	}

	/**
	 * @return mixed
	 */
	public function getPayName()
	{
		return $this->pay_name;
	}

	/**
	 * @param mixed $pay_name
	 */
	public function setPayName($pay_name)
	{
		$this->pay_name = $pay_name;
	}

	/**
	 * @return mixed
	 */
	public function getInvoiceTitle()
	{
		return $this->invoice_title;
	}

	/**
	 * @param mixed $invoice_title
	 */
	public function setInvoiceTitle($invoice_title)
	{
		$this->invoice_title = $invoice_title;
	}

	/**
	 * @return mixed
	 */
	public function getGoodsPrice()
	{
		return $this->goods_price;
	}

	/**
	 * @param mixed $goods_price
	 */
	public function setGoodsPrice($goods_price)
	{
		$this->goods_price = $goods_price;
	}

	/**
	 * @return mixed
	 */
	public function getUserMoney()
	{
		return $this->user_money;
	}

	/**
	 * @param mixed $user_money
	 */
	public function setUserMoney($user_money)
	{
		$this->user_money = $user_money;
	}

	/**
	 * @return mixed
	 */
	public function getCouponPrice()
	{
		return $this->coupon_price;
	}

	/**
	 * @param mixed $coupon_price
	 */
	public function setCouponPrice($coupon_price)
	{
		$this->coupon_price = $coupon_price;
	}

	/**
	 * @return mixed
	 */
	public function getIntegral()
	{
		return $this->integral;
	}

	/**
	 * @param mixed $integral
	 */
	public function setIntegral($integral)
	{
		$this->integral = $integral;
	}

	/**
	 * @return mixed
	 */
	public function getIntegralMoney()
	{
		return $this->integral_money;
	}

	/**
	 * @param mixed $integral_money
	 */
	public function setIntegralMoney($integral_money)
	{
		$this->integral_money = $integral_money;
	}

	/**
	 * @return mixed
	 */
	public function getOrderAmount()
	{
		return $this->order_amount;
	}

	/**
	 * @param mixed $order_amount
	 */
	public function setOrderAmount($order_amount)
	{
		$this->order_amount = $order_amount;
	}

	/**
	 * @return mixed
	 */
	public function getTotalAmount()
	{
		return $this->total_amount;
	}

	/**
	 * @param mixed $total_amount
	 */
	public function setTotalAmount($total_amount)
	{
		$this->total_amount = $total_amount;
	}

	/**
	 * @return mixed
	 */
	public function getAddTime()
	{
		return $this->add_time;
	}

	/**
	 * @param mixed $add_time
	 */
	public function setAddTime($add_time)
	{
		$this->add_time = $add_time;
	}

	/**
	 * @return mixed
	 */
	public function getPayTime()
	{
		return $this->pay_time;
	}

	/**
	 * @param mixed $pay_time
	 */
	public function setPayTime($pay_time)
	{
		$this->pay_time = $pay_time;
	}

	/**
	 * @return mixed
	 */
	public function getOrderPromId()
	{
		return $this->order_prom_id;
	}

	/**
	 * @param mixed $order_prom_id
	 */
	public function setOrderPromId($order_prom_id)
	{
		$this->order_prom_id = $order_prom_id;
	}

	/**
	 * @return mixed
	 */
	public function getOrderPromAmount()
	{
		return $this->order_prom_amount;
	}

	/**
	 * @param mixed $order_prom_amount
	 */
	public function setOrderPromAmount($order_prom_amount)
	{
		$this->order_prom_amount = $order_prom_amount;
	}

	/**
	 * @return mixed
	 */
	public function getIsComment()
	{
		return $this->is_comment;
	}

	/**
	 * @param mixed $is_comment
	 */
	public function setIsComment($is_comment)
	{
		$this->is_comment = $is_comment;
	}

	/**
	 * @return mixed
	 */
	public function getUserNote()
	{
		return $this->user_note;
	}

	/**
	 * @param mixed $user_note
	 */
	public function setUserNote($user_note)
	{
		$this->user_note = $user_note;
	}

	/**
	 * @return mixed
	 */
	public function getAdminNote()
	{
		return $this->admin_note;
	}

	/**
	 * @param mixed $admin_note
	 */
	public function setAdminNote($admin_note)
	{
		$this->admin_note = $admin_note;
	}

	/**
	 * @return mixed
	 */
	public function getAgencyId()
	{
		return $this->agency_id;
	}

	/**
	 * @param mixed $agency_id
	 */
	public function setAgencyId($agency_id)
	{
		$this->agency_id = $agency_id;
	}

	/**
	 * @return mixed
	 */
	public function getMemberAvatar()
	{
		return $this->member_avatar;
	}

	/**
	 * @param mixed $member_avatar
	 */
	public function setMemberAvatar($member_avatar)
	{
		$this->member_avatar = $member_avatar;
	}

	/**
	 * @return mixed
	 */
	public function getMemberName()
	{
		return $this->member_name;
	}

	/**
	 * @param mixed $member_name
	 */
	public function setMemberName($member_name)
	{
		$this->member_name = $member_name;
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
	public function getAdultNum()
	{
		return $this->adult_num;
	}

	/**
	 * @param mixed $adult_num
	 */
	public function setAdultNum($adult_num)
	{
		$this->adult_num = $adult_num;
	}

	/**
	 * @return mixed
	 */
	public function getChildNum()
	{
		return $this->child_num;
	}

	/**
	 * @param mixed $child_num
	 */
	public function setChildNum($child_num)
	{
		$this->child_num = $child_num;
	}


	
	


}
