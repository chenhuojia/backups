<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_msg")
 */
class Msg {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="text", name="memberid")
	 */
	protected $memberid;

	/**
	 * @ORM\Column(type="text",name="msg")
	 */
	protected $msg;
	
	/**
	 * @ORM\Column(type="text",name="url")
	 */
	protected $url;
	/**
	 * @ORM\Column(type="integer",name="type")
	 */
	protected $type;
	
	/**
	 * @ORM\Column(type="integer",name="is_read")
	 */
	protected $is_read;
	
	
	/**
	 * @ORM\Column(type="integer",name="cpid")
	 */
	protected $cpid;
	
	/**
	 * @ORM\Column(type="integer",name="addtime")
	 */
	protected $addtime;
	
	


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
     * @return Msg
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * memberid
     * @return unkown
     */
    public function getMemberid(){
        return $this->memberid;
    }

    /**
     * memberid
     * @param unkown $memberid
     * @return Msg
     */
    public function setMemberid($memberid){
        $this->memberid = $memberid;
        return $this;
    }

    /**
     * msg
     * @return unkown
     */
    public function getMsg(){
        return $this->msg;
    }

    /**
     * msg
     * @param unkown $msg
     * @return Msg
     */
    public function setMsg($msg){
        $this->msg = $msg;
        return $this;
    }

    /**
     * url
     * @return unkown
     */
    public function getUrl(){
        return $this->url;
    }

    /**
     * url
     * @param unkown $url
     * @return Msg
     */
    public function setUrl($url){
        $this->url = $url;
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
     * @return Msg
     */
    public function setType($type){
        $this->type = $type;
        return $this;
    }

    /**
     * is_read
     * @return unkown
     */
    public function getIs_read(){
        return $this->is_read;
    }

    /**
     * is_read
     * @param unkown $is_read
     * @return Msg
     */
    public function setIs_read($is_read){
        $this->is_read = $is_read;
        return $this;
    }



    /**
     * cpid
     * @return unkown
     */
    public function getCpid(){
        return $this->cpid;
    }

    /**
     * cpid
     * @param unkown $cpid
     * @return Msg
     */
    public function setCpid($cpid){
        $this->cpid = $cpid;
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
     * @return Msg
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

}
