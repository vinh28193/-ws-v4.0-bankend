<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/22/2018
 * Time: 10:50 AM
 */

namespace wallet\controllers;

use common\filters\Cors;
use Yii;
use yii\web\Response;
use yii\rest\Controller;

class ServiceController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
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