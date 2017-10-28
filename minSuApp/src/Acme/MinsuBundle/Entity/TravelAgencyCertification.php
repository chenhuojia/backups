<?php

namespace Acme\MinsuBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_travel_agency_certification")
 */
class TravelAgencyCertification {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer certificate_id
	 */
	protected $certificate_id;

	/**
	 * @ORM\Column(type="integer", name="agency_id")
	 */
	protected $agency_id =0;
    
    /**
     * @ORM\Column(type="string",name="Legal_representative")
     */ 
    protected $Legal_representative ="";

       /**
     * @ORM\Column(type="string",name="business_license")
     */ 
    protected $business_license ="";

    /**
     * @ORM\Column(type="string",name="trading_license ")
     */ 
    protected $trading_license  ="";

    /**
     * @ORM\Column(type="string",name="business_license_num ")
     */ 
    protected $business_license_num  ="";
    
    /**
     * @ORM\Column(type="string",name="trading_license_num ")
     */ 
    protected $trading_license_num  ="";

    /**
	 * @ORM\Column(type="integer", name="business_license_limit")
	 */
	protected $business_license_limit =0;

	/**
	 * @ORM\Column(type="integer", name="trading_license_limit")
	 */
	protected $trading_license_limit =0;

	/**
     * @ORM\Column(type="string",name="positive_identity ")
     */ 
    protected $positive_identity  ="";

    /**
     * @ORM\Column(type="string",name="opposite_identity ")
     */ 
    protected $opposite_identity  ="";

    /**
     * @ORM\Column(type="string",name="handheld_identity ")
     */ 
    protected $handheld_identity  ="";

     /**
     * @ORM\Column(type="integer",name="deposit ")
     */ 
    protected $deposit  =1000;
    /**
     * @ORM\Column(type="string",name="order_sn ")
     */ 
    protected $order_sn  ="";
    /**
     * @ORM\Column(type="string",name="pay_sn ")
     */ 
    protected $pay_sn  ="";

    /**
     * @ORM\Column(type="integer",name="pay_time ")
     */ 
    protected $pay_time  =0;
	
   
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
	public function getAgencyId()
	{
		return $this->agency_id;
	}

	/**
	 * @param mixed $agency_id
	 */
	public function setAgencyId($agency_id)
	{
		$this->agency_id = $agency_id;
	}

	/**
	 * @return mixed
	 */
	public function getBusinessLicense()
	{
		return $this->business_license;
	}

	/**
	 * @param mixed $business_license
	 */
	public function setBusinessLicense($business_license)
	{
		$this->business_license = $business_license;
	}

	/**
	 * @return mixed
	 */
	public function getTradingLicense()
	{
		return $this->trading_license;
	}

	/**
	 * @param mixed $trading_license
	 */
	public function setTradingLicense($trading_license)
	{
		$this->trading_license = $trading_license;
	}

	/**
	 * @return mixed
	 */
	public function getBusinessLicenseNum()
	{
		return $this->business_license_num;
	}

	/**
	 * @param mixed $business_license_num
	 */
	public function setBusinessLicenseNum($business_license_num)
	{
		$this->business_license_num = $business_license_num;
	}

	/**
	 * @return mixed
	 */
	public function getTradingLicenseNum()
	{
		return $this->trading_license_num;
	}

	/**
	 * @param mixed $trading_license_num
	 */
	public function setTradingLicenseNum($trading_license_num)
	{
		$this->trading_license_num = $trading_license_num;
	}

	/**
	 * @return mixed
	 */
	public function getBusinessLicenseLimit()
	{
		return $this->business_license_limit;
	}

	/**
	 * @param mixed $business_license_limit
	 */
	public function setBusinessLicenseLimit($business_license_limit)
	{
		$this->business_license_limit = $business_license_limit;
	}

	/**
	 * @return mixed
	 */
	public function getTradingLicenseLimit()
	{
		return $this->trading_license_limit;
	}

	/**
	 * @param mixed $trading_license_limit
	 */
	public function setTradingLicenseLimit($trading_license_limit)
	{
		$this->trading_license_limit = $trading_license_limit;
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

	/**
	 * @return mixed
	 */
	public function getDeposit()
	{
		return $this->deposit;
	}

	/**
	 * @param mixed $deposit
	 */
	public function setDeposit($deposit)
	{
		$this->deposit = $deposit;
	}

	/**
	 * @return mixed
	 */
	public function getLegalRepresentative()
	{
		return $this->Legal_representative;
	}

	/**
	 * @param mixed $Legal_representative
	 */
	public function setLegalRepresentative($Legal_representative)
	{
		$this->Legal_representative = $Legal_representative;
	}

	 /**
	 * @return string
	 */
	public function getOrderSn()
	{
		return $this->order_sn;
	}

	/**
	 * @param string $order_sn
	 */
	public function setOrderSn($order_sn)
	{
		$this->order_sn = $order_sn;
	}

	 /**
	 * @return string
	 */
	public function getPaySn()
	{
		return $this->pay_sn;
	}

	/**
	 * @param string $pay_sn
	 */
	public function setPaySn($pay_sn)
	{
		$this->pay_sn = $pay_sn;
	}

	/**
	 * @return int
	 */
	public function getPayTime()
	{
		return $this->pay_time;
	}

	/**
	 * @param int $pay_time
	 */
	public function setPayTime($pay_time)
	{
		$this->pay_time = $pay_time;
	}


	
	


}
