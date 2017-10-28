<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_my_wallet")
 */
class MyWallet {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="member_id")
	 */
	protected $member_id;

	/**
	 * @ORM\Column(type="string",name="add_time")
	 */
	protected $add_time;
	/**
	 * @ORM\Column(type="string",name="income")
	 */
	protected $income;
	/**
	 * @ORM\Column(type="string",name="expend")
	 */
	protected $expend;
	/**
	 * @ORM\Column(type="string",name="title")
	 */
	protected $title;
	/**
	 * @ORM\Column(type="string",name="dscp")
	 */
	protected $dscp;
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getMemberId() {
		return $this->member_id;
	}
	public function setMemberId($member_id) {
		$this->member_id = $member_id;
		return $this;
	}
	public function getAddTime() {
		return $this->add_time;
	}
	public function setAddTime($add_time) {
		$this->add_time = $add_time;
		return $this;
	}
	public function getIncome() {
		return $this->income;
	}
	public function setIncome($income) {
		$this->income = $income;
		return $this;
	}
	public function getExpend() {
		return $this->expend;
	}
	public function setExpend($expend) {
		$this->expend = $expend;
		return $this;
	}
	public function getTitle() {
		return $this->title;
	}
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
	public function getDscp() {
		return $this->dscp;
	}
	public function setDscp($dscp) {
		$this->dscp = $dscp;
		return $this;
	}
	
	

}
