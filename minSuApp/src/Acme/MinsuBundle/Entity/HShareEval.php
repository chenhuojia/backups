<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_homestay_share_eval")
 */
class HShareEval {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="homestay_id")
	 */
	protected $homestay_id;

	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="integer",name="member_id")
	 */
	protected $member_id;
	/**
	 * @ORM\Column(type="text",name="eval")
	 */
	protected $eval;
	/**
	 * @ORM\Column(type="integer",name="grade")
	 */
	protected $grade;
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getHomestayId() {
		return $this->homestay_id;
	}
	public function setHomestayId($homestay_id) {
		$this->homestay_id = $homestay_id;
		return $this;
	}
	public function getAddtime() {
		return $this->addtime;
	}
	public function setAddtime($addtime) {
		$this->addtime = $addtime;
		return $this;
	}
	public function getMemberId() {
		return $this->member_id;
	}
	public function setMemberId($member_id) {
		$this->member_id = $member_id;
		return $this;
	}
	public function getEval() {
		return $this->eval;
	}
	public function setEval($eval) {
		$this->eval = $eval;
		return $this;
	}
	public function getGrade() {
		return $this->grade;
	}
	public function setGrade($grade) {
		$this->grade = $grade;
		return $this;
	}
	


}
