<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_order_action")
 */
class TourOrderAction {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer action_id
	 */
	protected $action_id;

	/**
	 * @ORM\Column(type="integer", name="order_id")
	 */
	protected $order_id = 0;
	
	/**
	 * @ORM\Column(type="integer", name="action_user")
	 */
	protected $action_user = 0;

	/**
	 * @ORM\Column(type="integer", name="order_status")
	 */
	protected $order_status = 0;

	/**
	 * @ORM\Column(type="integer", name="pay_status")
	 */
	protected $pay_status = 0;

	/**
	 * @ORM\Column(type="string", name="action_note")
	 */
	protected $action_note="";

	/**
	 * @ORM\Column(type="integer", name="log_time")
	 */
	protected $log_time=0;

	/**
	 * @ORM\Column(type="string", name="status_desc")
	 */
	protected $status_desc;

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
	public function getActionUser()
	{
		return $this->action_user;
	}

	/**
	 * @param mixed $action_user
	 */
	public function setActionUser($action_user)
	{
		$this->action_user = $action_user;
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
	public function getActionNote()
	{
		return $this->action_note;
	}

	/**
	 * @param mixed $action_note
	 */
	public function setActionNote($action_note)
	{
		$this->action_note = $action_note;
	}

	/**
	 * @return mixed
	 */
	public function getLogTime()
	{
		return $this->log_time;
	}

	/**
	 * @param mixed $log_time
	 */
	public function setLogTime($log_time)
	{
		$this->log_time = $log_time;
	}

	/**
	 * @return mixed
	 */
	public function getStatusDesc()
	{
		return $this->status_desc;
	}

	/**
	 * @param mixed $status_desc
	 */
	public function setStatusDesc($status_desc)
	{
		$this->status_desc = $status_desc;
	}

	/**
	 * @return int
	 */
	public function getActionId()
	{
		return $this->action_id;
	}

	/**
	 * @param int $action_id
	 */
	public function setActionId($action_id)
	{
		$this->action_id = $action_id;
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
	




	
	


}
