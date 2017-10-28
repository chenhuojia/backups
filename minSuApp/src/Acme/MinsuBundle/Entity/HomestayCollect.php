<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-6-15
 * Time: 10:48
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="msk_homestay_collect")
 */
class HomestayCollect
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





















