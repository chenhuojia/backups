<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_group")
 */
class Group {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="sort")
	 */
	protected $sort;

	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="string",name="groupName")
	 */
	protected $groupName;
	/**
	 * @ORM\Column(type="string",name="img")
	 */
	protected $img;
	/**
	 * @ORM\Column(type="string",name="dscp")
	 */
	protected $dscp;
	/**
	 * @ORM\Column(type="integer", name="followNum")
	 */
	protected $followNum;
	/**
	 * @ORM\Column(type="integer", name="postNum")
	 */
	protected $postNum;


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
     * @return Group
     */
    public function setId($id){
        $this->id = $id;
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
     * @return Group
     */
    public function setSort($sort){
        $this->sort = $sort;
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
     * @return Group
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * groupName
     * @return unkown
     */
    public function getGroupName(){
        return $this->groupName;
    }

    /**
     * groupName
     * @param unkown $groupName
     * @return Group
     */
    public function setGroupName($groupName){
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * img
     * @return unkown
     */
    public function getImg(){
        return $this->img;
    }

    /**
     * img
     * @param unkown $img
     * @return Group
     */
    public function setImg($img){
        $this->img = $img;
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
     * @return Group
     */
    public function setDscp($dscp){
        $this->dscp = $dscp;
        return $this;
    }

    /**
     * followNum
     * @return unkown
     */
    public function getFollowNum(){
        return $this->followNum;
    }

    /**
     * followNum
     * @param unkown $followNum
     * @return Group
     */
    public function setFollowNum($followNum){
        $this->followNum = $followNum;
        return $this;
    }

    /**
     * postNum
     * @return unkown
     */
    public function getPostNum(){
        return $this->postNum;
    }

    /**
     * postNum
     * @param unkown $postNum
     * @return Group
     */
    public function setPostNum($postNum){
        $this->postNum = $postNum;
        return $this;
    }

}
