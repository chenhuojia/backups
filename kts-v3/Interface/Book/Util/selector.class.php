<?php
class selector
{
    const VERSION = '1.0.0';
    public static $dom = null;
    public static $dom_auth = null;
    public static $xpath = null;
    public static $error = null;
    public static function select($html, $selector, $selector_type = 'xpath',$method)
    {
        if (empty($html) || empty($selector)) 
        {
            return false;
        }
        $selector_type = strtolower($selector_type);
        if ($selector_type == 'xpath') 
        {
            return self::_xpath_select($html, $selector,false,$method);
        }
        elseif ($selector_type == 'regex') 
        {
            return self::_regex_select($html, $selector,false,$method);
        }
        elseif ($selector_type == 'css') 
        {
            return self::_css_select($html, $selector,false,$method);
        }
    }
    public static function remove($html, $selector, $selector_type = 'xpath',$method)
    {
        if (empty($html) || empty($selector)) 
        {
            return false;
        }
        $remove_html = "";
        $selector_type = strtolower($selector_type);
        if ($selector_type == 'xpath') 
        {
            $remove_html = self::_xpath_select($html, $selector, true,$method);
        }
        elseif ($selector_type == 'regex') 
        {
            $remove_html = self::_regex_select($html, $selector, true,$method);
        }
        elseif ($selector_type == 'css') 
        {
            $remove_html =  self::_css_select($html, $selector, true,$method);
        }
        $html = str_replace($remove_html, "", $html);
        return $html;
    }
    /**
     * xpath选择器
     * 
     * @param mixed $html
     * @param mixed $selector
     * @return void
     * @author seatle <seatle@foxmail.com> 
     * @created time :2016-10-26 12:53
     */
    private static function _xpath_select($html, $selector, $remove = false,$method)
    {
        if (!is_object(self::$dom))
        {
            self::$dom = new DOMDocument();
        }
        // 如果加载的不是之前的HTML内容，替换一下验证标识
        if (self::$dom_auth != md5($html)) 
        {
            self::$dom_auth = md5($html);
            @self::$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
            self::$xpath = new DOMXpath(self::$dom);
        }
        //libxml_use_internal_errors(true);
        //self::$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        //$errors = libxml_get_errors();
        //if (!empty($errors)) 
        //{
            //print_r($errors);
            //exit;
        //}
        $elements = @self::$xpath->query($selector);
        if ($elements === false)
        {
            self::$error = "the selector in the xpath(\"{$selector}\") syntax errors";
            return false;
        }
        $result = array();
        if (!is_null($elements)) 
        {
            foreach ($elements as $element) 
            {
                // 如果是删除操作，取一整块代码
                if ($remove) 
                {
                    $content = self::$dom->saveXml($element);
                }
                else 
                {
                    $content=$method::handler($element,self::$dom);
                }
                $result[] = $content;
            }
        }
        if (empty($result)) 
        {
            return false;
        }
        // 如果只有一个元素就直接返回string，否则返回数组
        return count($result) > 1 ? $method::result($result) : $result[0];
    }
    /**
     * 正则选择器
     * 
     * @param mixed $html
     * @param mixed $selector
     * @return void
     * @author seatle <seatle@foxmail.com> 
     * @created time :2016-10-26 12:53
     */
    private static function _regex_select($html, $selector, $remove = false)
    {
        if(@preg_match_all($selector, $html, $out) === false)
        {
            self::$error = "the selector in the regex(\"{$selector}\") syntax errors";
            return false;
        }
        $count = count($out);
        $result = array();
        // 一个都没有匹配到
        if ($count == 0) 
        {
            return false;
        }
        // 只匹配一个，就是只有一个 ()
        elseif ($count == 2) 
        {
            // 删除的话取匹配到的所有内容
            if ($remove) 
            {
                $result = $out[0];
            }
            else 
            {
                $result = $out[1];
            }
        }
        else 
        {
            for ($i = 1; $i < $count; $i++) 
            {
                // 如果只有一个元素，就直接返回好了
                $result[] = count($out[$i]) > 1 ? $out[$i] : $out[$i][0];
            }
        }
        if (empty($result)) 
        {
            return false;
        }
        
        return count($result) > 1 ? $result : $result[0];
    }
    private static function _css_select($html, $selector, $remove = false)
    {
    }
    public static function find_all($html, $selector)
    {
    }
}