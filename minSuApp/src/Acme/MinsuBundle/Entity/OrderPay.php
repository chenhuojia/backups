<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_order_pay")
 */
class OrderPay {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $pay_id
	 */
	protected $pay_id;

	/**
	 * @ORM\Column(type="integer", name="pay_sn")
	 */
	protected $pay_sn;
	
	/**
	 * @ORM\Column(type="integer", name="buyer_id")
	 */
	protected $buyer_id;
	
	/**
	 * @ORM\Column(type="string", name="api_pay_state")
	 */
	protected $api_pay_state;
	
	
	public function getPayId() {
		return $this->pay_id;
	}
	public function setPayId($pay_id) {
		$this->pay_id = $pay_id;
		return $this;
	}
	public function getPaySn() {
		return $this->pay_sn;
	}
	public function setPaySn($pay_sn) {
		$this->pay_sn = $pay_sn;
		return $this;
	}
	public function getBuyerId() {
		return $this->buyer_id;
	}
	public function setBuyerId($buyer_id) {
		$this->buyer_id = $buyer_id;
		return $this;
	}
	public function getApiPayState() {
		return $this->api_pay_state;
	}
	public function setApiPayState($api_pay_state) {
		$this->api_pay_state = $api_pay_state;
		return $this;
	}
	
	
}
