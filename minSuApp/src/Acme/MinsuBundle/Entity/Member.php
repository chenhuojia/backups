<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-16
 * Time: 13:06
 */
namespace Acme\MinsuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="msk_member")
 */
class Member
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
     * @ORM\Column(type="string", name="account")
     */
    protected $account;

    /**
     * @ORM\Column(type="string", name="password")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", name="true_name")
     */
    protected $true_name;

    /**
     * @ORM\Column(type="string", name="member_qqopenid")
     */
    protected $member_qqopenid;

    /**
     * @ORM\Column(type="string", name="member_sinaopenid")
     */
    protected $member_sinaopenid;

    /**
    * @ORM\Column(type="string", name="member_wxopenid")
    */
    protected $member_wxopenid;

    /**
     * @ORM\Column(type="boolean", name="sex")
     */
    protected $sex;

    /**
     * @ORM\Column(type="string", name="avatar")
     */
    protected $avatar;

    /**
     * @ORM\Column(type="integer", name="member_points")
     */
    protected $member_points;

    /**
     * @ORM\Column(type="string", name="member_login_time")
     */
    protected $member_login_time;

    /**
     * @ORM\Column(type="string", name="member_old_login_time")
     */
    protected $member_old_login_time;

    /**
     * @ORM\Column(type="string", name="member_login_ip")
     */
    protected $member_login_ip;

    /**
     * @ORM\Column(type="string", name="member_old_login_ip")
     */
    protected $member_old_login_ip;

    /**
     * @ORM\Column(type="boolean", name="login_num")
     */
    protected $login_num;

    /**
     * @ORM\Column(type="boolean", name="is_owner")
     */
    protected $is_owner;

    /**
     * @ORM\Column(type="boolean", name="member_state")
     */
    protected $member_state;

    /**
     * @ORM\Column(type="string", name="creat_date")
     */
    protected $creat_date;

    /**
     * @ORM\Column(type="string", name="token")
     */
    protected $token=0;


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
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getTrueName()
    {
        return $this->true_name;
    }

    /**
     * @param mixed $true_name
     */
    public function setTrueName($true_name)
    {
        $this->true_name = $true_name;
    }

    /**
     * @return mixed
     */
    public function getMemberQqopenid()
    {
        return $this->member_qqopenid;
    }

    /**
     * @param mixed $member_qqopenid
     */
    public function setMemberQqopenid($member_qqopenid)
    {
        $this->member_qqopenid = $member_qqopenid;
    }

    /**
     * @return mixed
     */
    public function getMemberSinaopenid()
    {
        return $this->member_sinaopenid;
    }

    /**
     * @param mixed $member_sinaopenid
     */
    public function setMemberSinaopenid($member_sinaopenid)
    {
        $this->member_sinaopenid = $member_sinaopenid;
    }

    /**
     * @return mixed
     */
    public function getMemberWxopenid()
    {
        return $this->member_wxopenid;
    }

    /**
     * @param mixed $member_wxopenid
     */
    public function setMemberWxopenid($member_wxopenid)
    {
        $this->member_wxopenid = $member_wxopenid;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getMemberPoints()
    {
        return $this->member_points;
    }

    /**
     * @param mixed $member_points
     */
    public function setMemberPoints($member_points)
    {
        $this->member_points = $member_points;
    }

    /**
     * @return mixed
     */
    public function getMemberLoginTime()
    {
        return $this->member_login_time;
    }

    /**
     * @param mixed $member_login_time
     */
    public function setMemberLoginTime($member_login_time)
    {
        $this->member_login_time = $member_login_time;
    }

    /**
     * @return mixed
     */
    public function getMemberOldLoginTime()
    {
        return $this->member_old_login_time;
    }

    /**
     * @param mixed $member_old_login_time
     */
    public function setMemberOldLoginTime($member_old_login_time)
    {
        $this->member_old_login_time = $member_old_login_time;
    }

    /**
     * @return mixed
     */
    public function getMemberLoginIp()
    {
        return $this->member_login_ip;
    }

    /**
     * @param mixed $member_login_ip
     */
    public function setMemberLoginIp($member_login_ip)
    {
        $this->member_login_ip = $member_login_ip;
    }

    /**
     * @return mixed
     */
    public function getMemberOldLoginIp()
    {
        return $this->member_old_login_ip;
    }

    /**
     * @param mixed $member_old_login_ip
     */
    public function setMemberOldLoginIp($member_old_login_ip)
    {
        $this->member_old_login_ip = $member_old_login_ip;
    }

    /**
     * @return mixed
     */
    public function getLoginNum()
    {
        return $this->login_num;
    }

    /**
     * @param mixed $login_num
     */
    public function setLoginNum($login_num)
    {
        $this->login_num = $login_num;
    }

    /**
     * @return mixed
     */
    public function getIsOwner()
    {
        return $this->is_owner;
    }

    /**
     * @param mixed $is_owner
     */
    public function setIsOwner($is_owner)
    {
        $this->is_owner = $is_owner;
    }

    /**
     * @return mixed
     */
    public function getMemberState()
    {
        return $this->member_state;
    }

    /**
     * @param mixed $member_state
     */
    public function setMemberState($member_state)
    {
        $this->member_state = $member_state;
    }

    /**
     * @return mixed
     */
    public function getCreatDate()
    {
        return $this->creat_date;
    }

    /**
     * @param mixed $creat_date
     */
    public function setCreatDate($creat_date)
    {
        $this->creat_date = $creat_date;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}





















