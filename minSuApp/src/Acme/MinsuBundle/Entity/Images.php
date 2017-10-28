<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_images")
 */
class Images {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="homestay_room_id")
	 */
	protected $homestay_room_id;

	/**
	 * @ORM\Column(type="integer" , name="member_id")
	 */
	protected $member_id;

	/**
	 * @ORM\Column(type="integer",name="img_type")
	 *
	 */
	protected $img_type;

	/**
	 * @ORM\Column(type="string",name="goods_image")
	 */
	protected $goods_image;
	
	/**
	 * @ORM\Column(type="string",name="img_dscp")
	 */
	protected $img_dscp;

	/**
	 * @ORM\Column(type="string",name="goods_image_sort")
	 */
	protected $goods_image_sort;
	/**
	 * @ORM\Column(type="string",name="is_default")
	 */
	protected $is_default;
	
	/**
	 * @ORM\Column(type="string",name="add_time")
	 */
	protected $add_time;
	

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
     * @return Images
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * homestay_room_id
     * @return unkown
     */
    public function getHomestay_room_id(){
        return $this->homestay_room_id;
    }

    /**
     * homestay_room_id
     * @param unkown $homestay_room_id
     * @return Images
     */
    public function setHomestay_room_id($homestay_room_id){
        $this->homestay_room_id = $homestay_room_id;
        return $this;
    }

    /**
     * member_id
     * @return unkown
     */
    public function getMember_id(){
        return $this->member_id;
    }

    /**
     * member_id
     * @param unkown $member_id
     * @return Images
     */
    public function setMember_id($member_id){
        $this->member_id = $member_id;
        return $this;
    }

    /**
     * img_type
     * @return unkown
     */
    public function getImg_type(){
        return $this->img_type;
    }

    /**
     * img_type
     * @param unkown $img_type
     * @return Images
     */
    public function setImg_type($img_type){
        $this->img_type = $img_type;
        return $this;
    }

    /**
     * goods_image
     * @return unkown
     */
    public function getGoods_image(){
        return $this->goods_image;
    }

    /**
     * goods_image
     * @param unkown $goods_image
     * @return Images
     */
    public function setGoods_image($goods_image){
        $this->goods_image = $goods_image;
        return $this;
    }

    /**
     * img_dscp
     * @return unkown
     */
    public function getImg_dscp(){
        return $this->img_dscp;
    }

    /**
     * img_dscp
     * @param unkown $img_dscp
     * @return Images
     */
    public function setImg_dscp($img_dscp){
        $this->img_dscp = $img_dscp;
        return $this;
    }

    /**
     * goods_image_sort
     * @return unkown
     */
    public function getGoods_image_sort(){
        return $this->goods_image_sort;
    }

    /**
     * goods_image_sort
     * @param unkown $goods_image_sort
     * @return Images
     */
    public function setGoods_image_sort($goods_image_sort){
        $this->goods_image_sort = $goods_image_sort;
        return $this;
    }

    /**
     * is_default
     * @return unkown
     */
    public function getIs_default(){
        return $this->is_default;
    }

    /**
     * is_default
     * @param unkown $is_default
     * @return Images
     */
    public function setIs_default($is_default){
        $this->is_default = $is_default;
        return $this;
    }




    /**
     * add_time
     * @return unkown
     */
    public function getAdd_time(){
        return $this->add_time;
    }

    /**
     * add_time
     * @param unkown $add_time
     * @return Images
     */
    public function setAdd_time($add_time){
        $this->add_time = $add_time;
        return $this;
    }

}
