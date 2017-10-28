<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_hot_city")
 */
class HotCity {
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
	 * @ORM\Column(type="integer",name="area_id")
	 */
	protected $area_id;
	/**
	 * @ORM\Column(type="string",name="img")
	 */
	protected $img;



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
     * @return HotCity
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
     * @return HotCity
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
     * @return HotCity
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * area_id
     * @return unkown
     */
    public function getArea_id(){
        return $this->area_id;
    }

    /**
     * area_id
     * @param unkown $area_id
     * @return HotCity
     */
    public function setArea_id($area_id){
        $this->area_id = $area_id;
        return $this;
    }

    /**
     * img
     * @return unkown
     */
    public function getImg(){
        return $this->img;
    }

    /**
     * img
     * @param unkown $img
     * @return HotCity
     */
    public function setImg($img){
        $this->img = $img;
        return $this;
    }

}
