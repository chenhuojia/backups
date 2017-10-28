<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-3
 * Time: 14:13
 */
namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_advs_image_sort")
 */
class advsImageSort
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
     * @ORM\Column(type="string", name="sort_type")
     */
    protected $sort_type;

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
    public function getSortType()
    {
        return $this->sort_type;
    }

    /**
     * @param mixed $sort_type
     */
    public function setSortType($sort_type)
    {
        $this->sort_type = $sort_type;
    }
}