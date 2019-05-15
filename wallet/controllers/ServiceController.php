<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/22/2018
 * Time: 10:50 AM
 */

namespace wallet\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;
use common\helpers\CorsCustom;
use common\models\model\Website;

class ServiceController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => CorsCustom::className(),
        ];
        return $behaviors;
    }

    public function response($success = false, $message = null, $data = null, $code = null, $total = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res['success'] = $success;
        $res['message'] = $message;
        $res['total'] = $total;
        $res['code'] = $code;
        if (is_object($data)) {
            $res['data'] = $data->getAttributes();
        } else {
            $res['data'] = $data;
        }
        return $res;
    }

    public function getWebsite($storeId)
    {
        return new Website($storeId);
    }

    public function renderJSON($success = false, $message = null, $data = null, $total = null)
    {
        header('Content-Type: application/json');
        $res['success'] = $success;
        $res['message'] = $message;
        $res['total'] = $total;
        $res['data'] = $data;

        echo json_encode($res, JSON_PRETTY_PRINT);
    }
}