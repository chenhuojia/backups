<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_group_tour_trip")
 */
class GroupTourTrip {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $trip_id
     */
    protected $trip_id;

    /**
     * @ORM\Column(type="integer", name="tour_id")
     */
    protected $tour_id =0;

     /**
     * @ORM\Column(type="string",name="title")
     */ 
    protected $title ="";

    /**
     * @ORM\Column(type="string",name="longitude")
     */ 
    protected $longitude ="";

     /**
     * @ORM\Column(type="string",name="latitude")
     */ 
    protected $latitude ="";

    /**
     * @ORM\Column(type="integer",name="num")
     */
    protected $num =1;

    

    /**
     * @return int
     */
    public function getTripId()
    {
        return $this->trip_id;
    }

    /**
     * @param int $trip_id
     */
    public function setTripId($trip_id)
    {
        $this->trip_id = $trip_id;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param mixed $num
     */
    public function setNum($num)
    {
        $this->num = $num;
    }

     

    

    

}
