<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_guide")
 */
class Guide {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $guide_id
	 */
	protected $guide_id;

	/**
	 * @ORM\Column(type="integer", name="member_id")
	 */
	protected $member_id =0;

    /**
     * @ORM\Column(type="integer",name="guide_price")
     */ 
    protected $guide_price =0;

    /**
     * @ORM\Column(type="string",name="experience")
     */
    protected $experience ="";

     /**
     * @ORM\Column(type="string",name="real_name")
     */ 
    protected $real_name ="";

    /**
     * @ORM\Column(type="integer",name="gender")
     */ 
    protected $gender = 0;


    /**
     * @ORM\Column(type="integer",name="age")
     */
    protected $age =0;


    /**
     * @ORM\Column(type="string",name="language")
     */
    protected $language ="";

    /**
     * @ORM\Column(type="string",name="introduction")
     */
    protected $introduction ="";

    /**
     * @ORM\Column(type="string",name="phone")
     */
    protected $phone ="";

    /**
     * @ORM\Column(type="string",name="identity_card")
     */
    protected $identity_card ="";

    /**
     * @ORM\Column(type="string",name="home_address")
     */
    protected $home_address ="";

    /**
     * @ORM\Column(type="string",name="lead_time")
     */
    protected $lead_time =0;

    /**
     * @ORM\Column(type="string",name="real_avatar")
     */
    protected $real_avatar = "";

    /**
     * @ORM\Column(type="integer",name="service_quality")
     */
    protected $service_quality = 0;

  
    /**
     * @ORM\Column(type="integer",name="is_other")
     */
    protected $is_other = 1;

    /**
     * @ORM\Column(type="integer",name="state")
     */
    protected $state =0;
    
    /**
     * @ORM\Column(type="integer",name="type")
     */
    protected $type =1;
    

    /**
     * @ORM\Column(type="integer",name="add_time")
     */
    protected $add_time =0;

    /**
     * @ORM\Column(type="string",name="remark")
     */
    protected $remark ="";

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
     * @ORM\Column(type="string",name="geohash")
     */
    protected $geohash ="";

    /**
     * @ORM\Column(type="string",name="addr")
     */
    protected $addr ="";

    /**
     * @ORM\Column(type="string",name="image_url")
     */
    protected $image_url ="";
    
    /**
     * @ORM\Column(type="string",name="video_url")
     */
    protected $video_url ="";

    /**
     * @return int
     */
    public function getGuideId()
    {
        return $this->guide_id;
    }

    /**
     * @param int $guide_id
     */
    public function setGuideId($guide_id)
    {
        $this->guide_id = $guide_id;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $umember_id
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
    }

    /**
     * @return mixed
     */
    public function getGuidePrice()
    {
        return $this->guide_price;
    }

    /**
     * @param mixed $guide_price
     */
    public function setGuidePrice($guide_price)
    {
        $this->guide_price = $guide_price;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getRealName()
    {
        return $this->real_name;
    }

    /**
     * @param mixed $real_name
     */
    public function setRealName($real_name)
    {
        $this->real_name = $real_name;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @param mixed $introduction
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    }

    /**
     * @return mixed
     */
    public function getHomeAddress()
    {
        return $this->home_address;
    }

    /**
     * @param mixed $home_address
     */
    public function setHomeAddress($home_address)
    {
        $this->home_address = $home_address;
    }

    /**
     * @return mixed
     */
    public function getLeadTime()
    {
        return $this->lead_time;
    }

    /**
     * @param mixed $lead_time
     */
    public function setLeadTime($lead_time)
    {
        $this->lead_time = $lead_time;
    }

    /**
     * @return mixed
     */
    public function getRealAvatar()
    {
        return $this->real_avatar;
    }

    /**
     * @param mixed $real_avatar
     */
    public function setRealAvatar($real_avatar)
    {
        $this->real_avatar = $real_avatar;
    }

    /**
     * @return mixed
     */
    public function getServiceQuality()
    {
        return $this->service_quality;
    }

    /**
     * @param mixed $service_quality
     */
    public function setServiceQuality($service_quality)
    {
        $this->service_quality = $service_quality;
    }

    

    /**
     * @return mixed
     */
    public function getIsOther()
    {
        return $this->is_other;
    }

    /**
     * @param mixed $is_other
     */
    public function setIsOther($is_other)
    {
        $this->is_other = $is_other;
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
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAddTime()
    {
        return $this->add_time;
    }

    /**
     * @param mixed $add_time
     */
    public function setAddTime($add_time)
    {
        $this->add_time = $add_time;
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
    public function getGeohash()
    {
        return $this->geohash;
    }

    /**
     * @param mixed $geohash
     */
    public function setGeohash($geohash)
    {
        $this->geohash = $geohash;
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
     * @return the $image_url
     */
    public function getImage_url()
    {
        return $this->image_url;
    }
    
    /**
     * @return the $video_url
     */
    public function getVideo_url()
    {
        return $this->video_url;
    }
    
    /**
     * @param string $image_url
     */
    public function setImage_url($image_url)
    {
        $this->image_url = $image_url;
    }
    
    /**
     * @param string $video_url
     */
    public function setVideo_url($video_url)
    {
        $this->video_url = $video_url;
    }

    
    
    /**
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
    

}
