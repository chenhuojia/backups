<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_group_tour")
 */
class GroupTour {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $tour_id
     */
    protected $tour_id;

    
     /**
     * @ORM\Column(type="string",name="tour_title")
     */ 
    protected $tour_title ="";

    /**
     * @ORM\Column(type="integer",name="adult_price")
     */ 
    protected $adult_price ="";
    
    /**
     * @ORM\Column(type="string",name="imgurl")
     */ 
    protected $imgurl ="";

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
     * @ORM\Column(type="integer",name="member_id")
     */ 
    protected $member_id =0;

     /**
     * @ORM\Column(type="string",name="member_name")
     */ 
    protected $member_name ="";

     /**
     * @ORM\Column(type="string",name="member_avatar")
     */ 
    protected $member_avatar ="";


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
     * @ORM\Column(type="integer",name="type")
     */
    protected $type =1;
    /**
     * @ORM\Column(type="string",name="longitude")
     */
    protected $longitude ="";
    /**
     * @ORM\Column(type="string",name="latitude")
     */
    protected $latitude ="";
    /**
     * @ORM\Column(type="string",name="geohash")
     */
    protected $geohash ="";

    /**
     * @ORM\Column(type="string",name="address")
     */
    protected $address ="";
   
    /**
     * @ORM\Column(type="string",name="video")
     */
    protected $video ="";

    
    
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
    public function getMemberName()
    {
        return $this->member_name;
    }

    /**
     * @param mixed $member_name
     */
    public function setMemberName($member_name)
    {
        $this->member_name = $member_name;
    }

    /**
     * @return mixed
     */
    public function getMemberAvatar()
    {
        return $this->member_avatar;
    }

    /**
     * @param mixed $member_avatar
     */
    public function setMemberAvatar($member_avatar)
    {
        $this->member_avatar = $member_avatar;
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
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return the $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    /**
     * @return the $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * @return the $geohash
     */
    public function getGeohash()
    {
        return $this->geohash;
    }
    
    /**
     * @return the $content
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @return the $image
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * @return the $video
     */
    public function getVideo()
    {
        return $this->video;
    }
    
    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
    
    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }
    
    /**
     * @param string $geohash
     */
    public function setGeohash($geohash)
    {
        $this->geohash = $geohash;
    }

    
    /**
     * @param string $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

     /**
     * @param string $video
     */
    public function setAdultPrice($adult_price)
    {
        $this->adult_price = $adult_price;
    }

     /**
     * @param string $video
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    



    

}
