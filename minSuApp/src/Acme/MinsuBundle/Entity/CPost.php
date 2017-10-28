<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_community_post")
 */
class CPost {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="groupId")
	 */
	protected $groupId;

	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="integer",name="memberId")
	 */
	protected $memberId;
	/**
	 * @ORM\Column(type="text",name="content")
	 */
	protected $content;
	/**
	 * @ORM\Column(type="integer",name="likeNum")
	 */
	protected $likeNum;
	/**
	 * @ORM\Column(type="integer",name="discussNum")
	 */
	protected $discussNum;
	
	/**
	 * @ORM\Column(type="string",name="title")
	 */
	protected $title;

	
	/**
	 * @ORM\Column(type="string",name="longitude")
	 */
	protected $longitude;
	
	/**
	 * @ORM\Column(type="string",name="latitude")
	 */
	protected $latitude;
	/**
	 * @ORM\Column(type="string",name="address")
	 */
	protected $address;
	
	/**
	 * @ORM\Column(type="string",name="att")
	 */
	protected $att;
	
	/**
	 * @ORM\Column(type="integer",name="homestay_id")
	 */
	protected $homestay_id;
	

    /**
     * id
     * @return unkown
     */
    public function getId(){
        return $this->id;
    }

    /**
     * id
     * @param unkown $id
     * @return CPost
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * groupId
     * @return unkown
     */
    public function getGroupId(){
        return $this->groupId;
    }

    /**
     * groupId
     * @param unkown $groupId
     * @return CPost
     */
    public function setGroupId($groupId){
        $this->groupId = $groupId;
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
     * @return CPost
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * memberId
     * @return unkown
     */
    public function getMemberId(){
        return $this->memberId;
    }

    /**
     * memberId
     * @param unkown $memberId
     * @return CPost
     */
    public function setMemberId($memberId){
        $this->memberId = $memberId;
        return $this;
    }

    /**
     * content
     * @return unkown
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * content
     * @param unkown $content
     * @return CPost
     */
    public function setContent($content){
        $this->content = $content;
        return $this;
    }

    /**
     * likeNum
     * @return unkown
     */
    public function getLikeNum(){
        return $this->likeNum;
    }

    /**
     * likeNum
     * @param unkown $likeNum
     * @return CPost
     */
    public function setLikeNum($likeNum){
        $this->likeNum = $likeNum;
        return $this;
    }

    /**
     * discussNum
     * @return unkown
     */
    public function getDiscussNum(){
        return $this->discussNum;
    }

    /**
     * discussNum
     * @param unkown $discussNum
     * @return CPost
     */
    public function setDiscussNum($discussNum){
        $this->discussNum = $discussNum;
        return $this;
    }
	public function getTitle() {
		return $this->title;
	}
	public function setTitle($title) {
		$this->title = $title;
		return $this;
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
	 * @return the $address
	 */
	public function getAddress()
	{
	    return $this->address;
	}
	
	/**
	 * @return the $Att
	 */
	public function getAtt()
	{
	    return $this->att;
	}
	
	/**
	 * @return the $homestay_id
	 */
	public function getHomestayId()
	{
	    return $this->homestay_id;
	}
	
	
	
	/**
	 * @param field_type $longitude
	 */
	public function setLongitude($longitude)
	{
	    $this->longitude = $longitude;
	}
	
	/**
	 * @param field_type $latitude
	 */
	public function setLatitude($latitude)
	{
	    $this->latitude = $latitude;
	}
	
	/**
	 * @param field_type $address
	 */
	public function setAddress($address)
	{
	    $this->address = $address;
	}
    
	/**
	 * @param field_type $att
	 */
	public function setAtt($att)
	{
	    $this->att = $att;
	}
	
	/**
	 * @param field_type $homestay_id
	 */
	public function setHomestayId($homestay_id)
	{
	    $this->homestay_id = $homestay_id;
	}
}
