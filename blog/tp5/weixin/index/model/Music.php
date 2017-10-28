<?php
namespace weixin\index\model;
use think\Model;
class Music extends Model
{
    protected $table = 'bk_music';
    public function getRandData(){
        $sql='SELECT * FROM `bk_music` AS t1 JOIN (SELECT ROUND( RAND() * ((SELECT MAX(id) FROM `bk_music`)-(SELECT MIN(id) FROM `bk_music`))+(SELECT MIN(id) FROM `bk_music`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1;';
        $data=$this->query($sql);
        return $data[0];
    }
    
}