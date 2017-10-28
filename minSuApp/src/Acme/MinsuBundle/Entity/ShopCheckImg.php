<?php
namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_shop_check_img")
 */
class ShopCheckImg {
    /**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
    protected $id;
    /**
     * @ORM\Column(type="integer",name="type")
     */
    protected $type;
    /**
     * @ORM\Column(type="string",name="url")
     */
    protected $url;
    /**
     * @ORM\Column(type="integer",name="shop_apply_id")
     */
    protected $shop_apply_id;
    /**
     * @ORM\Column(type="integer",name="is_show")
     */
    protected $is_show;

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
     * @return Counpon
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    /**
     * type
     * @return unkown
     */
    public function getType(){
        return $this->type;
    }
    
    /**
     * type
     * @param unkown $id
     * @return Counpon
     */
    public function setType($type){
        $this->type = $type;
        return $this;
    }
    /**
     * url
     * @return unkown
     */
    public function getUrl(){
        return $this->url;
    }
    
    /**
     * url
     * @param unkown $id
     * @return Counpon
     */
    public function setUrl($url){
        $this->url = $url;
        return $this;
    }
    /**
     * is_show
     * @return unkown
     */
    public function getIs_show(){
        return $this->is_show;
    }
    
    /**
     * id
     * @param unkown $id
     * @return Counpon
     */
    public function setIs_show($is_show){
        $this->is_show = $is_show;
        return $this;
    }
    
    /**
     * shop_apply_id
     * @return unkown
     */
    public function getShop_apply_id(){
        return $this->check_img_id;
    }
    
    /**
     * check_img_id
     * @param unkown $min_amount
     * @return Counpon
     */
    public function setShop_apply_id($shop_apply_id){
        $this->shop_apply_id = $shop_apply_id;
        return $this;
    }
}