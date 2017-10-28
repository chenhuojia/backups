<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_room_server_relation")
 */
class RoomServerRelation {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="room_id")
	 */
	protected $room_id;
	
	/**
	 * @ORM\Column(type="integer", name="room_server_id")
	 */
	protected $room_server_id;


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
     * @return RoomServerRelation
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * room_id
     * @return unkown
     */
    public function getRoom_id(){
        return $this->room_id;
    }

    /**
     * room_id
     * @param unkown $room_id
     * @return RoomServerRelation
     */
    public function setRoom_id($room_id){
        $this->room_id = $room_id;
        return $this;
    }

    /**
     * room_server_id
     * @return unkown
     */
    public function getRoom_server_id(){
        return $this->room_server_id;
    }

    /**
     * room_server_id
     * @param unkown $room_server_id
     * @return RoomServerRelation
     */
    public function setRoom_server_id($room_server_id){
        $this->room_server_id = $room_server_id;
        return $this;
    }

}
