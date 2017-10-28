<?php
class Util
{
    /**天猫
     * 
     */
    public static function getEmallBookinfo($ISBN){
         import('Book/Util/selector',APP_PATH);
         import('Common/Common/requests',APP_PATH);
         \requests::set_useragent('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');
         \requests::set_header('Accept-Language','zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,zh-TW;q=0.2');
         \requests::set_header('Accept-Encoding','gzip, deflate, sdch, br');
         \requests::set_header('Accept','*/*');
         \requests::set_header('Host','list.tmall.com');
         \requests::set_header('Cache-Control',' no-cache');
         \requests::set_header('Postman-Token',' be39b27e-233e-041b-b0ca-460854499c60');
         \requests::set_header('Connection','keep-alive');
         \requests::set_header('Cookie','_tb_token_=e6PCKy3Rmhpm; cookie2=2262e143914829af836aaa533844aea6; t=0abf0b5d7a67c8978ecb9cfbab009f55');
         $html =\requests::post('https://list.tmall.com/search_product.htm?q='.$ISBN);
         $data=\selector::select($html,'//*[@id="J_ItemList"]/div[1]/div/div[1]/a/@href');
    }
    /**
     * 获取当当图书
     */
    public static function getDDBookinfo($ISBN){
        import('Book/Util/selector',APP_PATH);
        import('Common/Common/requests',APP_PATH);
        $html =\requests::get('http://search.dangdang.com/?key='.$ISBN.'&act=input');
        $html=\selector::select('13143708937', '/^((13[0-9])|147|(15[0-35-9])|180|182|(18[5-9]))[0-9]{8}','regex');
        return $html;
    }
    /**京东
     * 
     */
    public static function getJDBookInfo($ISBN){
        import('Book/Util/selector',APP_PATH);
        import('Common/Common/requests',APP_PATH);
        import('Common/Common/elementHandler',APP_PATH);
        $html =\requests::get('https://item.jd.com/11144230.html');
        $infos=\selector::select($html, '//*[@id="parameter2"]/li','xpath','\Book\Util\elementHandler');
        
        return ($infos);
    }
}

