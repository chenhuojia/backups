<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_calendar")
 */
class Calendar {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $calendar_id
	 */
	protected $calendar_id;

	/**
	 * @ORM\Column(type="integer", name="year")
	 */
	protected $year =1970;

	/**
	 * @ORM\Column(type="integer",name="month")
	 */
	protected $month =0;

	/**
	 * @ORM\Column(type="integer",name="day_sum")
	 */
	protected $day_sum =0;

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
	public function getDaySum()
	{
		return $this->day_sum;
	}

	/**
	 * @param mixed $day_sum
	 */
	public function setDaySum($day_sum)
	{
		$this->day_sum = $day_sum;
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
