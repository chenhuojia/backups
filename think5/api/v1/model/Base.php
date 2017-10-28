<?php
namespace api\v1\model;
use think\Model;

class Base extends Model{
    protected $table;
    //自定义初始化
    public function initialize(){       
        $this->table='users';
        parent::initialize();
    }
    
}