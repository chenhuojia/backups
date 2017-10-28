<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_comment")
 */
class Comment {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $comment_id
	 */
	protected $comment_id;

	/**
	 * @ORM\Column(type="integer", name="goods_id")
	 */
	protected $goods_id;

	/**
	 * @ORM\Column(type="string",name="user_id")
	 */
	protected $user_id;
	/**
	 * @ORM\Column(type="string",name="username")
	 */
	protected $username;
	
	/**
	 * @ORM\Column(type="integer",name="goods_rank")
	 */
	protected $goods_rank;
	
	/**
	 * @ORM\Column(type="string",name="content")
	 */
	protected $content;
	/**
	 * @ORM\Column(type="integer",name="add_time")
	 */
	protected $add_time;
	
	/**
	 * @ORM\Column(type="string",name="ip_address")
	 */
	protected $ip_address;
	
	/**
	 * @ORM\Column(type="integer",name="is_show")
	 */
	protected $is_show=1;

	/**
	 * @ORM\Column(type="integer",name="parent_id")
	 */
	protected $parent_id=0;

	/**
	 * @ORM\Column(type="integer",name="is_img")
	 */
	protected $is_img=0;

	/**
	 * @ORM\Column(type="integer",name="order_id")
	 */
	protected $order_id=0;

	/**
     * @return the $comment_id
     */
    public function getComment_id()
    {
        return $this->comment_id;
    }

 /**
     * @return the $goods_id
     */
    public function getGoods_id()
    {
        return $this->goods_id;
    }

 /**
     * @return the $user_id
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * @return the $username
     */
    public function getUsername()
    {
        return $this->username;
    }

 /**
     * @return the $goods_rank
     */
    public function getGoods_rank()
    {
        return $this->goods_rank;
    }

 /**
     * @return the $content
     */
    public function getContent()
    {
        return $this->content;
    }

 /**
     * @return the $add_time
     */
    public function getAdd_time()
    {
        return $this->add_time;
    }

 /**
     * @return the $ip_address
     */
    public function getIp_address()
    {
        return $this->ip_address;
    }

 /**
     * @return the $is_show
     */
    public function getIs_show()
    {
        return $this->is_show;
    }

 /**
     * @return the $parent_id
     */
    public function getParent_id()
    {
        return $this->parent_id;
    }

 /**
     * @return the $is_img
     */
    public function getIs_img()
    {
        return $this->is_img;
    }

 /**
     * @return the $order_id
     */
    public function getOrder_id()
    {
        return $this->order_id;
    }

 /**
     * @param number $comment_id
     */
    public function setComment_id($comment_id)
    {
        $this->comment_id = $comment_id;
    }

 /**
     * @param field_type $goods_id
     */
    public function setGoods_id($goods_id)
    {
        $this->goods_id = $goods_id;
    }

 /**
     * @param field_type $user_id
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

 /**
     * @param field_type $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

 /**
     * @param field_type $goods_rank
     */
    public function setGoods_rank($goods_rank)
    {
        $this->goods_rank = $goods_rank;
    }

 /**
     * @param field_type $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

 /**
     * @param field_type $add_time
     */
    public function setAdd_time($add_time)
    {
        $this->add_time = $add_time;
    }

 /**
     * @param field_type $ip_address
     */
    public function setIp_address($ip_address)
    {
        $this->ip_address = $ip_address;
    }

 /**
     * @param field_type $is_show
     */
    public function setIs_show($is_show)
    {
        $this->is_show = $is_show;
    }

 /**
     * @param field_type $parent_id
     */
    public function setParent_id($parent_id)
    {
        $this->parent_id = $parent_id;
    }

 /**
     * @param number $is_img
     */
    public function setIs_img($is_img)
    {
        $this->is_img = $is_img;
    }

 /**
     * @param number $order_id
     */
    public function setOrder_id($order_id)
    {
        $this->order_id = $order_id;
    }



}
