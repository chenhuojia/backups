<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_draw_balance")
 */
class DrawBalance {
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
	 * @ORM\Column(type="string", name="c_name")
	 */
	protected $c_name;
	
	/**
	 * @ORM\Column(type="string", name="balance")
	 */
	protected $balance;
	
	
	/**
	 * @ORM\Column(type="string", name="ali_pre_acc")
	 */
	protected $ali_pre_acc;
	

	/**
	 * @ORM\Column(type="string", name="add_time")
	 */
	protected $add_time;
	
	
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
	public function getCName() {
		return $this->c_name;
	}
	public function setCName($c_name) {
		$this->c_name = $c_name;
		return $this;
	}
	public function getBalance() {
		return $this->balance;
	}
	public function setBalance($balance) {
		$this->balance = $balance;
		return $this;
	}
	public function getAliPreAcc() {
		return $this->ali_pre_acc;
	}
	public function setAliPreAcc($ali_pre_acc) {
		$this->ali_pre_acc = $ali_pre_acc;
		return $this;
	}
	public function getAddTime() {
		return $this->add_time;
	}
	public function setAddTime($add_time) {
		$this->add_time = $add_time;
		return $this;
	}
	
}
