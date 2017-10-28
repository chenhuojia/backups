<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-7-30
 * Time: 16:48
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="msk_homestay_upvote")
 */
class HomestayUpvote
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
     * @ORM\Column(type="integer", name="homestay_id")
     */
    protected $homestay_id;

    /**
     * @ORM\Column(type="integer", name="member_id")
     */
    protected $member_id;

    /**
     * @ORM\Column(type="boolean", name="upvote", options={"default":0})
     */
    protected $upvote;

    /**
     * @ORM\Column(type="integer", name="addtime")
     */
    protected $addtime;

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
    public function getHomestayId()
    {
        return $this->homestay_id;
    }

    /**
     * @param mixed $homestay_id
     */
    public function setHomestayId($homestay_id)
    {
        $this->homestay_id = $homestay_id;
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
    public function getUpvote()
    {
        return $this->upvote;
    }

    /**
     * @param mixed $upvote
     */
    public function setUpvote($upvote)
    {
        $this->upvote = $upvote;
    }

    /**
     * @return mixed
     */
    public function getAddtime()
    {
        return $this->addtime;
    }

    /**
     * @param mixed $addtime
     */
    public function setAddtime($addtime)
    {
        $this->addtime = $addtime;
    }

}