<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_order_refund")
 */
class TourOrderRefund {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $refund_id
	 */
	protected $refund_id;

	/**
	 * @ORM\Column(type="integer", name="order_sn")
	 */
	protected $order_sn = 0;
	
	/**
	 * @ORM\Column(type="integer", name="tour_id")
	 */
	protected $tour_id =0;

	/**
	 * @ORM\Column(type="integer", name="agency_id")
	 */
	protected $agency_id =0;

	/**
	 * @ORM\Column(type="string", name="reason")
	 */
	protected $reason = "";

	/**
	 * @ORM\Column(type="integer", name="user_id")
	 */
	protected $user_id =0;

	/**
	 * @ORM\Column(type="integer", name="goods_return")
	 */
	protected $goods_return = 1;

	/**
	 * @ORM\Column(type="string", name="apply_price")
	 */
	protected $apply_price=0;

	/**
	 * @ORM\Column(type="integer", name="addtime")
	 */
	protected $addtime=0;

	/**
	 * @ORM\Column(type="integer", name="is_agree")
	 */
	protected $is_agree =2;


	/**
	 * @ORM\Column(type="string", name="reply")
	 */
	protected $reply = "";

	
	/**
	 * @ORM\Column(type="string", name="reply_price")
	 */
	protected $reply_price='0';
	
	/**
	 * @ORM\Column(type="integer", name="replytime")
	 */
	protected $replytime = 0;
	
	/**
	 * @ORM\Column(type="string", name="instructions")
	 */
	protected $instructions = "";

	/**
	 * @return int
	 */
	public function getRefundId()
	{
		return $this->refund_id;
	}

	/**
	 * @param int $refund_id
	 */
	public function setRefundId($refund_id)
	{
		$this->refund_id = $refund_id;
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
	public function getReason()
	{
		return $this->reason;
	}

	/**
	 * @param mixed $reason
	 */
	public function setReason($reason)
	{
		$this->reason = $reason;
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
	public function getGoodsReturn()
	{
		return $this->goods_return;
	}

	/**
	 * @param mixed $goods_return
	 */
	public function setGoodsReturn($goods_return)
	{
		$this->goods_return = $goods_return;
	}

	/**
	 * @return mixed
	 */
	public function getApplyPrice()
	{
		return $this->apply_price;
	}

	/**
	 * @param mixed $apply_price
	 */
	public function setApplyPrice($apply_price)
	{
		$this->apply_price = $apply_price;
	}

	/**
	 * @return mixed
	 */
	public function getAddtime()
	{
		return $this->addtime;
	}

	/**
	 * @param mixed $addtime
	 */
	public function setAddtime($addtime)
	{
		$this->addtime = $addtime;
	}

	/**
	 * @return mixed
	 */
	public function getIsAgree()
	{
		return $this->is_agree;
	}

	/**
	 * @param mixed $is_agree
	 */
	public function setIsAgree($is_agree)
	{
		$this->is_agree = $is_agree;
	}

	/**
	 * @return mixed
	 */
	public function getReply()
	{
		return $this->reply;
	}

	/**
	 * @param mixed $reply
	 */
	public function setReply($reply)
	{
		$this->reply = $reply;
	}

	/**
	 * @return mixed
	 */
	public function getReplyPrice()
	{
		return $this->reply_price;
	}

	/**
	 * @param mixed $reply_price
	 */
	public function setReplyPrice($reply_price)
	{
		$this->reply_price = $reply_price;
	}

	/**
	 * @return mixed
	 */
	public function getReplytime()
	{
		return $this->replytime;
	}

	/**
	 * @param mixed $replytime
	 */
	public function setReplytime($replytime)
	{
		$this->replytime = $replytime;
	}

	/**
	 * @return mixed
	 */
	public function getInstructions()
	{
		return $this->instructions;
	}

	/**
	 * @param mixed $instructions
	 */
	public function setInstructions($instructions)
	{
		$this->instructions = $instructions;
	}
	
	
	
	


}
