<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_detail")
 */
class TourDetail {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $detail_id
     */
    protected $detail_id;

    /**
     * @ORM\Column(type="integer", name="tour_id")
     */
    protected $tour_id =0;

    /**
     * @ORM\Column(type="string",name="longitude")
     */ 
    protected $longitude ="";

     /**
     * @ORM\Column(type="string",name="latitude")
     */ 
    protected $latitude ="";

    /**
     * @ORM\Column(type="integer",name="site")
     */
    protected $site =1;

     /**
     * @ORM\Column(type="integer",name="state")
     */
    protected $state =1;

    /**
     * @return int
     */
    public function getDetailId()
    {
        return $this->detail_id;
    }

    /**
     * @param int $detail_id
     */
    public function setDetailId($detail_id)
    {
        $this->detail_id = $detail_id;
    }

    /**
     * @return mixed
     */
    public function getTourId()
    {
        return $this->tour_id;
    }

    /**
     * @param mixed $tour_id
     */
    public function setTourId($tour_id)
    {
        $this->tour_id = $tour_id;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

     /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    

    

}
