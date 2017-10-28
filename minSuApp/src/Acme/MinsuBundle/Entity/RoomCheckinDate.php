<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-9-9
 * Time: 11:26
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="msk_room_checkin_date")
 */
class RoomCheckinDate
{
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
     * @ORM\Column(type="string", name="date_has_checkin")
     */
    protected $date_has_checkin;

    /**
     * @ORM\Column(type="boolean", name="state")
     */
    protected $state;

    /**
     * @ORM\Column(type="integer", name="add_time")
     */
    protected $add_time;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->room_id;
    }

    /**
     * @param mixed $room_id
     */
    public function setRoomId($room_id)
    {
        $this->room_id = $room_id;
    }

    /**
     * @return mixed
     */
    public function getDateHasCheckin()
    {
        return $this->date_has_checkin;
    }

    /**
     * @param mixed $date_has_checkin
     */
    public function setDateHasCheckin($date_has_checkin)
    {
        $this->date_has_checkin = $date_has_checkin;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getAddTime()
    {
        return $this->add_time;
    }

    /**
     * @param mixed $add_time
     */
    public function setAddTime($add_time)
    {
        $this->add_time = $add_time;
    }
}















