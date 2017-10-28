<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_guide_calendar")
 */
class GuideCalendar {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $calendar_id
	 */
	protected $calendar_id;

	/**
	 * @ORM\Column(type="integer", name="guide_id")
	 */
	protected $guide_id =0;

	/**
	 * @ORM\Column(type="integer",name="year")
	 */
	protected $year =1970;

	/**
	 * @ORM\Column(type="integer",name="month")
	 */
	protected $month =0;

     /**
     * @ORM\Column(type="string",name="days")
     */ 
    protected $days ="";

	/**
	 * @return int
	 */
	public function getCalendarId()
	{
		return $this->calendar_id;
	}

	/**
	 * @param int $calendar_id
	 */
	public function setCalendarId($calendar_id)
	{
		$this->calendar_id = $calendar_id;
	}

	/**
	 * @return mixed
	 */
	public function getGuideId()
	{
		return $this->guide_id;
	}

	/**
	 * @param mixed $guide_id
	 */
	public function setGuideId($guide_id)
	{
		$this->guide_id = $guide_id;
	}

	/**
	 * @return mixed
	 */
	public function getYear()
	{
		return $this->year;
	}

	/**
	 * @param mixed $year
	 */
	public function setYear($year)
	{
		$this->year = $year;
	}

	/**
	 * @return mixed
	 */
	public function getMonth()
	{
		return $this->month;
	}

	/**
	 * @param mixed $month
	 */
	public function setMonth($month)
	{
		$this->month = $month;
	}

	/**
	 * @return mixed
	 */
	public function getDays()
	{
		return $this->days;
	}

	/**
	 * @param mixed $days
	 */
	public function setDays($days)
	{
		$this->days = $days;
	}


    

}
