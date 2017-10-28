<?php
namespace Book\Util;

class elementHandler
{

    public static function handler(&$element, &$dom)
    {
        $nodeName = $element->nodeName;
        $nodeType = $element->nodeType;
        // 1.Element 2.Attribute 3.Text
        // $nodeAttr = $element->getAttribute('src');
        // $nodes = util::node_to_array(self::$dom, $element);
        // echo $nodes['@src']."\n";
        // 如果是img标签，直接取src值
        if ($nodeType == 1 && in_array($nodeName, array(
            'img'
        ))) {
            $content = $element->getAttribute('src');
        }        // 如果是标签属性，直接取节点值
        elseif ($nodeType == 2 || $nodeType == 3 || $nodeType == 4) {
            $content = $element->nodeValue;
        } else {
            // 保留nodeValue里的html符号，给children二次提取
            $content = $dom->saveXml($element);
            // $content = trim(self::$dom->saveHtml($element));
            $content = preg_replace(array(
                "#^<{$nodeName}.*>#isU",
                "#</{$nodeName}>$#isU"
            ), array(
                '',
                ''
            ), $content);
        }
        $content = strip_tags($content);
        $content = self::make_semiangle($content);
        list ($key, $value) = explode(':', $content);
        return array(
            $key => $value
        );
    }
    public static function  result($re){
        foreach ($re AS $v){
            list($key, $val) = each($v);
            $rr[$key]=$val;
        }
        return $rr;
    }
    private static function make_semiangle($str)
    {
        $arr = array(
            '０' => '0',
            '１' => '1',
            '２' => '2',
            '３' => '3',
            '４' => '4',
            '５' => '5',
            '６' => '6',
            '７' => '7',
            '８' => '8',
            '９' => '9',
            'Ａ' => 'A',
            'Ｂ' => 'B',
            'Ｃ' => 'C',
            'Ｄ' => 'D',
            'Ｅ' => 'E',
            'Ｆ' => 'F',
            'Ｇ' => 'G',
            'Ｈ' => 'H',
            'Ｉ' => 'I',
            'Ｊ' => 'J',
            'Ｋ' => 'K',
            'Ｌ' => 'L',
            'Ｍ' => 'M',
            'Ｎ' => 'N',
            'Ｏ' => 'O',
            'Ｐ' => 'P',
            'Ｑ' => 'Q',
            'Ｒ' => 'R',
            'Ｓ' => 'S',
            'Ｔ' => 'T',
            'Ｕ' => 'U',
            'Ｖ' => 'V',
            'Ｗ' => 'W',
            'Ｘ' => 'X',
            'Ｙ' => 'Y',
            'Ｚ' => 'Z',
            'ａ' => 'a',
            'ｂ' => 'b',
            'ｃ' => 'c',
            'ｄ' => 'd',
            'ｅ' => 'e',
            'ｆ' => 'f',
            'ｇ' => 'g',
            'ｈ' => 'h',
            'ｉ' => 'i',
            'ｊ' => 'j',
            'ｋ' => 'k',
            'ｌ' => 'l',
            'ｍ' => 'm',
            'ｎ' => 'n',
            'ｏ' => 'o',
            'ｐ' => 'p',
            'ｑ' => 'q',
            'ｒ' => 'r',
            'ｓ' => 's',
            'ｔ' => 't',
            'ｕ' => 'u',
            'ｖ' => 'v',
            'ｗ' => 'w',
            'ｘ' => 'x',
            'ｙ' => 'y',
            'ｚ' => 'z',
            '（' => '(',
            '）' => ')',
            '〔' => '[',
            '〕' => ']',
            '【' => '[',
            '】' => ']',
            '〖' => '[',
            '〗' => ']',
            '“' => '[',
            '”' => ']',
            '‘' => '[',
            '’' => ']',
            '｛' => '{',
            '｝' => '}',
            '《' => '<',
            '》' => '>',
            '％' => '%',
            '＋' => '+',
            '—' => '-',
            '－' => '-',
            '～' => '-',
            '：' => ':',
            '。' => '.',
            '、' => ',',
            '，' => '.',
            '、' => '.',
            '；' => ',',
            '？' => '?',
            '！' => '!',
            '…' => '-',
            '‖' => '|',
            '”' => '"',
            '’' => '`',
            '‘' => '`',
            '｜' => '|',
            '〃' => '"',
            '　' => ' '
        );
        
        return strtr($str, $arr);
    }
}

