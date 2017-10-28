<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_guide_order_goods")
 */
class GuideOrderGoods {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer rec_id
	 */
	protected $rec_id;

	/**
	 * @ORM\Column(type="integer", name="order_id")
	 */
	protected $order_id = 0;
	
	/**
	 * @ORM\Column(type="string", name="book_time")
	 */
	protected $book_time = "0";

	/**
	 * @return int
	 */
	public function getRecId()
	{
		return $this->rec_id;
	}

	/**
	 * @param int $rec_id
	 */
	public function setRecId($rec_id)
	{
		$this->rec_id = $rec_id;
	}

	/**
	 * @return mixed
	 */
	public function getOrderId()
	{
		return $this->order_id;
	}

	/**
	 * @param mixed $order_id
	 */
	public function setOrderId($order_id)
	{
		$this->order_id = $order_id;
	}

	/**
	 * @return mixed
	 */
	public function getBookTime()
	{
		return $this->book_time;
	}

	/**
	 * @param mixed $book_time
	 */
	public function setBookTime($book_time)
	{
		$this->book_time = $book_time;
	}

	




}
