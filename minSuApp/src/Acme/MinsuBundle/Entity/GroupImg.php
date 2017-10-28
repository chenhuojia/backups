<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_group_img")
 */
class GroupImg {
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
	 * @ORM\Column(type="integer" , name="memberId")
	 */
	protected $memberId;

	/**
	 * @ORM\Column(type="integer",name="imgType")
	 *
	 */
	protected $imgType;

	/**
	 * @ORM\Column(type="string",name="imageName")
	 */
	protected $imageName;

	/**
	 * @ORM\Column(type="string",name="sort")
	 */
	protected $sort;

	
	/**
	 * @ORM\Column(type="string",name="addTime")
	 */
	protected $addTime;
	


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
     * @return GroupImg
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
     * @return GroupImg
     */
    public function setGroupId($groupId){
        $this->groupId = $groupId;
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
     * @return GroupImg
     */
    public function setMemberId($memberId){
        $this->memberId = $memberId;
        return $this;
    }

    /**
     * imgType
     * @return unkown
     */
    public function getImgType(){
        return $this->imgType;
    }

    /**
     * imgType
     * @param unkown $imgType
     * @return GroupImg
     */
    public function setImgType($imgType){
        $this->imgType = $imgType;
        return $this;
    }

    /**
     * imageName
     * @return unkown
     */
    public function getImageName(){
        return $this->imageName;
    }

    /**
     * imageName
     * @param unkown $imageName
     * @return GroupImg
     */
    public function setImageName($imageName){
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * sort
     * @return unkown
     */
    public function getSort(){
        return $this->sort;
    }

    /**
     * sort
     * @param unkown $sort
     * @return GroupImg
     */
    public function setSort($sort){
        $this->sort = $sort;
        return $this;
    }




    /**
     * addTime
     * @return unkown
     */
    public function getAddTime(){
        return $this->addTime;
    }

    /**
     * addTime
     * @param unkown $addTime
     * @return GroupImg
     */
    public function setAddTime($addTime){
        $this->addTime = $addTime;
        return $this;
    }

}
