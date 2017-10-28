<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_guide_comment")
 */
class GuideComment {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $comment_id
	 */
	protected $comment_id;

	/**
	 * @ORM\Column(type="integer", name="guide_id")
	 */
	protected $guide_id =0;

     /**
     * @ORM\Column(type="integer",name="comment_user")
     */ 
    protected $comment_user =0;

	/**
	 * @ORM\Column(type="string",name="username")
	 */
	protected $username ="";

	/**
	 * @ORM\Column(type="string",name="imageurl")
	 */
	protected $imageurl ="";

	/**
	 * @ORM\Column(type="string",name="content")
	 */
	protected $content ="";

	/**
	 * @ORM\Column(type="integer", name="addtime")
	 */
	protected $addtime =0;

	/**
	 * @ORM\Column(type="integer", name="service_quality")
	 */
	protected $service_quality =5;

	/**
	 * @ORM\Column(type="integer", name="kind")
	 */
	protected $kind =1;

	/**
	 * @return int
	 */
	public function getCommentId()
	{
		return $this->comment_id;
	}

	/**
	 * @param int $comment_id
	 */
	public function setCommentId($comment_id)
	{
		$this->comment_id = $comment_id;
	}

	/**
	 * @return mixed
	 */
	public function getGuideId()
	{
		return $this->guide_id;
	}

	/**
	 * @param mixed $guide_id
	 */
	public function setGuideId($guide_id)
	{
		$this->guide_id = $guide_id;
	}

	/**
	 * @return mixed
	 */
	public function getCommentUser()
	{
		return $this->comment_user;
	}

	/**
	 * @param mixed $comment_user
	 */
	public function setCommentUser($comment_user)
	{
		$this->comment_user = $comment_user;
	}

	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return mixed
	 */
	public function getImageurl()
	{
		return $this->imageurl;
	}

	/**
	 * @param mixed $imageurl
	 */
	public function setImageurl($imageurl)
	{
		$this->imageurl = $imageurl;
	}

	/**
	 * @return mixed
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param mixed $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return mixed
	 */
	public function getAddtime()
	{
		return $this->addtime;
	}

	/**
	 * @param mixed $addtime
	 */
	public function setAddtime($addtime)
	{
		$this->addtime = $addtime;
	}

	/**
	 * @return mixed
	 */
	public function getServiceQuality()
	{
		return $this->service_quality;
	}

	/**
	 * @param mixed $service_quality
	 */
	public function setServiceQuality($service_quality)
	{
		$this->service_quality = $service_quality;
	}

	/**
	 * @return mixed
	 */
	public function getKind()
	{
		return $this->kind;
	}

	/**
	 * @param mixed $kind
	 */
	public function setKind($kind)
	{
		$this->kind = $kind;
	}

	


}
