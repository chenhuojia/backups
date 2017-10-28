<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_restock_of_homestay")
 */
class RestockOfHomestay {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer $id
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", name="restockId")
	 */
	protected $restockId;
	
	/**
	 * @ORM\Column(type="integer", name="homesId")
	 */
	protected $homesId;




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
     * @return RestockOfHomestay
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * restockId
     * @return unkown
     */
    public function getRestockId(){
        return $this->restockId;
    }

    /**
     * restockId
     * @param unkown $restockId
     * @return RestockOfHomestay
     */
    public function setRestockId($restockId){
        $this->restockId = $restockId;
        return $this;
    }

    /**
     * homesId
     * @return unkown
     */
    public function getHomesId(){
        return $this->homesId;
    }

    /**
     * homesId
     * @param unkown $homesId
     * @return RestockOfHomestay
     */
    public function setHomesId($homesId){
        $this->homesId = $homesId;
        return $this;
    }

}
