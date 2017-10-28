<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_discuss_like")
 */
class MemberLikeDiscuss {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="discuss_id")
	 */
	protected $discuss_id;

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
     * discuss_id
     * @return unkown
     */
    public function getDiscussId(){
        return $this->discuss_id;
    }

    /**
     * discuss_id
     * @param unkown $discuss_id
     * @return MemberLikePost
     */
    public function setDiscussId($discuss_id){
        $this->discuss_id = $discuss_id;
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
