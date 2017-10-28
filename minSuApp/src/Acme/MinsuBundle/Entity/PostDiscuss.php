<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_post_discuss")
 */
class PostDiscuss {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="comPostId")
	 */
	protected $comPostId;

	/**
	 * @ORM\Column(type="string",name="addtime")
	 */
	protected $addtime;
	/**
	 * @ORM\Column(type="integer",name="memberId")
	 */
	protected $memberId;
	/**
	 * @ORM\Column(type="text",name="discuss")
	 */
	protected $discuss;
	/**
	 * @ORM\Column(type="integer",name="discussParentId")
	 */
	protected $discussParentId;
	/**
	 * @ORM\Column(type="integer",name="type")
	 */
	protected $type;
	/**
	 * @ORM\Column(type="integer",name="grade")
	 */
	protected $grade;

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
     * @return PostDiscuss
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * comPostId
     * @return unkown
     */
    public function getComPostId(){
        return $this->comPostId;
    }

    /**
     * comPostId
     * @param unkown $comPostId
     * @return PostDiscuss
     */
    public function setComPostId($comPostId){
        $this->comPostId = $comPostId;
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
     * @return PostDiscuss
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
     * @return PostDiscuss
     */
    public function setMemberId($memberId){
        $this->memberId = $memberId;
        return $this;
    }

    /**
     * discuss
     * @return unkown
     */
    public function getDiscuss(){
        return $this->discuss;
    }

    /**
     * discuss
     * @param unkown $discuss
     * @return PostDiscuss
     */
    public function setDiscuss($discuss){
        $this->discuss = $discuss;
        return $this;
    }

    /**
     * discussParentId
     * @return unkown
     */
    public function getDiscussParentId(){
        return $this->discussParentId;
    }

    /**
     * discussParentId
     * @param unkown $discussParentId
     * @return PostDiscuss
     */
    public function setDiscussParentId($discussParentId){
        $this->discussParentId = $discussParentId;
        return $this;
    }


    /**
     * type
     * @return unkown
     */
    public function getType(){
        return $this->type;
    }

    /**
     * type
     * @param unkown $type
     * @return PostDiscuss
     */
    public function setType($type){
        $this->type = $type;
        return $this;
    }
	public function getGrade() {
		return $this->grade;
	}
	public function setGrade($grade) {
		$this->grade = $grade;
		return $this;
	}
	

}
