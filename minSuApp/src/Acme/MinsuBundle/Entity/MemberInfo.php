<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-16
 * Time: 17:47
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_member_info")
 */
class MemberInfo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="nickname")
     */
    protected $nickname;

    /**
     * @ORM\Column(type="string", name="introduce")
     */
    protected $introduce;

    /**
     * @ORM\Column(type="string", name="background_image")
     */
    protected $background_image ="";

    /**
     * @ORM\Column(type="integer", name="my_minsu")
     */
    protected $my_minsu =0;
    /**
     * @ORM\Column(type="integer", name="my_sense")
     */
    protected $my_sense =0;
    /**
     * @ORM\Column(type="integer", name="my_daoyou")
     */
    protected $my_daoyou =0;
    /**
     * @ORM\Column(type="integer", name="my_yigong")
     */
    protected $my_yigong =0;
    /**
     * @ORM\Column(type="integer", name="my_lvyoutuan")
     */
    protected $my_lvyoutuan =0;
    
    /**
     * @return mixed
     */
    public function getIntroduce()
    {
        return $this->introduce;
    }

    /**
     * @param mixed $introduce
     */
    public function setIntroduce($introduce)
    {
        $this->introduce = $introduce;
    }

    /**
     * @ORM\Column(type="string", name="member_id")
     */
    protected $member_id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
    }

    /**
     * @return mixed
     */
    public function getBackgroundImage()
    {
        return $this->background_image;
    }

    /**
     * @param mixed $background_image
     */
    public function setBackgroundImage($background_image)
    {
        $this->background_image = $background_image;
    }

    /**
     * @return the $my_minsu
     */
    public function getMy_minsu()
    {
        return $this->my_minsu;
    }
    
    /**
     * @return the $my_sense
     */
    public function getMy_sense()
    {
        return $this->my_sense;
    }
    
    /**
     * @return the $my_daoyou
     */
    public function getMy_daoyou()
    {
        return $this->my_daoyou;
    }
    
    /**
     * @return the $my_yigong
     */
    public function getMy_yigong()
    {
        return $this->my_yigong;
    }
    
    /**
     * @return the $my_lvyoutuan
     */
    public function getMy_lvyoutuan()
    {
        return $this->my_lvyoutuan;
    }
    
    
    /**
     * @param number $my_minsu
     */
    public function setMy_minsu($my_minsu)
    {
        $this->my_minsu = $my_minsu;
    }
    
    /**
     * @param number $my_sense
     */
    public function setMy_sense($my_sense)
    {
        $this->my_sense = $my_sense;
    }
    
    /**
     * @param number $my_daoyou
     */
    public function setMy_daoyou($my_daoyou)
    {
        $this->my_daoyou = $my_daoyou;
    }
    
    /**
     * @param number $my_yigong
     */
    public function setMy_yigong($my_yigong)
    {
        $this->my_yigong = $my_yigong;
    }
    
    /**
     * @param number $my_lvyoutuan
     */
    public function setMy_lvyoutuan($my_lvyoutuan)
    {
        $this->my_lvyoutuan = $my_lvyoutuan;
    }
    
    
}



















