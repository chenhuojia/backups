<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_points_goods")
 */
class PointsGoods {
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
	 * @ORM\Column(type="string",name="goods_name")
	 */
	protected $goods_name;
	
	
	/**
	 * @ORM\Column(type="string",name="goods_points")
	 */
	protected $goods_points;
	/**
	 * @ORM\Column(type="string",name="quantity")
	 */
	protected $quantity;
	
	/**
	 * @ORM\Column(type="string",name="goods_images")
	 */
	protected $goods_images;
	
	/**
	 * @ORM\Column(type="string",name="state")
	 */
	protected $state;


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
     * @return PointGoods
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
     * @return PointGoods
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
     * @return PointGoods
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * goods_name
     * @return unkown
     */
    public function getGoods_name(){
        return $this->goods_name;
    }

    /**
     * goods_name
     * @param unkown $goods_name
     * @return PointGoods
     */
    public function setGoods_name($goods_name){
        $this->goods_name = $goods_name;
        return $this;
    }

    /**
     * goods_points
     * @return unkown
     */
    public function getGoods_points(){
        return $this->goods_points;
    }

    /**
     * goods_points
     * @param unkown $goods_points
     * @return PointGoods
     */
    public function setGoods_points($goods_points){
        $this->goods_points = $goods_points;
        return $this;
    }

    /**
     * quantity
     * @return unkown
     */
    public function getQuantity(){
        return $this->quantity;
    }

    /**
     * quantity
     * @param unkown $quantity
     * @return PointGoods
     */
    public function setQuantity($quantity){
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * goods_images
     * @return unkown
     */
    public function getGoods_images(){
        return $this->goods_images;
    }

    /**
     * goods_images
     * @param unkown $goods_images
     * @return PointGoods
     */
    public function setGoods_images($goods_images){
        $this->goods_images = $goods_images;
        return $this;
    }

    /**
     * state
     * @return unkown
     */
    public function getState(){
        return $this->state;
    }

    /**
     * state
     * @param unkown $state
     * @return PointGoods
     */
    public function setState($state){
        $this->state = $state;
        return $this;
    }

}
