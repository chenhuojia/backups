<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-6-6
 * Time: 13:16
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_travel_note_images")
 */
class TravelNoteImage{
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
     * @ORM\Column(type="string", name="travel_note_image")
     */
    protected $travel_note_image;

    /**
     * @ORM\Column(type="integer", name="travel_note_image_sort")
     */
    protected $travel_note_image_sort;

    /**
     * @ORM\Column(type="integer", name="is_default")
     */
    protected $is_default;

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
    public function getTravelNoteImage()
    {
        return $this->travel_note_image;
    }

    /**
     * @param mixed $travel_note_image
     */
    public function setTravelNoteImage($travel_note_image)
    {
        $this->travel_note_image = $travel_note_image;
    }

    /**
     * @return mixed
     */
    public function getTravelNoteImageSort()
    {
        return $this->travel_note_image_sort;
    }

    /**
     * @param mixed $travel_note_image_sort
     */
    public function setTravelNoteImageSort($travel_note_image_sort)
    {
        $this->travel_note_image_sort = $travel_note_image_sort;
    }

    /**
     * @return mixed
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * @param mixed $is_default
     */
    public function setIsDefault($is_default)
    {
        $this->is_default = $is_default;
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























