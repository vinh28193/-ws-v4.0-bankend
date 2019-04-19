<?php

namespace api\modules\v1\controllers\service;

use Yii;
use api\controllers\BaseApiController;

class ChatlistsServiceController extends BaseApiController
{
    /** Role :
        case 'cms':
        case 'warehouse':
        case 'operation':
        case 'sale':
        case 'master_sale':
        case 'master_operation':
        case 'superAdmin' :
    **/
    public function rules()
    {
        
    }

    public function verbs()
    {
        
    }

    public static function removeKeyFileChat($key,$filename)
    {
        $path = Yii::getAlias('@webroot/listchats/'.$filename);
        $tempArray = self::readFileChat($filename);
        if(isset($tempArray[$key])) unset($tempArray[$key]);
        $tempArray = array_values($tempArray);
        $jsonData = json_encode($tempArray);
        file_put_contents($path, $jsonData);
        return $tempArray;

    }

    public static function writeFileChat($content,$filename)
    {
        
    	$path = Yii::getAlias('@webroot/listchats/'.$filename);
        $tempArray = self::readFileChat($filename);
        $content     = mb_strtolower($content);
        if(empty($tempArray))
        {
         $tempArray = array();  
        }
        array_unshift($tempArray, $content);

        $jsonData = json_encode($tempArray);

        file_put_contents($path, $jsonData);

        return $tempArray;

    }

    public static function readFileChat($filename)
    {
    	$path = Yii::getAlias('@webroot/listchats/'.$filename);
        $content = file_get_contents($path);
        $listchats = json_decode($content, true);
        return $listchats;
    }

    public static function deleteContentFileChat($key,$filename)
    {
    	$path = Yii::getAlias('@webroot/listchats/'.$filename);
        $content = file_get_contents($path);
        $listchats = json_decode($content, true);
        return $listchats;
    }

    public static function checkStringInFile($string,$array)
    {
        $return = 0;
        if(in_array($string, $array)) $return = 1;
        return $return; // 0:false;1 :true
    }
}
