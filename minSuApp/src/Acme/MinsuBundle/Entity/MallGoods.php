<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_mall_goods")
 */
class MallGoods {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $goods_id
	 */
	protected $goods_id;

	/**
	 * @ORM\Column(type="integer", name="cat_id")
	 */
	protected $cat_id;

	/**
	 * @ORM\Column(type="string",name="goods_name")
	 */
	protected $goods_name;
	/**
	 * @ORM\Column(type="integer",name="store_count")
	 */
	protected $store_count=10;
	/**
	 * @ORM\Column(type="integer",name="market_price")
	 */
	protected $market_price=0;
	/**
	 * @ORM\Column(type="integer",name="shop_price")
	 */
	protected $shop_price=0;
	/**
	 * @ORM\Column(type="string", name="keywords")
	 */
	protected $keywords=0;
	/**
	 * @ORM\Column(type="string", name="goods_remark")
	 */
	protected $goods_remark=0;
	/**
	 * @ORM\Column(type="string", name="goods_content")
	 */
	protected $goods_content=0;
	/**
	 * @ORM\Column(type="integer", name="is_real")
	 */
	protected $is_real=1;
	/**
	 * @ORM\Column(type="integer", name="is_free_shipping")
	 */
	protected $is_free_shipping=1;
	/**
	 * @ORM\Column(type="integer", name="on_time")
	 */
	protected $on_time=0;
	/**
	 * @ORM\Column(type="integer", name="is_recommend")
	 */
	protected $is_recommend=0;
	/**
	 * @ORM\Column(type="integer", name="is_new")
	 */
	protected $is_new=1;
	/**
	 * @ORM\Column(type="integer", name="is_hot")
	 */
	protected $is_hot=0;
	/**
	 * @ORM\Column(type="integer", name="last_update")
	 */
	protected $last_update=0;
	/**
	 * @ORM\Column(type="integer", name="goods_type")
	 */
	protected $goods_type=0;
	/**
	 * @ORM\Column(type="integer", name="spec_type")
	 */
	protected $spec_type=0;
	/**
	 * @ORM\Column(type="integer", name="suppliers_id")
	 */
	protected $suppliers_id=0;
	/**
	 * @ORM\Column(type="integer", name="click_count")
	 */
	protected $click_count=0;
	/**
	 * @ORM\Column(type="integer", name="comment_count")
	 */
	protected $comment_count=0;
	/**
	 * @ORM\Column(type="integer", name="sales_sum")
	 */
	protected $sales_sum=0;
	/**
	 * @ORM\Column(type="integer", name="is_on_sale")
	 */
	protected $is_on_sale=1;

	
	/**
     * @return the $goods_id
     */
    public function getGoods_id()
    {
        return $this->goods_id;
    }

    /**
     * @return the $cat_id
     */
    public function getCat_id()
    {
        return $this->cat_id;
    }

    /**
     * @return the $goods_name
     */
    public function getGoods_name()
    {
        return $this->goods_name;
    }

    /**
     * @return the $store_count
     */
    public function getStore_count()
    {
        return $this->store_count;
    }

     /**
     * @return the $market_price
     */
    public function getMarket_price()
    {
        return $this->market_price;
    }

    /**
     * @return the $shop_price
     */
    public function getShop_price()
    {
        return $this->shop_price;
    }

    /**
     * @return the $keywords
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return the $goods_remark
     */
    public function getGoods_remark()
    {
        return $this->goods_remark;
    }

    /**
     * @return the $goods_content
     */
    public function getGoods_content()
    {
        return $this->goods_content;
    }

    /**
     * @return the $is_real
     */
    public function getIs_real()
    {
        return $this->is_real;
    }

    /**
     * @return the $is_free_shipping
     */
    public function getIs_free_shipping()
    {
        return $this->is_free_shipping;
    }

    /**
     * @return the $on_time
     */
    public function getOn_time()
    {
        return $this->on_time;
    }

    /**
     * @return the $is_recommend
     */
    public function getIs_recommend()
    {
        return $this->is_recommend;
    }

    /**
     * @return the $is_new
     */
    public function getIs_new()
    {
        return $this->is_new;
    }

