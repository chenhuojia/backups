<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_mall_order")
 */
class MallOrder {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer",)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $order_id
	 */
	protected $order_id;

	/**
	 * @ORM\Column(type="string", name="order_sn")
	 */
	protected $order_sn=0;

	/**
	 * @ORM\Column(type="integer",name="user_id")
	 */
	protected $user_id=0;
	/**
	 * @ORM\Column(type="integer",name="shop_id")
	 */
	protected $shop_id=0;
	/**
	 * @ORM\Column(type="integer",name="order_status")
	 */
	protected $order_status=1;
	/**
	 * @ORM\Column(type="integer",name="shipping_status")
	 */
	protected $shipping_status=1;
	/**
	 * @ORM\Column(type="string", name="consignee")
	 */
	protected $consignee=0;
	/**
	 * @ORM\Column(type="string", name="country")
	 */
	protected $country='中国';
	/**
	 * @ORM\Column(type="string", name="province")
	 */
	protected $province='0';
	/**
	 * @ORM\Column(type="string", name="city")
	 */
	protected $city=0;
	/**
	 * @ORM\Column(type="string", name="district")
	 */
	protected $district='0';
	/**
	 * @ORM\Column(type="string", name="twon")
	 */
	protected $twon='0';
	/**
	 * @ORM\Column(type="string", name="address")
	 */
	protected $address='0';
	/**
	 * @ORM\Column(type="integer", name="mobile")
	 */
	protected $mobile=0;
	/**
	 * @ORM\Column(type="string", name="shipping_code")
	 */
	protected $shipping_code='0';
	/**
	 * @ORM\Column(type="string", name="shipping_name")
	 */
	protected $shipping_name=0;
	/**
	 * @ORM\Column(type="string", name="pay_code")
	 */	
	protected $pay_code=0;
	
	/**
	 * @ORM\Column(type="string", name="pay_sn")
	 */
	protected $pay_sn=0;
	/**
	 * @ORM\Column(type="string", name="pay_name")
	 */
	protected $pay_name=0;
	/**
	 * @ORM\Column(type="string", name="invoice_title")
	 */
	protected $invoice_title=0;
	/**
	 * @ORM\Column(type="integer", name="goods_price")
	 */
	protected $goods_price=0;
	/**
	 * @ORM\Column(type="integer", name="goods_num")
	 */
	protected $goods_num=0;
	/**
	 * @ORM\Column(type="integer", name="shipping_price")
	 */
	protected $shipping_price=0;
	/**
	 * @ORM\Column(type="integer", name="user_money")
	 */
	protected $user_money=0;
	/**
	 * @ORM\Column(type="integer", name="coupon_price")
	 */
	protected $coupon_price='0';
	/**
	 * @ORM\Column(type="integer", name="integral")
	 */
	protected $integral=0;
	/**
	 * @ORM\Column(type="integer", name="integral_money")
	 */
	protected $integral_money=0;
	/**
	 * @ORM\Column(type="integer", name="order_amount")
	 */
	protected $order_amount=0;
	/**
	 * @ORM\Column(type="integer", name="total_amount")
	 */
	protected $total_amount=0;
	/**
	 * @ORM\Column(type="integer", name="add_time")
	 */
	protected $add_time=0;
	/**
	 * @ORM\Column(type="integer", name="time_left")
	 */
	protected $time_left=0;
	/**
 	 * @ORM\Column(type="integer", name="shipping_time")
	 */
	protected $shipping_time=0;
	/**
	 * @ORM\Column(type="integer", name="confirm_time")
	 */
	protected $confirm_time=0;
	/**
	 * @ORM\Column(type="integer", name="pay_time")
	 */
	protected $pay_time=0;
	/**
	 * @ORM\Column(type="integer", name="order_prom_id")
	 */
	protected $order_prom_id=0;
	/**
	 * @ORM\Column(type="integer", name="order_prom_amount")
	 */
	protected $order_prom_amount=0;
	/**
	 * @ORM\Column(type="integer", name="discount")
	 */
	protected $discount=0;
	/**
	 * @ORM\Column(type="string", name="user_note")
	 */
	protected $user_note=0;
	/**
	 * @ORM\Column(type="string", name="admin_note")
	 */
	protected $admin_note=0;
	/**
	 * @ORM\Column(type="integer", name="is_distribut")
	 */
	protected $is_distribut=0;

