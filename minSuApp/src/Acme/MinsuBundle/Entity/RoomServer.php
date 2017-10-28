<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_room_server")
 */
class RoomServer {
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
	 * @ORM\Column(type="string",name="server_name")
	 */
	protected $server_name;


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
     * @return Homestay
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
     * @return Homestay
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
     * @return Homestay
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }


    /**
     * server_name
     * @return unkown
     */
    public function getServer_name(){
        return $this->server_name;
    }

    /**
     * server_name
     * @param unkown $server_name
     * @return Homestay
     */
    public function setServer_name($server_name){
        $this->server_name = $server_name;
        return $this;
    }

}
