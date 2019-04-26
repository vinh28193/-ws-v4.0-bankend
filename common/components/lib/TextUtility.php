<?php


namespace common\components\lib;


class TextUtility
{
    public static function GeneratePackingCode($id,$contry = 'VN'){
        $idStr = $id> 999 ? $id : substr('000'.$id,(strlen('000'.$id) - 4),4);
        return strtoupper('WS'.$contry.'PK'.$idStr);
    }
}