	/**
     * @return the $pay_sn
     */
    public function getPay_sn()
    {
        return $this->pay_sn;
    }

 /**
     * @return the $time_left
     */
    public function getTime_left()
    {
        return $this->time_left;
    }

 /**
     * @param number $pay_sn
     */
    public function setPay_sn($pay_sn)
    {
        $this->pay_sn = $pay_sn;
    }

 /**
     * @param number $time_left
     */
    public function setTime_left($time_left)
    {
        $this->time_left = $time_left;
    }

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
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * @param mixed $user_id
	 */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	/**
	 * @return mixed
	 */
	public function getShopId()
	{
		return $this->shop_id;
	}

	/**
	 * @param mixed $shop_id
	 */
	public function setShopId($shop_id)
	{
		$this->shop_id = $shop_id;
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
	public function getShippingStatus()
	{
		return $this->shipping_status;
	}

	/**
	 * @param mixed $shipping_status
	 */
	public function setShippingStatus($shipping_status)
	{
		$this->shipping_status = $shipping_status;
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
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param mixed $country
	 */
	public function setCountry($country)
	{
		$this->country = $country;
	}

	/**
	 * @return mixed
	 */
	public function getProvince()
	{
		return $this->province;
	}

	/**
	 * @param mixed $province
	 */
	public function setProvince($province)
	{
		$this->province = $province;
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	 * @return mixed
	 */
	public function getDistrict()
	{
		return $this->district;
	}

	/**
	 * @param mixed $district
	 */
	public function setDistrict($district)
	{
		$this->district = $district;
	}

	/**
	 * @return mixed
	 */
	public function getTwon()
	{
		return $this->twon;
	}

	/**
	 * @param mixed $twon
	 */
	public function setTwon($twon)
	{
		$this->twon = $twon;
	}

	/**
	 * @return mixed
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @param mixed $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
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
	public function getShippingCode()
	{
		return $this->shipping_code;
	}

	/**
	 * @param mixed $shipping_code
	 */
	public function setShippingCode($shipping_code)
	{
		$this->shipping_code = $shipping_code;
	}

	/**
	 * @return mixed
	 */
	public function getShippingName()
	{
		return $this->shipping_name;
	}

	/**
	 * @param mixed $shipping_name
	 */
	public function setShippingName($shipping_name)
	{
		$this->shipping_name = $shipping_name;
	}

	/**
	 * @return mixed
	 */
	public function getPayCode()
	{
		return $this->pay_code;
	}

	/**
	 * @param mixed $pay_code
	 */
	public function setPayCode($pay_code)
	{
		$this->pay_code = $pay_code;
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
	public function getGoodsNum()
	{
		return $this->goods_num;
	}

	/**
	 * @param mixed $goods_num
	 */
	public function setGoodsNum($goods_num)
	{
		$this->goods_num = $goods_num;
	}

	/**
	 * @return mixed
	 */
	public function getShippingPrice()
	{
		return $this->shipping_price;
	}

	/**
	 * @param mixed $shipping_price
	 */
	public function setShippingPrice($shipping_price)
	{
		$this->shipping_price = $shipping_price;
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
	public function getShippingTime()
	{
		return $this->shipping_time;
	}

	/**
	 * @param mixed $shipping_time
	 */
	public function setShippingTime($shipping_time)
	{
		$this->shipping_time = $shipping_time;
	}

	/**
	 * @return mixed
	 */
	public function getConfirmTime()
	{
		return $this->confirm_time;
	}

	/**
	 * @param mixed $confirm_time
	 */
	public function setConfirmTime($confirm_time)
	{
		$this->confirm_time = $confirm_time;
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
	public function getDiscount()
	{
		return $this->discount;
	}

	/**
	 * @param mixed $discount
	 */
	public function setDiscount($discount)
	{
		$this->discount = $discount;
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
	public function getIsDistribut()
	{
		return $this->is_distribut;
	}

	/**
	 * @param mixed $is_distribut
	 */
	public function setIsDistribut($is_distribut)
	{
		$this->is_distribut = $is_distribut;
	}




}
