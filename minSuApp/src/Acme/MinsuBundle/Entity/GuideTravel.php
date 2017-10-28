<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_guide_travel")
 */
class GuideTravel {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $travel_id
	 */
	protected $travel_id;

	/**
	 * @ORM\Column(type="integer", name="guide_id")
	 */
	protected $guide_id = 0;

     /**
     * @ORM\Column(type="string",name="travel_title")
     */ 
    protected $travel_title = "";

    /**
     * @ORM\Column(type="string",name="addr")
     */ 
    protected $addr = "";

    /**
     * @ORM\Column(type="string",name="longitude")
     */ 
    protected $longitude = "0.00";

    /**
     * @ORM\Column(type="string",name="latitude")
     */ 
    protected $latitude= "0.00";

    /**
     * @ORM\Column(type="string",name="service_price")
     */ 
    protected $service_price= "0.00";

     /**
     * @ORM\Column(type="string",name="city")
     */ 
    protected $city ="";

     /**
     * @ORM\Column(type="string",name="district")
     */ 
    protected $district ="";
    
     /**
     * @ORM\Column(type="string",name="travel_img")
     */ 
    protected $travel_img ="";

	/**
	 * @return int
	 */
	public function getTravelId()
	{
		return $this->travel_id;
	}

	/**
	 * @param int $travel_id
	 */
	public function setTravelId($travel_id)
	{
		$this->travel_id = $travel_id;
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
	public function getTravelTitle()
	{
		return $this->travel_title;
	}

	/**
	 * @param mixed $travel_title
	 */
	public function setTravelTitle($travel_title)
	{
		$this->travel_title = $travel_title;
	}

	/**
	 * @return mixed
	 */
	public function getAddr()
	{
		return $this->addr;
	}

	/**
	 * @param mixed $addr
	 */
	public function setAddr($addr)
	{
		$this->addr = $addr;
	}

	/**
	 * @return mixed
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}

	/**
	 * @param mixed $longitude
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
	}

	/**
	 * @return mixed
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}

	/**
	 * @param mixed $latitude
	 */
	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;
	}

	/**
	 * @return mixed
	 */
	public function getServicePrice()
	{
		return $this->service_price;
	}

	/**
	 * @param mixed $service_price
	 */
	public function setServicePrice($service_price)
	{
		$this->service_price = $service_price;
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	 * @return mixed
	 */
	public function getDistrict()
	{
		return $this->district;
	}

	/**
	 * @param mixed $district
	 */
	public function setDistrict($district)
	{
		$this->district = $district;
	}

	/**
	 * @return mixed
	 */
	public function getTravelImg()
	{
		return $this->travel_img;
	}

	/**
	 * @param mixed $travel_img
	 */
	public function setTravelImg($travel_img)
	{
		$this->travel_img = $travel_img;
	}

	
    

}
