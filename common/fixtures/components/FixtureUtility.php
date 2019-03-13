<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 13:28
 */

namespace common\fixtures\components;


use yii\helpers\ArrayHelper;

class FixtureUtility
{
    public static function getProvince(){
        return include '.\common\fixtures\data\data_fiexd\system_state_province.php';
    }
    public static function getIdsProvinceByIdCountry($id = 1){
        $list = self::getProvince();
        $ids = [];
        foreach ($list as $data){
            if(isset($data['country_id']) && $data['country_id'] == $id && isset($data['id'])){
                $ids[] = $data['id'];
            }
        }
        return $ids;
    }
    public static function getProvincesByIdCountry($id = 1){
        $list = self::getProvince();
        $ids = [];
        foreach ($list as $data){
            if(isset($data['country_id']) && $data['country_id'] == $id && isset($data['id'])){
                $ids[] = $data;
            }
        }
        return $ids;
    }

    public static function getDistrict(){
        return include '.\common\fixtures\data\data_fiexd\system_district.php';
    }
    public static function getIdsDistrictByIdProvince($id = 1){
        $list = self::getDistrict();
        $ids = [];
        foreach ($list as $data){
            if(isset($data['province_id']) && $data['province_id'] == $id && isset($data['id'])){
                $ids[] = $data['id'];
            }
        }
        return $ids;
    }
    public static function getDistrictsByIdProvince($id = 1){
        $list = self::getDistrict();
        $ids = [];
        foreach ($list as $data){
            if(isset($data['province_id']) && $data['province_id'] == $id && isset($data['id'])){
                $ids[] = $data;
            }
        }
        return $ids;
    }
    public static function getDataWithColumn($path,$column,$condition=null){
        $list = include $path;
        $res = [];
        foreach ($list as $data){
            if($condition){
                $check = true;
                foreach ($condition as $c => $v){
                    if(is_array($v) && !in_array($data[$c],$v)){
                        $check = false;
                        break;
                    }elseif(!is_array($v) && $data[$c] != $v){
                        $check = false;
                        break;
                    }
                }
                if($check){
                    if($column){
                        $res[] = $data[$column];
                    }else{
                        $res[] = $data;
                    }
                }
            }else{
                if($column){
                    $res[] = $data[$column];
                }else{
                    $res[] = $data;
                }
            }
        }
        return $res;
    }
    /**
     * @param $items
     * @param $field
     * @return float|int
     */
    public static function getSumArray($items, $field)
    {
        $array = ArrayHelper::getColumn($items, $field, false);
        if (count($array) === 0) {
            return 0;
        }
        return array_sum($array);
    }
    public static function getRandomCode($length = 12,$typechar = 2){
        $code = "";
        for($ind = 1; $ind <= $length; $ind++){
            $type = $typechar == 1 || $typechar ==0 ? $typechar : rand(0,1);
            if($type == 1){
                $code .= rand(0,9);
            }else{
                $code .= chr(rand(65,90));
            }
        }
        return $code;
    }

}