<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_member_attent_group")
 */
class MemberAttentGroup {
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
     * @return MemberAttentGroup
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
     * @return MemberAttentGroup
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
     * @return MemberAttentGroup
     */
    public function setMemberId($memberId){
        $this->memberId = $memberId;
        return $this;
    }

}
