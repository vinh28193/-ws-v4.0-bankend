<?php
namespace common\components;


class ReponseData
{

    public static function reponseMess($success = false,$message = '',$data = []){
        return json_encode([
            'success' => $success,
            'message' => $message,
            'data' => (array)$data,
        ]);
    }
    public static function reponseArray($success = false,$message = '',$data = [],$code = ''){
        return [
            'success' => $success,
            'message' => $message,
            'data' => (array)$data,
            'code' => $code
        ];
    }
}