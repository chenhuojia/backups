<?php

namespace Acme\MinsuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="msk_admin")
 */
class MskAdmin
{
    /**
     * @ORM\Column(type="integer",name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",name="admin_name")
     */
    protected $admin_name;

    /**
     * @ORM\Column(type="string",name="admin_password")
     */
    protected $admin_password;

    /**
     * @ORM\Column(type="string",name="admin_login_time")
     */
    protected $admin_login_time;

    /**
     * @ORM\Column(type="string",name="admin_login_num")
     */
    protected $admin_login_num;

    /**
     * @ORM\Column(type="string",name="admin_is_super")
     */
    protected $admin_gid;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAdminName()
    {
        return $this->admin_name;
    }

    /**
     * @param mixed $admin_name
     */
    public function setAdminName($admin_name)
    {
        $this->admin_name = $admin_name;
    }

    /**
     * @return mixed
     */
    public function getAdminPassword()
    {
        return $this->admin_password;
    }

    /**
     * @param mixed $admin_password
     */
    public function setAdminPassword($admin_password)
    {
        $this->admin_password = $admin_password;
    }

    /**
     * @return mixed
     */
    public function getAdminLoginTime()
    {
        return $this->admin_login_time;
    }

    /**
     * @param mixed $admin_login_time
     */
    public function setAdminLoginTime($admin_login_time)
    {
        $this->admin_login_time = $admin_login_time;
    }

    /**
     * @return mixed
     */
    public function getAdminLoginNum()
    {
        return $this->admin_login_num;
    }

    /**
     * @param mixed $admin_login_num
     */
    public function setAdminLoginNum($admin_login_num)
    {
        $this->admin_login_num = $admin_login_num;
    }

    /**
     * @return mixed
     */
    public function getAdminGid()
    {
        return $this->admin_gid;
    }

    /**
     * @param mixed $admin_gid
     */
    public function setAdminGid($admin_gid)
    {
        $this->admin_gid = $admin_gid;
    }
}






















































