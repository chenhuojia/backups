<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_points")
 */
class MemberPoints {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="pl_memberid")
	 */
	protected $pl_memberid;

	/**
	 * @ORM\Column(type="integer",name="pl_points")
	 */
	protected $pl_points;
	/**
	 * @ORM\Column(type="string",name="pl_desc")
	 */
	protected $pl_desc;
	
	/**
	 * @ORM\Column(type="integer",name="pl_addtime")
	 */
	protected $pl_addtime;
	
	/**
	 * @ORM\Column(type="string",name="pl_eng")
	 */
	protected $pl_eng;

	
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
     * @return MemberPoints
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * pl_memberid
     * @return unkown
     */
    public function getPl_memberid(){
        return $this->pl_memberid;
    }

    /**
     * pl_memberid
     * @param unkown $pl_memberid
     * @return MemberPoints
     */
    public function setPl_memberid($pl_memberid){
        $this->pl_memberid = $pl_memberid;
        return $this;
    }

    /**
     * pl_points
     * @return unkown
     */
    public function getPl_points(){
        return $this->pl_points;
    }

    /**
     * pl_points
     * @param unkown $pl_points
     * @return MemberPoints
     */
    public function setPl_points($pl_points){
        $this->pl_points = $pl_points;
        return $this;
    }

    /**
     * pl_desc
     * @return unkown
     */
    public function getPl_desc(){
        return $this->pl_desc;
    }

    /**
     * pl_desc
     * @param unkown $pl_desc
     * @return MemberPoints
     */
    public function setPl_desc($pl_desc){
        $this->pl_desc = $pl_desc;
        return $this;
    }

    /**
     * pl_addtime
     * @return unkown
     */
    public function getPl_addtime(){
        return $this->pl_addtime;
    }

    /**
     * pl_addtime
     * @param unkown $pl_addtime
     * @return MemberPoints
     */
    public function setPl_addtime($pl_addtime){
        $this->pl_addtime = $pl_addtime;
        return $this;
    }


    /**
     * pl_eng
     * @return unkown
     */
    public function getPl_eng(){
        return $this->pl_eng;
    }

    /**
     * pl_eng
     * @param unkown $pl_eng
     * @return MemberPoints
     */
    public function setPl_eng($pl_eng){
        $this->pl_eng = $pl_eng;
        return $this;
    }

}
