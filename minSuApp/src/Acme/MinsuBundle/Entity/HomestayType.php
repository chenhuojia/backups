<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_homestay_type")
 */
class HomestayType {
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
	 * @ORM\Column(type="string",name="homestay_type_name")
	 */
	protected $homestay_type_name;





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
     * @return HomestayType
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
     * @return HomestayType
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
     * @return HomestayType
     */
    public function setAddtime($addtime){
        $this->addtime = $addtime;
        return $this;
    }

    /**
     * homestay_type_name
     * @return unkown
     */
    public function getHomestay_type_name(){
        return $this->homestay_type_name;
    }

    /**
     * homestay_type_name
     * @param unkown $homestay_type_name
     * @return HomestayType
     */
    public function setHomestay_type_name($homestay_type_name){
        $this->homestay_type_name = $homestay_type_name;
        return $this;
    }

}
