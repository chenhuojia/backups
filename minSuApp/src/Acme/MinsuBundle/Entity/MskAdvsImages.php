<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-3
 * Time: 14:00
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="msk_advs_images")
 */
class MskAdvsImages
{
    /**
     * @ORM\Column(type="integer",name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",name="advs_text")
     */
    protected $advs_text;

    /**
     * @ORM\Column(type="string",name="advs_image_path")
     */
    protected $advs_image_path;

    /**
     * @ORM\Column(type="boolean",name="advs_image_sort_id")
     */
    protected $advs_image_sort_id;

    /**
     * @ORM\Column(type="integer",name="advs_is_default")
     */
    protected $advs_is_default;

    /**
     * @ORM\Column(type="integer",name="advs_add_time")
     */
    protected $advs_add_time;

    /**
     * @ORM\Column(type="integer",name="advs_admin_id")
     */
    protected $advs_admin_id;

    /**
     * @ORM\Column(type="integer",name="advs_image_sort_num")
     */
    protected $advs_image_sort_num;

    /**
     * @return mixed
     */
    public function getAdvsImageSortNum()
    {
        return $this->advs_image_sort_num;
    }

    /**
     * @param mixed $advs_image_sort_num
     */
    public function setAdvsImageSortNum($advs_image_sort_num)
    {
        $this->advs_image_sort_num = $advs_image_sort_num;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAdvsText()
    {
        return $this->advs_text;
    }

    /**
     * @param mixed $advs_text
     */
    public function setAdvsText($advs_text)
    {
        $this->advs_text = $advs_text;
    }

    /**
     * @return mixed
     */
    public function getAdvsImagePath()
    {
        return $this->advs_image_path;
    }

    /**
     * @param mixed $advs_image_path
     */
    public function setAdvsImagePath($advs_image_path)
    {
        $this->advs_image_path = $advs_image_path;
    }

    /**
     * @return mixed
     */
    public function getAdvsImageSortId()
    {
        return $this->advs_image_sort_id;
    }

    /**
     * @param mixed $advs_image_sort_id
     */
    public function setAdvsImageSortId($advs_image_sort_id)
    {
        $this->advs_image_sort_id = $advs_image_sort_id;
    }

    /**
     * @return mixed
     */
    public function getAdvsIsDefault()
    {
        return $this->advs_is_default;
    }

    /**
     * @param mixed $advs_is_default
     */
    public function setAdvsIsDefault($advs_is_default)
    {
        $this->advs_is_default = $advs_is_default;
    }

    /**
     * @return mixed
     */
    public function getAdvsAddTime()
    {
        return $this->advs_add_time;
    }

    /**
     * @param mixed $advs_add_time
     */
    public function setAdvsAddTime($advs_add_time)
    {
        $this->advs_add_time = $advs_add_time;
    }

    /**
     * @return mixed
     */
    public function getAdvsAdminId()
    {
        return $this->advs_admin_id;
    }

    /**
     * @param mixed $advs_admin_id
     */
    public function setAdvsAdminId($advs_admin_id)
    {
        $this->advs_admin_id = $advs_admin_id;
    }
}





















