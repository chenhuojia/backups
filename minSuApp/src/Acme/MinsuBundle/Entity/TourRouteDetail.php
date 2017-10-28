<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_tour_route_detail")
 */
class TourRouteDetail {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $detail_id
     */
    protected $detail_id;

    /**
     * @ORM\Column(type="integer", name="route_id")
     */
    protected $route_id =0;

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
    public function getRouteId()
    {
        return $this->route_id;
    }

    /**
     * @param mixed $route_id
     */
    public function setRouteId($route_id)
    {
        $this->route_id = $route_id;
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

    

    

}
