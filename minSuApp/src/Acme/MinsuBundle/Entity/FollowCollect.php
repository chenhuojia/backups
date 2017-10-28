<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-6-13
 * Time: 14:45
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="msk_follow_collect")
 */
class FollowCollect
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
     * @ORM\Column(type="integer", name="travel_note_id")
     */
    protected $travel_note_id;

    /**
     * @ORM\Column(type="integer", name="member_id")
     */
    protected $member_id;

    /**
     * @ORM\Column(type="boolean", name="is_upvote", options={"default":0})
     */
    protected $is_upvote;

    /**
     * @ORM\Column(type="boolean", name="is_collect", options={"default":0})
     */
    protected $is_collect;

    /**
     * @ORM\Column(type="integer", name="add_time")
     */
    protected $add_time;

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

    /**
     * @return mixed
     */
    public function getIsUpvote()
    {
        return $this->is_upvote;
    }

    /**
     * @param mixed $is_upvote
     */
    public function setIsUpvote($is_upvote)
    {
        $this->is_upvote = $is_upvote;
    }

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
    public function getTravelNoteId()
    {
        return $this->travel_note_id;
    }

    /**
     * @param mixed $travel_note_id
     */
    public function setTravelNoteId($travel_note_id)
    {
        $this->travel_note_id = $travel_note_id;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
    }
    
    /**
     * @return mixed
     */
    public function getIsCollect()
    {
        return $this->is_collect;
    }

    /**
     * @param mixed $is_collect
     */
    public function setIsCollect($is_collect)
    {
        $this->is_collect = $is_collect;
    }
}

























