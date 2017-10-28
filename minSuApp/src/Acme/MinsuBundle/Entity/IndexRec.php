<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_index_recommend")
 */
class IndexRec {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="sort")
	 */
	protected $sort;

	/**
	 * @ORM\Column(type="string",name="add_time")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="integer",name="rec_id")
	 */
	protected $rec_id;
	
	/**
	 * @ORM\Column(type="integer",name="type")
	 */
	protected $type;
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getSort() {
		return $this->sort;
	}
	public function setSort($sort) {
		$this->sort = $sort;
		return $this;
	}
	public function getAddtime() {
		return $this->addtime;
	}
	public function setAddtime($addtime) {
		$this->addtime = $addtime;
		return $this;
	}
	public function getRecId() {
		return $this->rec_id;
	}
	public function setRecId($rec_id) {
		$this->rec_id = $rec_id;
		return $this;
	}
	public function getType() {
		return $this->type;
	}
	public function setType($type) {
		$this->type = $type;
		return $this;
	}
	



}