    /**
     * @return the $is_hot
     */
    public function getIs_hot()
    {
        return $this->is_hot;
    }

    /**
     * @return the $last_update
     */
    public function getLast_update()
    {
        return $this->last_update;
    }

    /**
     * @return the $goods_type
     */
    public function getGoods_type()
    {
        return $this->goods_type;
    }

    /**
     * @return the $spec_type
     */
    public function getSpec_type()
    {
        return $this->spec_type;
    }

    /**
     * @return the $suppliers_id
     */
    public function getSuppliers_id()
    {
        return $this->suppliers_id;
    }

    /**
     * @return the $click_count
     */
    public function getClick_count()
    {
        return $this->click_count;
    }

    /**
     * @return the $comment_count
     */
    public function getComment_count()
    {
        return $this->comment_count;
    }

    /**
     * @return the $sales_sum
     */
    public function getSales_sum()
    {
        return $this->sales_sum;
    }

    /**
     * @return the $is_on_sale
     */
    public function getIs_on_sale()
    {
        return $this->is_on_sale;
    }

    /**
     * @param number $goods_id
     */
    public function setGoods_id($goods_id)
    {
        $this->goods_id = $goods_id;
    }

    /**
     * @param field_type $cat_id
     */
    public function setCat_id($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    /**
     * @param field_type $goods_name
     */
    public function setGoods_name($goods_name)
    {
        $this->goods_name = $goods_name;
    }

    /**
     * @param field_type $store_count
     */
    public function setStore_count($store_count)
    {
        $this->store_count = $store_count;
    }

    /**
     * @param field_type $market_price
     */
    public function setMarket_price($market_price)
    {
        $this->market_price = $market_price;
    }

    /**
     * @param field_type $shop_price
     */
    public function setShop_price($shop_price)
    {
        $this->shop_price = $shop_price;
    }

    /**
     * @param field_type $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @param field_type $goods_remark
     */
    public function setGoods_remark($goods_remark)
    {
        $this->goods_remark = $goods_remark;
    }

    /**
     * @param field_type $goods_content
     */
    public function setGoods_content($goods_content)
    {
        $this->goods_content = $goods_content;
    }

    /**
     * @param field_type $is_real
     */
    public function setIs_real($is_real)
    {
        $this->is_real = $is_real;
    }

     /**
     * @param field_type $is_free_shipping
     */
    public function setIs_free_shipping($is_free_shipping)
    {
        $this->is_free_shipping = $is_free_shipping;
    }

    /**
     * @param field_type $on_time
     */
    public function setOn_time($on_time)
    {
        $this->on_time = $on_time;
    }

    /**
     * @param field_type $is_recommend
     */
    public function setIs_recommend($is_recommend)
    {
        $this->is_recommend = $is_recommend;
    }

    /**
     * @param field_type $is_new
     */
    public function setIs_new($is_new)
    {
        $this->is_new = $is_new;
    }

    /**
     * @param field_type $is_hot
     */
    public function setIs_hot($is_hot)
    {
        $this->is_hot = $is_hot;
    }

    /**
     * @param field_type $last_update
     */
    public function setLast_update($last_update)
    {
        $this->last_update = $last_update;
    }

    /**
     * @param field_type $goods_type
     */
    public function setGoods_type($goods_type)
    {
        $this->goods_type = $goods_type;
    }

    /**
     * @param field_type $spec_type
     */
    public function setSpec_type($spec_type)
    {
        $this->spec_type = $spec_type;
    }

    /**
     * @param field_type $suppliers_id
     */
    public function setSuppliers_id($suppliers_id)
    {
        $this->suppliers_id = $suppliers_id;
    }

    /**
     * @param field_type $click_count
     */
    public function setClick_count($click_count)
    {
        $this->click_count = $click_count;
    }

    /**
     * @param field_type $comment_count
     */
    public function setComment_count($comment_count)
    {
        $this->comment_count = $comment_count;
    }

    /**
     * @param field_type $sales_sum
     */
    public function setSales_sum($sales_sum)
    {
        $this->sales_sum = $sales_sum;
    }

    /**
     * @param field_type $is_on_sale
     */
    public function setIs_on_sale($is_on_sale)
    {
        $this->is_on_sale = $is_on_sale;
    }

	



}
