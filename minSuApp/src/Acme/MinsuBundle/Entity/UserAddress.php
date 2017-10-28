<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_user_address")
 */
class UserAddress {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer",name="address_id")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $address_id
	 */
	protected $address_id;

	/**
	 * @ORM\Column(type="integer", name="user_id")
	 */
	protected $user_id;

	/**
	 * @ORM\Column(type="string",name="consignee")
	 */
	protected $consignee;
	/**
	 * @ORM\Column(type="string",name="country")
	 */
	protected $country;
	/**
	 * @ORM\Column(type="integer",name="province")
	 */
	protected $province;
	/**
	 * @ORM\Column(type="string",name="city")
	 */
	protected $city;
	/**
	 * @ORM\Column(type="integer", name="district")
	 */
	protected $district;
	/**
	 * @ORM\Column(type="integer", name="twon")
	 */
	protected $twon;
	/**
	 * @ORM\Column(type="string", name="address")
	 */
	protected $address;
	/**
 	 * @ORM\Column(type="string", name="mobile")
	 */
	protected $mobile;
	/**
	 * @ORM\Column(type="integer", name="is_default")
	 */
	protected $is_default;


    /**
     * address_id
     * @return unkown
     */
    public function getaddress_id(){
        return $this->address_id;
    }

    /**
     * address_id
     * @param unkown $id
     * @return Group
     */
    public function setaddress_id($address_id){
        $this->address_id = $address_id;
        return $this;
    }

    /**
     * user_id
     * @return unkown
     */
    public function getuser_id(){
        return $this->user_id;
    }

    /**
     * user_id
     * @param unkown $sort
     * @return Group
     */
    public function setuser_id($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * consignee
     * @return unkown
     */
    public function getconsignee(){
        return $this->consignee;
    }

    /**
     * consignee
     * @param unkown $addtime
     * @return Group
     */
    public function setconsignee($consignee){
        $this->consignee = $consignee;
        return $this;
    }

    /**
     * country
     * @return unkown
     */
    public function getcountry(){
        return $this->country;
    }

    /**
     * country
     * @param unkown $groupName
     * @return Group
     */
    public function setcountry($country){
        $this->country = $country;
        return $this;
    }

    /**
     * province
     * @return unkown
     */
    public function getprovince(){
        return $this->province;
    }

    /**
     * province
     * @param unkown $img
     * @return Group
     */
    public function setprovince($province){
        $this->province = $province;
        return $this;
    }

    /**
     * city
     * @return unkown
     */
    public function getcity(){
        return $this->city;
    }

    /**
     * dscp
     * @param unkown $dscp
     * @return Group
     */
    public function setcity($city){
        $this->city = $city;
        return $this;
    }

    /**
     * district
     * @return unkown
     */
    public function getdistrict(){
        return $this->district;
    }

    /**
     * district
     * @param unkown $followNum
     * @return Group
     */
    public function setdistrict($district){
        $this->district = $district;
        return $this;
    }

    /**
     * twon
     * @return unkown
     */
    public function gettwon(){
        return $this->twon;
    }

    /**
     * twon
     * @param unkown $postNum
     * @return Group
     */
    public function settwon($twon){
        $this->twon = $twon;
        return $this;
    }
    
    /**
     * address
     * @return unkown
     */
    public function getaddress(){
        return $this->address;
    }
    
    /**
     * address
     * @param unkown $postNum
     * @return Group
     */
    public function setaddress($address){
        $this->address = $address;
        return $this;
    }
    
    /**
     * mobile
     * @return unkown
     */
    public function getmobile(){
        return $this->mobile;
    }
    
    /**
     * mobile
     * @param unkown $postNum
     * @return Group
     */
    public function setmobile($mobile){
        $this->mobile = $mobile;
        return $this;
    }
    
    /**
     * is_default
     * @return unkown
     */
    public function getis_default(){
        return $this->is_default;
    }
    
    /**
     * is_default
     * @param unkown $postNum
     * @return Group
     */
    public function setis_default($is_default){
        $this->is_default = $is_default;
        return $this;
    }

}
