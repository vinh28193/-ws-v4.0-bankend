<?php


namespace common\components\lib;


class TextUtility
{
    public static function GeneratePackingCode($id){
        $idStr = $id> 999 ? $id : substr('000'.$id,(strlen('000'.$id) - 4),4);
        return 'WSVNPK'.$idStr;
    }
}