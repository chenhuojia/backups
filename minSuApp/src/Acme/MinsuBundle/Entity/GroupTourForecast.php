<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_group_tour_forecast")
 */
class GroupTourForecast {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $forecast_id
	 */
	protected $forecast_id;
	
	/**
	 * @ORM\Column(type="integer", name="tour_id")
	 */
	protected $tour_id=0;


	/**
	 * @ORM\Column(type="integer", name="member_id")
	 */
	protected $member_id =0;

	/**
	 * @ORM\Column(type="string", name="avatar")
	 */
	protected $avatar="";

	/**
	 * @ORM\Column(type="string", name="username")
	 */
	protected $username="";

	/**
	 * @ORM\Column(type="string", name="account")
	 */
	protected $account="";

	/**
	 * @ORM\Column(type="string", name="identity_card")
	 */
	protected $identity_card="";
	
	/**
	 * @ORM\Column(type="integer", name="enroll_time")
	 */
	protected $enroll_time=0;
	
	/**
	 * @ORM\Column(type="integer", name="state")
	 */
	protected $state = 0;

	/**
	 * @return int
	 */
	public function getForecastId()
	{
		return $this->forecast_id;
	}

	/**
	 * @param int $forecast_id
	 */
	public function setForecastId($forecast_id)
	{
		$this->forecast_id = $forecast_id;
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
	public function getMemberId()
	{
		return $this->member_id;
	}

	/**
	 * @param mixed $member_id
	 */
	public function setMemberId($member_id)
	{
		$this->member_id = $member_id;
	}

	/**
	 * @return mixed
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}

	/**
	 * @param mixed $avatar
	 */
	public function setAvatar($avatar)
	{
		$this->avatar = $avatar;
	}

	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return mixed
	 */
	public function getAccount()
	{
		return $this->account;
	}

	/**
	 * @param mixed $account
	 */
	public function setAccount($account)
	{
		$this->account = $account;
	}

	/**
	 * @return mixed
	 */
	public function getIdentityCard()
	{
		return $this->identity_card;
	}

	/**
	 * @param mixed $identity_card
	 */
	public function setIdentityCard($identity_card)
	{
		$this->identity_card = $identity_card;
	}

	/**
	 * @return mixed
	 */
	public function getEnrollTime()
	{
		return $this->enroll_time;
	}

	/**
	 * @param mixed $enroll_time
	 */
	public function setEnrollTime($enroll_time)
	{
		$this->enroll_time = $enroll_time;
	}

	/**
	 * @return mixed
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @param mixed $state
	 */
	public function setState($state)
	{
		$this->state = $state;
	}

	
	


}
