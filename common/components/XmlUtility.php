<?php


namespace common\components;


use DOMDocument;

class XmlUtility
{
    public static function xmlToArray($xmlstr)
    {
        $xmlstr = trim(preg_replace('/(.*)(\s*)(<\?xml)/', '$3', $xmlstr));
        preg_match('/<checkout_url>(.*)<\/checkout_url>/', $xmlstr, $out);
        if (isset($out[0])) {
            $xmlstr = str_replace($out[0], '', $xmlstr);
        }
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        $arrResult = self::domToArray($doc->documentElement);
        if (isset($out[1])) {
            $arrResult = array_merge($arrResult, array('checkout_url' => $out[1]));
        }
        return $arrResult;
    }

    private static function domToArray($node)
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
                break;
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::domToArray($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (!isset($output[$t]))
                            $output[$t] = array();
                        $output[$t][] = $v;
                    } elseif ($v) {
                        $output = (string)$v;
                    }
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = array();
                        foreach ($node->attributes as $attrName => $attrNode)
                            $a[$attrName] = (string)$attrNode->value;
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes')
                            $output[$t] = $v[0];
                    }
                }
                break;
        }
        return $output;
    }
}