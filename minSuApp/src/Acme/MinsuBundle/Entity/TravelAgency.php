<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_travel_agency")
 */
class TravelAgency {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $agency_id
	 */
	protected $agency_id;

	/**
	 * @ORM\Column(type="integer", name="member_id")
	 */
	protected $member_id =0;

    /**
     * @ORM\Column(type="string",name="agency_name")
     */ 
    protected $agency_name ="";

    /**
     * @ORM\Column(type="string",name="agency_image")
     */ 
    protected $agency_image ="";

    /**
     * @ORM\Column(type="string",name="experience")
     */
    protected $experience ="";

    /**
     * @ORM\Column(type="string",name="introduction")
     */
    protected $introduction ="";

     /**
     * @ORM\Column(type="string",name="agency_address")
     */
    protected $agency_address ="";

    /**
     * @ORM\Column(type="string",name="agency_tel")
     */
    protected $agency_tel ="";

    /**
     * @ORM\Column(type="integer",name="age_line")
     */
    protected $age_line =14;

    /**
     * @ORM\Column(type="integer",name="service_quality")
     */
    protected $service_quality =5;

    /**
     * @ORM\Column(type="integer",name="state")
     */
    protected $state =0;

    /**
     * @ORM\Column(type="integer",name="add_time")
     */
    protected $add_time =0;

    /**
     * @ORM\Column(type="string",name="remark")
     */
    protected $remark ="";

     /**
     * @ORM\Column(type="string",name="manage_name")
     */
    protected $manage_name ="";

     /**
     * @ORM\Column(type="string",name="manage_identity_card")
     */
    protected $manage_identity_card ="";


    /**
     * @ORM\Column(type="string",name="manage_phone")
     */
    protected $manage_phone ="";


    /**
     * @ORM\Column(type="integer",name="proxy_num")
     */
    protected $proxy_num =0;

    /**
     * @return int
     */
    public function getAgencyId()
    {
        return $this->agency_id;
    }

    /**
     * @param int $agency_id
     */
    public function setAgencyId($agency_id)
    {
        $this->agency_id = $agency_id;
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
    public function getAgencyImage()
    {
        return $this->agency_image;
    }

    /**
     * @param mixed $agency_image
     */
    public function setAgencyImage($agency_image)
    {
        $this->agency_image = $agency_image;
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
    public function getAgencyAddress()
    {
        return $this->agency_address;
    }

    /**
     * @param mixed $agency_address
     */
    public function setAgencyAddress($agency_address)
    {
        $this->agency_address = $agency_address;
    }

    /**
     * @return mixed
     */
    public function getAgencyTel()
    {
        return $this->agency_tel;
    }

    /**
     * @param mixed $agency_tel
     */
    public function setAgencyTel($agency_tel)
    {
        $this->agency_tel = $agency_tel;
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
    public function getManageName()
    {
        return $this->manage_name;
    }

    /**
     * @param mixed $manage_name
     */
    public function setManageName($manage_name)
    {
        $this->manage_name = $manage_name;
    }

    /**
     * @return mixed
     */
    public function getManageIdentityCard()
    {
        return $this->manage_identity_card;
    }

    /**
     * @param mixed $manage_identity_card
     */
    public function setManageIdentityCard($manage_identity_card)
    {
        $this->manage_identity_card = $manage_identity_card;
    }

    /**
     * @return mixed
     */
    public function getManagePhone()
    {
        return $this->manage_phone;
    }

    /**
     * @param mixed $manage_phone
     */
    public function setManagePhone($manage_phone)
    {
        $this->manage_phone = $manage_phone;
    }

    /**
     * @return mixed
     */
    public function getProxyNum()
    {
        return $this->proxy_num;
    }

    /**
     * @param mixed $proxy_num
     */
    public function setProxyNum($proxy_num)
    {
        $this->proxy_num = $proxy_num;
    }






    

}
