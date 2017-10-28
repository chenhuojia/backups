<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_order_evaluation")
 */
class OrderEvaluation {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="order_sn")
	 */
	protected $order_sn;

	/**
	 * @ORM\Column(type="string",name="guest_id")
	 */
	protected $guest_id;
	/**
	 * @ORM\Column(type="integer",name="grade")
	 */
	protected $grade;
	/**
	 * @ORM\Column(type="text",name="evaluation")
	 */
	protected $evaluation;
	/**
	 * @ORM\Column(type="integer",name="add_time")
	 */
	protected $add_time;
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getOrderSn() {
		return $this->order_sn;
	}
	public function setOrderSn($order_sn) {
		$this->order_sn = $order_sn;
		return $this;
	}
	public function getGuestId() {
		return $this->guest_id;
	}
	public function setGuestId($guest_id) {
		$this->guest_id = $guest_id;
		return $this;
	}
	public function getGrade() {
		return $this->grade;
	}
	public function setGrade($grade) {
		$this->grade = $grade;
		return $this;
	}
	public function getEvaluation() {
		return $this->evaluation;
	}
	public function setEvaluation($evaluation) {
		$this->evaluation = $evaluation;
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
