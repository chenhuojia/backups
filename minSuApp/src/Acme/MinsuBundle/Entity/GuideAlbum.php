<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_guide_album")
 */
class GuideAlbum {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer album_id
	 */
	protected $album_id;

	/**
	 * @ORM\Column(type="integer", name="guide_id")
	 */
	protected $guide_id;

     /**
     * @ORM\Column(type="string",name="addtime")
     */ 
    protected $addtime;

	/**
	 * @ORM\Column(type="string",name="imageurl")
	 */
	protected $imageurl;

	/**
	 * @return int
	 */
	public function getAlbumId()
	{
		return $this->album_id;
	}

	/**
	 * @param int $album_id
	 */
	public function setAlbumId($album_id)
	{
		$this->album_id = $album_id;
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

    

}
