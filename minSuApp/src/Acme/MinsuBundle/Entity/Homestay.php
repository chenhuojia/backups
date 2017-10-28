<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_homestay")
 */
class Homestay {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="member_id")
	 */
	protected $member_id;

	/**
	 * @ORM\Column(type="string" , name="homestay_name")
	 */
	protected $homestay_name;

	/**
	 * @ORM\Column(type="string",name="homestay_title")
	 *
	 */
	protected $homestay_title;

	/**
	 * @ORM\Column(type="string",name="homestay_type_id")
	 */
	protected $homestay_type_id;
	
	/**
	 * @ORM\Column(type="string",name="bottom_price")
	 */
	protected $bottom_price =0;

	/**
	 * @ORM\Column(type="string",name="homestay_phone")
	 */
	protected $homestay_phone ="";
	/**
	 * @ORM\Column(type="string",name="homestay_addr")
	 */
	protected $homestay_addr;
	/**
	 * @ORM\Column(type="string",name="repast")
	 */
	protected $repast;
	/**
	 * @ORM\Column(type="string",name="invoice")
	 */
	protected $invoice;
	
	/**
	 * @ORM\Column(type="string",name="reception_time")
	 */
	protected $reception_time;
	/**
	 * @ORM\Column(type="text",name="dscp")
	 */
	protected $dscp;
	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="string",name="is_manage")
	 */
	protected $is_manage;

    /**
     * @ORM\Column(type="boolean",name="state")
     */
    protected $state;
    
    
    /**
     * @ORM\Column(type="string",name="upload_ip")
     */
    protected $upload_ip;
    
    /**
     * @ORM\Column(type="integer",name="upload_terminal")
     */
    protected $upload_terminal;
    
    /**
     * @ORM\Column(type="float",name="longitude")
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="float",name="latitude")
     */
    protected $latitude;

    /**
     * @ORM\Column(type="string",name="geohash")
     */
    protected $geohash="";
    
    /**
     * @ORM\Column(type="string",name="province")
     */
    protected $province;
    
    /**
     * @ORM\Column(type="string",name="city")
     */
    protected $city;
    
    /**
     * @ORM\Column(type="string",name="district")
     */
    protected $district;

    /**
     * @ORM\Column(type="string",name="image_url")
     */
    protected $image_url;
    
    /**
     * @ORM\Column(type="string",name="video_url")
     */
    protected $video_url;
    

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
     * id
     * @return 
     */
    public function getId(){
        return $this->id;
    }

    /**
     * id
     * @param  $id
     * @return Homestay
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * member_id
     * @return unkown
     */
    public function getMember_id(){
        return $this->member_id;
    }

    /**
     * member_id
     * @param unkown $member_id
     * @return Homestay
     */
    public function setMember_id($member_id){
        $this->member_id = $member_id;
        return $this;
    }

    /**
     * homestay_name
     * @return unkown
     */
    public function getHomestay_name(){
        return $this->homestay_name;
    }

    /**
     * homestay_name
     * @param unkown $homestay_name
     * @return Homestay
     */
    public function setHomestay_name($homestay_name){
        $this->homestay_name = $homestay_name;
        return $this;
    }

    /**
     * homestay_title
     * @return unkown
     */
    public function getHomestay_title(){
        return $this->homestay_title;
    }

    /**
     * homestay_title
     * @param unkown $homestay_title
     * @return Homestay
     */
    public function setHomestay_title($homestay_title){
        $this->homestay_title = $homestay_title;
        return $this;
    }

    /**
     * homestay_type_id
     * @return unkown
     */
    public function getHomestay_type_id(){
        return $this->homestay_type_id;
    }

    /**
     * homestay_type_id
     * @param unkown $homestay_type_id
     * @return Homestay
     */
    public function setHomestay_type_id($homestay_type_id){
        $this->homestay_type_id = $homestay_type_id;
        return $this;
    }

    /**
     * bottom_price
     * @return unkown
     */
    public function getBottom_price(){
        return $this->bottom_price;
    }

    /**
     * bottom_price
     * @param unkown $bottom_price
     * @return Homestay
     */
    public function setBottom_price($bottom_price){
        $this->bottom_price = $bottom_price;
        return $this;
    }

    /**
     * homestay_phone
     * @return unkown
     */
    public function getHomestay_phone(){
        return $this->homestay_phone;
    }

    /**
     * homestay_phone
     * @param unkown $homestay_phone
     * @return Homestay
     */
    public function setHomestay_phone($homestay_phone){
        $this->homestay_phone = $homestay_phone;
        return $this;
    }

    /**
     * homestay_addr
     * @return unkown
     */
    public function getHomestay_addr(){
        return $this->homestay_addr;
    }

    /**
     * homestay_addr
     * @param unkown $homestay_addr
     * @return Homestay
     */
    public function setHomestay_addr($homestay_addr){
        $this->homestay_addr = $homestay_addr;
        return $this;
    }

    /**
     * repast
     * @return unkown
     */
    public function getRepast(){
        return $this->repast;
    }

    /**
     * repast
     * @param unkown $repast
     * @return Homestay
     */
    public function setRepast($repast){
        $this->repast = $repast;
        return $this;
    }

    /**
     * invoice
     * @return unkown
     */
    public function getInvoice(){
        return $this->invoice;
    }

    /**
     * invoice
     * @param unkown $invoice
     * @return Homestay
     */
    public function setInvoice($invoice){
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * reception_time
     * @return unkown
     */
    public function getReception_time(){
        return $this->reception_time;
    }

    /**
     * reception_time
     * @param unkown $reception_time
     * @return Homestay
     */
    public function setReception_time($reception_time){
        $this->reception_time = $reception_time;
        return $this;
    }

    /**
     * dscp
     * @return unkown
     */
    public function getDscp(){
        return $this->dscp;
    }

    /**
     * dscp
     * @param unkown $dscp
     * @return Homestay
     */
    public function setDscp($dscp){
        $this->dscp = $dscp;
        return $this;
    }

    /**
     * addtime
     * @return unkown
     */
    public function getAddtime(){
        return $this->addtime;
    }

    /**
     * addtime
     * @param unkown $addtime
     * @return Homestay
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }



    /**
     * is_manage
     * @return unkown
     */
    public function getIs_manage(){
        return $this->is_manage;
    }

    /**
     * is_manage
     * @param unkown $is_manage
     * @return Homestay
     */
    public function setIs_manage($is_manage){
        $this->is_manage = $is_manage;
        return $this;
    }


    /**
     * upload_ip
     * @return unkown
     */
    public function getUpload_ip(){
        return $this->upload_ip;
    }

    /**
     * upload_ip
     * @param unkown $upload_ip
     * @return Homestay
     */
    public function setUpload_ip($upload_ip){
        $this->upload_ip = $upload_ip;
        return $this;
    }

    /**
     * upload_terminal
     * @return unkown
     */
    public function getUpload_terminal(){
        return $this->upload_terminal;
    }

    /**
     * upload_terminal
     * @param unkown $upload_terminal
     * @return Homestay
     */
    public function setUpload_terminal($upload_terminal){
        $this->upload_terminal = $upload_terminal;
        return $this;
    }

    /**
     * longitude
     * @return unkown
     */
    public function getLongitude(){
        return $this->longitude;
    }

    /**
     * longitude
     * @param unkown $longitude
     * @return Homestay
     */
    public function setLongitude($longitude){
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * latitude
     * @return unkown
     */
    public function getLatitude(){
        return $this->latitude;
    }

    /**
     * latitude
     * @param unkown $latitude
     * @return Homestay
     */
    public function setLatitude($latitude){
        $this->latitude = $latitude;
        return $this;
    }

      /**
     * geohash
     * @return unkown
     */
    public function getGeohash(){
        return $this->geohash;
    }

    /**
     * geohash
     * @param unkown $geohash
     * @return geohash
     */
    public function setGeohash($geohash){
        $this->geohash = $geohash;
        return $this;
    }


    /**
     * province
     * @return unkown
     */
    public function getProvince(){
        return $this->province;
    }

    /**
     * province
     * @param unkown $province
     * @return Homestay
     */
    public function setProvince($province){
        $this->province = $province;
        return $this;
    }

    /**
     * city
     * @return unkown
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * city
     * @param unkown $city
     * @return Homestay
     */
    public function setCity($city){
        $this->city = $city;
        return $this;
    }


    /**
     * district
     * @return unkown
     */
    public function getDistrict(){
        return $this->district;
    }

    /**
     * district
     * @param unkown $district
     * @return Homestay
     */
    public function setDistrict($district){
        $this->district = $district;
        return $this;
    }

    /**
     * image_url
     * @return unkown
     */
    public function getImageUrl(){
        return $this->image_url;
    }

    /**
     * image_url
     * @param unkown $image_url
     * @return Homestay
     */
    public function setImageUrl($image_url){
        $this->image_url = $image_url;
        return $this;
    }

     /**
     * video_url
     * @return unkown
     */
    public function getVideoUrl(){
        return $this->video_url;
    }

    /**
     * video_url
     * @param unkown $image_url
     * @return Homestay
     */
    public function setVideoUrl($video_url){
        $this->video_url = $video_url;
        return $this;
    }

}
