<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour")
 */
class Tour {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $tour_id
     */
    protected $tour_id;

    /**
     * @ORM\Column(type="integer", name="agency_id")
     */
    protected $agency_id =0;

    /**
     * @ORM\Column(type="integer", name="proxy_id")
     */
    protected $proxy_id =0;

    /**
     * @ORM\Column(type="string",name="agency_name")
     */ 
    protected $agency_name =0;

    
     /**
     * @ORM\Column(type="string",name="tour_title")
     */ 
    protected $tour_title ="";
    
         /**
     * @ORM\Column(type="string",name="imgurl")
     */ 
    protected $imgurl ="";

    /**
     * @ORM\Column(type="integer",name="default_adult_price")
     */ 
    protected $default_adult_price =0;


    /**
     * @ORM\Column(type="integer",name="default_child_price")
     */ 
    protected $default_child_price =0;


    /**
     * @ORM\Column(type="integer",name="age_line")
     */
    protected $age_line =14;

    /**
     * @ORM\Column(type="string",name="period")
     */
    protected $period =1;

    /**
     * @ORM\Column(type="integer",name="starting_time")
     */
    protected $starting_time =0;

    /**
     * @ORM\Column(type="string",name="starting_place")
     */ 
    protected $starting_place ="";

     /**
     * @ORM\Column(type="string",name="service_price")
     */ 
    protected $service_price =0;

      /**
     * @ORM\Column(type="string",name="telphone")
     */ 
    protected $telphone ="";

      /**
     * @ORM\Column(type="string",name="booking_notice")
     */ 
    protected $booking_notice ="";

     /**
     * @ORM\Column(type="integer",name="chief_id")
     */ 
    protected $chief_id =0;

     /**
     * @ORM\Column(type="string",name="chief_name")
     */ 
    protected $chief_name ="";

     /**
     * @ORM\Column(type="string",name="chief_avatar")
     */ 
    protected $chief_avatar ="";


     /**
     * @ORM\Column(type="integer",name="addtime")
     */ 
    protected $addtime =0;

     /**
     * @ORM\Column(type="integer",name="state")
     */ 
    protected $state =1;

     /**
     * @ORM\Column(type="integer",name="entered")
     */ 
    protected $entered =0;

    /**
     * @ORM\Column(type="integer",name="planned")
     */
    protected $planned =0;

    /**
     * @ORM\Column(type="integer",name="end_time")
     */
    protected $end_time =0;

     /**
     * @ORM\Column(type="string",name="destination")
     */ 
    protected $destination ="";

    /**
     * @ORM\Column(type="integer",name="route_id")
     */
    protected $route_id =0;

    /**
     * @return int
     */
    public function getTourId()
    {
        return $this->tour_id;
    }

    /**
     * @param int $tour_id
     */
    public function setTourId($tour_id)
    {
        $this->tour_id = $tour_id;
    }

    /**
     * @return mixed
     */
    public function getAgencyId()
    {
        return $this->agency_id;
    }

    /**
     * @param mixed $agency_id
     */
    public function setAgencyId($agency_id)
    {
        $this->agency_id = $agency_id;
    }

    /**
     * @return mixed
     */
    public function getProxyId()
    {
        return $this->proxy_id;
    }

    /**
     * @param mixed $proxy_id
     */
    public function setProxyId($proxy_id)
    {
        $this->proxy_id = $proxy_id;
    }


    /**
     * @return mixed
     */
    public function getAgencyName()
    {
        return $this->agency_name;
    }

    /**
     * @param mixed $agency_name
     */
    public function setAgencyName($agency_name)
    {
        $this->agency_name = $agency_name;
    }

    /**
     * @return mixed
     */
    public function getTourTitle()
    {
        return $this->tour_title;
    }

    /**
     * @param mixed $tour_title
     */
    public function setTourTitle($tour_title)
    {
        $this->tour_title = $tour_title;
    }

    /**
     * @return mixed
     */
    public function getImgurl()
    {
        return $this->imgurl;
    }

    /**
     * @param mixed $imgurl
     */
    public function setImgurl($imgurl)
    {
        $this->imgurl = $imgurl;
    }

    /**
     * @return mixed
     */
    public function getDefaultAdultPrice()
    {
        return $this->default_adult_price;
    }

    /**
     * @param mixed $default_adult_price
     */
    public function setDefaultAdultPrice($default_adult_price)
    {
        $this->default_adult_price = $default_adult_price;
    }

    /**
     * @return mixed
     */
    public function getDefaultChildPrice()
    {
        return $this->default_child_price;
    }

    /**
     * @param mixed $default_child_price
     */
    public function setDefaultChildPrice($default_child_price)
    {
        $this->default_child_price = $default_child_price;
    }

    /**
     * @return mixed
     */
    public function getAgeLine()
    {
        return $this->age_line;
    }

    /**
     * @param mixed $age_line
     */
    public function setAgeLine($age_line)
    {
        $this->age_line = $age_line;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return mixed
     */
    public function getStartingTime()
    {
        return $this->starting_time;
    }

    /**
     * @param mixed $starting_time
     */
    public function setStartingTime($starting_time)
    {
        $this->starting_time = $starting_time;
    }

    /**
     * @return mixed
     */
    public function getStartingPlace()
    {
        return $this->starting_place;
    }

    /**
     * @param mixed $starting_place
     */
    public function setStartingPlace($starting_place)
    {
        $this->starting_place = $starting_place;
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
    public function getTelphone()
    {
        return $this->telphone;
    }

    /**
     * @param mixed $telphone
     */
    public function setTelphone($telphone)
    {
        $this->telphone = $telphone;
    }

    /**
     * @return mixed
     */
    public function getBookingNotice()
    {
        return $this->booking_notice;
    }

    /**
     * @param mixed $booking_notice
     */
    public function setBookingNotice($booking_notice)
    {
        $this->booking_notice = $booking_notice;
    }

    /**
     * @return mixed
     */
    public function getAddtime()
    {
        return $this->addtime;
    }

    /**
     * @param mixed $addtime
     */
    public function setAddtime($addtime)
    {
        $this->addtime = $addtime;
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

    /**
     * @return mixed
     */
    public function getEntered()
    {
        return $this->entered;
    }

    /**
     * @param mixed $entered
     */
    public function setEntered($entered)
    {
        $this->entered = $entered;
    }

    /**
     * @return mixed
     */
    public function getPlanned()
    {
        return $this->planned;
    }

    /**
     * @param mixed $planned
     */
    public function setPlanned($planned)
    {
        $this->planned = $planned;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param mixed $end_time
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return mixed
     */
    public function getRouteId()
    {
        return $this->route_id;
    }

    /**
     * @param mixed $route_id
     */
    public function setRouteId($route_id)
    {
        $this->route_id = $route_id;
    }

    /**
     * @return mixed
     */
    public function getChiefId()
    {
        return $this->chief_id;
    }

    /**
     * @param mixed $chief_id
     */
    public function setChiefId($chief_id)
    {
        $this->chief_id = $chief_id;
    }

    /**
     * @return mixed
     */
    public function getChiefName()
    {
        return $this->chief_name;
    }

    /**
     * @param mixed $chief_name
     */
    public function setChiefName($chief_name)
    {
        $this->chief_name = $chief_name;
    }

    /**
     * @return mixed
     */
    public function getChiefAvatar()
    {
        return $this->chief_avatar;
    }

    /**
     * @param mixed $chief_avatar
     */
    public function setChiefAvatar($chief_avatar)
    {
        $this->chief_avatar = $chief_avatar;
    }

    






    

}
