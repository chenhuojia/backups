<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-6-16
 * Time: 11:07
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_member_relation")
 */
class MemberRelation
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
     * @ORM\Column(type="bigint", name="from_member_id")
     */
    protected $from_member_id;

    /**
     * @ORM\Column(type="bigint", name="to_member_id")
     */
    protected $to_member_id;

    /**
     * @ORM\Column(type="boolean", name="relation_type")
     */
    protected $relation_type;

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
    public function getFromMemberId()
    {
        return $this->from_member_id;
    }

    /**
     * @param mixed $from_member_id
     */
    public function setFromMemberId($from_member_id)
    {
        $this->from_member_id = $from_member_id;
    }

    /**
     * @return mixed
     */
    public function getToMemberId()
    {
        return $this->to_member_id;
    }

    /**
     * @param mixed $to_member_id
     */
    public function setToMemberId($to_member_id)
    {
        $this->to_member_id = $to_member_id;
    }

    /**
     * @return mixed
     */
    public function getRelationType()
    {
        return $this->relation_type;
    }

    /**
     * @param mixed $relation_type
     */
    public function setRelationType($relation_type)
    {
        $this->relation_type = $relation_type;
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

























