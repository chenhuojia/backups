<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_cpost_like")
 */
class MemberLikePost {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="pId")
	 */
	protected $pId;

	/**
	 * @ORM\Column(type="integer",name="memberId")
	 */
	protected $memberId;


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
     * @return MemberLikePost
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * pId
     * @return unkown
     */
    public function getPId(){
        return $this->pId;
    }

    /**
     * pId
     * @param unkown $pId
     * @return MemberLikePost
     */
    public function setPId($pId){
        $this->pId = $pId;
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
     * @return MemberLikePost
     */
    public function setMemberId($memberId){
        $this->memberId = $memberId;
        return $this;
    }

}
