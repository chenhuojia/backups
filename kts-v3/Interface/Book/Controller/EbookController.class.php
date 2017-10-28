<?php
namespace Book\Controller;
use Common\Controller\ApiController;
class EbookController extends ApiController{
    
    /**
     * 预览图书
     * $bookid
     */
    public function preRead(){
        //判断是否为电子书
    }
    

    /**
     * @param unknown $orderId
     * @param unknown $bookId
     */
    public function  dowloadEbook(){
        //根据orderID检查是否 已经 付款等条件
        //根据$bookid下载
    }
}

