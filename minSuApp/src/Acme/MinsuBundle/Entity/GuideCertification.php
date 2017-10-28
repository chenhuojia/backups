<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_guide_certification")
 */
class GuideCertification {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer certificate_id
	 */
	protected $certificate_id;

	/**
	 * @ORM\Column(type="integer", name="guide_id")
	 */
	protected $guide_id =0;

     /**
     * @ORM\Column(type="string",name="positive_identity")
     */ 
    protected $positive_identity ="";

	/**
	 * @ORM\Column(type="string",name="opposite_identity")
	 */
	protected $opposite_identity ="";

	/**
	 * @ORM\Column(type="string",name="guide_card")
	 */
	protected $guide_card ="";

	/**
	 * @ORM\Column(type="string",name="handheld_identity")
	 */
	protected $handheld_identity ="";


	/**
	 * @return int
	 */
	public function getCertificateId()
	{
		return $this->certificate_id;
	}

	/**
	 * @param int $certificate_id
	 */
	public function setCertificateId($certificate_id)
	{
		$this->certificate_id = $certificate_id;
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
	public function getPositiveIdentity()
	{
		return $this->positive_identity;
	}

	/**
	 * @param mixed $positive_identity
	 */
	public function setPositiveIdentity($positive_identity)
	{
		$this->positive_identity = $positive_identity;
	}

	/**
	 * @return mixed
	 */
	public function getOppositeIdentity()
	{
		return $this->opposite_identity;
	}

	/**
	 * @param mixed $opposite_identity
	 */
	public function setOppositeIdentity($opposite_identity)
	{
		$this->opposite_identity = $opposite_identity;
	}

	/**
	 * @return mixed
	 */
	public function getGuideCard()
	{
		return $this->guide_card;
	}

	/**
	 * @param mixed $guide_card
	 */
	public function setGuideCard($guide_card)
	{
		$this->guide_card = $guide_card;
	}

	/**
	 * @return mixed
	 */
	public function getHandheldIdentity()
	{
		return $this->handheld_identity;
	}

	/**
	 * @param mixed $handheld_identity
	 */
	public function setHandheldIdentity($handheld_identity)
	{
		$this->handheld_identity = $handheld_identity;
	}



}
